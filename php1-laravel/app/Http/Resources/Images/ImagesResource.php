<?php

namespace App\Http\Resources\Images;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ImagesResource extends ResourceCollection
{
    public $collects = SingleImageResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
