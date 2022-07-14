<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'categories';
    protected $guarded = ['id'];

    //make a relation with itself
    public function subcategories(){
        return $this->hasMany(Category::class,'parent_id');
    }

    //make a relation with products
    public function products(){
        return $this->hasMany(Product::class);
    }
}
