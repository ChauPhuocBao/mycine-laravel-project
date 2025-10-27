<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="{{ asset('assets/img/logo.png') }}">

    <title>MyCine</title>

    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" 
        rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" 
        crossorigin="anonymous"
    >

    <script src="https://kit.fontawesome.com/a9cdbedf57.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>


<body data-bs-spy="scroll" data-bs-target="#myScrollSpy" data-bs-root-margin="0px 0px -40%" data-bs-smooth-scroll="true"
    tabindex="0" id="top">

    <header>
        <nav class="navbar navbar-expand-lg navbar-dark" id="myScrollSpy">
            <div class="container">
                <a class="navbar-brand d-flex" href="{{ route('home') }}">
                    <img src="{{ asset('assets/img/logo.png') }}" class="align-text-top" alt="logo" width="40">
                    <h4 class="mt-2 ms-2">MyCine</h4>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-lg-0 p-md-2">                        
                        <!-- STATIC NAVIGATION LINKS -->
                        <li class="nav-item me-2">
                            <a class="nav-link active" aria-current="page" href="{{ route('home') }}">Home</a>
                        </li>
                        <!-- Categories -->
                        <li class="nav-item dropdown me-2">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarCategoriesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Categories
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarCategoriesDropdown" style="background-color: #222;">
                                <!-- Check if $all_categories exists and has items -->
                                @if(isset($all_categories) && $all_categories->count() > 0)
                                    <!-- Loop through each category -->
                                    @foreach($all_categories as $category)
                                        <li>
                                            <!-- Link to category page (adjust route later) -->
                                            <a class="dropdown-item" href="{{ route('category.show', ['slug' => $category->slug]) }}" style="color: #f8f9fa;">{{ $category->name }}</a>
                                        </li>
                                    @endforeach
                                @else
                                    <li><a class="dropdown-item disabled" href="#">No categories found</a></li>
                                @endif
                            </ul>
                        </li>  

                        <li class="nav-item me-2">
                            <a class="nav-link" href="#">
                                <i class="fa fa-search" data-bs-toggle="tooltip"
                                    data-bs-placement="bottom" data-bs-title="Search Movies"></i>
                            </a>
                        </li>
                      
                        
                        <!-- START: DYNAMIC AUTHENTICATION DROPDOWN / LINKS -->    
                        @auth
                            <!-- DROPDOWN MENU FOR AUTHENTICATED USER (Shows Name + Profile/Logout) -->
                            <li class="nav-item dropdown ms-2">
                                <a class="nav-link dropdown-toggle btn-subscribe" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 8px 12px; border-radius: 5px;">
                                    {{ Auth::user()->name }}
                                </a>

                                <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" aria-labelledby="navbarDropdown" style="background-color: #222;">
                                    
                                    <!-- Profile Link -->
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.edit') }}" style="color: #f8f9fa;">
                                            Profile
                                        </a>
                                    </li>
                                    
                                    <!-- Divider -->
                                    <li><hr class="dropdown-divider" style="border-top: 1px solid #444;"></li>
                                    
                                    <!-- Logout Link -->
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}" 
                                           onclick="event.preventDefault(); document.getElementById('logout-form-desktop').submit();" style="color: #f8f9fa;"> Log Out
                                        </a>
                                    </li>
                                </ul>
                            </li>

                        @else
                            <!-- LINKS FOR GUEST USER (Login and Register/Subscribe) -->
                            
                            <!-- Login Link (Plain text link) -->
                            <li class="nav-item me-2">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>

                            <!-- Register Button -->
                            <li class="nav-item ms-2 mt-1">
                                <a href="{{ route('register') }}">
                                    <button class="btn-subscribe">Register</button>
                                </a>
                            </li>
                        @endauth

                        <!-- Hidden form to submit the Logout POST request -->
                        <form id="logout-form-desktop" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    @yield('content')

    <footer class="text-center">

    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>

    <script src="{{ asset('assets/js/script.js') }}"></script>

</body>

</html>