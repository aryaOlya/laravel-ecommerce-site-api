<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\brand\BrandCollection;
use App\Http\Resources\v1\brand\BrandResource;
use App\Http\Resources\v1\category\CategoryCollection;
use App\Http\Resources\v1\category\CategoryResource;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryController extends ApiController
{

    public function index()
    {
        $categories = Category::paginate(3);
        return $this::successResponse(200,[
            'brands'=>BrandResource::collection($categories),
            'links'=> BrandResource::collection($categories)->response()->getData()->links,
            'meta'=> BrandResource::collection($categories)->response()->getData()->meta,
        ]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'parent_id'=>'integer',
            'name'=>'required|string|unique:categories,name',
            'description'=>'string',
        ]);

        if ($validator->fails()){
            return $this::errorResponse(422,$validator->messages());
        }

        $category = Category::create([
            'parent_id'=>$request->parent_id,
            'name'=>$request->name,
            'description'=>$request->description
        ]);

        return $this::successResponse(201,new CategoryResource($category));
    }


    public function show($id)
    {
        $category = Category::findOrFail($id);
        return $this::successResponse(200,new BrandResource($category));
    }


    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(),[
            'parent_id'=>'integer',
            'name'=>['required',Rule::unique('categories','name')->ignore($category->id)],
            'description'=>'string',
        ]);

        if ($validator->fails()){
            return $this::errorResponse(422,$validator->messages());
        }

        $category->update([
            'parent_id'=>$request->parent_id,
            'name'=>$request->name,
            'description'=>$request->description
        ]);

        return $this::successResponse(201,new CategoryResource($category));
    }


    public function destroy(Category $category)
    {
        $children = Category::where('parent_id',$category->id)->get();
        //return $children;
        $category->delete();
        foreach ($children as $child){
            Category::find($child->id)->update([
                'parent_id'=>0
            ]);
        }
        return $this::successResponse(200,new CategoryResource($category));
    }

    public function subcategory(Category $category){
        //just show the subcategories
//        $subcategory = Category::where('parent_id',$category->id)->get();
//        return $this::successResponse(200,new CategoryCollection($subcategory));

        //show the parent & categories both with relations
        return $this::successResponse(200,new CategoryResource($category->load('subcategories')));

    }
}
