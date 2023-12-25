<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Contracts\Http\Kernel;



use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product=Product::getAllProduct();
        
        return view('backend.product.products',["products"=>$product]);
    }
/**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brand=Brand::get();
        $category=Category::where('is_parent',1)->get();
        // return $category;
        return view('backend.product.create')->with('categories',$category)->with('brands',$brand);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       // return $request->all();
    
    //    $this->validate($request,[
    //     'title'=>'string|required',
    //     'summary'=>'string|required',
    //     'description'=>'string|nullable',
    //     'photo'=>'required',
    //     'size'=>'nullable',
    //     'stock'=>"required|numeric",
    //     'cat_id'=>'required|exists:categories,id',
    //     'brand_id'=>'nullable|exists:brands,id',
    //     'child_cat_id'=>'nullable|exists:categories,id',
    //     'is_featured'=>'sometimes|in:1',
    //     'status'=>'required|in:active,inactive',
    //     'condition'=>'required|in:default,new,hot',
    //     'price'=>'required|numeric',
    //     'discount'=>'nullable|numeric'
    // ]);
        
   

    $request->validate([

        'title'=>'string|required',
        'summary'=>'string|required',
        'description'=>'string|nullable',
        'photo'=>'required',
        'size'=>'nullable',
        'stock'=>"required|numeric",
        'cat_id'=>'required|exists:categories,id',
        'brand_id'=>'nullable|exists:brands,id',
        'child_cat_id'=>'nullable|exists:categories,id',
        'is_featured'=>'sometimes|in:1',
        'status'=>'required|in:active,inactive',
        'condition'=>'required|in:default,new,hot',
        'price'=>'required|numeric',
        'discount'=>'nullable|numeric'

    ]);

    $fileModel = new Product();


    $fileModel->title = $request->title;
    $fileModel->summary = $request->summary;    
    $fileModel->description = $request->description;
    if ($request->hasFile('photo')) 
    {
        
        $photoPath = time() . '_' . $request->file('photo')->getClientOriginalName();
       
        $request->file('photo')->move(public_path('images/'), $photoPath);
   
        $fileModel->photo = $photoPath;
    }

    // $fileModel->size = $request->size;
    $fileModel->stock = $request->stock;
    $fileModel->cat_id = $request->cat_id;
    $fileModel->brand_id = $request->brand_id;    
    $fileModel->child_cat_id = $request->child_cat_id;
    $fileModel->is_featured = $request->is_featured;
    $fileModel->status = $request->status;
    $fileModel->condition = $request->condition;
    $fileModel->price = $request->price;
    $fileModel->discount = $request->discount;
    
        

        $slug=Str::slug($request->title);
        $count=Product::where('slug',$slug)->count();
        if($count>0){
            $slug=$slug.'-'.date('ymdis').'-'.rand(0,999);
        }
        $fileModel['slug']=$slug;
        $fileModel['is_featured']=$request->input('is_featured',0);
        $size=$request->input('size');
        if($size){
            $fileModel['size']=implode(',',$size);
        }
        else{
            $fileModel['size']='';
        }
        // return $size;
        // return $data;

        // $status=Product::create($data);
        if($fileModel){
            request()->session()->flash('success','Product Successfully added');
            $fileModel->save();
        }
        else{
            request()->session()->flash('error','Please try again!!');
        }
        return redirect()->route('/product');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $brand=Brand::get();
        $product=Product::findOrFail($id);
        $category=Category::where('is_parent',1)->get();
        $items=Product::where('id',$id)->get();
        // return $items;
        return view('backend.product.edit')->with('product',$product)
                    ->with('brands',$brand)
                    ->with('categories',$category)->with('items',$items);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product=Product::findOrFail($id);
        $this->validate($request,[
            'title'=>'string|required',
            'summary'=>'string|required',
            'description'=>'string|nullable',
            'photo'=>'string|required',
            'size'=>'nullable',
            'stock'=>"required|numeric",
            'cat_id'=>'required|exists:categories,id',
            'child_cat_id'=>'nullable|exists:categories,id',
            'is_featured'=>'sometimes|in:1',
            'brand_id'=>'nullable|exists:brands,id',
            'status'=>'required|in:active,inactive',
            'condition'=>'required|in:default,new,hot',
            'price'=>'required|numeric',
            'discount'=>'nullable|numeric'
        ]);

        $data=$request->all();
        $data['is_featured']=$request->input('is_featured',0);
        $size=$request->input('size');
        if($size){
            $data['size']=implode(',',$size);
        }
        else{
            $data['size']='';
        }
        // return $data;
        $status=$product->fill($data)->save();
        if($status){
            request()->session()->flash('success','Product Successfully updated');
        }
        else{
            request()->session()->flash('error','Please try again!!');
        }
        return redirect()->route('/product');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product=Product::findOrFail($id);
        $status=$product->delete();
        
        if($status){
            request()->session()->flash('success','Product successfully deleted');
        }
        else{
            request()->session()->flash('error','Error while deleting product');
        }
        return redirect()->route('/product');
    }
}
