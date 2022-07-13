<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'Brands';
    protected $guarded = ['id'];
    public function products(){
        return $this->hasMany(Product::class);
    }
}
