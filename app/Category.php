<?php

namespace SHammer;

use Illuminate\Database\Eloquent\Model;
use Nestable\NestableTrait;


class Category extends Model
{
    public $timestamps = false;

    use NestableTrait;

    protected $parent = 'parent_id';

    protected $fillable = ['name', 'description', 'parent_id'];

    public function subcategories()
    {
        return $this->hasMany('SHammer\Category', 'parent_id');
    }
}
