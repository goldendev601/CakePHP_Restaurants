<div class="content-wrapper">
    <section class="content-header">
        <h1> Add Bonuspoint </h1>
        
        <ol class="breadcrumb">
            <li>
              <a href="<?php echo ADMIN_BASE_URL ;?>dashboard">
                 <i class="fa fa-dashboard"></i> Home</a>
            </li>           
            <li class="active">
               <a href="<?php echo ADMIN_BASE_URL ;?>bonuspoints">Manage Bonuspoint</a>
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
                        ?>

                        <div class="box-body">
                            <div class="form-group clearfix">
                                <label for="restaurant_id" class="col-sm-2 control-label">Restaurant Name<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('restaurant_id',[
                                            'type' => 'select',
                                            'id'   => 'restaurant_id',
                                            'class' => 'form-control',
                                            'empty' => 'Select Restaurant',
                                            'options' => $restaurantLists,
                                            'label' => false
                                        ]) ?>
                                      <span class="restaurantErr"></span>
                                </div>
                            </div>

                            <div class="form-group clearfix">
                                <div class="col-md-7 col-md-offset-2 margin-bottom" id="bonusmore_0">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="input text">
                                                <input id="no_oforder_0" class="form-control orderlist" name="data[Bonus][0][no_oforder]" placeholder="Order" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input text">
                                                <input id="no_ofpoint_0" class="form-control pointlist" name="data[Bonus][0][no_ofpoint]" placeholder="Point" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <a class="addPrice btn green" href="javascript:void(0);" onclick="multipleBonus();"><i class="fa fa-plus"></i>Add Price </a>
                                        </div>
                                    </div>
                                    <span class="commonErr_0"></span>
                                </div>

                                <div id="moreOption"></div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <a class="btn btn-default" href="<?php echo ADMIN_BASE_URL?>bonuspoints/">Cancel</a>
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

         if(restaurant_id == '') {
            $('.restaurantErr').addClass('error').html('Please enter restaurant name');
            $("#restaurant_id").focus();
            return false;
        }
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