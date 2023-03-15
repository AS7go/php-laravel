<?php

namespace App\Models;

use App\Services\FileStorageService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
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
            get: function () {
                $key = "products.images.{$this->attributes['path']}";
                logs()->info('load image ...');
                if (!Cache::has($key)) { //защита от лишних запросов на сервер
                    logs()->info('load from s3 ...');
                    $link = Storage::temporaryUrl($this->attributes['path'], now()->addMinutes(10));
                    Cache::put($key, $link, 550);
                    return $link;
                }

                return Cache::get($key);
            }
        );
    }
}
