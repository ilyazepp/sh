<?php

namespace SHammer;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{	
    public $timestamps = false;

    protected $fillable = ['name', 'description', 'image', 'imageDescription', 'price', 'user_id', 'category_id'];

    /**
     * Get the product category.
     */
    public function categories()
    {
        return $this->hasOne('SHammer\Category', 'id');
    }

    /**
     * Get the product tags.
     */
    public function tags()
    {
        return $this->hasManyThrough('SHammer\Tag', 'SHammer\ProductTag', 'product_id', 'id');
    }
}
