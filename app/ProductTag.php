<?php

namespace SHammer;

use Illuminate\Database\Eloquent\Model;

class ProductTag extends Model
{
    protected $table = 'product_tags';

    public $timestamps = false;

    protected $fillable = ['product_id', 'tag_id'];

    public function product()
    {
    	return $this->belongsTo('SHammer\Product', 'product_id');
    }
}
