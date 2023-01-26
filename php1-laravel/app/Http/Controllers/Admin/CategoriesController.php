<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateCategory;
use App\Http\Requests\Admin\UpdateCategory;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $categories = Category::with('parent:id,name')->withCount('products')->orderByDesc('id')->paginate(5);
//        $categories = Category::with('parent:id,name')->withCount('products')->paginate(5);
//        $categories = Category::withCount('products')->paginate(10);
//        $categories = Category::paginate(10);
        return view('admin/categories/index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
//        dd(config('permission.access.categories.publish'));
        return view('admin/categories/create', ['categories' => Category::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCategory $request)
    {
//        dd($request->validated()); //name, description, parent_id

        Category::create($request->validated());
        return redirect()->route('admin.categories.index');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Category $category)
    {
        $this->middleware('permission:' . config('permission.access.categories.edit'));
//        dd($category);
        return view('admin/categories/edit', ['categories' => Category::all(), 'category' => $category]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCategory $request, Category $category)
    {
//        dd($request->validated(), $category->updateOrFail($request->validated()));
//        dd($request->validated(), $category);
        $category->updateOrFail($request->validated());
        return redirect()->route('admin.categories.edit', $category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
