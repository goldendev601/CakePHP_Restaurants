<header class="main-header res-main-header">
    <a href="<?= REST_BASE_URL ?>" class="logo">
        <span class="logo-mini"><b>C</b>n<b>E</b></span>
        <span class="logo-lg"><b><?= $siteSettings['site_name'] ?></b></span>
    </a>
    <nav class="navbar navbar-static-top">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu res-nav-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="<?php echo REST_BASE_URL.'users/logout';?>" class="btn btn-flat">Sign out</a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <img src="<?php echo DIST_URL; ?>img/user2-160x160.jpg" class="img-circle" alt="User Image">
                            <p>
                                Alexander Pierce - Web Developer
                                <small>Member since Nov. 2012</small>
                            </p>
                        </li>
                        <li class="user-footer">

                            <div class="pull-right">
                                <a href="<?php echo REST_BASE_URL.'users/logout';?>" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>