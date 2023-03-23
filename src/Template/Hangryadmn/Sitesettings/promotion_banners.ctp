<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Promotions Banner Settings
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Promotions Banner Settings</a></li>
            <li class="active">Settings</li>
        </ol>
    </section>
    <section class="content clearfix">
        <div class="col-xs-12">
            <div class="row">
                <div class="box my-box">
                    <div class="box-body">
                        <?=
                            $this->Form->create('PromotionBanner',[
                                'class'=>"form-horizontal",
                                'enctype'  =>'multipart/form-data'
                                ]);
                        ?>

                        <?php if(!empty($bannerSection)) {
                            foreach($bannerSection as $key => $value) {
                                ?>
                                <div class="form-group">
                                    <div class="col-sm-5">
                                        <div class="clearfix">
                                            <div class="col-sm-5">
                                                <div class="labelname">Promotion Banner</div>
                                            </div>
                                            <div class="col-sm-2"> <?php
                                                echo $this->Form->input('banner_image', [
                                                    'type' => 'file',
                                                    'id' => 'bannerImage_'.($key+1).'',
                                                    'name' => 'data[PromotionBanner]['.($key+1).'][banner_image]',
                                                    'placeholder' => 'Banner Image',
                                                    'label' => false
                                                ]); ?>
                                                <?= $this->Form->input('pass_id',[
                                                        'type' => 'hidden',
                                                        'id'   => 'pass_id'.($key+1).'',
                                                        'name' => 'data[PromotionBanner]['.($key+1).'][pass_id]',
                                                        'value' => $value['id']
                                                ]) ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <span>
                                            <img width="200" height="150" src="<?php echo DRIVERS_LOGO_URL.'/uploads/siteImages/promoBanner/'.$value['banner_image']; ?>">
                                        </span>

                                    </div>
                                </div>
                                <div class="form-group clearfix" id="removeBanner_0">
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <div class="labelname">Banner Link</div>
                                            </div>
                                            <div class="col-sm-2"><?=
                                                $this->Form->input('banner_link', [
                                                    'class' => 'form-control bannerLink',
                                                    'type' => 'text',
                                                    'id' => 'bannerLink_'.($key+1).'',
                                                    'name' => 'data[PromotionBanner]['.($key+1).'][banner_link]',
                                                    'placeholder' => 'Banner Link',
                                                    'value' => $value['banner_link'],
                                                    'label' => false
                                                ]); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php
                            }
                        }else{?>
                       
                            <div class="form-group ">
                                <div class="col-sm-9 col-sm-offset-3">
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <div class="labelname">Promotion Banner</div>
                                        </div>
                                        <div class="col-sm-2"> <?=
                                             $this->Form->input('banner_image', [
                                                // 'class' => 'form-control bannerImage',
                                                'type' => 'file',
                                                'id' => 'bannerImage_0',
                                                'name' => 'data[PromotionBanner][0][banner_image]',
                                                'placeholder' => 'Banner Image',
                                                'label' => false
                                            ]); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group clearfix" id="removeBanner_0">
                                <div class="col-sm-9 col-sm-offset-3">
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <div class="labelname">Banner Link</div>
                                        </div>
                                        <div class="col-sm-2"><?=
                                             $this->Form->input('banner_link', [
                                                'type' => 'text',
                                                'id' => 'bannerLink_0',
                                                'name' => 'data[PromotionBanner][0][banner_link]',
                                                'placeholder' => 'Banner Link',
                                                'label' => false
                                            ]); ?>
                                        </div>
                                       <!--  <div class="col-sm-2">
                                            <a onclick="appendBanner();" class="btn btn-success">Add</a>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                           <?php
                           
                        }?>                    

                        <div class="clearfix"></div>
                        <div class="pull-right">
                            <a onclick="return appendBanner();" class="btn btn-success">Add</a>
                        </div>
                        <div class="clearfix"></div>
                        <div class="appendPromotionBanner"></div>
                    </div>
                    <div class="col-xs-12 no-padding m-t-20">
                        <button class="btn btn-info m-r-15" type="submit">Submit</button>
                        <a href="<?php echo ADMIN_BASE_URL?>sitesettings/promotionBanners" class="btn btn-default">Cancel</a>
                    </div>
                    <?=  $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </section>
</div>



<script>
var locationRow = (typeof j != 'undefined') ? j : 1;

function appendBanner() {

    //alert(locationRow);
    if($("#bannerImage_"+locationRow).length != 0) {
        locationRow++;
        appendBanner();
        return false;
    }


    $('.appendPromotionBanner').append(
        '<div class="form-group clearfix" id="removeBanner_'+locationRow+'">'+
        '<div class="form-group clearfix"><div class="col-sm-5">'+
        '<div class="clearfix">'+
        '<div class="col-sm-5"><div class="labelname">Promotion Banner</div></div><div class="col-sm-2">'+
        '<input type="file"  name=data[PromotionBanner]['+locationRow+'][banner_image] id="bannerImage_'+locationRow+'" >'+
        '</div>'+
         '</div>'+
        '</div>'+
        '</div>'+
        '<div class="form-group clearfix"><div class="col-sm-12">'+
        '<div class="col-sm-2"><div class="labelname">Banner Link</div></div>'+
        '<div class="col-sm-2">'+
        '<input type="text" class="form-control" name=data[PromotionBanner]['+locationRow+'][banner_link] id="bannerLink_'+locationRow+'" placeholder="Link">'+
        '</div>'+
        
        '<a onclick="removeBanner('+locationRow+');" class="btn btn-danger">X</a>'+
        '</div>'+
        '</div>'+
        '</div>'+
        '</div>'
    );
    locationRow++;
}
function removeBanner(removeId) {
    $('#removeBanner_'+removeId).remove();
    return false;
}

</script>