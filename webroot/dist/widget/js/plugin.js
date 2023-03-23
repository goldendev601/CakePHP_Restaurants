$(document).ready(function(){
    $("html,body").css("min-height","100%");
    // $("[data-MOA-resid]").css({"padding":"7px","font-size":"17px","float":"left","background":"#5cb85c","border-radius":"3px","color":"#fff","font-weight":"bold","margin":"10px","cursor":"pointer","position":"relative","z-index":"1055"});
    $("[data-MOA-resid]").click(function(){$("#menuOrderPlugin").modal('show')});
    $(document).find('head').append('<style type="text/css">iframe{border:0;}#menuOrderPlugin button.close{border-radius:3px;background:#fff;height:40px;width:40px;top:0px;right:-50px;position:absolute;z-index:10;text-align:center;line-height:40px;opacity:1;padding:0;margin:0;}.modal-dialog,.modal-body{padding:0!important;}#menuOrderPlugin.modal{overflow-y:hidden;}</style>');  
    $("body").append('<div role="dialog" data-keyboard="false" data-backdrop="static" class="modal fade in" id="menuOrderPlugin"><div class="modal-dialog modal-lg"><div class="modal-content"><button data-dismiss="modal" class="close" type="button">X</button><div class="modal-body outerwrap"><iframe width="100%" height="100%" src="https://www.menuorderapp.com/menu/'+$("[data-MOA-resid]").attr("data-MOA-resid")+'"></iframe></div></div></div></div>');
    $("#menuOrderPlugin").find(".modal-body.outerwrap").height($(window).height()-50);
});