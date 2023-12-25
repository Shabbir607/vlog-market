@extends('backend.layouts.master')
@section('title','vlog-market || Brand Create')
@section('main-content')

<div class="card">
    <h5 class="card-header">Add Marketplace</h5>
    <div class="card-body">
      <form method="post" action="{{route('/market.store')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="form-group">
          <label for="name" class="col-form-label">Name  <span class="text-danger">*</span></label>
        <input id="inputTitle" type="text" name="name" placeholder="Enter title"  value="{{old('title')}}" class="form-control">
        @error('name')
        <span class="text-danger">{{$message}}</span>
        @enderror
        </div>
        <div class="form-group">
          <label for="cat_id"> Country <span class="text-danger">*</span></label>
          <select name="country" id="cat_id" class="form-control">
              <option value="">--Select any Country--</option>
              @foreach($country as $key=>$country)
                  <option value='{{$country->id}}'>{{$country->name}}</option>
              @endforeach
          </select>
        </div>
        <div class="form-group mb-3">
          <button type="reset" class="btn btn-warning">Reset</button>
           <button class="btn btn-success" type="submit">Submit</button>
        </div>
      </form>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{asset('backend/summernote/summernote.min.css')}}">
@endpush
@push('scripts')
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script src="{{asset('backend/summernote/summernote.min.js')}}"></script>
<script>
    $('#lfm').filemanager('image');

    $(document).ready(function() {
    $('#description').summernote({
      placeholder: "Write short description.....",
        tabsize: 2,
        height: 150
    });
    });
</script>
@endpush