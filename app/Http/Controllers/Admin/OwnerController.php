<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OwnerController extends Controller
{
    public function index()
    {
        $totalAdmins = User::where('role', 'admin')->count();
        $totalOwners = User::where('role', 'owner')->count();

        $totalOrders  = Order::count();
        $totalRevenue = Order::whereIn('status', ['selesai', 'dikirim'])->sum('total_price');

        $activities = ActivityLog::with('user')
            ->latest()
            ->take(20)
            ->get();

        return view('admin.owner.dashboard', compact(
            'totalAdmins',
            'totalOwners',
            'totalOrders',
            'totalRevenue',
            'activities'
        ));
    }

    public function manageAdmins()
    {
        $admins = User::whereIn('role', ['admin', 'owner'])->latest()->paginate(10);
        return view('admin.owner.manage-admins', compact('admins'));
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role'     => 'required|in:admin,owner',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'phone'    => $request->phone ?? '',
            'address'  => $request->address ?? '',
        ]);

        ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'admin_created',
            'description' => 'Owner membuat akun ' . $request->role . ': ' . $request->name . ' (' . $request->email . ')',
            'model_type'  => 'User',
            'model_id'    => User::latest()->first()->id,
        ]);

        return redirect()->route('admin.owner.manage-admins')
            ->with('success', 'Akun ' . $request->role . ' berhasil dibuat!');
    }

    public function destroyAdmin($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        $name  = $user->name;
        $email = $user->email;
        $role  = $user->role;
        $user->delete();

        ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'admin_deleted',
            'description' => 'Owner menghapus akun ' . $role . ': ' . $name . ' (' . $email . ')',
            'model_type'  => 'User',
        ]);

        return redirect()->route('admin.owner.manage-admins')
            ->with('success', 'Akun ' . $name . ' berhasil dihapus.');
    }

    public function auditLog()
    {
        $activities = ActivityLog::with('user')
            ->latest()
            ->paginate(50);

        return view('admin.owner.audit-log', compact('activities'));
    }
}
