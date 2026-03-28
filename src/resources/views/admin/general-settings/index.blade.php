@extends('laraflex::layouts.admin')

@section('content')

@auth
<div class="container-fluid px-4">
    <h1 class="mt-4"><i class="fas fa-columns"></i> General Settings</h1>
    <ol class="breadcrumb mb-4"></ol>

    @if(session('success'))
    <div class="alert alert-success" role="alert">
        {!! session('success') !!}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger" role="alert">
        {!! session('error') !!}
    </div>
    @endif


    <form action="{{ route('admin.general-settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')

    <div class="container">
    <div class="row">
      <div class="col-md-3">
        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
          <a class="nav-link active" id="tab1-tab" data-bs-toggle="pill" href="#tab1" role="tab" aria-controls="tab1"
            aria-selected="true">General</a>
          <a class="nav-link" id="tab2-tab" data-bs-toggle="pill" href="#tab2" role="tab" aria-controls="tab2"
            aria-selected="false">Social links</a>
          <a class="nav-link" id="tab3-tab" data-bs-toggle="pill" href="#tab3" role="tab" aria-controls="tab3"
            aria-selected="false">Cookie consent</a>
        </div>
      </div>

     
                    


      <div class="col-md-9">
        <div class="tab-content" id="v-pills-tabContent">
          <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
            

          <div class="card"> <!-- Start Card -->
             
         
          <div class="card-header">
                    <h5 class="card-title">General Settings</h5>
                </div>


          <div class="card-body">


                        <div class="mb-3">
                            <label for="title" class="form-label">Site title</label>
                            <input type="text" class="form-control" id="site_title" name="site_title" value="{{ $settings->site_title }}">
                        </div>

                        <div class="mb-3">
                            <label for="site_email" class="form-label">Site email</label>
                            <input type="text" class="form-control" id="site_email" name="site_email" value="{{ $settings->site_email }}">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="site_description" rows="3">{{ $settings->site_description }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="keywords" class="form-label">Keywords</label>
                            <input type="text" class="form-control" id="keywords" name="keywords" value="{{ $settings->keywords }}">
                        </div>

                        <button type="submit" class="btn btn-primary">Save</button>
                 
                </div>
    </div> <!-- End Card -->


          </div>
          <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
           

          
          <div class="card"> <!-- Start Card -->
             
         
          <div class="card-header">
                    <h5 class="card-title">Social Links</h5>
                </div>


          <div class="card-body">


                        <div class="mb-3">
                            <label for="twitter" class="form-label">Twitter</label>
                            <input type="text" class="form-control" id="twitter" name="twitter" value="{{ $settings->twitter }}">
                        </div>

                        <div class="mb-3">
                            <label for="facebook" class="form-label">Facebook</label>
                            <input type="text" class="form-control" id="facebook" name="facebook" value="{{ $settings->facebook }}">
                        </div>

                        <div class="mb-3">
                            <label for="pinterest" class="form-label">Pinterest</label>
                            <input type="text" class="form-control" id="pinterest" name="pinterest" value="{{ $settings->pinterest }}">
                        </div>

                        <div class="mb-3">
                            <label for="instagram" class="form-label">Instagram</label>
                            <input type="text" class="form-control" id="instagram" name="instagram" value="{{ $settings->instagram }}">
                        </div>

                        <div class="mb-3">
                            <label for="youtube" class="form-label">YouTube</label>
                            <input type="text" class="form-control" id="youtube" name="youtube" value="{{ $settings->youtube }}">
                        </div>

                        <div class="mb-3">
                            <label for="github" class="form-label">GitHub</label>
                            <input type="text" class="form-control" id="github" name="github" value="{{ $settings->github }}">
                        </div>


                        <button type="submit" class="btn btn-primary">Save</button>
               
                </div>
    </div> <!-- End Card -->



          </div> <!-- End Tab 2 -->

          <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab3-tab">
           
          <div class="card"> <!-- Start Card -->
             
         
             <div class="card-header">
                       <h5 class="card-title">Cookie Policy Page</h5>
                   </div>
   
   
             <div class="card-body">
               
   
   
                           <div class="mb-3">
                               <label for="use_cookies_page" class="form-label">Cookie page</label>
                               <!-- Ckeditor -->
                               <textarea class="form-control" id="ckeditorcontent" name="use_cookies_page" rows="4">{{ $settings->use_cookies_page }}</textarea>
                           </div>

    
                    
                           <button type="submit" class="btn btn-primary">Save</button>
                       </form>
                   </div>
       </div> <!-- End Card -->

          </div> <!-- End tab 3 -->
        </div>
      </div>
    </div>
  </div>

<div class="margin"></div>

@include('laraflex::admin.partials.pages-ckeditor-conf')

@endauth

@endsection



