<div class="fos-total-outer-wrapper">
   <div id="dispatch_orders" class="fos-left-section">
      <div class="fos-tab text-center">
         <ul class="fos-tab-select text-center">
            <li>
               <a data-order="Unassign" class="active">
                  <div id="unassign_order" class="text-center fos-order-num">4</div>
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
            <div class="fos-order-part fos-order-part_3591">
               <div onclick="return trackingMap(3591,159);" class="col-xs-12 no-padding">
                  <div class="fos-rest-name-details">
                     <i class="fa fa-building-o"></i>Demo TBC
                     <div class="fos-ord-status pull-right">Accepted</div>
                  </div>
                  <div class="fos-ord-id">
                     <i class="fa fa-tasks"></i>ORD003591
                     <div class="col-sm-5 fos-ord-payment no-padding-right pull-right text-right">COD</div>
                  </div>
                  <div class="fos-cust-name">
                     <i class="fa fa-user"></i>Vss manikandan
                     <div class="col-sm-5 fos-timeanddate no-padding pull-right text-right"><i class="fa fa-clock-o"></i>ASAP</div>
                  </div>
                  <div class="fos-cust-address"><i class="fa fa-map-marker"></i>Vadapalani, Chennai, Tamil Nadu, India</div>
               </div>
               <div class="fos-assign-status">View</div>
               <div class="fos-assign-btn">Assign</div>
            </div>
            <div class="fos-order-part fos-order-part_3590">
               <div onclick="return trackingMap(3590,19);" class="col-xs-12 no-padding">
                  <div class="fos-rest-name-details">
                     <i class="fa fa-building-o"></i>Topaz Noodles Bar
                     <div class="fos-ord-status pull-right">Accepted</div>
                  </div>
                  <div class="fos-ord-id">
                     <i class="fa fa-tasks"></i>ORD003590
                     <div class="col-sm-5 fos-ord-payment no-padding-right pull-right text-right">COD</div>
                  </div>
                  <div class="fos-cust-name">
                     <i class="fa fa-user"></i>Vss manikandan
                     <div class="col-sm-5 fos-timeanddate no-padding pull-right text-right"><i class="fa fa-clock-o"></i>ASAP</div>
                  </div>
                  <div class="fos-cust-address"><i class="fa fa-map-marker"></i>Vadapalani, Chennai, Tamil Nadu, India</div>
               </div>
               <div class="fos-assign-status">View</div>
               <div class="fos-assign-btn">Assign</div>
            </div>
            <div class="fos-order-part fos-order-part_3589">
               <div onclick="return trackingMap(3589,2);" class="col-xs-12 no-padding">
                  <div class="fos-rest-name-details">
                     <i class="fa fa-building-o"></i>Mohan Restaurant
                     <div class="fos-ord-status pull-right">Accepted</div>
                  </div>
                  <div class="fos-ord-id">
                     <i class="fa fa-tasks"></i>ORD003589
                     <div class="col-sm-5 fos-ord-payment no-padding-right pull-right text-right">COD</div>
                  </div>
                  <div class="fos-cust-name">
                     <i class="fa fa-user"></i>Vss manikandan
                     <div class="col-sm-5 fos-timeanddate no-padding pull-right text-right"><i class="fa fa-clock-o"></i>ASAP</div>
                  </div>
                  <div class="fos-cust-address"><i class="fa fa-map-marker"></i>Vadapalani, Chennai, Tamil Nadu, India</div>
               </div>
               <div class="fos-assign-status">View</div>
               <div class="fos-assign-btn">Assign</div>
            </div>
            <div class="fos-order-part fos-order-part_3587">
               <div onclick="return trackingMap(3587,106);" class="col-xs-12 no-padding">
                  <div class="fos-rest-name-details">
                     <i class="fa fa-building-o"></i>Testing
                     <div class="fos-ord-status pull-right">Accepted</div>
                  </div>
                  <div class="fos-ord-id">
                     <i class="fa fa-tasks"></i>ORD003587
                     <div class="col-sm-5 fos-ord-payment no-padding-right pull-right text-right">COD</div>
                  </div>
                  <div class="fos-cust-name">
                     <i class="fa fa-user"></i>Vss manikandan
                     <div class="col-sm-5 fos-timeanddate no-padding pull-right text-right"><i class="fa fa-clock-o"></i>ASAP</div>
                  </div>
                  <div class="fos-cust-address"><i class="fa fa-map-marker"></i>Vadapalani, Chennai, Tamil Nadu, India</div>
               </div>
               <div class="fos-assign-status">View</div>
               <div class="fos-assign-btn">Assign</div>
            </div>
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
      <div id="initialmap">
      </div>
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
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBmLsrfRQj8SFU2MrPBPS1vtHX8gKxoubs&callback=initMap">
    </script>




