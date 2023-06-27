<?php

namespace App\Services;

use App\Models\Gallery;
use App\Http\Requests\GalleryRequest;

class GalleryService
{
    public function getAllGalleries()
    {
        return Gallery::all();
    }

    public function getGalleryById($id)
    {
        return Gallery::findOrFail($id);
    }

    public function createGallery(GalleryRequest $request)
    {
        $validatedData = $request->validated();

        $user = Auth::user();
        $gallery = $user->galleries()->create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
        ]);

        $imageUrls = $validatedData['image_urls'];
        foreach ($imageUrls as $imageUrl) {
        
            $image = Image::create([
                'url' => $imageUrl,
            ]);
        
            $gallery->images()->attach($image->id);
        }
        return $gallery;
    }

    public function updateGallery(GalleryRequest $request, $id)
    {
        $validatedData = $request->validated();
        $gallery = Gallery::findOrFail($id);
        $gallery->update($validatedData);
        return $gallery;
    }

    public function deleteGallery($id)
    {
        $gallery = Gallery::findOrFail($id);
        $gallery->delete();
    }
}