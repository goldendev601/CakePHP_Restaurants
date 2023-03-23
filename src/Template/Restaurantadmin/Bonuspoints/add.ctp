<div class="content-wrapper">
    <section class="content-header">
        <h1> Add Bonuspoint </h1>

        <ol class="breadcrumb">
            <li>
                <a href="<?php echo REST_BASE_URL ;?>dashboard">
                    <i class="fa fa-dashboard"></i> Home</a>
            </li>
            <li class="active">
                <a href="<?php echo REST_BASE_URL ;?>bonuspoints">Manage Bonuspoint</a>
            </li>
        </ol>
    </section>

    <section class="content clearfix">
        <div class="col-xs-12">
            <div class="row">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <!-- Bonus -->
                        </h3>
                    </div>

                    <?php
                        echo $this->Form->create('BonusAdd', [
                            'id' => 'bonusForm'
                        ]);
                        echo $this->Form->input('restaurant_id',[
                            'type' => 'hidden',
                            'id'   => 'restaurant_id',
                            'class' => 'form-control',
                            'value' => !empty($resId) ? $resId : '',
                            'label' => false
                         ]);
                    ?>

                    <div class="box-body">
                        <div class="form-group clearfix">
                            <?php if(!empty($bonusList)) {
                                foreach ($bonusList as $mkey => $mvalue) { ?>
                                    <div class="col-md-7 col-md-offset-2 margin-bottom" id="bonusmore_<?php echo $mkey;?>">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="input text">
                                                    <?php
                                                    echo $this->Form->input('Bonus.no_oforder',[
                                                        'class'=>'form-control orderlist',
                                                        'placeholder'=>'Order',
                                                        'id' => 'no_oforder_'.$mkey,
                                                        'name' => 'data[Bonus]['.$mkey.'][no_oforder]',
                                                        'value' => $mvalue['no_oforder'],
                                                        'label'=>false
                                                    ]);
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input text">
                                                    <?php
                                                    echo $this->Form->input('Bonus.no_ofpoint',[
                                                        'class'=>'form-control pointlist',
                                                        'placeholder'=>'Point',
                                                        'id' => 'no_ofpoint_'.$mkey,
                                                        'name' => 'data[Bonus]['.$mkey.'][no_ofpoint]',
                                                        'value' => $mvalue['no_ofpoint'],
                                                        'label'=>false
                                                    ]);
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <?php if(!empty($mkey !=0)) { ?>
                                                    <span class="BonusRemove" onclick="removeBonus(<?php echo $mkey; ?>);">
                                                        <i class="fa fa-times"></i>
                                                    </span>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <span class="commonErr_<?php echo $mkey;?>"></span>
                                    </div>
                                <?php }
                            }else{?>
                                <div class="col-md-7 col-md-offset-2 margin-bottom" id="bonusmore_0">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="input text">
                                                <input id="no_oforder_0" class="form-control orderlist"
                                                       name="data[Bonus][0][no_oforder]" placeholder="Order"
                                                       type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input text">
                                                <input id="no_ofpoint_0" class="form-control pointlist"
                                                       name="data[Bonus][0][no_ofpoint]" placeholder="Point"
                                                       type="text">
                                            </div>
                                        </div>
                                    </div>
                                    <span class="commonErr_0"></span>
                                </div>
                            <?php } ?>
                            <div id="moreOption"></div>
                            <div class="col-md-1">
                                <a class="addPrice btn green" href="javascript:void(0);"
                                   onclick="multipleBonus();"><i class="fa fa-plus"></i>Add Price </a>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <a class="btn btn-default" href="<?php echo REST_BASE_URL?>bonuspoints/">Cancel</a>
                        <button type="button" class="btn btn-info pull-right" onclick="bonusAddEdit();">Submit</button>
                    </div>
                    <?= $this->Form->end();?>
                </div>
            </div>
        </div>
    </section>
</div>


<script type="text/javascript">

    var i = 1;
    function multipleBonus() {
        if($("#bonusmore_"+i).length != 0) {
            i++;
            multipleBonus();
            return false;
        }

        html = '<div class="col-md-7 col-md-offset-2 margin-bottom" id="bonusmore_'+i+'">'+
            '<div class="row">'+
            '<div class="col-md-4">'+
            '<div class="input text">'+
            '<input id="no_oforder_'+i+'" class="form-control orderlist" name="data[Bonus]['+i+'][no_oforder]" placeholder="Order" type="text">'+
            '</div>'+
            '</div>'+
            '<div class="col-md-3">'+
            '<div class="input text">'+
            '<input id="no_ofpoint_'+i+'" class="form-control pointlist " name="data[Bonus]['+i+'][no_ofpoint]" placeholder="Point"  type="text">'+
            '</div>'+
            '</div>'+
            '<div class="col-md-1">'+
            '<span class="BonusRemove" onclick="removeBonus('+i+');">'+
            '<i class="fa fa-times"></i>'+
            '</span>'+
            '</div>'+
            '</div>'+
            '<span class="commonErr_'+i+'"></span>'+
            '</div>';
        i++;

        $('#moreOption').append(html);
        html = '';
        return false;
    }

    function removeBonus(id) {
        $('#bonusmore_' + id).remove();
    }

    function bonusAddEdit(){

        var restaurant_id = $.trim($("#restaurant_id").val());
        var num_per = $("input.orderlist").length;
        var num_dis = $("input.pointlist").length;
        $('.error').html('');

        if(num_per >0) {
            if (num_per > 0) {
                var k = num_per;
                $("input.orderlist").each(function () {

                    if ($(this).val() == '') {
                        $("."+this.id).addClass('error').html("You can't leave order field as empty!");
                        $(this).focus();
                        return false;
                    } else {
                        k--;
                    }
                });

            }
            if (num_dis > 0) {
                var j = num_dis;
                $("input.pointlist").each(function () {
                    if ($(this).val() == '') {
                        $("."+this.id).addClass('error').html("You can't leave bonus field as empty!");
                        $(this).focus();
                        return false;
                    } else {
                        j--;
                    }
                });

            }
        }
        if(k == 0 && j == 0) {
            $("#bonusForm").submit();
        }
    }
</script>
}