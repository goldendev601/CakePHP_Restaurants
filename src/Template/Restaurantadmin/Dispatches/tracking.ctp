<div class="fos-total-outer-wrapper">
   <div id="dispatch_orders" class="fos-left-section">
      <div class="fos-tab text-center">
         <ul class="fos-tab-select text-center">
            <li>
               <a data-order="Unassign" class="active">
                  <div id="unassign_order" class="text-center fos-order-num"><?= count($acceptedOrder) ?></div>
                  <div class="text-center">Unassigned</div>
               </a>
            </li>
            <li>
               <a data-order="Waiting">
                  <div id="waiting_order" class="text-center fos-order-num">0</div>
                  <div class="text-center">Waiting</div>
               </a>
            </li>
            <li>
               <a data-order="Assign">
                  <div id="assign_order" class="text-center fos-order-num">0</div>
                  <div class="text-center">Assigned</div>
               </a>
            </li>
            <li>
               <a data-order="Complete">
                  <div id="complete_order" class="text-center fos-order-num">1</div>
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
                         <div class="fos-assign-status">View</div>
                         <div class="fos-assign-btn" onclick="return getDriverList(<?= $aValue['id'] ?>)">Assign</div>
                     </div>
             <?php

                 }
             } ?>

         </div>
         <div style="display:none;" id="Waiting" class="fos-common">
            <div class="fos-no-orders">No Orders</div>
         </div>
         <div style="display:none;" id="Assign" class="fos-common">
            <div class="fos-no-orders">No Orders</div>
         </div>
         <div style="display:none;" id="Complete" class="fos-common">
            <div class="fos-new-input">
               <input type="text" onkeyup="fossearchorder(this)" class="form-control fos-input"><i class="fa fa-search"></i>
            </div>
            <div class="fos-order-part">
               <div class="fos-rest-name-details">
                  <i class="fa fa-building-o"></i>Mohan Restaurant
                  <div class="fos-ord-status pull-right">Delivered</div>
               </div>
               <div class="fos-ord-id">
                  <i class="fa fa-tasks"></i>ORD003588
                  <div class="col-sm-5 fos-ord-payment no-padding-right pull-right text-right">COD</div>
               </div>
               <div class="fos-cust-name">
                  <i class="fa fa-user"></i>Vss manikandan
                  <div class="col-sm-5 fos-timeanddate no-padding pull-right text-right"><i class="fa fa-clock-o"></i>ASAP</div>
               </div>
               <div class="fos-cust-address"><i class="fa fa-map-marker"></i>Vadapalani, Chennai, Tamil Nadu, India</div>
               <div class="fos-assign-status">View</div>
            </div>
         </div>
      </div>
   </div>
   <div class="fos-right-wrapper" id="fos-right-wrapper" style="">
       <div class="loading-icon" style="display: none;"><img src="<?php echo BASE_URL."/images/Spinner.gif"; ?>" alt=""></div>
      <div id="initialmap"></div>
       <div id="trackMap"></div>
   </div>
   <div id="online_drivers" class="fos-right-new-wrapper">
      <div class="fos-new-top">Online Drivers</div>
      <table class="table online_driver_table">
         <tr>
            <td align="center"><img class="fos-profile-img" alt="" src="../../images/no-image.png"></td>
            <td>
               <div class="">Muthu</div>
               <div class="">+919873216540</div>
            </td>
            <td align="right">
               <div class="no_w">No waiting</div>
               <div class="no_o">No Orders</div>
            </td>
         </tr>
         <tr>
            <td align="center"><img class="fos-profile-img" alt="" src="../../images/no-image.png"></td>
            <td>
               <div class="">Nandha</div>
               <div class="">+919873216540</div>
            </td>
            <td align="right">
               <div class="no_w">No waiting</div>
               <div class="no_o">No Orders</div>
            </td>
         </tr>
         <tr>
            <td align="center"><img class="fos-profile-img" alt="" src="../../images/no-image.png"></td>
            <td>
               <div class="">Arun</div>
               <div class="">+919873216540</div>
            </td>
            <td align="right">
               <div class="no_w">No waiting</div>
               <div class="no_o">No Orders</div>
            </td>
         </tr>
         <tr>
            <td align="center"><img class="fos-profile-img" alt="" src="../../images/no-image.png"></td>
            <td>
               <div class="">Ram</div>
               <div class="">+919873216540</div>
            </td>
            <td align="right">
               <div class="no_w">No waiting</div>
               <div class="no_o">No Orders</div>
            </td>
         </tr>
      </table>
   </div>
   <div class="close_arrow" onclick="closeNav();"><i class="fa fa-close"></i></div>
   <div class="open_arrow" onclick="openNav();"><i class="fa fa-angle-left"></i></div>

</div>

<script src="http://js.pusher.com/4.1/pusher.min.js"></script>
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
              $('.loading-icon').show();

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
                              $('.loading-icon').hide();
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
              alert('comeee');
              updateDispatchMap()
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
                  //dispatchDrivers();

                  return false;
              }
          });

          /*$.post(rp+'/AjaxAction',{'Action':'dispatchOrder'}, function(response) {
          });*/
      }

      function getDriverList(id) {
          if(id != '') {
              $.ajax({
                  'type' : 'POST',
                  'url' : jssitebaseurl+'orders/ajaxaction',
                  'data': {id:id,action:'getDriverLists'},
                  success: function(data) {
                      $("#driverListPopup").modal('show');
                      $("#assignOrder").html(data);
                  }
              })
          }
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
                        <th>Vehicle</th>
                        <th>Status</th>
                        <th class="sorting_asc">Distance</th>
                        <th>ETA</th>
                        <th>Assign</th>
                    </tr>
                    </thead>
                    <tbody id = "assignOrder">
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>





