<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'category_id',
        'category',
        'amount',
        'description',
        'expense_date',
    ];

    public function categoryRecord()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'expense_date' => 'date',
        ];
    }
}