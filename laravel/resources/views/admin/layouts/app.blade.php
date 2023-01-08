<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $pageTitle ?? config('app.name', 'DNM') }}</title>

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <link rel="stylesheet" href="{{ asset('assets/admin/plugins/fontawesome-free/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/admin/adminlte/css/adminlte.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/admin/adminlte/css/style.css') }}">

        @vite(['resources/js/admin.js'])

        @stack('scripts')

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>
    </head>
    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            @include('admin.layouts.navbar')
            @include('admin.layouts.sidebar')

            <div class="content-wrapper pb-3">
                <!-- Content header -->
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0">{{ $pageTitle ?? '' }}</h1>
                            </div>
                            <div class="col-sm-6">
                                @if ($breadcrumb)
                                    <ol class="breadcrumb float-sm-right">
                                        @foreach ($breadcrumb as $breadcrumbText => $breadcrumbLink)
                                            @if (empty($breadcrumbLink))
                                                <li class="breadcrumb-item active line-clamp-1">{{ $breadcrumbText }}</li>
                                            @else
                                                <li class="breadcrumb-item">
                                                    <a href="{{ $breadcrumbLink }}">{{ $breadcrumbText }}</a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ol>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- [END] Content header -->

                <!-- Main content -->
                <div class="content">
                    <div class="container-fluid">
                        {{ $slot }}
                    </div>
                </div>
                <!-- [END] Main content -->
            </div>

            <!-- Footer -->
            <footer class="main-footer">
                <strong>
                    Copyright &copy; {{ date('Y') }} <a href="{{ route('index') }}" target="_blank">{{ config('app.name') }}</a>
                </strong>
            </footer>
            <!-- [END] Footer -->
        </div>

        <script src="{{ asset('assets/admin/plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/admin/adminlte/js/adminlte.min.js') }}"></script>
        @stack('scriptsBottom')
    </body>
</html>