<?php

namespace App\Models;

use App\Services\FileStorageService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function imageable()
    {
        return $this->morphTo();
    }

    public function setPathAttribute($image)
    {
//        dd($this->attributes);
        $this->attributes['path'] = FileStorageService::upload(
            $image,
            $this->attributes['directory'] ?? null
        );
    }

    public function url(): Attribute
    {
        return Attribute::make(
            get: fn() => Storage::exists($this->imagePath)
                ? Storage::url($this->imagePath)
                : $this->imagePath
        );
    }

    public function imagePath(): Attribute
    {
        return Attribute::get(
            fn() => $this->attributes['directory'] . '/' . $this->attributes['path']
        );
    }
}
