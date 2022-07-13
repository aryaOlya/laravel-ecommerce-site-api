<?php

namespace App\Http\Resources\v1\product;

use App\Http\Resources\v1\product_images\ProductImageCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'brand_id'=>$this->brand_id,
            'category_id'=>$this->category_id,
            'primary_image'=>$this->primary_image,
            'description'=>$this->description,
            'price'=>$this->price,
            'quantity'=>$this->quantity,
            'delivery_amount'=>$this->delivery_amount,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
            'deleted_at'=>$this->deleted_at,
            'images'=>new ProductImageCollection($this->whenLoaded('images'))
        ];
    }
}
