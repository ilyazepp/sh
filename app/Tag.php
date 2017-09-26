<?php

namespace SHammer;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $timestamps = false;

    protected $fillable = ['title'];

    public function product_tag() 
    {
    	return $this->hasMany('SHammer\ProductTag');
    }
}
