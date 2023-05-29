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
                        <a type="button" class="nav-link {{ Request::is('dashboard/database') ? 'active' : '' }}"
                            data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true"
                            aria-controls="collapseOne">
                            <span data-feather="database"></span>
                            Database
                        </a>
                        <div id="collapseOne"
                            class="accordion-collapse collapse {{ Request::is('dashboard/database/*') ? 'show' : '' }}"
                            aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <ul class="acoordion-body list-unstyled container ms-2">
                                <li class="nav-item"><a
                                        class="nav-link dropdown-item {{ Request::is('dashboard/database/mahasiswa') ? 'active' : '' }}"
                                        href="/dashboard/database/mahasiswa"><span class="me-2"
                                            data-feather="chevrons-right"></span>Student</a></li>
                                <li class="nav-item"><a
                                        class="nav-link dropdown-item {{ Request::is('dashboard/database/dosen') ? 'active' : '' }}"
                                        href="/dashboard/database/dosen"><span class="me-2"
                                            data-feather="chevrons-right"></span>Lecture</a></li>
                                <li class="nav-item"><a
                                        class="nav-link dropdown-item {{ Request::is('dashboard/database/admin') ? 'active' : '' }}"
                                        href="/dashboard/database/admin"><span class="me-2"
                                            data-feather="chevrons-right"></span>Admin</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item accordion">
                        <a type="button" class="nav-link {{ Request::is('dashboard/academic') ? 'active' : '' }}"
                            data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true"
                            aria-controls="collapseTwo">
                            <span data-feather="calendar"></span>
                            Academic
                        </a>
                        <div id="collapseTwo"
                            class="accordion-collapse collapse {{ Request::is('dashboard/academic/*') ? 'show' : '' }}"
                            aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <ul class="acoordion-body list-unstyled container ms-2">
                                <li class="nav-item"><a
                                        class="nav-link dropdown-item {{ Request::is('dashboard/academic/class') ? 'active' : '' }}"
                                        href="/dashboard/academic/class"><span class="me-2"
                                            data-feather="chevrons-right"></span>Class</a></li>
                                <li class="nav-item"><a
                                        class="nav-link dropdown-item {{ Request::is('dashboard/academic/course') ? 'active' : '' }}"
                                        href="/dashboard/academic/course"><span class="me-2"
                                            data-feather="chevrons-right"></span>Course</a></li>
                                <li class="nav-item"><a
                                        class="nav-link dropdown-item {{ Request::is('dashboard/academic/schedule') ? 'active' : '' }}"
                                        href="/dashboard/academic/schedule"><span class="me-2"
                                            data-feather="chevrons-right"></span>Schedule</a></li>
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
                        <a class="nav-link {{ Request::is('dashboard/kelas*') ? 'active' : '' }}" href="/dashboard/jadwal">
                            <span data-feather="hard-drive"></span>
                            Kelas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('dashboard/presensi*') ? 'active' : '' }}"
                            href="/dashboard/presensi">
                            <span data-feather="camera"></span>
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
