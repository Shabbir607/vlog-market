@extends('backend.layouts.master')

@section('main-content')

<div class="card">
    <h5 class="card-header">Add Jobs</h5>
    <div class="card-body">
      <form method="post" action="{{route('/post.store')}}">
        {{csrf_field()}}
        <div class="form-group">
          <label for="inputTitle" class="col-form-label">Title <span class="text-danger">*</span></label>
          <input id="inputTitle" type="text" name="title" placeholder="Enter title"  value="{{old('title')}}" class="form-control">
          @error('title')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <label for="compeny" class="col-form-label">Company <span class="text-danger">*</span></label>
        <input id="compeny" type="text" name="company" placeholder="Enter Company name"  value="{{old('title')}}" class="form-control"  >

        <label for="location" class="col-form-label">Location <span class="text-danger">*</span></label>
        <input id="location" type="text" name="location" placeholder="Enter Location"  value="{{old('title')}}" class="form-control"  >

        <label for="jobType">Job Type:</label>
        <select id="jobType" name="type" class="form-control">
        <option value="full time">Full Time</option>
        <option value="part time">Part Time</option>
        <option value="remote">Remote</option>
        </select>

        <label for="salary">Salary Range</label>
    <select name="salary" id="salaryRangeSelect" class="form-control">
        <!-- JavaScript will populate the options dynamically -->
    </select>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var salarySelect = document.getElementById('salaryRangeSelect');

        // Define the salary ranges
        var salaryRanges = [
            { value: '0-20k', label: '0 - 20,000' },
            { value: '21k-40k', label: '21,000 - 40,000' },
            { value: '41k-60k', label: '41,000 - 60,000' },
            { value: '61k-80k', label: '61,000 - 80,000' },
            { value: '81k-100k', label: '81,000 - 100,000' },
            { value: '101k-120k', label: '101,000 - 120,000' },
            { value: '121k-140k', label: '121,000 - 140,000' },
            { value: '141k-160k', label: '141,000 - 160,000' },
            { value: '161k-180k', label: '161,000 - 180,000' },
            { value: '181k-200k', label: '181,000 - 200,000' }
        ];

        // Populate the select options
        salaryRanges.forEach(function (range) {
            var option = new Option(range.label, range.value);
            salarySelect.add(option);
        });
    });
</script>

        <!-- <label for="inputTitle" class="col-form-label">Salary <span class="text-danger">*</span></label>
        <input id="inputTitle" type="number" name="salary" placeholder="Enter Salary"  value="{{old('title')}}" class="form-control"  > -->


        <!-- <div class="form-group">
          <label for="quote" class="col-form-label">Quote</label>
          <textarea class="form-control" id="quote" name="quote">{{old('quote')}}</textarea>
          @error('quote')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div> -->

       
        <div class="form-group">
          <label for="description" class="col-form-label">Description</label>
          <textarea class="form-control" id="description" name="description">{{old('description')}}</textarea>
          @error('description')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <div class="form-group">
          <label for="summary" class="col-form-label">Details <span class="text-danger">*</span></label>
          <textarea class="form-control" id="summary" name="summary">{{old('summary')}}</textarea>
          @error('summary')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <div class="form-group">
          <label for="is_parent">Parent Category <span class="text-danger">*</span></label>
          <select name="is_parent" class="form-control">
              <option value="">--Select any category--</option>
              @foreach($categories as $key=>$data)
                  <option value='{{$data->id}}'>{{$data->is_parent}}</option>
              @endforeach
          </select>
        </div>
        <div class="form-group">
          <label for="post_cat_id">Category <span class="text-danger">*</span></label>
          <select name="post_cat_id" class="form-control">
              <option value="">--Select any category--</option>
              @foreach($categories as $key=>$data)
                  <option value='{{$data->id}}'>{{$data->title}}</option>
              @endforeach
          </select>
        </div>
        
        <div class="form-group">
          <label for="tags">Tag</label>
          <select name="tags[]" multiple  data-live-search="true" class="form-control selectpicker">
              <option value="">--Select any tag--</option>
              @foreach($tags as $key=>$data)
                  <option value='{{$data->title}}'>{{$data->title}}</option>
              @endforeach
          </select>
        </div>



        <div class="form-group">
          <label for="added_by">Author</label>
          <select name="added_by" class="form-control">
              <option value="">--Select any one--</option>
              @foreach($users as $key=>$data)
                <option value='{{$data->id}}' {{($key==0) ? 'selected' : ''}}>{{$data->name}}</option>
            @endforeach
          </select>
        </div>
        
        
        <div class="form-group">
          <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
          <select name="status" class="form-control">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
          </select>
          @error('status')
          <span class="text-danger">{{$message}}</span>
          @enderror
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
@endpush
@push('scripts')
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script src="{{asset('backend/summernote/summernote.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script>
    $('#lfm').filemanager('image');

    $(document).ready(function() {
      $('#summary').summernote({
        placeholder: "Write short description.....",
          tabsize: 2,
          height: 100
      });
    });

    $(document).ready(function() {
      $('#description').summernote({
        placeholder: "Write detail .....",
          tabsize: 2,
          height: 150
      });
    });

    $(document).ready(function() {
      $('#quote').summernote({
        placeholder: "Write detail Quote.....",
          tabsize: 2,
          height: 100
      });
    });
    // $('select').selectpicker();

</script>
@endpush