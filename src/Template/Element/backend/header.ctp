<header class="main-header">
    <a href="<?= ADMIN_BASE_URL ?>" class="logo">
        <span class="logo-mini"><b>C</b>n<b>E</b></span>
        <span class="logo-lg"><b><?= $siteSettings['site_name'] ?></b></span>
    </a>
    <nav class="navbar navbar-static-top">
       
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                   <a href="<?= ADMIN_BASE_URL ?>orders" class=""> <i class="fa fa-shopping-cart"></i> Orders</a>    
                </li>
                <li class="dropdown user user-menu">
                <a class="dropdown-toggle" data-toggle="dropdown" href="<?= ADMIN_BASE_URL ?>dispatch" aria-expanded="true"><i class="fa fa-automobile"></i> Dispatch</a>
                    <ul class="dropdown-menu">
                      <li class="header"><a href="<?= ADMIN_BASE_URL ?>dispatches">Manage Orders</a></li>
                      <li class="header"><a href="<?= ADMIN_BASE_URL ?>drivers">Manage Drivers</a></li>
                    </ul>
                </li>
                <li class="dropdown user user-menu">
                    <a target="_blank" href="<?= ADMIN_BASE_URL ?>dispatches/tracking" class=""><i class="fa fa-automobile"></i> Dispatcher</a>
                </li>
                <li class="dropdown user user-menu">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true"><i class="fa fa-cog"></i> Settings</a>
                   <ul class="dropdown-menu">
                      <li class="header"><a href="<?= ADMIN_BASE_URL ?>sitesettings">Site Settings</a></li>
                      <li class="header"><a href="<?= ADMIN_BASE_URL ?>sitesettings/paymentSettings">Payment Settings</a></li>
                    </ul>
                </li>
                 <li class="dropdown user user-menu">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"  aria-expanded="true"><i class="fa fa-shopping-cart"></i> Restaurants</a>
                   <ul class="dropdown-menu">
                      <li class="header"><a href="<?= ADMIN_BASE_URL ?>restaurants">Manage Restaurant </a></li>
                      <li class="header"><a href="<?= ADMIN_BASE_URL ?>menus">Manage Menu</a></li>
                      <li class="header"><a href="<?= ADMIN_BASE_URL ?>offers">Restaurant Offer</a></li>
                      <li class="header"><a href="<?= ADMIN_BASE_URL ?>reviews">Restaurant Reviews</a></li>
                    </ul>
                </li>        
                <li class="dropdown user user-menu">
                    <a href="<?php echo ADMIN_BASE_URL.'users/logout';?>" class=""><i class="fa fa-sign-out"></i> Sign out</a>

                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <img src="<?php echo DIST_URL; ?>img/user2-160x160.jpg" class="img-circle" alt="User Image">
                            <p>
                                Super Admin
                                <small>Member since Jan. 2018</small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <!-- <div class="pull-left">
                                <a href="#" class="btn btn-default btn-flat">Profile</a>
                            </div> -->
                            <div class="pull-right">
                                <a href="<?php echo ADMIN_BASE_URL.'users/logout';?>" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
