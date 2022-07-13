<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\brand\BrandResource;
use App\Http\Resources\v1\category\CategoryResource;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BrandController extends ApiController
{

    public function index()
    {
        $brands = Brand::select(['name','display_name'])->paginate(15);
        return $this::successResponse(200,[
            'brands'=>BrandResource::collection($brands),
            'links'=> BrandResource::collection($brands)->response()->getData()->links,
            'meta'=> BrandResource::collection($brands)->response()->getData()->meta,
        ]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'display_name'=>'required|unique:brands,display_name'
        ]);

        if ($validator->fails()){
            return $this::errorResponse(422,$validator->messages());
        }

        $brand = Brand::create([
            'name'=>$request->name,
            'display_name'=>$request->display_name
        ]);

        return $this::successResponse(201,new CategoryResource($brand));
    }


    public function show($id)
    {
        $brand = Brand::findOrFail($id);
        return $this::successResponse(200,new BrandResource($brand));
    }


    public function update(Request $request, Brand $brand)
    {
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'display_name'=>['required',Rule::unique('brands')->ignore($brand->id)],
        ]);

        if ($validator->fails()){
            return $this::errorResponse(422,$validator->messages());
        }

        $brand->update([
            'name'=>$request->name,
            'display_name'=>$request->display_name
        ]);

        return $this::successResponse(201,$brand);
    }


    public function destroy(Brand $brand)
    {
        $brand->delete();
        return $this::successResponse(200,new BrandResource($brand));
    }

    public function products(Brand $brand){
        return $this::successResponse(200,new BrandResource($brand->load('products')));
    }
}
