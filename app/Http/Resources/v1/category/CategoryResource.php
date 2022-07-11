<?php

namespace App\Http\Resources\v1\category;

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
            'id'=>$request->id,
            'parent_id'=>$request->parent_id,
            'name'=>$request->name,
            'description'=>$request->description,
            'created_at'=>$request->created_at,
            'updated_at'=>$request->updated_at,
            'deleted_at'=>$request->deleted_at,
            'subcategories'=>new CategoryCollection($this->whenLoaded('subcategories'))
        ];
    }
}
