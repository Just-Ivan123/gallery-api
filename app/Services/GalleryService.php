<?php

namespace App\Services;

use App\Http\Requests\GalleryRequest;
use App\Models\Gallery;
use App\Models\User;
use App\Models\Image;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;


class GalleryService
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    
    public function getAllGalleries()
    {
        $galleries = Gallery::with(['user' => function ($query) {
            $query->select('id', 'first_name', 'last_name');
        }])
        ->with('images') // Remove the take(1) method call
        ->paginate(10);
        
        
        return $galleries;
    }

    public function getGalleryById($id)
    {
        return Gallery::with(['user', 'images', 'comments'])->findOrFail($id);
    }

    public function getUserGalleries($user_id)
    {
        $user = User::findOrFail($user_id);
    
        $galleries = Gallery::where('user_id', $user_id)
            ->with('images')
            ->paginate(10);

        return [
            'user' => [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
            ],
            'galleries' => $galleries,
        ];
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
            $image = Image::create([
                'url' => $imageUrl,
                'gallery_id' => $gallery->id,
            ]);

            $image->save();
        }

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