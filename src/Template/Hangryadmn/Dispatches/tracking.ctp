<div class="fos-total-outer-wrapper">
    <span class="statusshow" style="display:none;"></span>
   <div id="dispatch_orders" class="fos-left-section">
      <div class="fos-tab text-center">
         <ul class="fos-tab-select text-center">
            <li>
               <a data-order="Unassign" class="active" onclick="return loadAllIcon('Unassign','');">
                  <div id="unassign_order" class="text-center fos-order-num"><?= count($acceptedOrder) ?></div>
                  <div class="text-center">Unassigned</div>
               </a>
            </li>
            <li>
               <a data-order="Waiting" onclick="return loadAllIcon('Waiting','');">
                  <div id="waiting_order" class="text-center fos-order-num"><?= count($waitingOrders) ?></div>
                  <div class="text-center">Waiting</div>
               </a>
            </li>
            <li>
               <a data-order="Assign" onclick="return loadAllIcon('Assign','');">
                  <div id="assign_order" class="text-center fos-order-num"><?= count($assignedOrders) ?></div>
                  <div class="text-center">Assigned</div>
               </a>
            </li>
            <li>
               <a data-order="Complete" onclick="return loadAllIcon('Complete','');">
                  <div id="complete_order" class="text-center fos-order-num"><?= count($completedOrders) ?></div>
                  <div class="text-center">Completed</div>
               </a>
            </li>
         </ul>
      </div>
      <div id="all_orders">
         <div id="Unassign" class="fos-common">
            <div class="fos-new-input">
               <input type="text" onkeyup="fossearchorder(this)" class="form-control fos-input"><i class="fa fa-search"></i>
            </div>
             <?php if(!empty($acceptedOrder)) {

                 foreach ($acceptedOrder as $aKey => $aValue) { ?>
                     <div class="fos-order-part fos-order-part_<?= $aValue['restaurant_id'] ?>">
                         <div onclick="return trackingMap(<?= $aValue['id'] ?>,<?= $aValue['restaurant_id'] ?>);" class="col-xs-12 no-padding">
                             <div class="fos-rest-name-details">
                                 <i class="fa fa-building-o"></i><?= $aValue['restaurant']['restaurant_name'] ?>
                                 <div class="fos-ord-status pull-right"><?= $aValue['status'] ?></div>
                             </div>
                             <div class="fos-ord-id">
                                 <i class="fa fa-tasks"></i><?= $aValue['order_number'] ?>
                                 <div class="col-sm-5 fos-ord-payment no-padding-right pull-right text-right"><?= strtoupper($aValue['payment_method']) ?></div>
                             </div>
                             <div class="fos-cust-name">
                                 <i class="fa fa-user"></i><?= $aValue['customer_name'] ?>
                                 <div class="col-sm-5 fos-timeanddate no-padding pull-right text-right"><i class="fa fa-clock-o"></i><?= ($aValue['assoonas'] == 'now') ? 'ASAP': $aValue['delivery_time'] ?></div>
                             </div>
                             <div class="fos-cust-address"><i class="fa fa-map-marker"></i><?= $aValue['address'] ?></div>
                         </div>
                         <div class="fos-assign-status" data-toggle="modal" onclick="return viewOrder(<?= $aValue['id'] ?>)">View</div>
                         <div class="fos-assign-btn" onclick="return getDriverList(<?= $aValue['id'] ?>)">Assign</div>
                     </div>
             <?php

                 }
             }else { ?>
                 <div class="fos-no-orders">No Orders</div>
             <?php } ?>

         </div>
         <div style="display:none;" id="Waiting" class="fos-common">
             <div class="fos-new-input">
                 <input type="text" onkeyup="fossearchorder(this)" class="form-control fos-input"><i class="fa fa-search"></i>
             </div>
             <?php if(!empty($waitingOrders)) {
                 foreach ($waitingOrders as $wKey => $wvalue) { ?>

                     <div class="fos-order-part fos-order-part_<?= $wvalue['restaurant_id'] ?>">
                         <div onclick="return trackingMap(<?= $wvalue['id'] ?>,<?= $wvalue['restaurant_id'] ?>);" class="col-xs-12 no-padding">
                             <div class="fos-rest-name-details">
                                 <i class="fa fa-building-o"></i><?= $wvalue['restaurant']['restaurant_name'] ?>
                                 <div class="fos-ord-status pull-right"><?= $wvalue['status'] ?></div>
                             </div>
                             <div class="fos-ord-id">
                                 <i class="fa fa-tasks"></i><?= $wvalue['order_number'] ?>
                                 <div class="col-sm-5 fos-ord-payment no-padding-right pull-right text-right"><?= strtoupper($wvalue['payment_method']) ?></div>
                             </div>
                             <div class="fos-cust-name">
                                 <i class="fa fa-user"></i><?= $wvalue['customer_name'] ?>
                                 <div class="col-sm-5 fos-timeanddate no-padding pull-right text-right"><i class="fa fa-clock-o"></i><?= ($wvalue['assoonas'] == 'now') ? 'ASAP': $wvalue['delivery_time'] ?></div>
                             </div>
                             <div class="fos-cust-address"><i class="fa fa-map-marker"></i><?= $wvalue['address'] ?></div>
                         </div>
                         <div class="fos-assign-status" data-toggle="modal" onclick="return viewOrder(<?= $wvalue['id'] ?>)">View</div>
                         <?php
                             if(!empty($wvalue['driver']['driver_name'])) { ?>
                                 <div class="fos-assign-btn"><?php echo $wvalue['driver']['driver_name'];?></div><?php
                             }
                         ?>
                         <div class="fos-assign-btn" onclick="return rejectOrder(<?= $wvalue['id'] ?>)">Reject</div>
                     </div>

             <?php
                 }
             }else { ?>
                 <div class="fos-no-orders">No Orders</div>
             <?php } ?>

         </div>
         <div style="display:none;" id="Assign" class="fos-common">
             <div class="fos-new-input">
                 <input type="text" onkeyup="fossearchorder(this)" class="form-control fos-input"><i class="fa fa-search"></i>
             </div>

             <?php if(!empty($assignedOrders)) {
                 foreach ($assignedOrders as $assignKey => $assignvalue) { ?>

                     <div class="fos-order-part fos-order-part_<?= $assignvalue['restaurant_id'] ?>">
                         <div onclick="return trackingMap(<?= $assignvalue['id'] ?>,<?= $assignvalue['restaurant_id'] ?>);" class="col-xs-12 no-padding">
                             <div class="fos-rest-name-details">
                                 <i class="fa fa-building-o"></i><?= $assignvalue['restaurant']['restaurant_name'] ?>
                                 <div class="fos-ord-status pull-right"><?= $assignvalue['status'] ?></div>
                             </div>
                             <div class="fos-ord-id">
                                 <i class="fa fa-tasks"></i><?= $assignvalue['order_number'] ?>
                                 <div class="col-sm-5 fos-ord-payment no-padding-right pull-right text-right"><?= strtoupper($assignvalue['payment_method']) ?></div>
                             </div>
                             <div class="fos-cust-name">
                                 <i class="fa fa-user"></i><?= $assignvalue['customer_name'] ?>
                                 <div class="col-sm-5 fos-timeanddate no-padding pull-right text-right"><i class="fa fa-clock-o"></i><?= ($assignvalue['assoonas'] == 'now') ? 'ASAP': $assignvalue['delivery_time'] ?></div>
                             </div>
                             <div class="fos-cust-address"><i class="fa fa-map-marker"></i><?= $assignvalue['address'] ?></div>
                         </div>
                         <div class="fos-assign-status" data-toggle="modal" onclick="return viewOrder(<?= $assignvalue['id'] ?>)">View</div>
                         <?php
                         if(!empty($assignvalue['driver']['driver_name'])) { ?>
                             <div class="fos-assign-btn"><?php echo $assignvalue['driver']['driver_name'];?></div><?php
                         }
                         ?>
                         <div class="fos-assign-btn" onclick="return rejectOrder(<?= $assignvalue['id'] ?>)">Reject</div>
                     </div>

                     <?php
                 }
             }else { ?>
                 <div class="fos-no-orders">No Orders</div>
             <?php } ?>
         </div>
         <div style="display:none;" id="Complete" class="fos-common">
            <div class="fos-new-input">
               <input type="text" onkeyup="fossearchorder(this)" class="form-control fos-input"><i class="fa fa-search"></i>
            </div>
             <?php if(!empty($completedOrders)) {
                 foreach ($completedOrders as $cKey => $cValue) { ?>

                     <div class="fos-order-part fos-order-part_<?= $cValue['restaurant_id'] ?>">
                         <div onclick="return trackingMap(<?= $cValue['id'] ?>,<?= $cValue['restaurant_id'] ?>);" class="col-xs-12 no-padding">
                             <div class="fos-rest-name-details">
                                 <i class="fa fa-building-o"></i><?= $cValue['restaurant']['restaurant_name'] ?>
                                 <div class="fos-ord-status pull-right"><?= $cValue['status'] ?></div>
                             </div>
                             <div class="fos-ord-id">
                                 <i class="fa fa-tasks"></i><?= $cValue['order_number'] ?>
                                 <div class="col-sm-5 fos-ord-payment no-padding-right pull-right text-right"><?= strtoupper($cValue['payment_method']) ?></div>
                             </div>
                             <div class="fos-cust-name">
                                 <i class="fa fa-user"></i><?= $cValue['customer_name'] ?>
                                 <div class="col-sm-5 fos-timeanddate no-padding pull-right text-right"><i class="fa fa-clock-o"></i><?= ($cValue['assoonas'] == 'now') ? 'ASAP': $cValue['delivery_time'] ?></div>
                             </div>
                             <div class="fos-cust-address"><i class="fa fa-map-marker"></i><?= $cValue['address'] ?></div>
                         </div>
                         <div class="fos-assign-status" data-toggle="modal" onclick="return viewOrder(<?= $cValue['id'] ?>)">View</div>
                     </div>

                     <?php
                 }
             }else { ?>
                 <div class="fos-no-orders">No Orders</div>
             <?php } ?>
         </div>
      </div>
   </div>
   <div class="fos-right-wrapper" id="fos-right-wrapper" style="">
       <div class="loading_img" style="display: none">
        <img  style= "" width="60" height="60" src="<?php echo DIST_URL; ?>img/loader.gif">
      </div>
      <div id="initialmap"></div>
       <div id="trackMap"></div>
   </div>
   <div id="online_drivers" class="fos-right-new-wrapper">
      <div class="fos-new-top">Online Drivers</div>
       <?php
       $main = 0;
       $delstatus = 1;
       foreach($onlineDrivers as $key => $value) {
           $nextValue = $key+1;

           $currCount = 0;
           $compCount = 0;
           $waitCount = 0;
           foreach($value['orders'] as $keys => $vals) {
               if($vals['status'] != '') {
                   if($vals['status'] != 'Delivered' && $vals['status']!= 'Waiting') {
                       $currCount++;
                   } elseif($vals['status'] == 'Waiting') {
                       $waitCount++;
                   } elseif($vals['status'] == 'Delivered') {
                       $compCount++;
                   }
               }
           }

           $main = 0;
           if(!empty($onlineDrivers[$nextValue]['orders'])) {
               foreach($onlineDrivers[$nextValue]['orders'] as $k => $v) {
                   if($v['status'] != '') {
                       if($v['status'] != 'Delivered') {
                           $main++;
                       }
                   }
               }
           } ?>
           <div class="accordion_head">
           <div class="fos-rt-section">
               <div class="fos-online-drivers"><?php
                   if(!empty($value['driver']['image'])) { ?>
                       <img class="fos-profile-img" onerror="this.onerror=null;this.src='<?php echo BASE_URL."/images/no-image.png"; ?>'" class="img-responsive img_fields" src="<?php //echo $siteUrl. '/driversImage/'.$value['image']; ?>" alt=""><?php
                   } else { ?>
                       <img class="fos-profile-img" class="img-responsive img_fields" src="<?php echo BASE_URL."/images/no-image.png"; ?>" alt=""><?php
                   }?>
                   <div class="fos-names">
                       <h6><?php echo $value['driver_name'];?></h6>
                       <p><?php echo $countries['phone_code'].''.$value['phone_number'];?></p>
                   </div>


                   <a class="fos-view-details  pull-right"><?php
                       if($waitCount != 0) {
                           echo $waitCount.' waiting';
                       } else {
                           echo 'No waiting';
                       }
                       echo "<br>";
                       echo ($currCount == 1) ? $currCount.' Order' : ($currCount == 0 ? 'No Orders' : $currCount.' Orders');?><?php if($value['orders']['order_count'] != 0) { ?><?php } ?>
                   </a>



               </div>
           </div>
           </div><?php if($value['orders']['order_count'] != 0) { ?>
               <div class="accordion_body" style="display:none;">
               <div class="fos-t-back">
                   <?php
                   if($value['orders']['order_count'] != 0) {

                       ?>
                       <div class="fos-tab-ord">
                       <ul class="fos-ord-tab text-center">
                           <li>
                               <a class="current-order active" data-orders="Currentorder_<?php echo $value['id'];?>">
                                   <div class="text-center">Current Order (<?php echo ($currCount + $waitCount);?>)</div>
                               </a>
                           </li>
                           <li>
                               <a data-orders="Completedorder_<?php echo $value['id'];?>">
                                   <div class="text-center fos-ord-de-list">Completed Order (<?php echo $compCount;?>)</div>
                               </a>
                           </li>
                       </ul>
                       </div><?php
                   } ?>

                   <div class="fos-c-ord fos-current-order-show" id="Currentorder_<?php echo $value['id'];?>">
                       <?php

                       // if($value['Order']['order_count'] != 0) {
                       $counts = 0;
                       foreach($value['orders'] as $key => $val) {
                           if(is_array($val)) {
                               if(!empty($val['id']) && $val['status'] != 'Delivered') {
                                   $counts++;

                                   ?>
                                   <div class="fos-task-section">
                                   <div class="fos-ordered-id"><?php echo $val['order_number'];?> <div class="fos-task-processing pull-right"><?php echo $val['status'];?> </div></div>
                                   <div class="fos-ordered-cs-name"><?php echo $val['customer_name'];?></div>
                                   <div class="fos-ordered-address"><?php echo $val['address'];?></div>
                                   </div><?php
                               }

                           }

                       }

                       if($counts == 0) { ?>
                           <div class="fos-no-orders">No Orders</div><?php
                       }
                       ?>
                   </div>
                   <div class="fos-c-ord fos-completed-order-show" id="Completedorder_<?php echo $value['id'];?>"  style="display:none;">
                       <?php
                       // if($value['Order']['order_count'] != 0) {
                       $count = 0;
                       foreach($value['orders'] as $key => $val) {
                           if(is_array($val)) {
                               if(!empty($val['id']) && $val['status'] == 'Delivered') {
                                   $count++;

                                   ?>
                                   <div class="fos-task-section">
                                   <div class="fos-ordered-id"><?php echo $val['order_number'];?> <div class="fos-task-processing pull-right"><?php echo $val['status'];?> </div></div>
                                   <div class="fos-ordered-cs-name"><?php echo $val['customer_name'];?></div>
                                   <div class="fos-ordered-address"><?php echo $val['address'];?></div>
                                   </div><?php
                               }
                           }
                       }

                       if($count == 0) { ?>
                           <div class="fos-no-orders">No Orders</div><?php
                       }
                       ?>
                   </div>
               </div>
               </div><?php
           }
           if($main == 0 && $delstatus == 1 && $value['orders']['order_count'] != 0) {
               $main = 1;
               $delstatus = 0;
               ?><div class="fos-rt-section border-top-line"></div><?php
           }
       } ?>
   </div>
   <div class="close_arrow" onclick="closeNav();"><i class="fa fa-close"></i></div>
   <div class="open_arrow" onclick="openNav();"><i class="fa fa-angle-left"></i></div>

</div>

<script src="https://js.pusher.com/4.1/pusher.min.js"></script>
<script>
      function initMap() {
        var uluru = {lat: 13.081116, lng: 80.275255};
        var map = new google.maps.Map(document.getElementById('initialmap'), {
          zoom: 10,
          center: uluru
        });
        var marker = new google.maps.Marker({
          position: uluru,
          map: map
        });
      }

      function trackingMap(ordId,storeId) {

          if (ordId != '') {
              $('.loading_img').show();

              $.ajax({
                  'type' : 'POST',
                  'url' : jssitebaseurl+'orders/ajaxaction',
                  'data': {id:ordId,action:'InitialTracking'},
                  success: function(data) {
                      $("#initialmap").html(data);
                      $.ajax({
                          'type' : 'POST',
                          'url' : jssitebaseurl+'orders/ajaxaction',
                          'data': {id:ordId,action:'LoadDispatchMap'},
                          success: function(data1) {
                              removeMapIcons();
                              var result = data1.split('||@@||');
                              $('#trackMap').html(result[0]);
                              $('.loading_img').hide();
                          }
                      });
                  }
              });


          }
          /*setTimeout(function() {
              trackingMap(ordId);
          }, 4000);*/
          $(".fos-order-part").removeClass("activebg");
          $(".fos-order-part_"+ordId).addClass("activebg");
          return false;
      }


      //Remove all icons from map
      function removeMapIcons() {
          deleteMarkers();
          if ($('[name=direction]').val() == 'available') {
              directions1Display.setMap(null);
              directions1Display.setPanel(null);
          }
      }

      //Delete all marker
      function deleteMarkers() {
          for (var i = 0; i < markers.length; i++) {
              markers[i].setMap(null);
          }

      }

      $(document).ready(function(){
          $('#initialmap').html('');
          $.ajax({
              'type' : 'POST',
              'url' : jssitebaseurl+'orders/ajaxaction',
              'data': {id:'',action:'InitialTracking'},
              success: function(data) {
                  $("#initialmap").html(data);
                  loadAllIcon('Unassign','all');
              }
          });

          return false;
      });

      function loadAllIcon(type,val) {

          if($.trim(val) == '') {
              $('.loading-icon').show();
          }

          $.ajax({
              'type' : 'POST',
              'url' : jssitebaseurl+'orders/ajaxaction',
              'data': {'type':type,action:'LoadAllMap'},
              success: function(data) {
                  removeMapIcons();
                  $('#trackMap').html(data);
                  $('.loading-icon').hide();
              }
          });

          // });
      }

      function fossearchorder(id){
          var that = id, $allListElements = $('.fos-order-part');
          var $matchingListElements = $allListElements.filter(function(i, li){
              var listItemText = $(li).text().toUpperCase(), searchText = that.value.toUpperCase();
              return ~listItemText.indexOf(searchText);
          });
          $allListElements.hide();
          $matchingListElements.show();
      }

      $(document).ready(function () {

          // Enable pusher logging - don't include this in production
          Pusher.logToConsole = true;

          var pusher = new Pusher('<?php echo PUSHER_AUTHKEY ?>', {
              encrypted: true
          });

          var channel = pusher.subscribe('my-channelTrack');
          channel.bind('my-eventTrack', function(data) {

              if(data.message != 'Test') {

                  $(".statusshow").show();
                  $(".statusshow").html(data.message);
                  setTimeout(function(){
                      $(".statusshow").css("display",'none');
                  },3000);
              }

              updateDispatchMap()

          });

          var channel = pusher.subscribe('my-channelReject');
          channel.bind('my-eventReject', function(data) {
              $(".statusshow").show();
              $(".statusshow").html('Order was '+data.id+' was rejected');
              setTimeout(function(){
                  $(".statusshow").css("display",'none');
              },3000);

              updateDispatchMap();
          });
      });

      function updateDispatchMap() {

          $.ajax({
              'type' : 'POST',
              'url' : jssitebaseurl+'orders/ajaxaction',
              'data': {action:'dispatchUpdate'},
              success: function(response) {
                  $('#all_orders').html(response);

                  var unassignCount = $.trim($('#unassignCount').val());
                  var assignCount = $.trim($('#assignCount').val());
                  var completeCount = $.trim($('#completeCount').val());
                  var waitCount = $.trim($('#waitCount').val());

                  $('#unassign_order').html(unassignCount);
                  $('#assign_order').html(assignCount);
                  $('#complete_order').html(completeCount);
                  $('#waiting_order').html(waitCount);
                  var idd = $(".fos-tab-select li a.active").attr('data-order');
                  $(".fos-common").hide();
                  $("#"+idd).show();
                  loadAllIcon(idd,'');
                  dispatchDrivers();

                  return false;
              }
          });

          /*$.post(rp+'/AjaxAction',{'Action':'dispatchOrder'}, function(response) {
          });*/
      }

      function dispatchDrivers() {

          $.ajax({
              'type' : 'POST',
              'url' : jssitebaseurl+'orders/ajaxaction',
              'data': {action:'dispatchDrivers'},
              success: function(response) {
                  $('#online_drivers').html(response);

                  $(".accordion_head").click(function () {
                      if ($('.accordion_body').is(':visible')) {
                          $(".accordion_body").slideUp();
                      }
                      if ($(this).next(".accordion_body").is(':visible')) {
                          $(this).next(".accordion_body").slideUp();
                      } else {
                          $(this).next(".accordion_body").slideDown();
                          $('.fos-current-order-show').show();
                          $('.fos-completed-order-show').hide();
                          $('.current-order').addClass('active');
                          $('.completed-order').removeClass('active');
                      }
                  });

                  $(".fos-ord-tab li a").click(function(){
                      $('.fos-c-ord').hide();
                      $(".fos-ord-tab li a").removeClass('active')
                      var iddd = $(this).attr("data-orders")
                      $(this).addClass('active')
                      $("#"+iddd).show();
                  });

                  return false;
              }
          });


      }

      function getDriverList(id) {
          if(id != '') {
              $("#driverListPopup").modal('show');
              $.ajax({
                  'type' : 'POST',
                  'url' : jssitebaseurl+'orders/ajaxaction',
                  'data': {id:id,action:'getDriverLists'},
                  success: function(data) {
                      $('.loading_icon').hide();
                      $("#assignOrder").html(data);
                  }
              });
          }
      }

      function assignOrder(ord, driver) {

          $('#assign'+driver).hide();
          $('#waiting'+driver).show();

          $.ajax({
              'type' : 'POST',
              'url' : jssitebaseurl+'orders/assignOrder',
              'data': {id:ord,driver:driver},
              success: function(data) {
                  $("#driverListPopup").modal('hide');
                  updateDispatchMap();
                  return false;
              }
          });
          return false;
      }

      function rejectOrder(orderId) {
          $.ajax({
              'type' : 'POST',
              'url' : jssitebaseurl+'orders/orderStatus',
              'data': {id:orderId,status:'Accepted'},
              success: function(data) {
                  if (data == 1) {
                      updateDispatchMap();
                      return false;
                  }
              }
          });
      }

      function viewOrder(ordId) {
           
          $.ajax({
              'type' : 'POST',
              'url' : jssitebaseurl+'orders/ajaxaction',
              'data': {id:ordId,action:'viewOrder'},
              success: function(data) {
                 $("#fosModal").modal('show');
                  $("#fosModal").html(data);
                  return false;
              }
          });
      }

</script>

<div class="modal fade" id="driverListPopup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel1">Online Drivers</h4>
            </div>
            <div class="modal-body" >
            
                <table class="table table-striped table-bordered table-hover" id="sample_12">
                    <thead>
                    <tr>
                        <th> S.No </th>
                        <th>Driver</th>
                        <th class="sorting_asc">Distance</th>
                        <th>ETA</th>
                        <th>Assign</th>
                    </tr>
                    </thead>
                    <tbody id="assignOrder">
                      <tr>
                        <td colspan="7" align="center" class="loading_icon"><img width="60" height="60" src="<?php echo DIST_URL; ?>img/loader.gif"></td>
                      </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<div class="modal fade in" id="fosModal" role="dialog"  aria-hidden="false">
    <!-- <div class="loading_img">
      <img width="60" height="60" src="<?php echo DIST_URL; ?>img/loader.gif">">
    </div> -->
</div>





