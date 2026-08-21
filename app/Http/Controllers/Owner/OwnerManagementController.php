<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OwnerManagementController extends Controller
{
    public function index()
    {
        $owners = User::where('role', 'owner')
            ->where('id', '!=', auth()->id())
            ->latest()
            ->get();

        return view('owner.manage_owners', compact('owners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50', 'unique:users,phone'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'owner',
        ]);

        return redirect()->route('owner.manage_owners.index')->with('success', 'Owner added successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->role !== 'owner') {
            abort(403, 'Unauthorized deletion target.');
        }

        if ((int) $user->id === (int) auth()->id()) {
            return redirect()->route('owner.manage_owners.index')->with('error', 'You cannot delete your own owner account.');
        }

        $user->delete();

        return redirect()->route('owner.manage_owners.index')->with('success', 'Owner deleted successfully.');
    }
}
