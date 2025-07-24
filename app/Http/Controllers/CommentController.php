<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\EducationalMaterial;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    use AuthorizesRequests;

    public function index(EducationalMaterial $material)
    {
        $comments = $material->comments()
            ->with(['user', 'replies.user'])
            ->whereNull('parent_id')
            ->latest()
            ->get();

        return response()->json($comments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:2000',
            'educational_material_id' => 'required|exists:educational_materials,id',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        $comment = Comment::create([
            'content' => $request->content,
            'user_id' => Auth::id(),
            'educational_material_id' => $request->educational_material_id,
            'parent_id' => $request->parent_id
        ]);

        return response()->json($comment->load('user'));
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        
        $comment->delete();
        
        return response()->json(['message' => 'Komentar berhasil dihapus']);
    }
}