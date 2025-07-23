<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Category;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;

class AdminController extends Controller
{
    // Dashboard Admin
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_courses' => Course::count(),
            'pending_courses' => Course::where('is_published', false)->count(),
            'total_categories' => Category::count(),
        ];

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'recentUsers' => User::with('role')->latest()->take(5)->get(),
            'recentCourses' => Course::with(['category', 'user'])->latest()->take(5)->get(),
        ]);
    }

    public function users(Request $request)
    {
        return Inertia::render('Admin/Users/Index', [
            'users' => User::with('role')
                ->when($request->search, function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                ->latest()
                ->paginate(10),
            'roles' => UserRole::all(),
            'filters' => $request->only(['search']), // Tambahkan ini
        ]);
    }

    public function batchUpdate(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'role_id' => 'required|exists:user_roles,id'
        ]);

        // Cek jika mencoba mengubah role admin terakhir
        $adminUsers = User::whereIn('id', $request->user_ids)
            ->whereHas('role', function ($q) {
                $q->where('name', 'admin');
            })->count();

        $totalAdmins = User::whereHas('role', function ($q) {
            $q->where('name', 'admin');
        })->count();

        if ($adminUsers > 0 && ($totalAdmins - $adminUsers) < 1) {
            return back()->with('error', 'Tidak bisa mengubah role admin terakhir');
        }

        User::whereIn('id', $request->user_ids)
            ->update(['role_id' => $request->role_id]);

        return back()->with('success', 'Role pengguna berhasil diperbarui');
    }

    public function batchDelete(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        // Cek jika mencoba menghapus admin terakhir
        $adminUsers = User::whereIn('id', $request->user_ids)
            ->whereHas('role', function ($q) {
                $q->where('name', 'admin');
            })->count();

        $totalAdmins = User::whereHas('role', function ($q) {
            $q->where('name', 'admin');
        })->count();

        if ($adminUsers > 0 && ($totalAdmins - $adminUsers) < 1) {
            return back()->with('error', 'Tidak bisa menghapus admin terakhir');
        }

        // Jangan izinkan menghapus diri sendiri
        if (in_array(auth()->id(), $request->user_ids)) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri');
        }

        User::whereIn('id', $request->user_ids)->delete();

        return back()->with('success', 'Pengguna berhasil dihapus');
    }

    /**
     * Store a newly created user from admin panel.
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:user_roles,id',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
                'email_verified_at' => now(), // Verifikasi email otomatis untuk admin
            ]);

            return redirect()->route('admin.users')
                ->with('success', 'Pengguna berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal menambahkan pengguna: ' . $e->getMessage());
        }
    }

    public function updateUserRole(User $user, Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:user_roles,id',
        ]);

        $user->update(['role_id' => $request->role_id]);

        return back()->with('success', 'Role pengguna berhasil diperbarui');
    }

    public function destroyUser(User $user)
    {
        // Tambahkan pengecekan jika user yang dihapus adalah admin terakhir
        if ($user->role->name === 'admin' && User::where('role_id', $user->role_id)->count() <= 1) {
            return back()->with('error', 'Tidak bisa menghapus admin terakhir');
        }

        $user->delete();
        return back()->with('success', 'Pengguna berhasil dihapus');
    }

    public function exportUsers(Request $request)
    {
        $request->validate([
            'format' => 'sometimes|in:csv,xlsx,pdf',
            'columns' => 'sometimes|array',
            'columns.*' => 'in:name,email,role,created_at'
        ]);

        $format = $request->format ?? 'xlsx';
        $columns = $request->columns ?? ['name', 'email', 'role', 'created_at'];
        $fileName = 'users-export-' . now()->format('Y-m-d-H-i-s') . '.' . $format;

        return Excel::download(new UsersExport($columns), $fileName);
    }

    public function resetPassword(User $user)
    {
        $user->update(['password' => Hash::make('password')]);
        return back()->with('success', 'Password berhasil direset');
    }

    // Manajemen Kursus
    public function courses()
    {
        return Inertia::render('Admin/Courses/Index', [
            'courses' => Course::with(['category', 'user'])
                ->latest()
                ->paginate(10),
        ]);
    }

    public function approveCourse(Course $course)
    {
        $course->update(['is_published' => true]);
        return back()->with('success', 'Course approved successfully');
    }

    public function destroyCourse(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses')->with('success', 'Course deleted successfully');
    }

    // Manajemen Kategori
    public function categories()
    {
        return Inertia::render('Admin/Categories/Index', [
            'categories' => Category::latest()->paginate(10),
        ]);
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        return back()->with('success', 'Category created successfully');
    }

    public function updateCategory(Category $category, Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        return back()->with('success', 'Category updated successfully');
    }

    public function destroyCategory(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Category deleted successfully');
    }
}
