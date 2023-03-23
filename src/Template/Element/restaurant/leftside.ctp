<aside class="main-sidebar res-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?php echo DIST_URL; ?>img/user2-160x160.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>Restaurant Admin</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <ul class="sidebar-menu res-sidebar-menu" data-widget="tree">
            <li class="header res-header">MAIN NAVIGATION</li>
            <li class="active">
                <a href="<?php echo REST_BASE_URL; ?>dashboard"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
            </li>

            <li class="active">
                <a href="<?php echo REST_BASE_URL; ?>changepassword"><i class="fa fa-dashboard"></i> <span>Change Password</span></a>
            </li>
            <li class="">
                <a href="<?php echo REST_BASE_URL; ?>restaurants"><i class="fa fa-cog"></i> <span>Settings</span></a>
            </li>
            <li class="">
                <a href="<?php echo REST_BASE_URL; ?>categories"><i class="fa fa-th-list"></i> <span>Category</span></a>
            </li>
           <!--  <li class="">
                <a href="<?php echo REST_BASE_URL; ?>coupons"><i class="fa fa-bars"></i> <span>Coupons</span></a>
            </li> -->
            <li class="">
                <a href="<?php echo REST_BASE_URL; ?>addons"><i class="fa fa-puzzle-piece"></i> <span>Addons</span></a>
            </li>
            <li class="">
                <a href="<?php echo REST_BASE_URL; ?>menus"><i class="fa fa-briefcase"></i> <span>Manage Menu</span></a>
            </li>
          
            <?php if($restaurantDetails['restaurant_dispatch'] == 'Yes') { ?>
                <li class="">
                    <a href="<?= REST_BASE_URL ?>dispatch"><i class="fa fa-automobile"></i> <span>Dispatch</span></a>
                </li>
            <?php } ?>
            <li class="">
                <a href="<?php echo REST_BASE_URL; ?>orders/index"><i class="fa fa-shopping-cart"></i> <span>Order</span></a>
            </li>
            <li class="">
                <a href="<?php echo REST_BASE_URL; ?>orders/collectionorder"><i class="fa fa-truck"></i> <span>Pickup Order</span></a>
            </li>
          <!-- <li class="">
                <a href="<?php /* echo REST_BASE_URL;*/ ?>invoices/index"><i class="fa fa-list-alt"></i> <span>Invoice</span></a>
            </li> -->
            <li class="">
                <a href="<?php echo REST_BASE_URL; ?>reports/index"><i class="fa fa-file-text-o"></i> <span>Report</span></a>
            </li>
            <li class="">
                <a href="<?php echo REST_BASE_URL; ?>vouchers/index"><i class="fa fa-bars"></i> <span>Coupons</span></a>
            </li>

            <!--<li class="">
                <a href="<?php /*echo REST_BASE_URL; */?>deals/index"><i class="fa fa-tags"></i> <span>Deals</span></a>
            </li>-->
             <li class="">
                <a href="<?php echo REST_BASE_URL; ?>offers/index"><i class="fa fa-money"></i> <span>Offers</span></a>
            </li>

            <!-- <li class="">
                <a href="<?php /* echo REST_BASE_URL; */ ?>bookaTables/index"><i class="fa  fa-wheelchair"></i> <span>Book aTable</span></a>
                
            </li> -->
            <?php if($restaurantDetails['restaurant_dispatch'] == 'Yes') { ?>
                <li class="">
                    <a href="<?php echo REST_BASE_URL; ?>drivers/index"><i class="fa fa-car"></i> <span>Driver</span></a>
                </li>
            <?php } ?>
        </ul>
    </section>
</aside>
