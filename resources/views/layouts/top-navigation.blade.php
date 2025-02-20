<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <ul class="navbar-nav">
        <li class="d-none d-md-block">
            <a class="btn @if(request()->is('requisitar')) btn-primary @else btn-outline-secondary @endif" href="/requisitar">
                <i class="bi bi-motherboard fa-sm fa-fw mr-2"></i>
                Requisitar
            </a>
            <a class="btn @if(request()->is('entregar')) btn-primary @else btn-outline-secondary @endif" href="/entregar">
                <i class="bi bi-box-arrow-down fa-sm fa-fw mr-2"></i>
                Entregar
            </a>
        </li>
        <li class="dropdown no-arrow">
            <a class="btn btn-link d-md-none rounded-circle mr-3" href="#" id="menuDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-bars"></i>
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right fs-5 shadow animated--grow-in"
                aria-labelledby="menuDropdown">
                <a class="dropdown-item" href="/requisitar">
                    <i class="bi bi-motherboard fa-sm fa-fw mr-2 text-gray-600"></i>
                    Requisitar
                </a>
                <a class="dropdown-item" href="/entregar">
                    <i class="bi bi-box-arrow-down fa-sm fa-fw mr-2 text-gray-600"></i>
                    Entregar
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="/activity">
                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-500"></i>
                    Atividade
                </a>
            </div>
        </li>
    </ul>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->name }}</span>
                <img class="img-profile rounded-circle"
                    src="{{ auth()->user()->avatar }}">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in fs-5"
                aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Settings
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                    Activity Log
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>
    </ul>

</nav>
<!-- End of Topbar -->