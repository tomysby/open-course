<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Material;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;

// app/Http/Controllers/CommentController.php
class CommentController extends Controller
{
    public function store(Request $request, EducationalMaterial $material)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        $material->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
            'parent_id' => $request->parent_id
        ]);

        return back()->with('success', 'Comment added successfully');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        $comment->delete();
        return back()->with('success', 'Comment deleted successfully');
    }
}