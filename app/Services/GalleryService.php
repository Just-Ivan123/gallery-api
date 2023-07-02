<?php

namespace App\Services; 

use App\Http\Requests\GalleryRequest;
use App\Models\Gallery;
use App\Models\User;
use App\Models\Image;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\Builder;

class GalleryService
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    
    public function getAllGalleries($searchQuery = null)
    {
        $query = Gallery::with(['user' => function ($query) {
            $query->select('id', 'first_name', 'last_name');
        }])
        ->with('images')
        ->orderBy('created_at', 'desc');
    
        if ($searchQuery) {
            $query->where(function (Builder $query) use ($searchQuery) {
                $query->where('title', 'like', '%' . $searchQuery . '%')
                    ->orWhere('description', 'like', '%' . $searchQuery . '%')
                    ->orWhereHas('user', function (Builder $query) use ($searchQuery) {
                        $query->where('first_name', 'like', '%' . $searchQuery . '%')
                            ->orWhere('last_name', 'like', '%' . $searchQuery . '%');
                    });
            });
        }
    
        $galleries = $query->paginate(10);
    
        return $galleries;
    }

    public function getGalleryById($id)
    {
        return Gallery::with(['user', 'images', 'comments' => function ($query) {
            $query->orderBy('created_at', 'desc')->with('user'); 
        }])->findOrFail($id);
    }

    public function getUserGalleries($user_id, $searchQuery = null)
    {
        $query = Gallery::where('user_id', $user_id)
        ->with(['user' => function ($query) {
            $query->select('id', 'first_name', 'last_name');
        }])
        ->with('images')
        ->orderBy('created_at', 'desc');

    if ($searchQuery) {
        $query->where(function (Builder $query) use ($searchQuery) {
            $query->where('title', 'like', '%' . $searchQuery . '%')
                ->orWhere('description', 'like', '%' . $searchQuery . '%')
                ->orWhereHas('user', function (Builder $query) use ($searchQuery) {
                    $query->where('first_name', 'like', '%' . $searchQuery . '%')
                        ->orWhere('last_name', 'like', '%' . $searchQuery . '%');
                });
        });
    }

    $galleries = $query->paginate(10);

    return $galleries;
    }

    public function createGallery(GalleryRequest $request)
    {
        
        $validatedData = $request->validated();

        $user = Auth::user();
        $gallery = $user->galleries()->create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
        ]);

        $imageUrls = $validatedData['imageUrls'];
        foreach ($imageUrls as $imageUrl) {
            $image = $gallery->images()->create([
                'url' => $imageUrl,
                'gallery_id' => $gallery->id,
            ]);

            $image->save();
        }
        $gallery->load('user', 'images');
        
        return $gallery;
   
    }

    public function updateGallery(GalleryRequest $request, $gallery_id)
    {
        $validatedData = $request->validated();

        $user = Auth::user();
        $gallery = $user->galleries()->findOrFail($gallery_id);

        $gallery->update([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
        ]);

        $gallery->images()->delete();

        $imageUrls = $validatedData['imageUrls'];
        foreach ($imageUrls as $imageUrl) {
            $image = $gallery->images()->create([
                'url' => $imageUrl,
                'gallery_id' => $gallery->id,
            ]);

            $image->save();
        }
        $gallery->load('user', 'images');
        
        return $gallery;
    }

    public function deleteGallery($gallery_id)
    {
        $user = Auth::user();
        $gallery = $user->galleries()->findOrFail($gallery_id);

        $gallery->images()->delete();

        $gallery->delete();

        return response()->json(['message' => 'Gallery deleted successfully']);
    }
}