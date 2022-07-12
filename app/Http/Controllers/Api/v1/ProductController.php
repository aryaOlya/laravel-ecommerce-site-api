<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\product\ProductResource;
use App\Models\Product;
use App\Models\ProductImage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends ApiController
{

    public function index()
    {

    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'brand_id'=>'required|integer',
            'category_id'=>'required|integer',
            'primary_image'=>'required|image',
            'description'=>'required|string',
            'price'=>'required|integer',
            'quantity'=>'required|integer',
            'delivery_amount'=>'required|integer',
            'images.*'=>'image'
        ]);
        //return $request->all();

        if ($validator->fails()){
            return $this::errorResponse(422,$validator->messages());
        }

        $primaryImageName = Carbon::now()->microsecond.'.'.$request->primary_image->extension();
        //return $primaryImageName;
        $request->primary_image->move('images/products/primary',$primaryImageName);

        $imagesName = [];
        if ($request->has('images')){
            foreach ($request->images as $image){
                $imageName = Carbon::now()->microsecond.'.'.$image->extension();
                $image->move('images/products/secondary',$imageName);
                array_push($imagesName,$imageName);
            }
        }

        $product = Product::create([
            'name'=>$request->name,
            'brand_id'=>$request->brand_id,
            'category_id'=>$request->category_id,
            'primary_image'=>$primaryImageName,
            'description'=>$request->description,
            'price'=>$request->price,
            'quantity'=>$request->quantity,
            'delivery_amount'=>$request->delivery_amount,
        ]);

        foreach ($imagesName as $imageName){
            ProductImage::create([
                'product_id'=>$product->id,
                'image'=>$imageName
            ]);
        }

        return $this::successResponse(201,new ProductResource([$product,$imagesName]));


    }


    public function show($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
