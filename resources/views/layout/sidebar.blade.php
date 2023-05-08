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
                    <li class="nav-item accordion">
                        <a type="button" class="nav-link {{ Request::is('dashboard/database*') ? 'active' : '' }}"
                            data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true"
                            aria-controls="collapseOne">
                            <span data-feather="database"></span>
                            Database
                        </a>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                            data-bs-parent="#accordionExample">
                            <ul class="acoordion-body list-unstyled container ms-4">
                                <li><a class="dropdown-item mb-1" href="/dashboard/database/mahasiswa"><span class="me-2" data-feather="chevrons-right"></span>Mahasiswa</a></li>
                                <li><a class="dropdown-item mb-1" href="/dashboard/database/dosen"><span class="me-2" data-feather="chevrons-right"></span>Dosen</a></li>
                                <li><a class="dropdown-item mb-1" href="/dashboard/database/jadwal"><span class="me-2" data-feather="chevrons-right"></span>Jadwal</a></li>
                                <li><a class="dropdown-item mb-1" href="/dashboard/database/admin"><span class="me-2" data-feather="chevrons-right"></span>Admin</a></li>
                            </ul>
                        </div>
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
                        <a class="nav-link {{ Request::is('dashboard/kelas*') ? 'active' : '' }}" href="/dashboard/kelas">
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
                        <a class="nav-link {{ Request::is('dashboard/presensi*') ? 'active' : '' }}"
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
