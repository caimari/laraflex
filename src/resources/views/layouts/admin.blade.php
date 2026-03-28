<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>

        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="Laraflex" />
        
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Laraflex</title>

        <!-- Data Tables -->
        <!--  <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" /> -->
        
        <!-- Boostrap 5 -->
        <link href="https://develop.laraflex.org/vendor/caimari/laraflex/src/resources/assets/css/styles.css" rel="stylesheet" />
        
        <!-- Jquery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <!-- Select 2 -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <!-- Tagify -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css">
        <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.min.js"></script>

        <!-- Prism -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.25.0/themes/prism.min.css">

        <!-- Spectrum Color Picker -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/spectrum-colorpicker@1.8.1/spectrum.min.css">
        <script src="https://cdn.jsdelivr.net/npm/spectrum-colorpicker@1.8.1/spectrum.min.js"></script>

        <!-- File Manager -->
        <!--<link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}"> -->
        <!-- <script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script> -->

        <!-- Ckeditor & Code Snippet & Highlight -->
        <script src="/vendor/caimari/laraflex/assets/vendor/ckeditor/ckeditor.js"></script>
        <script src="/vendor/caimari/laraflex/assets/vendor/ckeditor/plugins/codesnippet/plugin.js"></script>
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/styles/default.min.css">
        <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/highlight.min.js"></script>
        <link href="/vendor/caimari/laraflex/assets/vendor/ckeditor/plugins/codesnippet/lib/highlight/styles/default.css" rel="stylesheet">

        <!--<script src="https://cdn.ckeditor.com/4.22.1/standard-all/ckeditor.js"></script>
        <script src="/vendor/caimari/laraflex/assets/vendor/ckeditor/plugins/codesnippet/plugin.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.16.1/plugins/codesnippet/lib/highlight/highlight.pack.js"></script>-->


        <!-- Sweet Alert -->
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
 
        <!-- Boxicons CDN Link -->
        <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
        
        <!-- Fontawesome -->
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    
        <!-- Material Design Icons  -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">



        <style>
    .sortable i {
        display: none;
    }

    .sortable[data-sort="title"] .fa-chevron-up {
        display: inline-block;
    }

    .margin {
        margin: 1em 0;
    }
   
    .no-link-decoration {
        text-decoration: none;
        cursor: default;
        color: inherit;
    }

        /* Estilos para la barra de desplazamiento en WebKit (Chrome, Safari) */
    #layoutSidenav_nav::-webkit-scrollbar {
        width: 6px; /* Ancho de la barra */
        background-color: #F5F5F5; /* Color de fondo de la barra */
    }

    #layoutSidenav_nav::-webkit-scrollbar-thumb {
        background-color: #007BFF; /* Color de la barra de desplazamiento */
        border-radius: 3px; /* Borde redondeado de la barra de desplazamiento */
    }
        </style>

    </head>
    
    <body class="sb-nav-fixed">


         <!-- Navigation bar -->
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            
            <!-- Navbar Brand-->
                
            <a class="navbar-brand ps-3" href="{{ route('login') }}">
            <span class="icon-container">
            <i class='bx bxs-heart bx-border-circle'></i>
            </span>
            Laraflex
            </a>

            
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            
            
            <!-- Navbar search-->
            @auth
            <form id="searchForm" class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group">
                <input id="searchTerm" class="form-control" type="text" name="searchTerm" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                <button class="btn btn-primary" id="btnNavbarSearch" type="submit"><i class="fas fa-search"></i></button>
            </div>
        </form>
            @endauth

            <!-- Navbar menu dropdown -->
            @auth
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="{{ route('users.edit') }}">Edit profile</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="{{ route('panel.logout') }}">Logout</a></li>
                    </ul>
                </li>
            </ul>
            @endauth
        </nav>

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">

               
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    @auth

                    <div class="sb-sidenav-menu">
                        
                    
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">Cms</div>

                    <!-- Dashboard -->
                    <a class="nav-link" href="{{ route('panel.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>Dashboard</a>

                <!-- Pages -->
                <div id="pagesContainer">
                    <a class="nav-link collapsed" id="pageLink" href="#">
                        <div class="sb-nav-link-icon"><i class="far fa-file-alt"></i></div>
                        Pages
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapsePagesLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link {{ request()->is('panel/pages') ? 'active' : '' }}" id="allPagesLink" href="{{ route('pages.index') }}">All Pages</a>
                            <a class="nav-link {{ request()->is('panel/pages/create') ? 'active' : '' }}" id="addNewPageLink" href="{{ route('pages.create') }}">Add new</a>
                        </nav>
                    </div>
                </div>

                <!-- Posts -->
                <div id="postsContainer">
                    <a class="nav-link collapsed" id="postLink" href="#">
                        <div class="sb-nav-link-icon"><i class="far fa-newspaper"></i></div>
                        Posts
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link {{ request()->is('panel/posts') ? 'active' : '' }}" id="allPostsLink" href="{{ route('posts.index') }}">All Posts</a>
                            <a class="nav-link {{ request()->is('panel/posts/create') ? 'active' : '' }}" id="addNewLink" href="{{ route('posts.create') }}">Add new</a>
                            <a class="nav-link {{ request()->is('panel/post-categories') ? 'active' : '' }}" id="CategoriesLink" href="{{ route('post-categories.index') }}">Categories</a>
                            <a class="nav-link {{ request()->is('panel/post-tags') ? 'active' : '' }}" id="TagsLink" href="{{ route('post.tags.index') }}">Tags</a>
                        </nav>
                    </div>
                </div>

                    <!-- Menus -->
                    <a class="nav-link" href="{{ route('panel.menus') }}">
                     <div class="sb-nav-link-icon"><i class="fas fa-bars"></i></div>Menus</a>

                    <!-- Dynamic menus generated -->
                    @php
                        $sections = DB::table('admin_generated')->get();
                    @endphp

                    @if ($sections->count() > 0)
                        <div class="sb-sidenav-menu-heading">Generated</div>
                        @foreach ($sections as $section)
                            @php
                                $formattedName = str_replace(['-', '_'], ' ', $section->name);
                                $formattedName = ucwords($formattedName);
                                $formattedRoute = $section->route;
                            @endphp
                            @if (Route::has($formattedRoute))
                                <a class="nav-link" href="{{ route($formattedRoute) }}">
                                    <div class="sb-nav-link-icon"><i class="far fa-folder-open"></i></div>{{ $formattedName }}</a>
                            @endif
                        @endforeach
                    @endif

                        <!-- Interface section -->
                        <div class="sb-sidenav-menu-heading">Interface</div>
                                         
                            <a class="nav-link" href="{{ route('fmanager') }}">
                                <div class="sb-nav-link-icon"><i class="far fa-folder-open"></i></div>
                                File manager
                            </a>

                            <a class="nav-link" href="{{ route('galleries.index') }}">
                                <div class="sb-nav-link-icon"><i class="far fa-images"></i></div>
                                Galleries
                            </a>
                         
                            <!-- Members Module -->
                            @if(collect(app()->getProviders(\Caimari\Members\MembersServiceProvider::class))->isNotEmpty())
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseThemes" aria-expanded="false" aria-controls="collapseThemes">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Members
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>

                            <div class="collapse" id="collapseThemes" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="{{ route('panel.members.index') }}">Members</a>
                                    <a class="nav-link" href="{{ route('panel.members.type.index') }}">Type</a>
                                </nav>
                            </div>

                            @endif

                            <a class="nav-link" href="{{ route('tables.index') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-puzzle-piece"></i></div>
                                Tables & Crud
                            </a>

                            <a class="nav-link" href="{{ route('panel.code-snippets.index') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-code"></i></div>
                                Code Snippets
                            </a>
                           



                            <a class="nav-link" href="{{ route('users.index') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                                Users
                            </a>

                           


                            <!-- Appearance -->
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseThemes" aria-expanded="false" aria-controls="collapseThemes">
                            <div class="sb-nav-link-icon"><i class="fas fa-puzzle-piece"></i></div>
                            Appearance
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>

                            <div class="collapse" id="collapseThemes" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="{{ route('panel.themes') }}">Themes</a>
                                    <a class="nav-link" href="{{ route('theme.options') }}">Theme Options</a>
                                </nav>
                            </div>

                            <!-- Config -->

                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseSettingsLayouts" aria-expanded="false" aria-controls="collapseSettingsLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Config
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>

                            <div class="collapse" id="collapseSettingsLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="{{ route('admin.general-settings.index') }}">General Settings</a>
                                  <!--  <a class="nav-link" href="#">Languages</a> -->

                                            
                                </nav>
                            </div>

                                <!-- Packages -->
                                <!-- <a class="nav-link" href="{{ route('packages.index') }}">
                                            <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                            Packages
                                            </a>-->


                                        </div>
                                    </div> 

                                <!-- Logged info -->
                                <div class="sb-sidenav-footer">
                                    <div class="small">Logged in as:</div>
                                    {{ auth()->user()->name }}
                                </div>
                    @endauth
            </nav>
     </div>
            
            <!-- Content Yield -->
            <div id="layoutSidenav_content">
                <main>
                        <div class="card mb-4"></div>
                        @yield('content')
                </main>

                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Laraflex 2023</div>
                            
                            <div>
                                <a href="https://laraflex.org">Help</a>
                                &middot;
                                <a href="https://laraflex.org/license">License</a>
                            </div>

                        </div>
                    </div>
                </footer>

        </div>

    </div>



        <!-- Posts Menu JS-->
        <script>
    $(document).ready(function() {
        
        // Hover event for postsContainer

        /*
         $('#postsContainer').hover(function() {
            $('#collapseLayouts').collapse('show');
        }, function() {
            if (!$(this).hasClass('persistent')) {
                $('#collapseLayouts').collapse('hide');
            }
        }); */

        // Click event for postLink
        $('#postLink').click(function(e) {
            $('#collapseLayouts').collapse('hide');
            setTimeout(function() {
                window.location.href = '{{ route('posts.index') }}';
            }, 500); // delay in milliseconds
        });

        // Check the current route and compare with the 'Add new', 'All Posts', 'Categories', and 'Tags' routes
        var currentRoute = '{{ Route::currentRouteName() }}';
        if (currentRoute.startsWith('posts') || currentRoute.startsWith('post-categories') || currentRoute.startsWith('post.tags')) {
            $('#collapseLayouts').collapse('show');
            $('#postsContainer').addClass('persistent');
        }
    });
</script>


<!-- Pages Menu JS -->
<script>
    $(document).ready(function() {
    
    /*
    $('#pagesContainer').hover(function() {
        $('#collapsePagesLayouts').collapse('show');
    }, function() {
        if (!$(this).hasClass('persistent')) {
            $('#collapsePagesLayouts').collapse('hide');
        }
    }); */

    $('#pageLink').click(function(e) {
        $('#collapsePagesLayouts').collapse('hide');
        setTimeout(function() {
            window.location.href = '{{ route('pages.index') }}';
        }, 500); // delay in milliseconds
    });

    // Check the current route and compare with the 'Add new' and 'All Pages' routes
    if ('{{ Route::currentRouteName() }}'.startsWith('pages')) {
        $('#collapsePagesLayouts').collapse('show');
        $('#pagesContainer').addClass('persistent');
    }
});
</script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="/vendor/caimari/laraflex/src/resources/assets/js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
   <!-- <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script> -->
        <script src="/vendor/caimari/laraflex/src/resources/assets/js/datatables-simple-demo.js"></script>

        <!--<script>
            document.addEventListener('DOMContentLoaded', function() {
                hljs.initHighlighting();
            });
            </script>-->


    <script>
    document.getElementById('searchForm').addEventListener('submit', function(event) {
        event.preventDefault();
        var searchTerm = document.getElementById('searchTerm').value;

        if (searchTerm.trim() !== '') {
            search(searchTerm);
        }
    });

    function search(searchTerm) {
        var url = '{{ route("panel.ajaxSearchResults") }}';
        var form = new FormData();
        form.append('searchTerm', searchTerm);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', url);
        xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                // Redirige a la página de resultados de búsqueda
                window.location.href = response.redirectUrl;
            }
        };
        xhr.send(form);
    }
    </script>

    
    </body>
</html>
