<?php

namespace App\Http\Controllers;

use App\Services\GalleryService;
use App\Http\Requests\GalleryRequest;

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
        return response()->json($galleries);
    }

    public function show($id)
    {
        $gallery = $this->galleryService->getGalleryById($id);
        return response()->json($gallery);
    }

    public function store(GalleryRequest $request)
    {
        $gallery = $this->galleryService->createGallery($request);
        return response()->json($gallery, 201);
    }

    public function update(GalleryRequest $request, $id)
    {
        $gallery = $this->galleryService->updateGallery($request, $id);
        return response()->json($gallery);
    }

    public function destroy($id)
    {
        $this->galleryService->deleteGallery($id);
        return response()->json(null, 204);
    }
}