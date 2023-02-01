<?php

namespace App\Repositories;

use App\Http\Requests\Admin\CreateProductRequest;
use App\Models\Product;
use App\Repositories\Contract\ProductRepositoryContract;

class ProductRepository implements ProductRepositoryContract
{
    public function create(CreateProductRequest $request): Product|bool
    {
        try {
            \DB::beginTransaction();

            $data = collect($request->validated())->except(['categories'])->toArray();
            $categories = $request->get('categories', []);
            $product = Product::create($data);
            $this->setCategories($product, $categories);
//            dd($data, $categories);
            dd($product, $product->categories);

            \DB::rollBack();
//            \DB::commit();

            return new Product();
        } catch (\Exception $exception) {
//            \DB::rollBack();
            logs()->warning($exception);

            return false;
        }
    }

    public function setCategories(Product $product, array $categories = []): void
    {
        if (!empty($categories)) {
            $product->categories()->attach($categories);
        }
    }
}
