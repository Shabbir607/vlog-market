<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\blogpost;
use App\Models\BlogPostCategory;
use App\Models\BlogPostTag;
use App\Models\User;

class BlogPostController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $blogposts=blogpost::getAllPost();

        // return $posts;
        return view('backend.blogpost.index')->with('posts',$blogposts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories=BlogPostCategory::get();
        $tags=BlogPostTag::get();
        $users=User::get();

        return view('backend.blogpost.create')->with('users',$users)->with('categories',$categories)->with('tags',$tags);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    //     // return $request->all();
    //     $this->validate($request,[
    //         'title'=>'string|required',
    //         'quote'=>'string|nullable',
    //         'summary'=>'string|required',
    //         'description'=>'string|nullable',
    //         'photo'=>'string|nullable',
    //         'tags'=>'nullable',
    //         'added_by'=>'nullable',
    //         'post_cat_id'=>'required',
    //         'status'=>'required|in:active,inactive'
    //     ]);
        $request->validate([
            'title'=>'string|required',
            'quote'=>'string|nullable',
            'summary'=>'string|required',
            'description'=>'string|nullable',
            'photo'=>'required',
            'tags'=>'nullable',
            'added_by'=>'nullable',
            'is_featured'=>'nullable',
            'post_cat_id'=>'required',
            'status'=>'required|in:active,inactive'
        ]);


        $fileModel = new blogpost();
        $fileModel->title = $request->title;
        $fileModel->summary = $request->summary;
        $fileModel->description = $request->description;
        $fileModel->quote = $request->quote;
        $fileModel->added_by = $request->added_by;
        $fileModel->is_featured = $request->is_featured;
        $fileModel->post_cat_id = $request->post_cat_id;
        $fileModel->status = $request->status;

        if ($request->hasFile('photo'))
        {

            $photoPath = time() . '_' . $request->file('photo')->getClientOriginalName();

            $request->file('photo')->move(public_path('images/'), $photoPath);

            $fileModel->photo = $photoPath;
        }

        $slug=Str::slug($request->title);
        $count=blogpost::where('slug',$slug)->count();
        if($count>0){
            $slug=$slug.'-'.date('ymdis').'-'.rand(0,999);
        }
        $fileModel['slug']=$slug;

        $tags=$request->input('tags');
        if($tags){
            $fileModel['tags']=implode(',',$tags);
        }
        else{
            $fileModel['tags']='';
        }
        // return $data;

        // dd($fileModel);
        // $status=blogpost::create($fileModel);

        if($fileModel){
            request()->session()->flash('success','Product Successfully added');
            $fileModel->save();
        }
        else{
            request()->session()->flash('error','Please try again!!');
        }
        return redirect()->route('/blogpost');
    }







    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post=blogpost::findOrFail($id);
        $categories=BlogPostCategory::get();
        $tags=BlogPostTag::get();
        $users=User::get();
        return view('backend.blogpost.edit')->with('categories',$categories)->with('users',$users)->with('tags',$tags)->with('post',$post);
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
    $fileModel = blogpost::findOrFail($id);

    $request->validate([
        'title' => 'string|required',
        'quote' => 'string|nullable',
        'summary' => 'string|required',
        'description' => 'string|nullable',
        'photo'=>'required', // Remove 'required' as you might not be updating the photo
        'tags' => 'nullable',
        'added_by' => 'nullable',
        'post_cat_id' => 'required',
        'status' => 'required|in:active,inactive'
    ]);

    $fileModel->title = $request->title;
    $fileModel->summary = $request->summary;
    $fileModel->description = $request->description;
    $fileModel->quote = $request->quote;
    $fileModel->added_by = $request->added_by;
    $fileModel->post_cat_id = $request->post_cat_id;
    $fileModel->status = $request->status;
    if ($request->hasFile('photo')) {
        $photoPath = time() . '_' . $request->file('photo')->getClientOriginalName();
        $request->file('photo')->move(public_path('images/'), $photoPath);
        $fileModel->photo = $photoPath;
    }

    $slug = Str::slug($request->title);
    $count = blogpost::where('slug', $slug)->where('id', '!=', $id)->count();
    if ($count > 0) {
        $slug = $slug . '-' . date('ymdis') . '-' . rand(0, 999);
    }
    $fileModel->slug = $slug;

    $tags = $request->input('tags');
    if ($tags) {
        $fileModel->tags = implode(',', $tags);
    } else {
        $fileModel->tags = '';
    }

    $status = $fileModel->save();

    if ($status) {
        request()->session()->flash('success', 'Post Successfully updated');
    } else {
        request()->session()->flash('error', 'Please try again!!');
    }

    return redirect()->route('/blogpost');
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post=blogpost::findOrFail($id);

        $status=$post->delete();

        if($status){
            request()->session()->flash('success','Post successfully deleted');
        }
        else{
            request()->session()->flash('error','Error while deleting post ');
        }
        return redirect()->route('/blogpost');
    }
}
