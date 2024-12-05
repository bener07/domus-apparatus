<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Admin <sup>csm</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item @if(request()->is('index')) active @endif">
        <a class="nav-link" href="/">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Home</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Administração
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#utilizadoresDropdown"
            aria-expanded="true" aria-controls="utilizadoresDropdown">
            <i class="fas fa-fw fa-cog"></i>
            <span>Utilizadores</span>
        </a>
        <div id="utilizadoresDropdown" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Gestão de Utilizadores:</h6>
                <a class="collapse-item @if(request()->is('dashboard/users')) active @endif" href="{{route('admin.users')}}">Lista de Utilizadores</a>
                <a class="collapse-item @if(request()->is('dashboard/users/add')) active @endif" href="{{ route('admin.users.add') }}">+ Adicionar</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#equipamentosDropdown"
            aria-expanded="true" aria-controls="equipamentosDropdown">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Equipamentos</span>
        </a>
        <div id="equipamentosDropdown" class="collapse" aria-labelledby="headingUtilities"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Custom Utilities:</h6>
                <a class="collapse-item @if(request()->is('utilities-color')) active @endif" href="/utilities-color">Colors</a>
                <a class="collapse-item @if(request()->is('utilities-border')) active @endif" href="/utilities-border">Borders</a>
                <a class="collapse-item @if(request()->is('utilities-animation')) active @endif" href="/utilities-animation">Animations</a>
                <a class="collapse-item @if(request()->is('utilities-other')) active @endif" href="/utilities-other">Other</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Componentes da Template
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
            aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-folder"></i>
            <span>Gestão</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Login Screens:</h6>
                <a class="collapse-item @if(request()->is('login')) active @endif" href="/login">Login</a>
                <a class="collapse-item @if(request()->is('register')) active @endif" href="/register">Register</a>
                <a class="collapse-item @if(request()->is('forgot-password')) active @endif" href="/forgot-password">Forgot Password</a>
                <div class="collapse-divider"></div>
                <h6 class="collapse-header">Other Pages:</h6>
                <a class="collapse-item @if(request()->is('404')) active @endif" href="/404">404 Page</a>
                <a class="collapse-item @if(request()->is('blank')) active @endif" href="/blank">Blank Page</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#componentesDropdown"
            aria-expanded="true" aria-controls="componentesDropdown">
            <i class="fas fa-fw fa-cog"></i>
            <span>Pequenos componentes</span>
        </a>
        <div id="componentesDropdown" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Custom Components:</h6>
                <a class="collapse-item @if(request()->is('buttons')) active @endif" href="/buttons">Buttons</a>
                <a class="collapse-item @if(request()->is('cards')) active @endif" href="/cards">Cards</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Tables -->
    <li class="nav-item @if(request()->is('tables')) active @endif">
        <a class="nav-link" href="/tables">
            <i class="fas fa-fw fa-table"></i>
            <span>Tables</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
