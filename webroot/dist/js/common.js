$(document).ready(function(){
	$('.ui-loader').hide();
	$(".filter-wrapper").change(function(){ 
		$(this).toggleClass("active");
	});
	 $('[data-toggle="tooltip"]').tooltip();   
	$('.menutab_ul li a').click(function(){
		$('.tab-content').hide();
		$('.menutab_ul li a').removeClass('active')
		var id = $(this).attr('data-href');
		$(this).addClass('active');
		$('#'+id).show();
	});
	$('.myaccount-tabs li').click(function(){
		$('.common_content').hide();
		$('.myaccount-tabs li').removeClass('active')
		var id = $(this).attr('data-content');
		$(this).addClass('active');
		$('#'+id).show();
	});
	$('.payment_tab li label').click(function(){
		$('.common_content').hide();
		var id = $(this).attr('data-check');
		$('#'+id).show();
	});
	$('.load_money_btn').click(function(){
		$('.load_money_content').show();
	});
    $('.payment_taba a').click(function(){
        $('.payment_taba a').removeClass('tabactive');
        $(this).addClass('tabactive');
    });
    $('.view_cart_item').click(function(){
		$(this).hide();
		$('.hide_cart_item').slideDown();
		$('.hide_cart_text').show();
	})
	$('.hide_cart_text').click(function(){
		$(this).hide();
		$('.hide_cart_item').slideUp();
		$('.view_cart_item').show();
	});
	$('.selectpicker').selectpicker();
	$('.use_wallet input[type=checkbox]').change(function(){
		if($(this).is(":checked",true)){
			$('.wallet-sec').show();
		}
		else {
			$('.wallet-sec').hide();
		}
		
	});
    $(".flashmessage").addClass("show");
    setTimeout(function(){
        $(".flashmessage").removeClass("show");
    },2500);
     $(".flashmessage-error").addClass("show-error");
    setTimeout(function(){
        $(".flashmessage-error").removeClass("show-error");
    },2500);
    $('.search_category').click(function(){

    });
    $('.search_category').click(function(){
    	$('.cuisinefil').slideToggle();
    });
    $('.apply_coup').click(function(){
    	var vouchercode = $.trim($("#vouchercode").val());
        $(this).hide();
        $(this).closest('div').find('input[type=text]').focus();
        $('.apply_submit').show();

    });

    var app_height = $(window).outerHeight() - ($('.top-header2').outerHeight()+ $('footer').outerHeight()-25);
    $('.app_page').css("height",app_height );
});

(function($){
        $(window).on("load",function(){
            $("a[rel='m_PageScroll2id']").mPageScroll2id();
        });
    })(jQuery);


$(window).scroll(function(){
		var topval = $(".top-header").outerHeight();
		if($(this).scrollTop() > topval) {
			$(".top-header").addClass("top-header-bg");
		}
		else{
			$(".top-header").removeClass("top-header-bg");
		}
});		


if($(window).width()>767){
		$(document).ready(function(){
			var wrapperHeight = $(window).height() - ($(".top-header2").outerHeight() + $(".res_footer").outerHeight());
			$(".inner_wrapper").css("min-height",wrapperHeight);
			$(".cart-wrapper").css("max-width",$(".cart-outer-wrapper").outerWidth());
		});
		$(window).resize(function(){
			var wrapperHeight = $(window).height() - ($(".top-header2").outerHeight() + $(".res_footer").outerHeight());
			$(".inner_wrapper").css("min-height",wrapperHeight);
			$(".cart-wrapper").css("max-width",$(".cart-outer-wrapper").outerWidth());
			scrollCart();
		});
		$(window).scroll(function(){
			var topval = $(".top-header2").outerHeight();
			if($(this).scrollTop() > topval) {
				
				$(".cart-wrapper").addClass("cart-wrapper-fixed");

			}
			else{
				$(".cart-wrapper").removeClass("cart-wrapper-fixed");

			}
			
		});
		
}

if($(window).width()>767){
function scrollCart(){
	var headfootcart = $(window).height() - ($(".top-header2").outerHeight() + $(".res_footer").outerHeight() + $(".cart-box").outerHeight() + $(".pic-del").outerHeight()+$(".min-order-value").outerHeight() + $(".cart-checkout").outerHeight()+80);
	
	if($(window).width()>767){
		$(".cart-items-scroll").css("max-height",headfootcart);
		$(".cart-items-scroll").enscroll('destroy');
		$(".cart-items-scroll").enscroll();
		$(".fospluginwrapper .cart-items-scroll").enscroll('destroy');
	}
	else{
		$(".cart-items-scroll").css("max-height","inherit");
		$(".cart-items-scroll").enscroll('destroy');
		$(".fospluginwrapper .cart-items-scroll").enscroll('destroy');
		
	}
}
$(window).on('load',function(){
	scrollCart();
	var checkouttopheight = $('.top-header2').outerHeight()+$("footer").outerHeight()+50;
	$(".checkoutScrollWrapper").css("min-height",$(".order_information_box").outerHeight()+checkouttopheight);
})
}
/*------------checkoutpage scroll script------------------------*/
if($(window).width()>767){
	$(document).ready(function(){
			var order_width = $('.order_information_inner').outerWidth();
			var topheight = $('.top-header2').outerHeight()+20;
			$('.order_information_box').css({'top': topheight, 'width':order_width});
	});
		$(window).scroll(function(){
			var checkouttop = $('.top-header2').outerHeight() + $('.order_information_box2').outerHeight();
			if(($(this).scrollTop() > checkouttop) && ($(window).scrollTop() + $(window).height() < $(document).height())) {
				$('.order_information_box').addClass('fixed');
			}	
	        else if($(window).scrollTop() + $(window).height() == $(document).height()) {
		      $('.order_information_box').css({'top':-$("footer").outerHeight()});
		  	}
	        else {
				$('.order_information_box').removeClass('fixed')
			}
		});

}

/*------------checkoutpage scroll script------------------------*/

function deliveryLater(id) {
	 if($(id).is(':checked'))  {
		$('#showCalendar').show();
	}
}
function deliveryNow(id) {
	 if($(id).is(':checked'))  {
		$('#showCalendar').hide();
	}
}


$(document).ready(function(){

	//alert("22222");
	if($("#late-option").is(':checked'))  {
		$('#showCalendar').show();
	}
	$('#latertime').datepicker({
        dateFormat: 'yy-mm-dd',
        minDate:0,
        onSelect: function(dateText, inst) {
            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'checkouts/ajaxaction',
                data   : {date:dateText,action:'getTiming'},
                success: function(data){
                	var data = data.split('@@');
                	if($.trim(data[1]) == 'closed') {

                        $("#overAllBtn").hide();
                		$("#paymentInfo").addClass('mask_checkout');
					}else {

                        $("#paymentInfo").removeClass('mask_checkout');
                        $("#overAllBtn").show();
                        checkMinimum();

					}
                    $("#timeLists").html(data[0]);return false;

                }
            });return false;
        }
    });

	$('#BookaTableBookingDate').datepicker({
		minDate:0,
		dateFormat: 'yy-mm-dd',
		onSelect: function(dateText, inst) {
            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'menus/ajaxaction',
                data   : {date:dateText,action:'getTiming'},
                success: function(data){
                	if(data != ''){
                		$("#timeList").html(data);
                	}
                    return false;
                }
            });return false;
        }
    });

});


function viewcart(id){
	$(".cart-hide-wrap").slideToggle('.cart-items-scroll');	
	if($(id).text() == $(id).attr("data-view-cart")){
		$(id).text($(id).attr("data-hide-cart"));
	}
	else{
		$(id).text($(id).attr("data-view-cart"));
	}
}




$(document).ready(function(){           
    $('.searchInput').keyup(function(){
        /*var that = this, $allListElements = $('.bu-head');
        var $matchingListElements = $allListElements.filter(function(i, li){
            var listItemText = $(li).text().toUpperCase(), searchText = that.value.toUpperCase();
            return ~listItemText.indexOf(searchText);
        });
        $allListElements.hide();
        $matchingListElements.show();*/
        var value = $(this).val().toLowerCase();
    	$(".signle-food-item-div").filter(function() {
     	$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    	});
    });
});

$(document).ready(function() {
    $('#reward_table').DataTable();
});

