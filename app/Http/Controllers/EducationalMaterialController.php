<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\EducationalMaterial;
use App\Models\Tag;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EducationalMaterialController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $query = EducationalMaterial::query()
            ->with(['user', 'category', 'tags'])
            ->latest();

        // Jika bukan admin, hanya tampilkan yang approved
        if (auth()->user()?->isAdmin() !== true) {
            $query->where('status', 'approved');
        }

        $materials = $query->paginate(10);

        // Jika guest (belum login)
        if (!auth()->check()) {
            return inertia('Guest/Materials', [
                'materials' => $materials
            ]);
        }

        // Jika user login
        return inertia('EducationalMaterials/Index', [
            'materials' => $materials,
            'canReview' => auth()->user()->isAdmin()
        ]);
    }

    public function create()
    {
        return inertia('EducationalMaterials/Create', [
            'types' => ['article', 'image', 'pdf', 'audio', 'video'],
            'categories' => Category::all(), // Pastikan model Category ada
            'tags' => Tag::all() // Pastikan model Tag ada
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:article,image,pdf,audio,video',
            'content' => 'required_if:type,article',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'file' => [
                'required_if:type,image,pdf,audio,video',
                Rule::when($request->type === 'image', ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048']),
                Rule::when($request->type === 'pdf', ['file', 'mimes:pdf', 'max:5120']),
                Rule::when($request->type === 'audio', ['file', 'mimes:mp3,wav', 'max:10240']),
                Rule::when($request->type === 'video', ['file', 'mimes:mp4,mov,avi', 'max:20480']),
            ]
        ]);

        $material = new EducationalMaterial([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'type' => $request->type,
            'category_id' => $request->category_id ?? null,
            'content' => $request->content ?? null,
            'status' => 'pending'
        ]);

        if ($request->hasFile('file')) {
            //$path = $request->file('file')->store('educational-materials');
            $filename = $request->file('file')->hashName();
            $path = $request->file('file')->storeAs('', $filename, 'educational-materials');
            $material->file_path = $path;

            // For video thumbnails (optional)
            if ($request->type === 'video') {
                try {
                    $thumbnailPath = $this->generateVideoThumbnail($request->file('file'));
                    $material->thumbnail_path = $thumbnailPath;
                } catch (\Exception $e) {
                    // Log error but don't fail
                    \Log::error('Video thumbnail generation failed: ' . $e->getMessage());
                }
            }
        }

        $material->save();

        if ($request->has('tags')) {
            $material->tags()->sync($request->tags);
        }

        return redirect()->route('materials.index')->with('success', 'Material submitted for review');
    }

    public function approve(EducationalMaterial $material)
    {
        $this->authorize('review', $material);
        
        $material->update(['status' => 'approved']);
        return back()->with('success', 'Material approved');
    }

    public function reject(Request $request, EducationalMaterial $material)
    {
        $this->authorize('review', $material);
        
        $request->validate(['reason' => 'required|string|max:500']);
        $material->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason
        ]);
        
        return redirect()->route('materials.index')->with('success', 'Material rejected');
    }

    protected function generateVideoThumbnail($videoFile)
    {
        // Implement FFmpeg thumbnail generation
        // This is a simplified example
        $thumbnailName = 'thumb_'.time().'.jpg';
        $thumbnailPath = 'educational-materials/'.$thumbnailName;
        
        // FFmpeg command to extract thumbnail
        $ffmpeg = \FFMpeg\FFMpeg::create();
        $video = $ffmpeg->open($videoFile->getRealPath());
        $frame = $video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(1));
        $frame->save(storage_path('app/public/'.$thumbnailPath));
        
        return $thumbnailName;
    }

    public function show(EducationalMaterial $material)
    {
        // Authorization check jika diperlukan
        //$this->authorize('view', $material);

        return inertia('EducationalMaterials/Show', [
            'material' => $material->load([
                'user',
                'category',
                'tags',
                'comments.user', // Load komentar beserta user pembuatnya
                'comments.replies.user' // Load balasan komentar beserta user pembuatnya
            ]),
            'canEdit' => auth()->user()->can('update', $material),
            'canReview' => auth()->user()->can('review', $material),
            'canDelete' => auth()->user()->can('delete', $material),
        ]);
    }
}