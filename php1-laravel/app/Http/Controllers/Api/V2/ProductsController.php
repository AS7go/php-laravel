<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\ProductsV2\ProductsResource;
use App\Http\Resources\ProductsV2\SingleProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return ProductsResource
     */
    public function index()
    {
        $products = Product::paginate(3);

        return new ProductsResource($products); //v2
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return SingleProductResource
     */
    public function show(Product $product)
    {
        if (!$this->userCan('read')){
            return $this->notAllowedResponse();
        }

        return new SingleProductResource($product); //V2
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
