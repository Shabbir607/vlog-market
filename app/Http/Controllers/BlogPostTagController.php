<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\blogpost;
use App\Models\BlogPostCategory;
use App\Models\BlogPostTag;
use App\Models\User;
use Illuminate\Support\Str;

class BlogPostTagController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $postTag=BlogPostTag::orderBy('id','DESC')->paginate(10);
        return view('/backend.blogposttag.index')->with('postTags',$postTag);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.blogposttag.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'title'=>'string|required',
            'status'=>'required|in:active,inactive'
        ]);
        $data=$request->all();
        $slug=Str::slug($request->title);
        $count=BlogPostTag::where('slug',$slug)->count();
        if($count>0){
            $slug=$slug.'-'.date('ymdis').'-'.rand(0,999);
        }
        $data['slug']=$slug;
        $status=BlogPostTag::create($data);
        if($status){
            request()->session()->flash('success','Post Tag Successfully added');
        }
        else{
            request()->session()->flash('error','Please try again!!');
        }
        return redirect()->route('/blogpost-tag');
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
        $postTag=BlogPostTag::findOrFail($id);
        return view('backend.blogposttag.edit')->with('postTag',$postTag);
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
        $postTag=BlogPostTag::findOrFail($id);
         // return $request->all();
         $this->validate($request,[
            'title'=>'string|required',
            'status'=>'required|in:active,inactive'
        ]);
        $data=$request->all();
        $status=$postTag->fill($data)->save();
        if($status){
            request()->session()->flash('success','Post Tag Successfully updated');
        }
        else{
            request()->session()->flash('error','Please try again!!');
        }
        return redirect()->route('/blogpost-tag');
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $postTag=BlogPostTag::findOrFail($id);
       
        $status=$postTag->delete();
        
        if($status){
            request()->session()->flash('success','Post Tag successfully deleted');
        }
        else{
            request()->session()->flash('error','Error while deleting post tag');
        }
        return redirect()->route('/blogpost-tag');
    }
}
