@extends('backend.layouts.master')

@section('title','Order Detail')

@section('main-content')
<div class="card">
    <h5 class="card-header">Job Application Form</h5>
    <div class="card-body">
    <form method="post" action="{{ route('jobapply.update', $jobApplication->id) }}">
            @csrf

            <div class="form-group">
                <label for="status">Application Status:</label>
                <select name="status" id="status" class="form-control">
                    <option value="new" {{ $jobApplication->status == 'new' ? 'selected' : '' }}>New</option>
                    <option value="review" {{ $jobApplication->status == 'review' ? 'selected' : '' }}>Review</option>
                    <option value="interview" {{ $jobApplication->status == 'interview' ? 'selected' : '' }}>Interview</option>
                    <option value="offer" {{ $jobApplication->status == 'offer' ? 'selected' : '' }}>Offer</option>
                    <option value="rejected" {{ $jobApplication->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <!-- Add more form fields related to job application as needed -->

            <button type="submit" class="btn btn-primary">Update Application</button>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    .order-info,.shipping-info{
        background:#ECECEC;
        padding:20px;
    }
    .order-info h4,.shipping-info h4{
        text-decoration: underline;
    }

</style>
@endpush
