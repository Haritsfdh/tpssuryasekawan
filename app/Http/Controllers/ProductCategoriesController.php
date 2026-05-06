<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class ProductCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();

        return view('pages.product.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $name = 'create';
        return view('pages.product.categories.create', compact('name'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $category = $request->input('category');

        Category::create([
            'name' => $category
        ]);

        return redirect('/product/category')->with('success', 'Successfully added category.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $name = 'edit';
        $category = Category::where('id', $id)->first();
        return view('pages.product.categories.create', compact(['name', 'category']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = ['name' => $request->input('update')];

        Category::where('id', $id)->update($data);

        return redirect('/product/category')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
        Category::destroy($id);

        return redirect('/product/category')->with('success', 'Category deleted successfully.');
    }
}
