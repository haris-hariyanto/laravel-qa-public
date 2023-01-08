<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a href="#" class="nav-link" data-widget="pushmenu" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('admin.index') }}" class="nav-link">{{ __('Admin') }}</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('index') }}" class="nav-link" target="_blank">{{ __('Home') }}</a>
        </li>
    </ul>
    <!-- [END] Left navbar links -->

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" role="button" data-toggle="dropdown" aria-expanded="false">
                {{ Auth::user()->username }}
            </a>
            <div class="dropdown-menu">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="dropdown-item btn btn-link nav-link" type="submit">{{ __('Logout') }}</button>
                </form>
            </div>
        </li>
    </ul>
    <!-- [END] Right navbar links -->
</nav>
<!-- [END] Navbar -->