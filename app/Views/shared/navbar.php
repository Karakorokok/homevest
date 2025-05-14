<?php 

    $sessionUserID = session()->get('id_user');
    $uri = service('uri'); 

?>

<nav class="navbar fixed-bottom bg-mylight border-top">
    <div class="container-fluid d-flex justify-content-around">
        <a href="<?= base_url('/home'); ?>" 
           class="nav-item d-flex flex-column justify-content-center align-items-center <?= ($uri->getSegment(1) == 'home') ? 'text-primary' : 'text-mydark'; ?>">
            <i class="fa-solid fa-house font-20"></i>
            <span class="small">Home</span>
        </a>
        <a href="<?= base_url('/message'); ?>" 
           class="nav-item d-flex flex-column justify-content-center align-items-center <?= ($uri->getSegment(1) == 'message' || $uri->getSegment(1) == 'messagelanding') ? 'text-primary' : 'text-mydark'; ?>">
            <i class="fa-solid fa-comment font-20"></i>
            <span class="small">Message</span>
        </a>
        <a href="<?= base_url('/profile'); ?>" 
           class="nav-item d-flex flex-column justify-content-center align-items-center <?= ($uri->getSegment(1) == 'profile') ? 'text-primary' : 'text-mydark'; ?>">
            <i class="fa-solid fa-user font-20"></i>
            <span class="small">Profile</span>
        </a>
    </div>
</nav>