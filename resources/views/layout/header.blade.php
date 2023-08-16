<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">{{ __("QRCode Presence") }}</a>
    <div class="btnLang">
        <div class="dropdown">
            <a class="btn btn-dark dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-earth-americas"></i>
            </a>
    
            <ul class="dropdown-menu mw-25 text-center" aria-labelledby="dropdownMenuLink">
                <li><a class="dropdown-item @if (Session::get('locale') == "id") active  @endif" href="/language/id">ID</a></li>
                <li><a class="dropdown-item @if (Session::get('locale') == "en") active  @endif" href="/language/en">EN</a></li>
            </ul>
        </div>
    </div>
    <button class="navbar-toggler position-absolute btn-secondary d-md-none collapsed " type="button"
        data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
        aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
</header>
