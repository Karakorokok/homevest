
<?php 
        $sessionUserID = session()->get('id_user');
        $sessionUserF = session()->get('firstname');
        $sessionUserM = session()->get('middlename');
        $sessionUserL = session()->get('lastname');
?>

<nav class="navbar bg-mylight sticky-top">
    <div class="container-fluid">
        <a data-mdb-toggle="offcanvas" data-mdb-offcanvas-init href="#adminSidebar" role="button" class="">
            <i class="fa-solid fa-bars text-dark"></i>
        </a>

        <div>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a data-mdb-dropdown-init class="nav-link dropdown-toggle d-flex align-items-center" href="" role="button">
                        <span class="me-2"><?= $sessionUserF . ' ' . $sessionUserL ?></span>
                        <img src="/resources/images/admin.png" class="rounded-circle" height="40" loading="lazy"/>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="/adminprofile">Profile</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="/logout">Logout</a>
                        </li>
                    </ul>

                </li>
            </ul>
        </div>

    </div>
</nav>

<style>
    @media (max-width: 768px) {
        .my-offcanvas {
            width: 90% !important; 
        }
    }
</style>

<!-- offcanvas sidebar -->
<div class="my-offcanvas offcanvas offcanvas-start" tabindex="-1" id="adminSidebar">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasExampleLabel">Home Vest Admin</h5>
        <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn-close text-reset" data-mdb-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">

        <ul class="list-group list-group-light">
            <a href="/adminhome">
                <li class="list-group-item px-3 border-0 <?= uri_string() == 'adminhome' ? 'active' : '' ?>">Home</li>
            </a>
            <a href="/adminagents">
                <li class="list-group-item px-3 border-0 <?= uri_string() == 'adminagents' ? 'active' : '' ?>">Agents</li>
            </a>
            <a href="/admindevelopers">
                <li class="list-group-item px-3 border-0 <?= uri_string() == 'admindevelopers' ? 'active' : '' ?>">Developers</li>
            </a>
        </ul>
       
    </div>
</div>

