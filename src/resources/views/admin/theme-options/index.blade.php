@extends('laraflex::layouts.admin')
@section('content')



<style>
    .form-group input:read-only,
    .form-group textarea:read-only {
        background-color: #f2f2f2; /* Cambia el color de fondo a tu preferencia */
    }
</style>

<div class="container-fluid px-4">
    <h1 class="mt-4"><i class="fas fa-puzzle-piece"></i> Theme Options</h1>
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


    <form action="{{ route('theme.options.update') }}" method="POST">
    @csrf
    @method('POST')



    <div class="container">
    <div class="row">
      <div class="col-md-2">
        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
          
        <a class="nav-link active" id="tab1-tab" data-bs-toggle="pill" href="#tab1" role="tab" aria-controls="tab1"
            aria-selected="true">Main Options</a>
          
            <a class="nav-link" id="tab2-tab" data-bs-toggle="pill" href="#tab2" role="tab" aria-controls="tab2"
            aria-selected="false">Theme Sections</a>
          
            <a class="nav-link" id="tab3-tab" data-bs-toggle="pill" href="#tab3" role="tab" aria-controls="tab3"
            aria-selected="false">Social links</a>

            <a class="nav-link" id="tab4-tab" data-bs-toggle="pill" href="#tab4" role="tab" aria-controls="tab4"
            aria-selected="false">Theme Style</a>

        </div>
      </div>

     
                    


      <div class="col-md-10">
        <div class="tab-content" id="v-pills-tabContent">
          <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
            

          <div class="card"> <!-- Start Card -->
             
         
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">General Settings</h5>
            <button type="submit" class="btn btn-primary">Save</button>  
         </div>



          <div class="card-body">

            <!-- Theme Name -->
<div class="form-group">
    <label for="site_name">Theme Name</label>
    <input type="text" name="name" id="name" class="form-control" value="{{ $options['name'] ?? '' }}" readonly>
</div>
<div class="margin"></div>
<!-- Theme Description -->
<div class="form-group">
    <label for="description">Theme Description</label>
    <textarea name="description" id="description" class="form-control" readonly>{{ $options['description'] ?? '' }}</textarea>
</div>

<div class="margin"></div>
<!-- Site Name-->
<div class="form-group">
    <label for="site_name">Site Title</label>
    <input type="text" name="site_title" id="site_title" class="form-control" value="{{ $options['site_title'] ?? '' }}">
</div>

<div class="margin"></div>


<div class="margin"></div>

<div class="mb-3">
    <label for="site_email" class="form-label">Site email</label>
    <input type="text" class="form-control" id="site_email" name="site_email" value="{{ $options['site_email'] ?? '' }}">
</div>

<div class="mb-3">
    <label for="description" class="form-label">Site description</label>
    <textarea class="form-control" id="description" name="site_description" rows="3">{{ $options['site_description'] ?? '' }}</textarea>
</div>

<div class="mb-3">
    <label for="keywords" class="form-label">Site Keywords</label>
    <input type="text" class="form-control" id="keywords" name="site_keywords" value="{{ $options['site_keywords'] ?? '' }}">
</div>
                  
        
                </div><!-- End Card Body -->
    </div> 


          </div>
          <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
      
  

          
          <div class="card"> <!-- Start Card -->
             
         
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">Theme Sections</h5>
            <button type="submit" class="btn btn-primary">Save</button>  
         </div>


          <div class="card-body">


   
          <div class="container">
  <div class="row">
    <div class="col">
      <!-- Contenido de la primera columna -->

      

@if (isset($options['theme_options']['header_sub_bar_active']) && $options['theme_options']['header_sub_bar_active'])
    <div class="form-group">
        <label for="header_sub_bar_active">Header Sub Bar</label>
        <div class="margin"></div>
        <div class="btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-primary {{ $options['header_sub_bar_active'] == 0 ? 'active' : '' }}">
                <input type="radio" name="header_sub_bar_active" value="0" autocomplete="off" {{ $options['header_sub_bar_active'] == 0 ? 'checked' : '' }}> Inactive
            </label>
            <label class="btn btn-primary {{ $options['header_sub_bar_active'] == 1 ? 'active' : '' }}">
                <input type="radio" name="header_sub_bar_active" value="1" autocomplete="off" {{ $options['header_sub_bar_active'] == 1 ? 'checked' : '' }}> Active
            </label>
        </div>
    </div>
@endif


<div class="margin"></div>
<!-- Breadcrumb Active -->
@if(isset($options['theme_options']['breadcrumb_active']))
<div class="form-group">
    <label for="breadcrumb_active">Breadcrumbs</label>
    <div class="margin"></div>
    <div class="btn-group-toggle" data-toggle="buttons">
        <label class="btn btn-primary {{ $options['breadcrumb_active'] == 0 ? 'active' : '' }}">
            <input type="radio" name="breadcrumb_active" value="0" autocomplete="off" {{ $options['breadcrumb_active'] == 0 ? 'checked' : '' }}> Inactive
        </label>
        <label class="btn btn-primary {{ $options['breadcrumb_active'] == 1 ? 'active' : '' }}">
            <input type="radio" name="breadcrumb_active" value="1" autocomplete="off" {{ $options['breadcrumb_active'] == 1 ? 'checked' : '' }}> Active
        </label>
    </div>
</div>
@endif


<div class="margin"></div>
<!-- Footer Active -->
<div class="form-group">
    <label for="footer_active">Footer</label>
    <div class="margin"></div>
    <div class="btn-group-toggle" data-toggle="buttons">
        <label class="btn btn-primary {{ $options['footer_active'] == 0 ? 'active' : '' }}">
            <input type="radio" name="footer_active" value="0" autocomplete="off" {{ $options['footer_active'] == 0 ? 'checked' : '' }}> Inactive
        </label>
        <label class="btn btn-primary {{ $options['footer_active'] == 1 ? 'active' : '' }}">
            <input type="radio" name="footer_active" value="1" autocomplete="off" {{ $options['footer_active'] == 1 ? 'checked' : '' }}> Active
        </label>
    </div>
</div>

<div class="margin"></div>
<!-- Footer 2 Active -->
<div class="form-group">
    <label for="footer_2_active">Second Footer</label>
    <div class="margin"></div>
    <div class="btn-group-toggle" data-toggle="buttons">
        <label class="btn btn-primary {{ $options['footer_2_active'] == 0 ? 'active' : '' }}">
            <input type="radio" name="footer_2_active" value="0" autocomplete="off" {{ $options['footer_2_active'] == 0 ? 'checked' : '' }}> Inactive
        </label>
        <label class="btn btn-primary {{ $options['footer_2_active'] == 1 ? 'active' : '' }}">
            <input type="radio" name="footer_2_active" value="1" autocomplete="off" {{ $options['footer_2_active'] == 1 ? 'checked' : '' }}> Active
        </label>
    </div>
</div>

<div class="margin"></div>
<!-- Footer Copyright Active -->
<div class="form-group">
    <label for="footer_copyright_active">Footer Copyright</label>
    <div class="margin"></div>
    <div class="btn-group-toggle" data-toggle="buttons">
        <label class="btn btn-primary {{ $options['footer_copyright_active'] == 0 ? 'active' : '' }}">
            <input type="radio" name="footer_copyright_active" value="0" autocomplete="off" {{ $options['footer_copyright_active'] == 0 ? 'checked' : '' }}> Inactive
        </label>
        <label class="btn btn-primary {{ $options['footer_copyright_active'] == 1 ? 'active' : '' }}">
            <input type="radio" name="footer_copyright_active" value="1" autocomplete="off" {{ $options['footer_copyright_active'] == 1 ? 'checked' : '' }}> Active
        </label>
    </div>
</div>

<div class="margin"></div>
<!-- Header Image Active -->
<div class="form-group">
    <label for="header_image_active">Header Image</label>
    <div class="margin"></div>
    <div class="btn-group-toggle" data-toggle="buttons">
        <label class="btn btn-primary {{ isset($options['header_image_active']) && $options['header_image_active'] == 0 ? 'active' : '' }}">
            <input type="radio" name="header_image_active" value="0" autocomplete="off" {{ isset($options['header_image_active']) && $options['header_image_active'] == 0 ? 'checked' : '' }}> Inactive
        </label>
        <label class="btn btn-primary {{ isset($options['header_image_active']) && $options['header_image_active'] == 1 ? 'active' : '' }}">
            <input type="radio" name="header_image_active" value="1" autocomplete="off" {{ isset($options['header_image_active']) && $options['header_image_active'] == 1 ? 'checked' : '' }}> Active
        </label>
    </div>
</div>

<div class="margin"></div>
<!-- Main Content Active -->
<div class="form-group">
    <label for="main_content_active">Content</label>
    <div class="margin"></div>
    <div class="btn-group-toggle" data-toggle="buttons">
        <label class="btn {{ $options['main_content_active'] == 0 ? 'btn-danger active' : 'btn-primary' }}">
            <input type="radio" name="main_content_active" value="0" autocomplete="off" {{ $options['main_content_active'] == 0 ? 'checked' : '' }}> Inactive
        </label>
        <label class="btn btn-primary {{ $options['main_content_active'] == 1 ? 'active' : '' }}">
            <input type="radio" name="main_content_active" value="1" autocomplete="off" {{ $options['main_content_active'] == 1 ? 'checked' : '' }}> Active
        </label>
    </div>
</div>


    </div>
    <div class="col">
      <!-- Contenido de la segunda columna -->

      <div class="margin"></div>
<div>
    <h4>Sidebar</h4>
</div>

<!-- Search Block in the Sidebar Active -->
<div class="margin"></div>
<div class="form-group">
    <label for="sidebar_search_active">Search Block in the Sidebar</label>
    <div class="margin"></div>
    <div class="btn-group-toggle" data-toggle="buttons">
        <label class="btn {{ $options['sidebar_search_active'] == 0 ? 'btn-danger active' : 'btn-primary' }}">
            <input type="radio" name="sidebar_search_active" value="0" autocomplete="off" {{ $options['sidebar_search_active'] == 0 ? 'checked' : '' }}> Inactive
        </label>
        <label class="btn btn-primary {{ $options['sidebar_search_active'] == 1 ? 'active' : '' }}">
            <input type="radio" name="sidebar_search_active" value="1" autocomplete="off" {{ $options['sidebar_search_active'] == 1 ? 'checked' : '' }}> Active
        </label>
    </div>
</div>

<!-- Categories Block in the Sidebar Active -->
<div class="margin"></div>
<div class="form-group">
    <label for="sidebar_post_cat_active">Categories Block in the Sidebar</label>
    <div class="margin"></div>
    <div class="btn-group-toggle" data-toggle="buttons">
        <label class="btn {{ $options['sidebar_post_cat_active'] == 0 ? 'btn-danger active' : 'btn-primary' }}">
            <input type="radio" name="sidebar_post_cat_active" value="0" autocomplete="off" {{ $options['sidebar_post_cat_active'] == 0 ? 'checked' : '' }}> Inactive
        </label>
        <label class="btn btn-primary {{ $options['sidebar_post_cat_active'] == 1 ? 'active' : '' }}">
            <input type="radio" name="sidebar_post_cat_active" value="1" autocomplete="off" {{ $options['sidebar_post_cat_active'] == 1 ? 'checked' : '' }}> Active
        </label>
    </div>
</div>

<!-- Post Tab Block in the Sidebar Active -->
<div class="margin"></div>
<div class="form-group">
    <label for="sidebar_post_cat_active">Tab Post Block in the Sidebar</label>
    <div class="margin"></div>
    <div class="btn-group-toggle" data-toggle="buttons">
        <label class="btn {{ $options['sidebar_post_tab_active'] == 0 ? 'btn-danger active' : 'btn-primary' }}">
            <input type="radio" name="sidebar_post_tab_active" value="0" autocomplete="off" {{ $options['sidebar_post_tab_active'] == 0 ? 'checked' : '' }}> Inactive
        </label>
        <label class="btn btn-primary {{ $options['sidebar_post_tab_active'] == 1 ? 'active' : '' }}">
            <input type="radio" name="sidebar_post_tab_active" value="1" autocomplete="off" {{ $options['sidebar_post_tab_active'] == 1 ? 'checked' : '' }}> Active
        </label>
    </div>
</div>

<!-- Widget Block in the Sidebar Active -->
<div class="margin"></div>
<div class="form-group">
    <label for="sidebar_text_widget_active">Widget Block in the Sidebar</label>
    <div class="margin"></div>
    <div class="btn-group-toggle" data-toggle="buttons">
        <label class="btn {{ $options['sidebar_text_widget_active'] == 0 ? 'btn-danger active' : 'btn-primary' }}">
            <input type="radio" name="sidebar_text_widget_active" value="0" autocomplete="off" {{ $options['sidebar_text_widget_active'] == 0 ? 'checked' : '' }}> Inactive
        </label>
        <label class="btn btn-primary {{ $options['sidebar_text_widget_active'] == 1 ? 'active' : '' }}">
            <input type="radio" name="sidebar_text_widget_active" value="1" autocomplete="off" {{ $options['sidebar_text_widget_active'] == 1 ? 'checked' : '' }}> Active
        </label>
    </div>
</div>





    </div> <!-- segunda columna END -->
    <div class="col">
      <!-- Contenido de la tercera columna -->

      

<!-- Botones Dinamicos -->
<!--
@if(!empty($dynamicBtnOptions['buttons']) && is_array($dynamicBtnOptions['buttons']))
    @foreach ($dynamicBtnOptions['buttons'] as $button)

        <div class="margin"></div>

        <div class="form-group">

            <label>{{ $button['label'] }}</label>

            <div class="margin"></div>

            <div class="btn-group-toggle" data-toggle="buttons">

                <label class="btn btn-primary">
                    <input type="radio" name="{{ $button['value'] }}" value="1" autocomplete="off" {{ !empty($options[$button['value']]) && $options[$button['value']] == 1 ? 'checked' : '' }}> Active
                </label>

                <label class="btn btn-danger">
                    <input type="radio" name="{{ $button['value'] }}" value="0" autocomplete="off" {{ !empty($options[$button['value']]) && $options[$button['value']] == 0 ? 'checked' : '' }}> Inactive
                </label>

            </div>

        </div>

    @endforeach
@endif

-->


    </div>
  </div>
</div>


<div class="margin"></div>

               
         </div> <!-- End Card Body -->
    </div> 



          </div> <!-- End Tab 2 -->

          <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab3-tab">
           
          <div class="card"> <!-- Start Card -->
             
         
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">Social Links</h5>
            <button type="submit" class="btn btn-primary">Save</button>  
         </div>
   
   
             <div class="card-body">
               

             <div class="mb-3">
    <label for="twitter" class="form-label">Twitter</label>
    <input type="text" class="form-control" id="twitter" name="twitter" value="{{ $options['twitter'] ?? '' }}">
</div>

<div class="mb-3">
    <label for="facebook" class="form-label">Facebook</label>
    <input type="text" class="form-control" id="facebook" name="facebook" value="{{ $options['facebook'] ?? '' }}">
</div>

<div class="mb-3">
    <label for="pinterest" class="form-label">Pinterest</label>
    <input type="text" class="form-control" id="pinterest" name="pinterest" value="{{ $options['pinterest'] ?? '' }}">
</div>

<div class="mb-3">
    <label for="instagram" class="form-label">Instagram</label>
    <input type="text" class="form-control" id="instagram" name="instagram" value="{{ $options['instagram'] ?? '' }}">
</div>

<div class="mb-3">
    <label for="youtube" class="form-label">YouTube</label>
    <input type="text" class="form-control" id="youtube" name="youtube" value="{{ $options['youtube'] ?? '' }}">
</div>

<div class="mb-3">
    <label for="github" class="form-label">GitHub</label>
    <input type="text" class="form-control" id="github" name="github" value="{{ $options['github'] ?? '' }}">
</div>


<div class="margin"></div>      


                    
              
                       
                   </div>
       </div> <!-- End Card -->

          </div> <!-- End tab 3 -->

           <div class="tab-pane fade" id="tab4" role="tabpanel" aria-labelledby="tab4-tab"> 

           <div class="card"> <!-- Start Card -->
             
         
             <div class="card-header d-flex justify-content-between align-items-center">
               <h5 class="card-title">Theme Style</h5>
               <button type="submit" class="btn btn-primary">Save</button>  
            </div>
                



<div class="mb-3">
  <label for="colorPicker" class="form-label">Color Default</label>
  <input type="text" id="colorPicker" name="color_default" class="form-control form-control-sm" value="{{ $options['color_default'] ?? '' }}">
</div>

<div class="mb-3">
  <label for="colorPicker_2" class="form-label">Color Default 2</label>
  <input type="text" id="colorPicker_2" name="color_default_2" class="form-control form-control-sm" value="{{ $options['color_default_2'] ?? '' }}">
</div>

<div class="mb-3">
  <label for="colorPicker_3" class="form-label">Sub Bar Color</label>
  <input type="text" id="colorPicker_3" name="sub_bar_color" class="form-control form-control-sm" value="{{ $options['sub_bar_color'] ?? '' }}">
</div>

<div class="mb-3">
  <label for="colorPicker_4" class="form-label">Sub Bar Text Color</label>
  <input type="text" id="colorPicker_4" name="sub_bar_text_color" class="form-control form-control-sm" value="{{ $options['sub_bar_text_color'] ?? '' }}">
</div>

<div class="mb-3">
  <label for="colorPicker_5" class="form-label">Header Logo Color</label>
  <input type="text" id="colorPicker_5" name="header_logo_color" class="form-control form-control-sm" value="{{ $options['header_logo_color'] ?? '' }}">
</div>

<script>
  $(document).ready(function() {
    $("#colorPicker").spectrum({
      preferredFormat: "hex",
      showInput: true,
      showInitial: true
    });

    $("#colorPicker_2").spectrum({
      preferredFormat: "hex",
      showInput: true,
      showInitial: true
    });

    $("#colorPicker_3").spectrum({
      preferredFormat: "hex",
      showInput: true,
      showInitial: true
    });

    $("#colorPicker_4").spectrum({
      preferredFormat: "hex",
      showInput: true,
      showInitial: true
    });

    $("#colorPicker_5").spectrum({
      preferredFormat: "hex",
      showInput: true,
      showInitial: true
    });

  });
</script>






                </div> <!-- End Card -->
            </div> <!-- End tab 4 -->

       
        
         </div>
      </div>
    </div>
  </div>
  </form>
<div class="margin"></div>

@include('laraflex::admin.partials.pages-ckeditor-conf')
@endsection