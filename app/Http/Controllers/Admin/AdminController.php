<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Import para sa Database
use Illuminate\Support\Facades\Hash; // Import para sa Password

class AdminController extends Controller
{
    /**
     * Main Dashboard View
     */
    public function dashboard()
    {
        return view('Admin.dashboard', [
            'section' => 'dashboard'
        ]);
    }

    public function customers() 
{
    // Simple filter: Lahat ng may role na Customer, kunin mo.
    $users = \App\Models\User::where('role', 'Customer')->get(); 

    return view('Admin.dashboard', [
        'section' => 'customers',
        'users'   => $users 
    ]); 
}

    /**
     * Store or Update Customer Record (CRUD)
     */
    public function saveCustomer(Request $request)
    {
        // Validation - sinisiguro na tama ang input
        $validated = $request->validate([
            'id'    => 'nullable|exists:users,id',
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $request->id,
            'role'  => 'required',
            'phone' => 'nullable',
            'address' => 'nullable',
        ]);

        // Logic para sa Add o Edit
        if ($request->id) {
            $user = User::find($request->id);
        } else {
            $user = new User();
            // Default password para sa mga bagong register
            $user->password = Hash::make('customer123'); 
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->status = $request->status ?? 'ACTIVE';
        $user->save();

        return response()->json([
            'success' => true, 
            'message' => 'User record saved successfully!',
            'user' => $user
        ]);
    }

    /**
     * Delete Customer Record (CRUD)
     */
    public function deleteCustomer($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'success' => true, 
            'message' => 'User deleted successfully!'
        ]);
    }

    /**
     * Orders Management Section
     */
    public function orders() 
    {
        return view('Admin.dashboard', [
            'section' => 'orders'
        ]);
    }

    /**
     * Products Catalog Section
     */
    public function products() 
    {
        return view('Admin.dashboard', [
            'section' => 'products'
        ]);
    }

    /**
     * Rates & Pricing Section
     */
    public function rates() 
    {
        return view('Admin.dashboard', [
            'section' => 'rates'
        ]);
    }

    /**
     * Business Analytics Section
     */
    public function analytics() 
    {
        return view('Admin.dashboard', [
            'section' => 'analytics'
        ]);
    }

    /**
     * Generation of Reports Section
     */
    public function reports() 
    {
        return view('Admin.dashboard', [
            'section' => 'reports'
        ]);
    }

    /**
     * System Settings Section
     */
    public function settings() 
    {
        return view('Admin.dashboard', [
            'section' => 'settings'
        ]);
    }

    /**
     * Help Center Section
     */
    public function helpcenter() 
    {
        return view('Admin.dashboard', [
            'section' => 'help center'
        ]);
    }

    /**
     * Admin Logout Logic
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}