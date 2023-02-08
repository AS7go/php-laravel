<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can(config('permission.access.products.edit'));
//        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $productsId = $this->route('product')->id;

        return [
//            'title' => ['required', 'string', 'min:2', 'max:255', 'unique:products'],
            'title' => ['required', 'string', 'min:2', 'max:255', Rule::unique('products', 'title')->ignore($productsId)],
            'description' => ['nullable', 'string'],
//            'SKU' => ['required', 'string', 'min:1', 'max:35', 'unique:products'],
            'SKU' => ['required', 'string', 'min:1', 'max:35', Rule::unique('products', 'SKU')->ignore($productsId)],
            'price' => ['required', 'numeric', 'min:1'],
            'discount' => ['required', 'numeric', 'min:0', 'max:99'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'categories.*' => ['required', 'numeric', 'exists:App\Models\Category,id'],
            'thumbnail' => ['required', 'image:jpeg,png'],
            'images.*' => ['image:jpeg,png']
        ];
    }
}
