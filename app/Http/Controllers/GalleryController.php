<?php

namespace App\Http\Controllers;

use App\Services\GalleryService;
use App\Http\Requests\GalleryRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    private $galleryService;

    public function __construct(GalleryService $galleryService)
    {
        $this->galleryService = $galleryService;
    }

    public function index(Request $request)
    {
        $searchQuery = $request->query('search');
        $galleries = $this->galleryService->getAllGalleries($searchQuery);
        return $galleries;
    }

    public function show($id)
    {
        $gallery = $this->galleryService->getGalleryById($id);
        return response()->json($gallery);
    }

    public function userGalleries(Request $request , $user_id)
    {
        $searchQuery = $request->query('search');
        $galleries = $this->galleryService->getUserGalleries($user_id, $searchQuery);
        return $galleries;
    }

    public function store(GalleryRequest $request)
    {
        try {
            $gallery = $this->galleryService->createGallery($request);
            return $gallery;
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