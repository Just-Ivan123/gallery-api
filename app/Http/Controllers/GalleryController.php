<?php

namespace App\Http\Controllers;

use App\Services\GalleryService;
use App\Http\Requests\GalleryRequest;
use Illuminate\Support\Facades\Auth;

class GalleryController extends Controller
{
    private $galleryService;

    public function __construct(GalleryService $galleryService)
    {
        $this->galleryService = $galleryService;
    }

    public function index()
    {
        $galleries = $this->galleryService->getAllGalleries();
        return $galleries;
    }

    public function show($id)
    {
        $gallery = $this->galleryService->getGalleryById($id);
        return response()->json($gallery);
    }

    public function userGalleries($user_id)
    {
        $galleries = $this->galleryService->getUserGalleries($user_id);
        return response()->json($galleries);
    }

    public function store(GalleryRequest $request)
    {
        try {
            $gallery = $this->galleryService->createGallery($request);
            return response()->json($gallery);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function update(GalleryRequest $request, $id)
    {
        $gallery = $this->galleryService->updateGallery($request, $id);
        return response()->json($gallery);
    }

    public function destroy($id)
    {
        return $this->galleryService->deleteGallery($id);
    }
}