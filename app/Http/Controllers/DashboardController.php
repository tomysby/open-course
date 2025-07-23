<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Course;
use App\Models\User;
use App\Models\Material;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'courses' => Course::count(),
            'students' => User::where('role_id', 3)->count(), // role_id 3 = student
            'materials' => Material::count(),
            'completion_rate' => 75, // Contoh nilai statis
        ];

        $recentCourses = Course::with('category')
            ->latest()
            ->take(5)
            ->get();

        $progressData = [
            'percentage' => 65,
            'courses' => [
                ['id' => 1, 'name' => 'Vue JS', 'progress' => 80],
                ['id' => 2, 'name' => 'Laravel', 'progress' => 45],
                ['id' => 3, 'name' => 'Tailwind', 'progress' => 70],
            ]
        ];

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recentCourses' => $recentCourses,
            'progressData' => $progressData,
        ]);
    }
}