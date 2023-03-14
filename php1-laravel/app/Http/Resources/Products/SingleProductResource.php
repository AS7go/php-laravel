<?php

namespace App\Http\Resources\Products;

use App\Http\Resources\Categories\CategoriesResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleProductResource extends JsonResource
{
    public static $wrap = 'data';
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'thumbnail'=>url($this->thumbnailUrl),
            'prices'=>$this->getPrices(),
            'categories' => new CategoriesResource($this->categories)
        ];
    }

    protected function getPrices(): array
    {
        return [
            'price' => $this->price,
            'discount' => $this->discount,
            'final_price' => $this->endPrice
        ];
    }
}
