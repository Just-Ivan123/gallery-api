<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Gallery;
use App\Models\User;
use App\Models\Image;
use App\Models\Comment;

class GalleryFactory extends Factory
{
    protected $model = Gallery::class;

    public function definition()
    {
        return [
            'title' => fake()->sentence,
            'description' => fake()->paragraph,
            
        ];
    }
    
    // Добавим метод для создания связанных с галереей изображений и комментариев
    public function configure()
    {
        return $this->afterCreating(function (Gallery $gallery) {
            Image::factory(3)->create(['gallery_id' => $gallery->id]);
            Comment::factory(5)->create(['user_id' => $gallery->user_id, 'gallery_id' => $gallery->id]);
        });
    }
}