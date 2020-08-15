

<div class="nav-left-sidebar sidebar-dark">
    <div class="menu-list">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="d-xl-none d-lg-none" href="/">Dashboard</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav flex-column">
                    <li class="nav-divider">Menu</li>
                    <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteNamed('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="fa fa-fw fa-user-circle"></i>Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::currentRouteNamed('projects.create') ? 'active' : '' }}" href="{{ route('projects.create') }}"><i class="fa fa-fw fa-plus"></i>Create
                            Project</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::currentRouteNamed('documentation') ? 'active' : '' }}" href="{{ route('documentation') }}"><i
                                class="fa fa-code" style="vertical-align: middle;"></i>Documentation</a>
                    </li>

                    <li class="nav-divider">
                        Projects
                    </li>
                    @foreach(Auth::user()->projects as $project)
                    <li class="nav-item" style="vertical-align: middle;"><a class="nav-link" href="{{ route('projects.show', $project) }}"><i
                                class="fa fa-fw fa-rocket"></i>{{ $project->name }}</a></li>
                    @endforeach
                </ul>
            </div>
        </nav>
    </div>
</div>