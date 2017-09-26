<?php

namespace SHammer\Http\Controllers;

use SHammer\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{

    public function search(Request $request)
    {
        $productArr = [];
        $tag = Tag::where('title', $request->get('q'))->with('product_tag')->get()->first()->product_tag;
        $productController = new ProductController();

        $productArr = $tag->each(function ($item) use ($productController) {
            $productController->show($item->product);
        });

        return response()->json(
            [
                'code'      => 200,
                'products'  => $productArr->toArray()
            ], 200);
    }
}
