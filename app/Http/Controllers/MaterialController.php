<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Material;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;

class MaterialController extends Controller
{
    public function show(Course $course, Material $material)
    {
        $user = Auth::user();
        if (!$course->is_published && !($user && $user->isAdmin())) {
            abort(403);
        }

        if ($material->course_id !== $course->id) {
            abort(404);
        }

        if (!Auth::check()) {
            // Guest: hanya kirim data ringkas
            return Inertia::render('Materials/Show', [
                'course' => [
                    'id' => $course->id,
                    'title' => $course->title,
                ],
                'material' => [
                    'id' => $material->id,
                    'title' => $material->title,
                    'description' => $material->description,
                    'thumbnail' => $material->thumbnail,
                ],
                'isGuest' => true,
            ]);
        }

        $comments = $material->comments()
            ->with(['user', 'replies' => function ($q) {
                $q->with('user');
            }])
            ->latest()
            ->get();

        return Inertia::render('Materials/Show', [
            'course' => $course,
            'material' => $material,
            'comments' => $comments,
            'isGuest' => false,
        ]);
    }
}
