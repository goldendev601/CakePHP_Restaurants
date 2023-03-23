var orderbtn = document.getElementsByClassName("fos-order");
if(orderbtn.length>0){
	console.log("Comeneat 2.0 Order Widget");
}else{
	document.body.innerHTML += '<a class="fos-order-button" style="padding:7px;font-size:17px;float:right;background:#5cb85c;border-radius:3px;color:#fff;font-weight:bold;margin:10px;cursor:pointer;position:absolute;right:10px;z-index:1;cursor:pointer;top:10px;min-width:100px;">Order Online</a>';
}
var cssstyle = 'html,body{height: 100%;-webkit-overflow-scrolling: touch;}iframe{border:0;}.lookingfor-scroll{width:100%!important;}.foodOrderingSystemPlugin button.close{border-radius:3px;background:#eee;height:40px;width:40px;top:7px;left:10px;position:absolute;z-index:10;text-align:center;line-height:40px;opacity:1;padding:0;margin:0;}.modal-dialog,.modal-body{padding:0!important;}.foodOrderingSystemPlugin.modal{overflow-y:hidden;margin:0px;}.modal-backdrop{z-index:104000!important;}.foodOrderingSystemPlugin.modal{z-index:1040000!important;}.modalwrap{height:100%;}.modelwrapcontent{height:100%;overflow-y:hidden;}.outerwrap{height:100%;overflow-y:auto;-webkit-overflow-scrolling: touch;}.modal,.modal-open{overflow:hidden}.modal,.modal-backdrop{top:0;right:0;bottom:0;left:0}.close{float:right;font-size:21px;font-weight:700;line-height:1;color:#000;text-shadow:0 1px 0 #fff;filter:alpha(opacity=20);opacity:.2}.close:focus,.close:hover{color:#000;text-decoration:none;cursor:pointer;filter:alpha(opacity=50);opacity:.5}button.close{-webkit-appearance:none;padding:0;cursor:pointer;background:0 0;border:0}.modal{position:fixed;z-index:1050;display:none;-webkit-overflow-scrolling:touch;outline:0}.modal.fade .modal-dialog{-webkit-transition:-webkit-transform .3s ease-out;-o-transition:-o-transform .3s ease-out;transition:transform .3s ease-out;-webkit-transform:translate(0,-25%);-ms-transform:translate(0,-25%);-o-transform:translate(0,-25%);transform:translate(0,-25%)}.modal.in .modal-dialog{-webkit-transform:translate(0,0);-ms-transform:translate(0,0);-o-transform:translate(0,0);transform:translate(0,0)}.modal-open .modal{overflow-x:hidden;overflow-y:auto}.modal-dialog{position:relative;width:auto;margin:10px 2%;}.modal-content{position:relative;-webkit-background-clip:padding-box;background-clip:padding-box;border:0px solid #999;outline:0;box-shadow:none!important;background:transparent;}.modal-backdrop{position:fixed;z-index:1040;background-color:#000}.modal-backdrop.fade{filter:alpha(opacity=0);opacity:0}.modal-backdrop.in{filter:alpha(opacity=50);opacity:.5}.modal-header{padding:15px;border-bottom:1px solid #e5e5e5}.modal-header .close{margin-top:-2px}.modal-title{margin:0;line-height:1.42857143}.modal-body{position:relative;padding:15px}.modal-footer{padding:15px;text-align:right;border-top:1px solid #e5e5e5}.modal-footer .btn+.btn{margin-bottom:0;margin-left:5px}.modal-footer .btn-group .btn+.btn{margin-left:-1px}.modal-footer .btn-block+.btn-block{margin-left:0}.modal-scrollbar-measure{position:absolute;top:-9999px;width:50px;height:50px;overflow:scroll}@media (min-width:768px){.modal-dialog{width:600px;margin:0 auto}.modal-sm{width:300px}}@media (min-width:992px){.modal-lg{width:900px}}@media (min-width:768px)	and (max-width:1200px) {#foodOrderingSystemPlugintable button.close{right:-10px!important;top:-20px!important;}}.visibilityhidden{opacity:0;pointer-events:none;display:none;}.visibilityvisible{opacity:1;pointer-events:unset;display:block;}.fos-mask{position:fixed;left:0;right:0;top:0;bottom:0;z-index:5;background:rgba(0,0,0,0.5);display:none;}.overflowhidden{overflow:hidden;}#foodOrderingSystemPluginorder .modalClose{display:none;}#foodOrderingSystemPluginorder.visibilityvisible .modalClose{display:block;}',
head = document.head || document.getElementsByTagName('head')[0],
style = document.createElement('style');
style.type = 'text/css';
if (style.styleSheet){
  style.styleSheet.cssText = cssstyle;
} else {
  style.appendChild(document.createTextNode(cssstyle));
}
head.appendChild(style);
var viewPortTag=document.createElement('meta');
viewPortTag.id="viewport";
viewPortTag.name = "viewport";
viewPortTag.content = "width=device-width, initial-scale=1.0;";
document.getElementsByTagName('head')[0].appendChild(viewPortTag);
var body = document.getElementsByTagName('body');
var isMobile = { Android: function() { return navigator.userAgent.match(/Android/i); }, BlackBerry: function() { return navigator.userAgent.match(/BlackBerry/i); }, iOS: function() { return navigator.userAgent.match(/iPhone|iPad|iPod/i); }, Opera: function() { return navigator.userAgent.match(/Opera Mini/i); }, Windows: function() { return navigator.userAgent.match(/IEMobile/i); }, any: function() { return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows()); } }; 
/*if(isMobile.any()) { 
	
}
else{*/
	document.body.innerHTML += '<div class="fos-mask"></div><div role="dialog" data-keyboard="false" data-backdrop="static" id="foodOrderingSystemPluginorder" class="modal fade in foodOrderingSystemPlugin"><div class="modalwrap modal-dialog modal-lg" style="width:96%;max-width:1170px;"><div class="modal-content modelwrapcontent"><button data-dismiss="modal" class="modalClose close closemodalwrap" data-close="" type="button">X</button><div class="modal-body outerwrap"><iframe id="foodOrderingSystemPluginorderiframe" width="100%" height="100%" src="https://www.hangrymenu.com/menu/'+resId+'"></iframe></div></div></div></div>';
//}
var openpopupOrder = document.getElementsByClassName("fos-order");
for(var i = 0; i < openpopupOrder.length; i++)
{    
	openpopupOrder.item(i).addEventListener("click", function(){
		document.getElementById("foodOrderingSystemPluginorder").classList.add("visibilityvisible");
		document.getElementsByClassName("fos-mask")[0].style.display = "block";
		document.body.classList.add("overflowhidden");
	});
}
var closepopupOrder = document.getElementsByClassName("closemodalwrap");
for(var i = 0; i < closepopupOrder.length; i++)
{    
	closepopupOrder.item(i).addEventListener("click", function(){
		document.getElementById("foodOrderingSystemPluginorder").classList.remove("visibilityvisible");
		document.getElementsByClassName("fos-mask")[0].style.display = "none";
		document.body.classList.remove("overflowhidden");
	});
}
var btnopenpopupOrder = document.getElementsByClassName("fos-order-button");
for(var i = 0; i < btnopenpopupOrder.length; i++)
{    
	btnopenpopupOrder.item(i).addEventListener("click", function(){
		document.getElementById("foodOrderingSystemPluginorder").classList.add("visibilityvisible");
		document.getElementsByClassName("fos-mask")[0].style.display = "block";
		document.body.classList.add("overflowhidden");
	});
}