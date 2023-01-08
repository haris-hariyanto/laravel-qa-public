<!-- Main sidebar container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand logo -->
    <a href="{{ route('admin.index') }}" class="brand-link">
        <img src="{{ asset('assets/admin/images/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name', 'DNM') }}</span>
    </a>
    <!-- [END] Brand logo -->

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('admin.questions.index') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.questions.index'])>
                        <i class="nav-icon fas fa-file"></i>
                        <p>{{ __('Questions') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.answers.index') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.answers.index'])>
                        <i class="nav-icon fas fa-file"></i>
                        <p>{{ __('Answers') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.grades.index') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.grades.index'])>
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>{{ __('Grades') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.subjects.index') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.subjects.index'])>
                        <i class="nav-icon fas fa-folder-open"></i>
                        <p>{{ __('Subjects') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.keywords.index') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.keywords.index'])>
                        <i class="nav-icon fas fa-list"></i>
                        <p>{{ __('Keywords') }}</p>
                    </a>
                </li>

                @can('auth-check', $userAuth->authorize('admin-pages-index'))
                    <li class="nav-item">
                        <a href="{{ route('admin.pages.index') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.pages.index'])>
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>{{ __('Pages') }}</p>
                        </a>
                    </li>
                @endcan

                @can('auth-check', $userAuth->authorize('admin-contacts-index'))
                    <li class="nav-item">
                        <a href="{{ route('admin.contacts.index') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.contacts.index'])>
                            <i class="nav-icon fas fa-envelope"></i>
                            <p>{{ __('Messages') }}</p>
                        </a>
                    </li>
                @endcan
            </ul>
        </nav>
        <!-- [END] Sidebar menu -->
    </div>
    <!-- [END] Sidebar -->
</aside>
<!-- [END] Main sidebar container -->