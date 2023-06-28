<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GalleryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required',
            'description' => 'required',
            'imageUrls' => 'required|array',
            'imageUrls.*' => ['required', 'regex:/\.(png|jpeg|jpg)$/i'],
        ];
    }
}
