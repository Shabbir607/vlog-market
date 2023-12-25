<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobApplication;

class JobApplyController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobs=JobApplication::orderBy('id')->paginate(10);
        // dd($jobs);
        return view('backend.jobapply.index')->with('jobs',$jobs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $jobs=JobApplication::find($id);
        // return $order;
        return view('backend.jobapply.show')->with('jobs',$jobs);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
{
    $jobApplication = JobApplication::find($id);

    // Assuming you want to set the initial status to 'new'
    $status = 'new';

    return view('backend.jobapply.edit', compact('jobApplication', 'status'));
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
        $jobs=JobApplication::find($id);
        $this->validate($request,[
            'status'=>'required|in:new,review,interview,offer,rejected'
        ]);
        $data=$request->all();
        // return $request->status;
       
        $status=$jobs->fill($data)->save();
        if($status){
            request()->session()->flash('success','Successfully updated order');
        }
        else{
            request()->session()->flash('error','Error while updating order');
        }
        return redirect()->route('/jobapply');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $jobs=JobApplication::find($id);
        if($jobs){
            $status=$jobs->delete();
            if($status){
                request()->session()->flash('success','Order Successfully deleted');
            }
            else{
                request()->session()->flash('error','Order can not deleted');
            }
            return redirect()->route('/jobapply');
        }
        else{
            request()->session()->flash('error','Order can not found');
            return redirect()->back();
        }
    }

}
