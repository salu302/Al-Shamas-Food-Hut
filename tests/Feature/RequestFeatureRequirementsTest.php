<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Complaint;
use App\Models\Expense;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ExpenseCategory;
use App\Models\User;
use App\Mail\CustomerOrderConfirmationMail;
use App\Mail\OrderConfirmationMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RequestFeatureRequirementsTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_sends_owner_and_customer_email_notifications(): void
    {
        Mail::fake();

        $category = Category::create([
            'name_en' => 'Pizza',
            'name_ur' => 'پیزا',
            'status' => true,
        ]);

        $item = Item::create([
            'category_id' => $category->id,
            'name_en' => 'Classic Pizza',
            'name_ur' => 'کلاسک پیزا',
            'description_en' => 'Classic pizza',
            'description_ur' => 'کلاسک پیزا',
            'price' => 250.00,
            'is_available' => true,
        ]);

        $customer = User::factory()->create(['role' => 'customer']);
        $this->actingAs($customer);

        session()->put('cart', [
            [
                'item_id' => $item->id,
                'quantity' => 2,
                'unit_price' => 250.00,
                'variant_name' => null,
            ],
        ]);

        $response = $this->post('/checkout', [
            'customer_name' => 'Ali Khan',
            'customer_phone' => '03001234567',
            'delivery_address' => 'Main Road',
            'customer_email' => 'customer@example.com',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', ['customer_email' => 'customer@example.com']);
        Mail::assertSent(OrderConfirmationMail::class, 1);
        Mail::assertSent(CustomerOrderConfirmationMail::class, 1);
    }

    public function test_owner_can_manage_only_owner_accounts(): void
    {
        $owner = User::factory()->create([
            'name' => 'Main Owner',
            'email' => 'owner@example.com',
            'role' => 'owner',
        ]);
        $this->actingAs($owner);

        $admin = User::factory()->create(['name' => 'Admin User', 'email' => 'admin@example.com', 'role' => 'admin']);
        $otherOwner = User::factory()->create(['name' => 'Co Owner', 'email' => 'coowner@example.com', 'role' => 'owner']);

        $listResponse = $this->get('/owner/manage-owners');
        $listResponse->assertOk();
        $listResponse->assertDontSee($admin->name);
        $listResponse->assertSee($otherOwner->name);

        $createResponse = $this->post('/owner/manage-owners', [
            'name' => 'New Co Owner',
            'phone' => '03009998888',
            'email' => 'new-owner@example.com',
            'password' => 'password123',
        ]);
        $createResponse->assertRedirect();
        $this->assertDatabaseHas('users', [
            'email' => 'new-owner@example.com',
            'phone' => '03009998888',
            'role' => 'owner',
        ]);

        $deleteResponse = $this->delete('/owner/manage-owners/' . $otherOwner->id);
        $deleteResponse->assertRedirect();
        $this->assertDatabaseMissing('users', ['id' => $otherOwner->id]);

        $this->assertDatabaseHas('users', ['id' => $owner->id, 'role' => 'owner']);
        $this->assertDatabaseHas('users', ['id' => $admin->id, 'role' => 'admin']);
    }

    public function test_owner_can_manage_expense_categories_and_update_expenses(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $this->actingAs($owner);
        $category = ExpenseCategory::where('name', 'Utilities')->firstOrFail();

        $this->post('/owner/expenses', [
            'category' => $category->id,
            'amount' => 100,
            'expense_date' => '2026-08-20',
            'description' => 'Electricity',
        ])->assertRedirect()->assertSessionHasNoErrors();

        $expense = Expense::firstOrFail();
        $this->post('/owner/expense-categories', ['name' => 'Transport'])->assertRedirect();
        $newCategory = ExpenseCategory::where('name', 'Transport')->firstOrFail();

        $this->put('/owner/expenses/' . $expense->id, [
            'category' => $newCategory->id,
            'amount' => 250,
            'expense_date' => '2026-08-21',
            'description' => 'Fuel',
        ])->assertRedirect();

        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'category_id' => $newCategory->id,
            'category' => 'Transport',
            'amount' => 250,
            'description' => 'Fuel',
        ]);

        $this->put('/owner/expense-categories/' . $newCategory->id, ['name' => 'Travel']);
        $this->assertDatabaseHas('expenses', ['id' => $expense->id, 'category' => 'Travel']);
    }

    public function test_admin_can_access_owner_management_routes(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $this->get(route('owner.orders.index'))->assertOk();
        $this->get(route('owner.items.index'))->assertOk();
        $this->get(route('owner.expenses.index'))->assertOk();
    }

    public function test_owner_can_record_whatsapp_sales_and_expenses_in_dashboard_metrics(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $this->actingAs($owner);

        $saleResponse = $this->post(route('owner.quick-sale.store'), [
            'customer_name' => 'WhatsApp Customer',
            'total_amount' => 1500,
        ]);

        $saleResponse->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'customer_name' => 'WhatsApp Customer',
            'total_amount' => 1500,
            'source' => 'whatsapp',
        ]);

        $expenseResponse = $this->post(route('owner.expenses.store'), [
            'category' => 'Supplies',
            'amount' => 300,
            'description' => 'Packaging',
            'expense_date' => today()->toDateString(),
        ]);

        $expenseResponse->assertRedirect();
        $this->assertDatabaseHas('expenses', ['category' => 'Supplies', 'amount' => 300]);

        $dashboardResponse = $this->get(route('dashboard'));
        $dashboardResponse->assertOk();
        $dashboardResponse->assertSee('Rs. 1,200.00');

        $expense = Expense::firstOrFail();
        $this->delete(route('owner.expenses.destroy', $expense))->assertRedirect();
        $this->assertDatabaseMissing('expenses', ['id' => $expense->id]);
    }

    public function test_owner_can_delete_order_and_dashboard_excludes_cancelled_revenue(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $this->actingAs($owner);

        $category = Category::create([
            'name_en' => 'Burgers',
            'name_ur' => 'برگر',
            'status' => true,
        ]);
        $item = Item::create([
            'category_id' => $category->id,
            'name_en' => 'Classic Burger',
            'name_ur' => 'کلاسک برگر',
            'description_en' => 'Burger',
            'description_ur' => 'برگر',
            'price' => 500,
            'is_available' => true,
        ]);
        $order = Order::create([
            'customer_name' => 'Delete Me',
            'customer_phone' => '03001234567',
            'delivery_address' => 'Address',
            'total_amount' => 500,
            'payment_method' => 'COD',
            'status' => 'pending',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'item_id' => $item->id,
            'quantity' => 1,
            'unit_price' => 500,
            'subtotal' => 500,
        ]);
        $cancelledOrder = Order::create([
            'customer_name' => 'Cancelled',
            'customer_phone' => '03001234567',
            'delivery_address' => 'Address',
            'total_amount' => 200,
            'payment_method' => 'COD',
            'status' => 'cancelled',
        ]);

        $this->get(route('dashboard'))->assertSee('Rs. 500.00');
        $this->delete(route('owner.orders.destroy', $order))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
        $this->assertDatabaseMissing('order_items', ['order_id' => $order->id]);
        $this->get(route('dashboard'))->assertSee('Rs. 0.00');
        $this->assertDatabaseHas('orders', ['id' => $cancelledOrder->id]);
    }

    public function test_owner_can_visit_deals_management_and_super_admin_can_reset_dashboard(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $this->actingAs($owner);

        $response = $this->get('/owner/deals');
        $response->assertOk();

        $admin = User::factory()->create(['role' => 'super_admin']);
        $this->actingAs($admin);

        $category = Category::create([
            'name_en' => 'Test Category',
            'name_ur' => 'ٹیسٹ زمرہ',
            'status' => true,
        ]);

        $item = Item::create([
            'category_id' => $category->id,
            'name_en' => 'Test Item',
            'name_ur' => 'ٹیسٹ آئٹم',
            'description_en' => 'Testing',
            'description_ur' => 'ٹیسٹنگ',
            'price' => 120.00,
            'is_available' => true,
        ]);

        $order = Order::create([
            'customer_name' => 'Test',
            'customer_phone' => '03001234567',
            'delivery_address' => 'Address',
            'total_amount' => 120.00,
            'payment_method' => 'COD',
            'status' => 'pending',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'item_id' => $item->id,
            'quantity' => 1,
            'unit_price' => 120.00,
            'subtotal' => 120.00,
        ]);

        Complaint::create([
            'name' => 'Customer',
            'phone' => '03001234567',
            'subject' => 'Issue',
            'message' => 'Test complaint',
        ]);

        $this->post('/admin/reset-dashboard')->assertRedirect(route('admin.dashboard'));
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_items', 0);
        $this->assertDatabaseCount('complaints', 0);
    }
}
