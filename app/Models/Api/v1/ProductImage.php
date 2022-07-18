<?php

namespace App\Models\Api\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductImage extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'product_images';
    protected $guarded = ['id'];
    public function product(){
        return $this->belongsTo(Product::class);
    }
}
