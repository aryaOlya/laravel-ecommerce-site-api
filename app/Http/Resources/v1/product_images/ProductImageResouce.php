<?php

namespace App\Http\Resources\v1\product_images;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductImageResouce extends JsonResource
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
        return[
            'id'=>$request->id,
            'product_id'=>$request->product_id,
            'image'=>$request->image
        ];
    }
}
