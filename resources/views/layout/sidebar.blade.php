<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky navbar-custom mt-3">
        <div>
            <ul class="nav flex-column">

                @can('isAdmin')
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" aria-current="page"
                            href="/dashboard">
                            <span data-feather="home"></span>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('dashboard/database') ? 'active' : '' }}"
                            href="/dashboard/database">
                            <span data-feather="database"></span>
                            Database
                        </a>
                    </li>
                @endcan

                @can('isDosen')
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" aria-current="page"
                            href="/dashboard">
                            <span data-feather="home"></span>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('dashboard/kelas') ? 'active' : '' }}"
                            href="/dashboard/kelas">
                            <span data-feather="hard-drive"></span>
                            Kelas
                        </a>
                    </li>
                @endcan

                @can('isMahasiswa')
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" aria-current="page"
                            href="/dashboard">
                            <span data-feather="home"></span>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('dashboard/presensi') ? 'active' : '' }}"
                            href="/dashboard/presensi">
                            <span data-feather="hard-drive"></span>
                            Presensi
                        </a>
                    </li>
                @endcan
                
            </ul>
        </div>
        <div>
            <ul class="nav flex-column mb-5 text-center">
                <li class="nav-item">
                    <form action="/logout" method="POST">
                        @csrf
                        <button class="btn btn-secondary px-5" type="submit">
                            <span data-feather="log-out"></span>
                            Sign out
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
