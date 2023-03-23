<?php
   if (!empty($staticpage_list)) { ?>
<section class="main_wrapper">
   <div class="loginBg">
      <div class="container">
         <div class="row">
            <h4 class="aboutus-head"><?php echo $staticpage_list['title'];?></h4>
            <div class="col-xs-12 no-padding aboutus-cont"><?php echo $staticpage_list['content'];?></div>
         </div>
      </div>
   </div>
</section>
<?php }
   else { ?>
<section class="main_wrapper">
   <div class="loginBg">
      <div class="container">
         <div class="row">
            <p class="text-center"></p>
         </div>
      </div>
   </div>
</section>
<?php } ?>

