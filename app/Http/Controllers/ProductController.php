<?php

namespace SHammer\Http\Controllers;

use SHammer\Product;
use SHammer\Category;
use SHammer\ProductTag;
use Illuminate\Http\Request;
use SHammer\Http\Requests\StoreProductRequest;

class ProductController extends Controller
{

    public function __construct() 
    {
        $this->middleware('make.auth', ['except' => ['show', 'index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Product::query();
        $query = $this->_filter($request, $query);        

        $products = $query->get()->each(function ($item) {
            $this->show($item);
        });

        return response()->json(
            [
                'code'      =>  200,
                'products'  =>  $products
            ], 200);
    }

    protected function _filter(Request $request, $query)
    {
        if ($request->has('name')) 
        {
            $query->where('name', 'like', '%' . $request->get('name') . '%');
        }

        if ($request->has('description')) 
        {
            $query->where('description', 'like', '%' . $request->get('description') . '%');
        }

        if ($request->has('priceFrom')) 
        {
            $query->where('price', '>=', $request->get('priceFrom'));
        }

        if ($request->has('priceTo')) 
        {
            $query->where('price', '<', $request->get('priceTo'));
        }

        return $query;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $product = $request->only([
                        'name',
                        'description',
                        'price',
                        'image',
                        'imageDescription',
                        'category_id'
                    ]);

        $product['user_id'] = \Auth::user()->id;

        $newProductId = Product::create($product)->id;

        $tags = $request->get('tags');
        
        if (is_array($tags) && count($tags)) {
            $tagsArr = array_map(function ($item) use ($newProductId) {
                return ['product_id' => $newProductId, 'tag_id' => $item];
            }, array_values($tags));

            ProductTag::insert($tagsArr);
        }

        return response()->json(
            [
                'code'      =>  200,
                'message'   =>  'Successfully added'
            ], 200);
    }

    /**
     * 
     *  Rcursivly get Categories
     * 
     */
    protected function _getSubCategories($category) 
    {
        $category->each(function ($item) {
            $this->_getSubCategories($item->subcategories);
        });
    }

    protected function _getShortDescription($description)
    {
        return substr($description, 0, strlen($description) * 0.2) . '...';
    }

    /**
     * Display the specified resource.
     *
     * @param  \SHammer\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $this->_getSubCategories($product->categories->subcategories);

        $product['tags'] = $product->tags;
        $product->shortDescription = $this->_getShortDescription($product->description);

        return response()->json(
            [
                'code'      =>  200,
                'product'   =>  $product
            ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \SHammer\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(StoreProductRequest $request, Product $product)
    {
        if ($product->user_id == \Auth::user()->id) {
            $product->update(request()->all());
            return response()->json(
            [
                'code'      =>  200,
                'message'   =>  'Successfully updated'
            ], 200);
        } else {
            return response()->json(
                [
                    'code'      =>  403,
                    'message'   =>  'You don\'t have permissions for this'
                ], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \SHammer\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if ($product->user_id == \Auth::user()->id) {
            $product->delete();
            return response()->json(
                [
                    'code'      =>  200,
                    'message'   =>  'Successfully deleted'
                ], 200);
        } else {
            return response()->json(
                [
                    'code'      =>  403,
                    'message'   =>  'You don\'t have permissions for this'
                ], 403);
        }
    }
}
