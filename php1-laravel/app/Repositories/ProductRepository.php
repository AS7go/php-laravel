<?php

namespace App\Repositories;

use App\Http\Requests\Admin\CreateProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Product;
use App\Repositories\Contracts\ImageRepositoryContract;
use App\Repositories\Contracts\ProductRepositoryContract;
use Illuminate\Support\Collection;

class ProductRepository implements ProductRepositoryContract
{
    public function __construct(protected ImageRepositoryContract $imagesRepository)
    {
    }

    public function create(CreateProductRequest $request): Product|bool
    {
        try {
            \DB::beginTransaction();

            $data = $this->getData($request);
            $product = Product::create($data['attributes']);
            $this->setProductData($product, $data);

            \DB::commit();

            return $product;
        } catch (\Exception $exception) {
            \DB::rollBack();
            logs()->warning($exception);
            return false;
        }
    }

    public function setCategories(Product $product, array $categories = []): void
    {
        if ($product->categories()->exists()) {
            $product->categories()->detach();
        }

        if (!empty($categories)) {
            $product->categories()->attach($categories);
        }
    }

    public function update(Product $product, UpdateProductRequest $request): bool
    {
        try {
            \DB::beginTransaction();

            $product->update($request->validated());
            $this->setProductData($product, $this->getData($request));

            \DB::commit();

            return true;
        } catch (\Exception $exception) {
            \DB::rollBack();
            logs()->warning($exception);
            return false;
        }
    }

    protected function setProductData(Product $product, array $data)
    {
        $this->setCategories($product, $data['categories']);
        $this->attachImages($product, $data['attributes']['images'] ?? []);
    }

    protected function getData(CreateProductRequest|UpdateProductRequest $request): array
    {
        return [
            'attributes' => collect($request->validated())->except(['categories'])->toArray(),
            'categories' => $request->get('categories', [])
        ];
    }

    protected function attachImages(Product $product, array $images = [])
    {
        $this->imagesRepository->attach($product, 'images', $images, $product->slug);
    }
}
