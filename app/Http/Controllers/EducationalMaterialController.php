<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EducationalMaterial;
use Illuminate\Validation\Rule;

class EducationalMaterialController extends Controller
{
    public function index()
    {
        $materials = EducationalMaterial::with('user')
            ->when(auth()->user()->isAdmin(), fn($q) => $q->where('status', 'pending'))
            ->when(!auth()->user()->isAdmin(), fn($q) => $q->where('user_id', auth()->id()))
            ->latest()
            ->paginate(10);

        return inertia('EducationalMaterials/Index', [
            'materials' => $materials,
            'canReview' => auth()->user()->isAdmin()
        ]);
    }

    public function create()
    {
        return inertia('EducationalMaterials/Create', [
            'types' => ['article', 'image', 'pdf', 'audio', 'video']
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
            'content' => $request->content,
            'status' => 'pending'
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('educational-materials');
            $material->file_path = $path;

            if ($request->type === 'video') {
                // Generate thumbnail for video (you'll need FFmpeg)
                $thumbnailPath = $this->generateVideoThumbnail($request->file('file'));
                $material->thumbnail_path = $thumbnailPath;
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
        
        return back()->with('success', 'Material rejected');
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
        
        return $thumbnailPath;
    }
}