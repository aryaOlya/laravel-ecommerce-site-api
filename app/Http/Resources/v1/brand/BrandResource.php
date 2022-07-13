<?php

namespace App\Http\Resources\v1\brand;

use App\Http\Resources\v1\product\ProductCollection;
use App\Http\Resources\v1\product_images\ProductImageCollection;
use App\Http\Resources\v1\product_images\ProductImageResouce;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return[
            'id'=>$this->id,
            'name'=>$this->name,
            'display_name'=>$this->display_name,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
            'deleted_at'=>$this->deleted_at,
            'products'=>new ProductCollection($this->whenLoaded('products'))
        ];
    }
}
