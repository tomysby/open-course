<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\EducationalMaterialController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AdminController;
use App\Models\Course;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\DashboardController;

Route::get('/', [EducationalMaterialController::class, 'index'])->name('home');

Route::get('/courses/search', [CourseController::class, 'search'])->name('courses.search');
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return auth()->user()->role->name === 'admin' 
            ? redirect()->route('admin.dashboard')
            : redirect()->route('dashboard');
    })->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Courses
    Route::resource('courses', CourseController::class)->only(['index', 'show', 'create', 'store']);
    // Materials
    Route::get('/courses/{course}/materials/{material}', [MaterialController::class, 'show'])
        ->name('materials.show');
    Route::resource('materials', EducationalMaterialController::class)
        ->only(['index', 'create', 'store', 'show']);
    
    // Comments
    Route::post('/courses/{course}/materials/{material}/comments', [CommentController::class, 'store'])
        ->name('comments.store');
    Route::post('/materials/{material}/comments', [CommentController::class, 'store'])
        ->name('comments.store')
        ->middleware('auth');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
        ->name('comments.destroy')
        ->middleware('auth');
});

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard Admin
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Manajemen Pengguna
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::put('/users/{user}/update-role', [AdminController::class, 'updateUserRole'])->name('admin.users.update-role');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    Route::put('/users/batch-update', [AdminController::class, 'batchUpdate'])->name('admin.users.batch-update');
    Route::delete('/users/batch-delete', [AdminController::class, 'batchDelete'])->name('admin.users.batch-delete');
    Route::get('/users/export', [AdminController::class, 'exportUsers'])->name('admin.users.export');
    Route::put('/users/{user}/reset-password', [AdminController::class, 'resetPassword'])->name('admin.users.reset-password');
    // Manajemen Kursus
    /*Route::get('/courses', [AdminController::class, 'courses'])->name('admin.courses');
    Route::post('/courses/{course}/approve', [AdminController::class, 'approveCourse'])->name('admin.courses.approve');
    Route::delete('/courses/{course}', [AdminController::class, 'destroyCourse'])->name('admin.courses.destroy');*/
    // Courses routes
    Route::get('/courses', [CourseController::class, 'index'])->name('admin.courses');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('admin.courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('admin.courses.store');
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('admin.courses.edit');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('admin.courses.update');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('admin.courses.destroy');
    Route::post('/courses/{course}/approve', [AdminController::class, 'approveCourse'])->name('admin.courses.approve');
    // Manajemen Kategori
    /*Route::get('/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
    Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('admin.categories.update');
    Route::delete('/categories/{category}', [AdminController::class, 'destroyCategory'])->name('admin.categories.destroy');*/
    // Categories routes
    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

    Route::post('/materials/{material}/approve', [EducationalMaterialController::class, 'approve'])
            ->name('materials.approve');
    Route::post('/materials/{material}/reject', [EducationalMaterialController::class, 'reject'])
            ->name('materials.reject');
});
Route::post('/upload', [FileUploadController::class, 'upload'])->middleware('auth');
require __DIR__.'/auth.php';
