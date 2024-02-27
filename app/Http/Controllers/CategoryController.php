<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Models\Marketplace;
use App\Models\Country;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category=Category::getAllCategory();
        // return $category;
        return view('backend.category.index')->with('categories',$category);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $markets = Marketplace::with("country:id,name")->get();
        $parent_cats=Category::where('is_parent',1)->orderBy('title','ASC')->get();
        return view('backend.category.create')->with('parent_cats',$parent_cats)
        ->with('markets',$markets);

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
        // $this->validate($request,[
        //     'title'=>'string|required',
        //     'summary'=>'string|nullable',
        //     'photo'=>'required',
        //     'status'=>'required|in:active,inactive',
        //     'is_parent'=>'sometimes|in:1',
        //     'parent_id'=>'nullable|exists:categories,id',
        // ]);


        $request->validate([

            'title'=>'string|required',
            'summary'=>'string|required',
            'photo'=>'required',
            'parent_id'=>'nullable|exists:categories,id',
            'is_parent'=>'sometimes|in:1',
            'status'=>'required|in:active,inactive',


        ]);

        $fileModel = new Category();

        $fileModel->title = $request->title;
    $fileModel->summary = $request->summary;

    if ($request->hasFile('photo'))
    {
        $photoPath = time() . '_' . $request->file('photo')->getClientOriginalName();

        $request->file('photo')->move(public_path('images/'), $photoPath);

        $fileModel->photo = $photoPath;
    }
    $fileModel->parent_id = $request->parent_id;
    $fileModel->is_parent = $request->is_parent;
    $fileModel->status = $request->status;
    $fileModel->country_id = $request->country;
    $fileModel->market_id = $request->market;

        // $data= $request->all();

        $slug=Str::slug($request->title);
        $count=Category::where('slug',$slug)->count();
        if($count>0){
            $slug=$slug.'-'.date('ymdis').'-'.rand(0,999);
        }
        $fileModel['slug']=$slug;
        $fileModel['is_parent']=$request->input('is_parent',0);
        // return $data;
        // $status=Category::create($data);
        if($fileModel){
            request()->session()->flash('success','Category successfully added');
            $fileModel->save();
        }
        else{
            request()->session()->flash('error','Error occurred, Please try again!');
        }
        return redirect()->route('/category');


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
        $parent_cats=Category::where('is_parent',1)->get();
        $category=Category::findOrFail($id);
        return view('backend.category.edit')->with('category',$category)->with('parent_cats',$parent_cats);
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
        // return $request->all();
        $category=Category::findOrFail($id);
        $this->validate($request,[
            'title'=>'string|required',
            'summary'=>'string|nullable',
            'photo'=>'string|nullable',
            'status'=>'required|in:active,inactive',
            'is_parent'=>'sometimes|in:1',
            'parent_id'=>'nullable|exists:categories,id',
        ]);
        $data= $request->all();
        $data['is_parent']=$request->input('is_parent',0);
        // return $data;
        $status=$category->fill($data)->save();
        if($status){
            request()->session()->flash('success','Category successfully updated');
        }
        else{
            request()->session()->flash('error','Error occurred, Please try again!');
        }
        return redirect()->route('/category');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
{
    $category = Category::findOrFail($id);
    $child_cat_id = Category::where('parent_id', $id)->pluck('id');

    $status = $category->delete();

    if ($status) {
        if (count($child_cat_id) > 0) {
            Category::shiftChild($child_cat_id);
        }
        request()->session()->flash('success', 'Category successfully deleted');
    } else {
        request()->session()->flash('error', 'Error while deleting category');
    }

    return redirect()->route('/category');
}


    public function getChildByParent(Request $request){
        // return $request->all();
        $category=Category::findOrFail($request->id);
        $child_cat=Category::getChildByParentID($request->id);
        // return $child_cat;
        if(count($child_cat)<=0){
            return response()->json(['status'=>false,'msg'=>'','data'=>null]);
        }
        else{
            return response()->json(['status'=>true,'msg'=>'','data'=>$child_cat]);
        }
    }
}
