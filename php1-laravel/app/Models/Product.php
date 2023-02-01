<?php

namespace App\Models;

use App\Services\FileStorageService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price',
        'discount',
        'thumbnail',
        'quantity',
        'SKU',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function setThumbnailAttribute($image)
    {
//        dd(strtolower(str_replace(' ', '_', $this->attributes['title'])));
//        dd($image, $this);
        if (!empty($this->attributes['thumbnail'])) {
            FileStorageService::remove($this->attributes['thumbnail']);
        }

        $this->attributes['thumbnail'] = FileStorageService::upload(
            $image,
            strtolower(str_replace(' ', '_', $this->attributes['title']))
        );
    }
}
