<?php

namespace App\Http\Resources\v1\category;

use App\Http\Resources\v1\product\ProductCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'parent_id'=>$this->parent_id,
            'name'=>$this->name,
            'description'=>$this->description,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
            'deleted_at'=>$this->deleted_at,
            'subcategories'=>new CategoryCollection($this->whenLoaded('subcategories')),
            'products'=>new ProductCollection($this->whenLoaded('products',function(){
                return $this->products->load('images');
            }))
        ];
    }
}
