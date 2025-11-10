<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        // Query customer role = user
        $query = User::where('role', 'user')
            ->withCount('bookings')
            ->orderBy('created_at', 'asc'); // urut berdasarkan paling awal bergabung

        // Filter pencarian email
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        $customers = $query->paginate(10)->withQueryString();

        return view('pages.admin.customer.customer', compact('customers'));
    }

    public function destroy($id)
    {
        $customer = User::findOrFail($id);

        $customer->delete();

        return redirect()->route('admin.customer.index')
            ->with('success', 'Customer berhasil dihapus.');
    }
}
