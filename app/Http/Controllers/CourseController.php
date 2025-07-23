<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Course;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    // app/Http/Controllers/Admin/CourseController.php
    public function index(Request $request)
    {
        $courses = Course::with('category')
            ->when($request->search, function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('category', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
                });
            })
            ->latest()
            ->paginate(10);

        return inertia('Admin/Courses/Index', [
            'courses' => $courses,
            'filters' => $request->only(['search'])
        ]);
    }

    public function show(Course $course)
    {
        if (!$course->is_published && !auth()->user()?->isAdmin()) {
            abort(403);
        }

        $materials = $course->materials()->orderBy('order')->get();

        return Inertia::render('Courses/Show', [
            'course' => $course->load('category', 'user'),
            'materials' => $materials,
            'canAccess' => auth()->check(),
        ]);
    }

    public function search(Request $request)
    {
        $request->validate([
            'search' => 'required|string|min:2|max:255'
        ]);

        $courses = Course::with('category')
            ->where('is_published', true)
            ->where(function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%')
                    ->orWhereHas('category', function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->search . '%');
                    });
            })
            ->select('id', 'title', 'slug', 'description', 'thumbnail', 'category_id')
            ->limit(10)
            ->get();

        return inertia()->render('Courses/Index', [
            'courses' => $courses,
            'filters' => $request->only(['search']),
            'isSearch' => true
        ]);
    }

    public function create()
    {
        $categories = Category::all();
        return inertia('Admin/Courses/Create', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:published,pending,rejected',
        ]);

        Course::create($request->only([
            'title', 'description', 'category_id', 'status'
        ]));

        return redirect()->route('admin.courses')->with('success', 'Course created successfully.');
    }

    public function edit(Course $course)
    {
        $categories = Category::all();
        return inertia('Admin/Courses/Edit', [
            'course' => $course,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:published,pending,rejected',
        ]);

        $course->update($request->only([
            'title', 'description', 'category_id', 'status'
        ]));

        return redirect()->route('admin.courses')->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses')->with('success', 'Course deleted successfully.');
    }
}
