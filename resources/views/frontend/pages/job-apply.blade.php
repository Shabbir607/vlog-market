@extends('frontend.layouts.master')

@section('title','vlog-market || Blog Detail page')

@section('main-content')
    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="{{route('/home')}}">Home<i class="ti-arrow-right"></i></a></li>
                            <li class="active"><a href="javascript:void(0);">Blog Single Sidebar</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumbs -->
    <a href="job.application.store" class="btn btn-primary btn-sm float-right" data-toggle="tooltip" data-placement="bottom" title="Add User"><i class="fas fa-plus"></i> Apply</a>
    <a href="{{route('jobs.apply',$post->slug)}}" class="btn btn-primary btn-sm float-right" data-toggle="tooltip" data-placement="bottom" title="Add User"><i class="fas fa-plus"></i> Save</a>
    <!-- Start Blog Single -->
    <section class="blog-single section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-12">
                    <div class="blog-single-main">
                        <div class="row">
                            <div class="col-12">
                                <div class="image">
                                    <img src="{{$post->photo}}" alt="{{$post->photo}}">
                                </div>
                                <div class="blog-detail">
                                    <h2 class="blog-title">{{$post->title}}</h2>
                                    <div class="blog-meta">
                                    <div class="shop-single-jobs">
                                
                                <div class="content">
                                    <p class="date"><i class="fa fa-calendar" aria-hidden="true"></i> {{$post->created_at->format('d M, Y. D')}}
                                        <span class="float-right">
                                            <i class="fa fa-user" aria-hidden="true"></i>
                                             {{$post->author_info->name ?? 'Anonymous'}}
                                        </span>
                                    </p>
                                    
                                </div>
                            </div>  
                                    <div class="sharethis-inline-reaction-buttons"></div>
                                    <h2>Description:</h2>
                                    <div class="content">
                                    
                                        <p>{!! ($post->description) !!}</p>
                                    </div>
                                    <h2>Details:</h2>
                                    <div class="content">
                                    
                                        <p>{!! ($post->summary) !!}</p>
                                    </div>
                                </div>

                                          <!--/ End Single Widget -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    

    <div class="container">
        <h2>Job Application Form</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('job.application.store') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="mb-3">
                <label for="date_of_birth" class="form-label">Date of Birth:</label>
                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
            </div>

            <div class="mb-3">
                <label for="education" class="form-label">Your Education:</label>
                <select class="form-select" id="education" name="education" required>
                    <option value="high_school">High School</option>
                    <option value="associate_degree">Associate Degree</option>
                    <option value="bachelor_degree">Bachelor's Degree</option>
                    <option value="master_degree">Master's Degree</option>
                    <option value="doctoral_degree">Doctoral Degree</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="gender" class="form-label">Gender:</label>
                <select class="form-select" id="gender" name="gender" required>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="nationality" class="form-label">Nationality:</label>
                <input type="text" class="form-control" id="nationality" name="nationality" required>
            </div>
            <div class="mb-3">
                <label for="current_location" class="form-label">Current Location:</label>
                <input type="text" class="form-control" id="current_location" name="current_location" required>
            </div>
            <div class="mb-3">
                <label for="education" class="form-label">Your Education:</label>
                <select class="form-select" id="education" name="education" required>
                    <option value="high_school">High School</option>
                    <option value="associate_degree">Associate Degree</option>
                    <option value="bachelor_degree">Bachelor's Degree</option>
                    <option value="master_degree">Master's Degree</option>
                    <option value="doctoral_degree">Doctoral Degree</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="career_level" class="form-label">Career Level:</label>
                <input type="text" class="form-control" id="career_level" name="career_level" required>
            </div>

            <div class="mb-3">
                <label for="experience" class="form-label">Experience:</label>
                <select class="form-select" id="experience" name="experience" required>
                    <option value="entry_level">Entry Level</option>
                    <option value="mid_level">Mid Level</option>
                    <option value="senior_level">Senior Level</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="position" class="form-label">Your Position:</label>
                <input type="text" class="form-control" id="position" name="position" required>
            </div>

            <div class="mb-3">
                <label for="salary_expectation" class="form-label">Salary Expectation:</label>
                <input type="number" class="form-control" id="salary_expectation" name="salary_expectation" required>
            </div>

            <div class="mb-3">
                <label for="commitment_level" class="form-label">Your Commitment Level:</label>
                <input type="text" class="form-control" id="commitment_level" name="commitment_level" required>
            </div>

            <div class="mb-3">
                <label for="visa_status" class="form-label">Visa Status:</label>
                <input type="text" class="form-control" id="visa_status" name="visa_status" required>
            </div>

            <div class="mb-3">
                <label for="record_video" class="form-label">Record or Upload Video:</label>
                <div class="input-group">
                    <input type="file" class="form-control" id="record_video" name="record_video" accept="video/*" capture="user" required>
                    <label class="input-group-text" for="record_video">
                        <i class="bi bi-camera"></i> Record or Upload
                    </label>
                </div>
            </div>

            <div class="mb-3">
                <label for="cv" class="form-label">Your CV:</label>
                <input type="file" class="form-control" id="cv" name="cv" accept=".pdf,.doc,.docx" required>
            </div>

            <div class="mb-3">
                <label for="drop_note" class="form-label">Drop Note (Optional):</label>
                <textarea class="form-control" id="drop_note" name="drop_note" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Apply for the Job</button>
        </form>
    </div>

    <!--/ End Blog Single -->
@endsection
@push('styles')
<script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=5f2e5abf393162001291e431&product=inline-share-buttons' async='async'></script>
@endpush
@push('scripts')
<script>
$(document).ready(function(){

    (function($) {
        "use strict";

        $('.btn-reply.reply').click(function(e){
            e.preventDefault();
            $('.btn-reply.reply').show();

            $('.comment_btn.comment').hide();
            $('.comment_btn.reply').show();

            $(this).hide();
            $('.btn-reply.cancel').hide();
            $(this).siblings('.btn-reply.cancel').show();

            var parent_id = $(this).data('id');
            var html = $('#commentForm');
            $( html).find('#parent_id').val(parent_id);
            $('#commentFormContainer').hide();
            $(this).parents('.comment-list').append(html).fadeIn('slow').addClass('appended');
          });

        $('.comment-list').on('click','.btn-reply.cancel',function(e){
            e.preventDefault();
            $(this).hide();
            $('.btn-reply.reply').show();

            $('.comment_btn.reply').hide();
            $('.comment_btn.comment').show();

            $('#commentFormContainer').show();
            var html = $('#commentForm');
            $( html).find('#parent_id').val('');

            $('#commentFormContainer').append(html);
        });

 })(jQuery)
})
</script>
@endpush
