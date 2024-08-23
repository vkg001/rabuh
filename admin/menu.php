<div class="left-sidebar">

    <div class="p-4">
        <div class="row mt-4">
            <div class="col-sm-12 text-center">
                <img src="<?php echo $user_data['profile_pic'] ?>" alt="user" style="width: 10rem; height: 10rem; border-radius: 50%; border-radius: 50%; object-fit: cover;">
            </div>
            <div class="col-sm-12 mt-2 text-center text-white align-self-center">
                <h5 class="font-14 m-0 fw-bold text-white">
                    <?php echo $user_data['name'] ?>
                </h5>
                <p class="mb-0" style="opacity: 0.8;"><?php echo $user_data['email'] ?></p>
            </div>
        </div>
    </div>
    <!-- Tab panes -->
    <hr class="hr-dashed">
    <!--end logo-->
    <div class="menu-content h-100" data-simplebar>
        <div class="menu-body navbar-vertical">
            <div class="collapse navbar-collapse tab-content" id="sidebarCollapse">
                <!-- Navigation -->
                <ul class="navbar-nav tab-pane active" id="Main" role="tabpanel">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="../home">
                                <i class="far fa-circle me-1" style="font-size: 0.4rem;"></i>
                                Home
                            </a>

                            <a class="nav-link" href="user">
                                <i class="far fa-circle me-1" style="font-size: 0.4rem;"></i>
                                Users
                            </a>

                            <a class="nav-link" href="posts">
                                <i class="far fa-circle me-1" style="font-size: 0.4rem;"></i>
                                Posts
                            </a>

                            <a class="nav-link" href="../profile">
                                <i class="far fa-circle me-1" style="font-size: 0.4rem;"></i>
                                My Profile
                            </a>
                        </li>
                    </ul>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- end left-sidenav-->
<!-- end leftbar-tab-menu-->

<!-- Top Bar Start -->
<!-- Top Bar Start -->
<div class="topbar">
    <nav class="navbar-custom" id="navbar-custom">
        <ul class="list-unstyled topbar-nav float-end mb-0">
            <li class="dropdown">
                <a class="nav-link dropdown-toggle nav-user" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <div class="d-flex align-items-center">
                        <img src="<?php echo $user_data['profile_pic'] ?>" alt="profile-user" class="rounded-circle me-2 thumb-sm" />
                        <div>
                            <small class="d-none d-md-block font-11">Admin</small>
                            <span class="d-none d-md-block fw-semibold font-12"><?php echo $user_data['name'] ?> <i class="mdi mdi-chevron-down"></i></span>
                        </div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="../profile"><i class="ti ti-user font-16 me-1 align-text-bottom"></i> Profile</a>
                    <div class="dropdown-divider mb-0"></div>
                    <a class="dropdown-item" href="../logout"><i class="ti ti-power font-16 me-1 align-text-bottom"></i> Logout</a>
                </div>
            </li>
        </ul>

        <ul class="list-unstyled topbar-nav mb-0">
            <li class="hide-phone app-search">
                <form role="search" action="#" method="get" onsubmit="return false;">
                    <input type="search" name="search" class="form-control top-search mb-0" placeholder="Type text...">
                    <button type="button" class="top-bar-search-btn"><i class="ti ti-search"></i></button>
                </form>
            </li>
        </ul>
    </nav>
    <!-- end navbar-->
</div>
<!-- Top Bar End -->
<!-- Top Bar End -->