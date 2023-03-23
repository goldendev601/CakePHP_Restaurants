<aside class="main-sidebar">
    <section class="sidebar">
         <!-- Dont Remove Starts -->
         <div class="user-panel">
            <div class="pull-left image">
                <img src="<?php echo DIST_URL; ?>img/user2-160x160.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>Super Admin</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>
            <li class="<?= ($action == 'dashboard') ? 'active menu-open' : '' ?>">
                <a href="<?= ADMIN_BASE_URL ?>dashboard">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>

            <li class="<?= ($action == 'changepassword') ? 'active menu-open' : '' ?>">
                <a href="<?= ADMIN_BASE_URL ?>changepassword">
                    <i class="fa fa-dashboard"></i> <span>Change Password</span>
                </a>
            </li>
            <?php
            if ($loggedUser['role_id'] == 1) {?>
            <li class="<?= ($controller == 'Subadmins') ? 'active menu-open' : '' ?>">
                <a href="<?= ADMIN_BASE_URL ?>subadmins">
                    <i class="fa fa-dashboard"></i> <span>Subadmin</span>
                </a>
            </li>
            <?php }
             if ($loggedUser['role_id'] == 4) {
                foreach ($modules as $k => $v) {
                    if (in_array($v['id'], $permissions)) { ?>
                        <li class=" <?= (!empty($subModules[$v['id']])) ? 'treeview' : ''  ?> ">
                            <a href="<?php echo ADMIN_BASE_URL . $v['page_url'] ?>">
                                <i class="fa fa-edit"></i>
                                <span><?= $v['modulesname'] ?></span>
                                <?php if (!empty($subModules[$v['id']])) { ?>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                <?php } ?>
                            </a>
                            <?php if (!empty($subModules[$v['id']])) { ?>
                            <ul class="treeview-menu">
                                <?php
                                foreach ($subModules[$v['id']] as $key => $val) {
                                    if ($v['id'] == $val['parent_id'] && in_array($val['id'], $permissions)) { ?>
                                        <li id="<?= $val['id'] ?>"><a href="<?php echo ADMIN_BASE_URL . $val['page_url'] ?>"><i class="fa fa-circle-o"></i> <?= $val['modulesname'] ?></a></li>
                                    <?php }
                                } ?>
                            </ul>
                            <?php } ?>
                        </li>
                  <?php }else {

                        if (!empty($subModules[$v['id']])) {
                            $parentId = '';
                            foreach ($subModules[$v['id']] as $key => $val) {
                                if (in_array($val['id'], $permissions)) { ?>
                                    <li class=" <?= (!empty($subModules[$v['id']])) ? 'treeview' : '' ?> ">
                                        <?php if($parentId != $val['parent_id']) { ?>
                                            <a href="<?php echo ADMIN_BASE_URL . $v['page_url'] ?>">
                                                <i class="fa fa-edit"></i>
                                                <span><?= $v['modulesname'] ?></span>
                                                <?php if (!empty($subModules[$v['id']])) { ?>
                                                    <span class="pull-right-container">
                                            <i class="fa fa-angle-left pull-right"></i>
                                        </span>
                                                <?php } ?>
                                            </a>
                                        <?php } ?>
                                        <?php if (!empty($subModules[$v['id']])) { ?>
                                            <ul class="treeview-menu">
                                                <?php
                                                foreach ($subModules[$v['id']] as $key => $val) {
                                                    if ($v['id'] == $val['parent_id'] && in_array($val['id'], $permissions)) { ?>
                                                        <li id="<?= $val['id'] ?>"><a
                                                                    href="<?php echo ADMIN_BASE_URL . $val['page_url'] ?>"><i
                                                                        class="fa fa-circle-o"></i> <?= $val['modulesname'] ?>
                                                            </a></li>
                                                    <?php }
                                                } ?>
                                            </ul>
                                        <?php } ?>
                                    </li>

                                <?php
                                    $parentId = $val['parent_id'];
                                }

                            }
                        }
                    }
                }
            } elseif($loggedUser['role_id'] == 1) {
                foreach ($modules as $k => $v) {
                    if (!empty($v['id'])) { //echo strtolower($controller); echo $v['page_url']; ?>
                        <li class="<?php echo (!empty($subModules[$v['id']])) ?'treeview' : '';?> <?= (in_array(strtolower($controller),$v['available'])) ? 'active menu-open' : '' ?> ">
                            <a href="<?php echo ADMIN_BASE_URL . $v['page_url'] ?>">
                                <i class="fa fa-edit"></i> 
                                <span><?= $v['modulesname'] ?></span>
                                <?php if (!empty($subModules[$v['id']])) { ?>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                <?php } ?>
                            </a>
                            <?php if (!empty($subModules[$v['id']])) { ?>
                            <ul class="treeview-menu">
                                <?php 
                                foreach ($subModules[$v['id']] as $key => $val) {
                                    if (!empty($val['id']) && $v['id'] == $val['parent_id']) { ?>
                                        <li class="<?= (strtolower($controller) == $val['page_url'] || (in_array(strtolower($action),$val['modulesName'])) ) ? 'active menu-open' : '' ?>" id="<?= $val['id'] ?>"><a href="<?php echo ADMIN_BASE_URL . $val['page_url'] ?>"><i class="fa fa-circle-o"></i> <?= $val['modulesname'] ?></a></li>
                                    <?php }
                                } ?>
                            </ul>
                            <?php } ?>
                        </li>
                    <?php }
                }
            }?>
        </ul>
    </section>
</aside>

