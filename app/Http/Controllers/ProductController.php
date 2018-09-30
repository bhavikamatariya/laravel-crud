<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$products = Product::select('products.*', 'categories.title AS category_title')
			->join('categories', 'categories.id', '=', 'products.category_id')
			->orderBy('products.created_at')->paginate(5);

		return view('products.index', compact('products'))
			->with('i', (request()->input('page', 1) - 1) * 5);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$categories = Category::select('title', 'id')->get();

		return view('products.create', compact('categories'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$request->validate([
			'title'       => 'required',
			'category_id' => 'required',
			'image'       => 'required|mimes:jpg,jpeg,png|max:2048',
			'description' => 'required',
		]);

		if ($request->hasFile('image'))
		{
			$imageName = time() . '.' . request()->image->getClientOriginalExtension();
			request()->image->move(public_path('images'), $imageName);
		}

		$product              = new Product();
		$product->title       = $request->get('title');
		$product->category_id = $request->get('category_id');
		$product->image       = $imageName ?: '';
		$product->description = $request->get('description');

		$product->save();

		return redirect()->route('products.index')
			->with('success', 'Products created successfully');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Product $product
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(Product $product)
	{
		$category = Category::find($product->category_id);

		return view('products.show', compact('product', 'category'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Product $product
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Product $product)
	{
		$categories = Category::select('title', 'id')->get();

		return view('products.edit', compact('product', 'categories'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \App\Product             $product
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Product $product)
	{
		$request->validate([
			'title'       => 'required',
			'category_id' => 'required',
			'description' => 'required',
		]);

		$product = Product::find($product->id);

		if ($request->hasFile('image'))
		{
			$imageName = time() . '.' . request()->image->getClientOriginalExtension();
			request()->image->move(public_path('images'), $imageName);
			$product->image = $imageName ?: '';
		}

		$product->title       = $request->get('title');
		$product->category_id = $request->get('category_id');
		$product->description = $request->get('description');

		$product->save();

		return redirect()->route('products.index')
			->with('success', 'Product updated successfully');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Product $product
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Product $product)
	{
		$product->delete();

		return redirect()->route('products.index')
			->with('success', 'Product deleted successfully');
	}
}
