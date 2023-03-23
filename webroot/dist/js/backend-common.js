$(document).ready(function(){
     //sitesettings
    smtpDetails();
    offlineDetails();
    paymentShow();
    paymentsShow();
    smsDetails();
    addressDetails();
    orderAssignDetails();
    $('.sidebar-menu li').click(function(){
        $('.sidebar-menu li').removeClass('active');
        $(this).addClass('active');
    });

    $("input[name='mail_option']").click(function() {
        smtpDetails();
    });
    $("input[name='offline_status']").click(function() {
        offlineDetails();
    });
    $("input[name='stripe_mode']").click(function() {
        paymentShow();
    });
    $("input[name='paypal_mode']").click(function() {
        paymentsShow();
    });
    $("input[name='sms_option']").click(function() {
        smsDetails();
    }); 
    $("input[name='address_mode']").click(function() {
        
        addressDetails();
    }); 
    $("input[name='assign_status']").click(function() {
        orderAssignDetails();
    });

    $(".flashmessage").addClass("show");
    setTimeout(function(){
        $(".flashmessage").removeClass("show");
    },5000);
    $(".flashmessage-error").addClass("show-error");
    setTimeout(function(){
        $(".flashmessage-error").removeClass("show-error");
    },5000);


    
    $(".category li span").click(function() {
        if($(this).text() == '-'){
            $(this).text("+");
        }
        else{
            $(this).text("-");
        }
        $(this).siblings('.subcategory').toggle("slow");
    });

        $(".category li input").change(function(){
            if($(this).prop("checked") == true){
                $(this).parent().next("ul").find("[type=checkbox]").prop("checked",true);
            }
            else{
                $(this).parent().next("ul").find("[type=checkbox]").prop("checked",false);
            }
        });
        $(".category ul.subcategory input").change(function(){
            var checklength = $(this).closest('ul').find('input[type=checkbox]').length;
            var checkedlength = $(this).closest('ul').find('input[type=checkbox]:checked').length;
            if(checklength == checkedlength) {
            $(this).closest('ul').prev('a').find('input[type=checkbox]').prop("checked",true);
            }
            else{
             $(this).closest('ul').prev('a').find('input[type=checkbox]').prop("checked",false);
            }
        });
        $(".category ul.subcategory input").change(function(){
            var checklength = $(this).closest('ul').find('input[type=checkbox]').length;
            var checkedlength = $(this).closest('ul').find('input[type=checkbox]:checked').length;
            if(checklength == checkedlength) {
            $(this).closest('ul').prev('a').find('input[type=checkbox]').prop("checked",true);
            }
            else{
             $(this).closest('ul').prev('a').find('input[type=checkbox]').prop("checked",false);
            }
        });

    $("input[name='offer_mode']").click(function() {
        var mode = $(this).val();
        if( mode != 'free_delivery'){
            $('#voucherOfferValue').show();
        } else{
            $('#voucherOfferValue').hide();
        }    
    });


    //-------------OFFER-----------------
    var date = new Date();
    $(function () {
        $( "#offer_from" ).datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            startDate: date
        });

        var from_date = $("#offer_from").val();
        if(from_date != '') {
            $("#offer_to").datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                startDate: from_date
            });
        }

        $("#offer_from").on("change", function () {
            $("#offer_to").datepicker("destroy");
            $("#offer_to").val('');
            $(".fromErr").html('');
            var from_date = $("#offer_from").val();
            $( "#offer_to" ).datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                startDate: from_date
            });
        });

        $( "#offer_to" ).on( "change", function() {
            var from_date = $("#offer_from").val();
            if(from_date == '') {
                $("#offer_to").val('');
                $(".fromErr").html('Please select offer from');
                return false;
            }
            $( "#datepicker" ).datepicker( "option", "showAnim", $( this ).val() );
        });
    });

    //------------------VOUCHER----------------
    $(function () {
        $( "#voucher_from" ).datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            startDate: date
        });

        var fromdate = $("#voucher_from").val();
        if(fromdate != '') {
            $("#voucher_to").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                startDate: fromdate
            });
        }

        $("#voucher_from").on("change", function () {
            $("#voucher_to").datepicker("destroy");
            $("#voucher_to").val('');
            $(".fromErr").html('');
            var fromdate = $("#voucher_from").val();
            $( "#voucher_to" ).datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                startDate: fromdate
            });
        });

        $( "#voucher_to" ).on( "change", function() {
            var fromdate = $("#voucher_from").val();
            if(fromdate == '') {
                $("#voucher_to").val('');
                $(".fromErr").html('Please select voucher from');
                return false;
            }
            $( "#datepicker" ).datepicker( "option", "showAnim", $( this ).val() );
        });
    });
   

    $('.mini_count,.mix_count,.offer_value,.subPrice,.menuPrice').keypress(function(event) {
        if(event.which == 8 || event.which == 0){
            return true;
        }
        if(event.which < 46 || event.which > 59 || event.which == 47) {
            return false;
        }
        if(event.which == 46 && $(this).val().indexOf('.') != -1) {
            return false;
        }
    });

    $('.offer_price,.offer_percentage,.user_price,.min_price,.free_price').keypress(function(event) {
        if(event.which == 8 || event.which == 0){
            return true;
        }
        if(event.which < 46 || event.which > 59 || event.which == 47) {
            return false;
        }
        if(event.which == 46 && $(this).val().indexOf('.') != -1) {
            return false;
        }
    });
});
 

function smtpDetails() {
    if ($("#mail-option-smtp").is(":checked")) {
        $("#smtp").show();
    } else {
        $("#smtp").hide();
    }
}
function smsDetails() {
    if ($("#sms-option-yes").is(":checked")) {
        $("#yes").show();
    } else {
        $("#yes").hide();
    }
}
function offlineDetails(){
    if ($("#offline-status-yes:checked").is(":checked")) {
        $("#offlineReason").show();
    } else {
        $("#offlineReason").hide();
    }
}
function addressDetails(){
    if ($("#address-mode-normal").is(":checked")){

        $("#normal_address").show();
    } else {
        $("#normal_address").hide();
    }
}
function orderAssignDetails() {
    if ($("#assign-status-yes").is(":checked")) {
        $("#assign_miles").show();
    } else {
        $("#assign_miles").hide();
    }
}
function paymentShow() {
  if ($("#stripe-mode-live").is(":checked")) {
    $("#Live").show();
    $("#Test").hide();
  } else {
    $("#Live").hide();
    $("#Test").show();
  }
}

function paymentsShow() {
  if ($("#paypal-mode-live").is(":checked")) {
    $("#LivePaypal").show();
    $("#TestPaypal").hide();
  } else {
    $("#LivePaypal").hide();
    $("#TestPaypal").show();
  }
}
function openNav() {
    document.getElementById("online_drivers").style.width = "350px";
    document.getElementById("fos-right-wrapper").style.paddingRight = "350px";
    $('.close_arrow').show("drop", 500);
    $('.open_arrow').hide("drop", 500);
}

function closeNav() {
    document.getElementById("online_drivers").style.width = "0";
    document.getElementById("fos-right-wrapper").style.paddingRight= "0";
$('.close_arrow').hide("drop", 500);
$('.open_arrow').show("drop", 500);
}


$(document).ready(function(){
    $(".fos-tab-select li a").click(function(){
    $(".fos-common").hide();
    $(".fos-tab-select li a").removeClass('active')
    var idd = $(this).attr("data-order")
    $(this).addClass('active')
    $("#"+idd).show();
  });
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
});

$(document).ready(function(){
    
    $('.sidebar').enscroll();
    $(".sucess-show").addClass("addshow");
    setTimeout(function(){
        $(".sucess-show").removeClass("addshow");
    },5000);
    $(".error-show").addClass("adderror");
    setTimeout(function(){
        $(".error-show ").removeClass("adderror");
    },5000);
});

function menuOpen() {
   document.getElementById("add_menu").style.width = "40%";
   $('.content-wrapper').addClass('addmenu_wrapper');
}
function menuClose() {
    document.getElementById("add_menu").style.width = "0%";
    $('.content-wrapper').removeClass('addmenu_wrapper');
}


$(document).ready(function(){
     $('.reward_input').click(function(){
        $('.reward-ul').show();
    })
    $('.reward-ul li a').click(function(){
        $('.reward-ul').hide();
        $('.reward_input').val($(this).text())
    });
    $('.reward_input_enter').blur(function(){
         $('.reward-ul').hide();
         $('.reward_input').val($(this).val());
    })

    
    $(document).click(function(event) {
        if ($(event.target).closest(".reward_input,.reward-ul").length == 0) {
            $(".reward-ul").fadeOut(200);
        }
    });
});