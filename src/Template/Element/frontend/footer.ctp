<?php
if($controller == 'Menus') { ?>
    <footer class="res_footer fos_hide_part">
        <div class="container">
            <div class="col-xs-12 no-padding">
                <div class="col-md-6 col-sm-6 no-padding">
                    <p class="copyright "> &copy; Copyrights
                        <a href=""><?= $siteSettings['site_name'] ?></a>.
                        <?php echo __('All Rights Reserved');?>.
                    </p>
                </div>
                <div class="col-md-6 col-sm-6 no-padding text-right social-icon">
                    <a href="#" class=""><i class="fa fa-facebook"></i></a>
                    <a href="#" class=""><i class="fa fa-twitter"></i></a>
                    <a href="#" class=""><i class="fa fa-tumblr"></i></a>
                </div>
            </div>
        </div>
    </footer> <?php
} else if($controller == 'Checkouts') { ?>
 <footer class="res_footer fos_hide_part">
        <div class="container">
            <div class="col-xs-12 no-padding">
                <div class="col-md-6 col-sm-6 no-padding">
                    <p class="copyright "> &copy; Copyrights
                        <a href=""><?= $siteSettings['site_name'] ?></a>.
                        <?php echo __('All Rights Reserved');?>.
                    </p>
                </div>
                <div class="col-md-6 col-sm-6 no-padding text-right
            social-icon">
                    <a href="#" class=""><i class="fa fa-facebook"></i></a>
                    <a href="#" class=""><i class="fa fa-twitter"></i></a>
                    <a href="#" class=""><i class="fa fa-tumblr"></i></a>
                </div>
            </div>
        </div>
    </footer> <?php
}
 else {

     /*if(isset($this->request->params['action']) && $this->request->params['pass'][0] != "mobterms" && $this->request->params['pass'][0] != "mobprivacy" || $this->request->params['action'] != "getPages") {*/ ?>

    <footer class="otherfooter hidden-xs fos_hide_part">
        <div class="container">
            <div class="footer-top col-md-10 col-md-offset-1 col-xs-12 no-padding">
                <div class="col-sm-3 col-xs-12 foothead pul-right">
                    <h1><?php echo __('For Company');?></h1>
                    <ul class="footer-li">
                     <?php
                     if(!empty($static_page_list)) {
                         foreach ($static_page_list as $key => $value) { ?>
                             <li>
                                 <a href="<?php echo BASE_URL; ?>pages/<?php echo $value['seo_url']; ?>"><?php echo $value['title']; ?></a>
                             </li>
                         <?php }
                     }?>
                    </ul>
                </div>
                <div class="col-sm-3 col-xs-12 foothead pul-right">
                    <h1><?php echo __('For Customer');?></h1>
                    <ul class="footer-li">
                        <li><a href="<?php echo BASE_URL.'users/login'?>"><?php echo __('Login');?></a></li>
                        <li><a href="<?php echo BASE_URL.'users/signup'?>"><?php echo __('Sign Up');?></a></li>
                    </ul>
                </div>
                <div class="col-sm-3 col-xs-12 foothead pul-right">
                    <h1><?php echo __('For Restaurant');?></h1>
                    <ul class="footer-li">
                        <li><a href="<?php echo BASE_URL.'restaurantadmin'?>"><?php echo __('Restaurant login');?></a></li>
                        <li><a href="<?php echo BASE_URL.'restaurantSignup'?>"><?php echo __('Registration');?></a></li>
                    </ul>
                </div>
                <div class="col-sm-3 col-xs-12 foothead pul-right">
                    <h1><?php echo __('Followers');?></h1>
                    <ul class="footer-li">
                        <li><a href="" target="_blank" class="foot-facebook"><i class="fa fa-facebook"></i>Facebook</a></li>
                        <li><a href="" target="_blank" class="foot-twitter"><i class="fa fa-twitter"></i>Twitter</a></li>
                        <li><a href="" target="_blank" class="foot-linkedin"><i class="fa fa-linkedin"></i>Linked in</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-xs-12 no-padding home-top text-center">
                <p class="copyright "> &copy; Copyrights
                    <a href=""><?= $siteSettings['site_name'] ?></a>.
                    <?php echo __('All Rights Reserved');?> .
                </p>
            </div>
        </div>
    </footer>
    <footer class="white_footer visible-xs fos_hide_part">
        <div class="col-xs-12  clearfix no-padding">
            <p class="copyright-white"> &copy; Copyrights
                <a href=""><?= $siteSettings['site_name'] ?></a>.
                <?php echo __('All Rights Reserved');?> .
            </p>
        </div>
    </footer>
<?php //}
}?>

<div class="ui-loader">
    <div class="spinner">
        <div class="spinner-icon"></div>
    </div>
</div>