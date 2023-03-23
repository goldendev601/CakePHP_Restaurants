<?php if($action == 'cartUpdate') { ?>
    <?php if(!empty($cartsDetails)) { ?>
        <div class="cart-hide-wrap cart-items-scroll">
            <?php
            foreach ($cartsDetails as $key => $value) { ?>
                <div class="menu-item-wrapper">
                    <div class="menu-item-sno"><?php echo $key+1; ?></div>
                    <div class="menu-item-middle">
                        <div class="menu-item-name-wrap">
                            <div class="menu-item-name"><?= $value['menu_name'] ?></div>
                            <div class="subAddons">
                                <?php echo $value['subaddons_name'] ?>
                            </div>
                            <div class="menu-item-text"></div>
                        </div>
                        <div class="menu-item-quantity">
                            <a  class="menu-item-qty-icon" onclick="updateToCart(<?= $value['id'] ?>,'remove')">
                                <i class="icon icon-minus"></i>
                            </a>
                            <span class="quantitycount text-center"><?= $value['quantity'] ?></span>
                            <a class="menu-item-qty-icon" onclick="updateToCart(<?= $value['id'] ?>,'add')">
                                <i class="icon icon-plus"></i>
                            </a>
                        </div>
                        <div class="menu-item-delete"><i onclick="updateToCart(<?= $value['id'] ?>,'delete')" class="icon icon-close"></i></div>
                        <div class="menu-item-amount"><?= $siteSettings['site_currency'] ?> <?= number_format($value['total_price'],2) ?></div>
                    </div>
                </div>

                <?php
            } ?>


            <div class="bill-section">
                <div class="subtotal">
                    <span class="subtotal-txt">Subtotal</span>
                    <span class="subtotal-amt"><?= $siteSettings['site_currency'] ?> <?php echo number_format($subTotal,2) ?></span>
                </div>
                <div class="subtotal" id="deliveryAmt" style="<?= ($orderType != 'delivery') ? 'display:none' : '' ?>">
                    <span class="subtotal-txt">Delivery Fee</span>
                    <span class="subtotal-amt">
                        <?php echo ($deliveryCharge == 0) ? 'Free' : $siteSettings['site_currency'].' '.number_format($deliveryCharge,2); ?>
                    </span>
                </div>
                <div class="subtotal">
                    <span class="subtotal-txt">Tax (<?= number_format($restaurantDetails['restaurant_tax'],2) ?>%)</span>
                    <span class="subtotal-amt"><?= $siteSettings['site_currency'] ?> <?php echo number_format($taxAmount,2) ?></span>
                </div>
                <?php if($this->request->session()->read('offerAmount') != '') { ?>
                    <div class="subtotal">
                        <span class="subtotal-txt"><?php echo __('Offer Amount');?> (<?= $this->request->session()->read('offerPercentage') ?> %)</span>
                        <span class="subtotal-amt"><?= ($siteSettings['site_currency']) ?> <span class="offerTotal"><?= number_format($this->request->session()->read('offerAmount'),2) ?> (-)</span>
                    </div>

                <?php } ?>
                <?php if($this->request->session()->read('rewardPoint') != '') { ?>
                    <div class="subtotal">
                        <span class="subtotal-txt"><?php echo __('Redeem Amount');?> (<?= $this->request->session()->read('rewardPercentage') ?> %)</span>
                        <span class="subtotal-amt"><?= ($siteSettings['site_currency']) ?> <span class="redeemTotal"><?= number_format($this->request->session()->read('rewardPoint'),2) ?> (-)</span>
                    </div>

                <?php } ?>
            </div>
        </div>
        <div class="col-xs-12 no-padding">
              <a class="pull-left viewcart" data-view-cart="View Cart" data-hide-cart="Hide Cart" onclick="viewcart(this);">View Cart</a>
        
            <div class="cart-checkout" id="deliveryTotal" style="<?= ($orderType != 'delivery') ? 'display:none' : '' ?>">
                <button onclick="return gotoCheckout()" id="submitBtn" <?php echo (empty($cartsDetails) || $subTotal < $restaurantDetails['minimum_order']) ? 'disabled' : ''; ?> class="btn-checkout" href="javascript:void(0)"> <?php echo __(($currentStatus == 'Open') ? 'Checkout' : (($currentStatus == 'PreOrder') ? 'Pre Order' : 'Pre Order'));?> ( <?= $siteSettings['site_currency'] ?> <?php echo number_format($totalAmount,2) ?> )</button>
            </div>

            <div class="cart-checkout" id="pickupTotal" style="<?= ($orderType != 'pickup') ? 'display:none' : '' ?>">
                <button onclick="return gotoCheckout()" id="submitBtn" <?php echo (empty($cartsDetails)) ? 'disabled' : ''; ?> class="btn-checkout" href="javascript:void(0)"> <?php echo __(($currentStatus == 'Open') ? 'Checkout' : (($currentStatus == 'PreOrder') ? 'Pre Order' : 'Pre Order'));?> ( <?= $siteSettings['site_currency'] ?> <?php echo number_format($withOutDelivery,2) ?> )</button>
            </div>
        </div>
        <?php if($restaurantDetails['restaurant_delivery'] == 'Yes') { ?>
            <div class="min-order-value minimumsection"> <?php echo __('Minimum Order');?> <?= $siteSettings['site_currency'] ?> <?= number_format($restaurantDetails['minimum_order'],2) ?> </div>
        <?php } ?>


        <?php if($this->request->session()->read('orderPoint') != '') { ?>
            <div class="common-class"><div id="blink_text" class="min-order-value reward_earning"><span class="reward_trophy"><i class="fa fa-trophy"></i></span> <?php echo __('You will earn');?> <?= floor($this->request->session()->read('orderPoint')) ?> <?php echo __('Points') ?> </div></div>
            <script type="text/javascript">
                 function flash(el, c1, c2) {
                    var text = document.getElementById(el);
                    text.style.color = (text.style.color == c2) ? c1 : c2;
                    }
                    setInterval(function() {
                        flash('blink_text', 'gray', 'red')
                    }, 1000);
            </script>
        <?php } ?>
    <?php }else { ?>
        <div class="cart-wrapper">
            <div class="cart-innerwrapper">
                <div class="cart-box">
                    <div class="col-md-12 text-left warehouse_head"><?php echo __('Your Cart Empty');?>
                    </div>
                </div>
                <div class="no-cart">
                    <div class="no-cart-image text-center"><img src="<?= BASE_URL ?>images/cartitem.png"></div>
                    <div class="no-cart-text">No Item(s) Added</div>
                </div>
            </div>
        </div>
   <?php
    } ?>

<?php echo '@@'.$minimumOrder.'@@'.number_format($subTotal,2);  die(); } ?>

<?php if($action == 'getMenuDetails') { //pr($menuDetails);die(); ?>
    <input type="hidden" id="menuId" value="<?= $menuId ?>">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title text-center"><?= $menuDetails['menu_name'] ?></h3>
            </div>
            <div class="modal-body menuInner clearfix">
                <div id="getDetails">
                    <input id="productAddonsSingle" class="addonsId" type="hidden" value="<?php echo $menuDetails['menu_details'][0]['id']; ?>">

                    <div class="col-xs-12 detailPopCont no-padding">
                        <div class="detailPopCont_top detailPopCont_topnew">
                            <div class="price_height">
                                <h5 class="addcart_popup_head-cart">Menu Price :</h5>
                                <?php
                                    $i = 0;
                                    foreach ($details as $dKey => $dValue) { ?>
                                        <h2 class="pull-left menuPrice_<?= $dKey ?> <?= ($i != 0) ? 'hide' : 'showClass'  ?> pul-right "> <?= $siteSettings['site_currency'] ?> <?= number_format($dValue['orginal_price'],2) ?></h2>
                                <?php
                                        $i++;
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="row">
                            <?php if(!empty($details) && $menuDetails['price_option'] != 'single' ) { ?>
                                <div class="col-xs-12 margin-top-15 selectSize">
                                    <div class="row">
                                        <?php
                                        $m = 0;
                                        foreach ($details as $key => $value) { ?>

                                                <div class="radio radio-inline">
                                                    <input type="radio" class="addonsId" id="productAddons_<?= $value['id'] ?>" name="addons_radio" <?= ($m == 0) ? 'checked' : '' ?> onclick="return getDetails(<?= $key  ?>)" value="<?= $value['id'] ?>" >
                                                    <label for="productAddons_<?= $value['id'] ?>"><?php echo $value['sub_name'] ?> </label>
                                                </div>
                                                <?php
                                            $m++;
                                            } ?>
                                    </div>
                                </div>
                            <?php } ?>                    


                            <div class="col-xs-12 no-padding selectSize">
                                <div class="row">

                                    <div id="loadMenuAddons">
                                        <?php if(!empty($addons)) {
                                            $mainAddonId = '';
                                            foreach ($addons as $akey => $avalue) { ?>
                                                <div class="col-xs-12 selectquantity">

                                                    <h5 class="addcart_popup_head"><?php echo $avalue['mainaddons_name'] ?>
                                                        &nbsp;<small>(minimum
                                                            : <?= $avalue['mainaddons_mini_count'] ?>
                                                            maximum
                                                            : <?= $avalue['mainaddons_count'] ?>)
                                                        </small>
                                                    </h5>

                                                    <?php

                                                    // ***************** Minimum maximum checking process ***************//
                                                    echo $this->Form->input('mainaddons_mini_count', array(
                                                        'type' => 'hidden',
                                                        'class' => 'mini_' . $akey,
                                                        'value' => $avalue['mainaddons_mini_count']));
                                                    echo $this->Form->input('mainaddons_max_count', array(
                                                        'type' => 'hidden',
                                                        'class' => 'maxi_' . $akey,
                                                        'value' => $avalue['mainaddons_count']));
                                                    echo $this->Form->input('main_addons_name', array(
                                                        'type' => 'hidden',
                                                        'class' => 'MainAddonsName',
                                                        'id' => $akey,
                                                        'value' => $avalue['mainaddons_name']));
                                                    //**************************** End *****************//

                                                    ?>

                                                    <?php if(!empty($avalue['sub_addons'])) {
                                                        foreach($avalue['sub_addons'] as $sKey => $sValue) { ?>

                                                            <?php if ($avalue['mainaddons_count'] > 1) {
                                                                $radioPrice = 0;
                                                                ?>
                                                                <div class="checkbox checkbox-inline">
                                                                    <input type="checkbox" id="checkCount_<?= $sKey ?>"
                                                                           class="addon_ss checkCount_<?= $akey ?>"
                                                                           name="subaddon_radio_<?= $akey ?>" value="<?= $sKey; ?>">
                                                                    <?php foreach ($sValue['price'] as $pKey => $pValue) { ?>

                                                                        <label class="menuPrice_<?= $pKey ?> <?= ($radioPrice != 0) ? 'hide' : 'showClass' ?>"
                                                                               for="checkCount_<?= $sKey ?>"><?= $sValue['name'] ?>
                                                                            (<?= ($pValue >0) ? $siteSettings['site_currency'].' '. number_format($pValue, 2) : 'Free' ?>
                                                                            )
                                                                        </label>
                                                                        <?php
                                                                        $radioPrice++;
                                                                    } ?>
                                                                </div>
                                                                <?php
                                                            } else {
                                                                $radioPrice = 0;
                                                                ?>
                                                                <div class="radio radio-inline">
                                                                    <input type="radio" id="checkCount_<?= $sKey ?>"
                                                                           class="addon_ss checkCount_<?= $akey ?>"
                                                                           name="subaddon_radio_<?= $akey ?>" value="<?= $sKey ?>">
                                                                    <?php foreach ($sValue['price'] as $pKey => $pValue) { ?>

                                                                        <label class="menuPrice_<?= $pKey ?> <?= ($radioPrice != 0) ? 'hide' : 'showClass' ?>"
                                                                               for="checkCount_<?= $sKey ?>"><?= $sValue['name'] ?>
                                                                            (<?= ($pValue >0) ? $siteSettings['site_currency'].' '. number_format($pValue, 2) : 'Free' ?>
                                                                            )</label>

                                                                        <?php
                                                                        $radioPrice++;
                                                                    } ?>
                                                                </div>
                                                                <?php
                                                            } ?>

                                                            <?php
                                                        } ?>
                                                    <div id="subaddonErr_<?php echo $akey; ?>" class=""></div>
                                                    <?php
                                                    } ?>

                                                </div>
                                                <?php
                                            }
                                        } ?>
                                    </div>
                                </div>
                            </div>                           
                        </div>
                    </div>                   
                </div>

                <div class="col-xs-12 text-center modal-footer">
                    <div class="pull-left pul-right">
                        <div class="col-sm-12 no-padding m-t-5">
                            <div class="bootstrap-touchspin">
                                <span class="input-group-btn"><button type="button" onclick="return decrement()" class="btn btn-default bootstrap-touchspin-down">-</button></span>
                                <input type="text" value="1" class="text-center form-control" id="quantity" style="display: block;">
                                <span class="input-group-btn"><button onclick="return increment()" type="button" class="btn btn-default bootstrap-touchspin-up">+</button></span>
                            </div>
                        </div>
                    </div>
                    <div class="pull-right width pul-left">
                        <button class="btn btn-md btn-primary btn-new" onclick="return variantCart(<?= $menuDetails['id'] ?>);" type="submit">Add To Cart </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php die(); } ?>

<?php if($action == 'getDetails') { ?>

    <div class="col-xs-12 detailPopCont no-padding">
        <div class="detailPopCont_top detailPopCont_topnew">
            <div class="price_height">
                <h5 class="addcart_popup_head-cart">Menu Price :</h5>
                <h2 class="pull-left"> <?= $siteSettings['site_currency'] ?> <?= number_format($menuAddonsList[0]['orginal_price'],2) ?></h2>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="row">
            <div class="col-xs-12 margin-top-15 selectSize">
                <div class="row">
                    <?php if(!empty($menuDetails['menu_details'])) {
                        foreach ($menuDetails['menu_details'] as $key => $value) { ?>

                            <div class="radio radio-inline">
                                <input type="radio" class="addonsId" id="productAddons_<?= $value['id'] ?>" name="addons_radio" <?= ($value['id'] == $id) ? 'checked' : '' ?> onclick="return getDetails(<?= $menuId ?>,<?= $value['id']  ?>)" >
                                <label for="productAddons_<?= $value['id'] ?>"><?php echo $value['sub_name'] ?> </label>
                            </div>
                            <?php

                        }
                    } ?>
                </div>
            </div>
            <div class="col-xs-12 no-padding selectSize">
                <div class="row">

                    <div id="loadMenuAddons">
                        <?php if(!empty($menuAddonsList[0]['menu_addons'])) {
                            $mainAddonId = '';
                            foreach ($menuAddonsList[0]['menu_addons'] as $akey => $avalue) { ?>
                                <div class="col-xs-12 selectquantity">
                                    <?php if($avalue['mainaddons_id'] != $mainAddonId) { ?>
                                        <h5 class="addcart_popup_head"><?php echo $avalue['mainaddon']['mainaddons_name'] ?>&nbsp;<small>(minimum : <?= $avalue['mainaddon']['mainaddons_mini_count'] ?> maximum : <?= $avalue['mainaddon']['mainaddons_count'] ?>)</small></h5>
                                        <?php

                                        // ***************** Minimum maximum checking process ***************//
                                        echo $this->Form->input('mainaddons_mini_count',array(
                                            'type' => 'hidden',
                                            'class' => 'mini_'.$akey,
                                            'value' => $avalue['mainaddon']['mainaddons_count']));
                                        echo $this->Form->input('mainaddons_max_count',array(
                                            'type' => 'hidden',
                                            'class' => 'maxi_'.$akey,
                                            'value' => $avalue['mainaddon']['mainaddons_count']));
                                        echo $this->Form->input('main_addons_name',array(
                                            'type' => 'hidden',
                                            'class' => 'MainAddonsName',
                                            'id' => $akey,
                                            'value' => $avalue['mainaddon']['mainaddons_name']));
                                        //**************************** End *****************//

                                        ?>
                                    <?php } ?>


                                    <?php if($avalue['mainaddon']['mainaddons_count'] > 1) { ?>
                                        <div class="checkbox checkbox-inline">
                                            <input type="checkbox" id="checkCount_<?= $avalue['id'] ?>" class="addonsId checkCount_<?= $avalue['id'] ?>"><label for="checkCount_0_0"><?= $avalue['subaddon']['subaddons_name'] ?>  (<?= $siteSettings['site_currency'] ?> <?= number_format($avalue['subaddons_price'],2) ?>)</label>
                                        </div>
                                        <?php
                                    }else { ?>
                                        <div class="radio radio-inline">
                                            <input type="radio" id="checkCount_<?= $avalue['id'] ?>" class="addonsId checkCount_<?= $avalue['id'] ?>" name="subaddon_radio">
                                            <label for="checkCount_<?= $avalue['id'] ?>"><?= $avalue['subaddon']['subaddons_name'] ?>  (<?= $siteSettings['site_currency'] ?> <?= number_format($avalue['subaddons_price'],2) ?>)</label>
                                        </div>
                                        <?php
                                    } ?>
                                </div>
                                <?php
                                $mainAddonId = $avalue['mainaddons_id'];
                            }
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
    die();
} 

 if($action == 'getTiming') {
    if(!empty($array_of_time)) { 
        echo $this->Form->input('booking_time',[
                'type'  => 'select',
                'class'   => 'form-control bookRequest',
                'options' => !empty($array_of_time) ? $array_of_time:'Closed',
                'empty'   => __('Select Time'),
                'id' => 'getDateTime',
                'value' => !empty($array_of_time) ? $array_of_time:'Closed',
                'label'   => false
            ]); ?>
        <span id="bookError1" class="error"></span>
  <?php  } 

 die(); } ?>
