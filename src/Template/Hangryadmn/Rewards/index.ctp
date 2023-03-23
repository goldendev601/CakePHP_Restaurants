<div class="content-wrapper">
    <section class="content-header">
        <h1>Reward Points</h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo ADMIN_BASE_URL ;?>dashboard">
                    <i class="fa fa-dashboard"></i> Home
                </a>
            </li>
            <li class="active">
                <a href="<?php echo ADMIN_BASE_URL ;?>restaurants">Manage Restaurant</a>
            </li>
        </ol>
    </section>
    <section class="content clearfix">
        <div class="col-xs-12">
            <?php
                echo $this->Form->create('offerAdd', [
                    'id' => 'offerAddEditForm'
                ]);
            ?>

                <div class="row">
                    <div class="box my-box reward-box">
                        <div class="form-group clearfix">
                            <div class="col-sm-3">
                                <label class="reward-label">Rewards Points :</label>
                            </div>
                            <div class="col-sm-7 reward_input">
                                <label class="radio-inline no-padding-left">
                                    <input id="reward_yes" type="radio" name="reward_option" class="minimal" <?= ($rewardsList['reward_option'] == 'Yes') ? 'checked' : '' ?> value="Yes">
                                    Yes
                                </label>
                                <label class="radio-inline">
                                    <input id="reward_no" type="radio" name="reward_option" class="minimal" <?= ($rewardsList['reward_option'] == 'No') ? 'checked' : '' ?> value="No">
                                    No
                                </label>
                            </div>
                        </div>

                        <div id="showReward" style="<?= ($rewardsList['reward_option'] == 'No') ? 'display:none' : '' ?>">
                            <div class="form-group clearfix">
                                <div class="col-sm-3">
                                    <label class="reward-label">Set Rewards :</label>
                                </div>
                                <div class="col-sm-3 reward_input">
                                    <input type="text" class="form-control" id="reward_amount" name="reward_amount" value="<?= $rewardsList['reward_amount'] ?>">
                                    <span class="dollar"><?= $siteSettings['site_currency'] ?></span>
                                </div>
                                <div class="col-sm-1 equal">=</div>
                                <div class="col-sm-3 reward_input">
                                    <input type="text" class="form-control" id="reward_point" name="reward_point" value="<?= $rewardsList['reward_point'] ?>">
                                    <span class="percent">points</span>
                                </div>
                                <span class="rewardAmtErr"></span>
                            </div>
                            <div class="form-group clearfix">
                                <div class="col-sm-3">
                                    <label class="reward-label">Set Percentage :</label>
                                </div>
                                <div class="col-sm-3 reward_input">
                                    <input type="text" class="form-control" id="reward_totalpoint" name="reward_totalpoint" value="<?= $rewardsList['reward_totalpoint'] ?>">
                                    <span class="dollar">points</span>
                                </div>
                                <div class="col-sm-1 equal">=</div>
                                <div class="col-sm-3 reward_input">
                                    <input type="text" class="form-control" id="reward_percentage" name="reward_percentage" value="<?= $rewardsList['reward_percentage'] ?>">
                                    <span class="percent">%</span>
                                </div>
                                <span class="rewardPercentageErr"></span>
                            </div>
                            <div class="form-group clearfix">
                                <div class="col-sm-3">
                                    <label class="reward-label">Validity :</label>
                                </div>
                                <div class="col-sm-3 reward_input">
                                    <input type="text" class="form-control" id="reward_validity" name="reward_validity" value="<?= $rewardsList['reward_validity'] ?>">
                                    <span class="dollar"><i class="fa fa-calendar"></i></span>
                                </div>
                                <span class="validityErr"></span>
                            </div>
                            <div class="form-group clearfix">
                                <div class="col-sm-3">
                                    <label class="reward-label">Redeem Order :</label>
                                </div>
                                <div class="col-sm-3 reward_input">

                                    <?php
                                    $orders = [
                                        '2' => '2nd Order',
                                        '3' => '3rd Order',
                                        '4' => '4th Order',
                                        '5' => '5th Order',
                                        '6' => '6th Order',
                                        '7' => '7th Order',
                                        '8' => '8th Order',
                                        '9' => '9th Order',
                                        '10' => '10th Order',
                                    ]

                                    ?>
                                    <?= $this->Form->input('redeem_order',[
                                        'type' => 'select',
                                        'id'   => 'redeem_order',
                                        'class' => 'form-control',
                                        'options'=> $orders,
                                        'empty'  => 'Select Redeem Order',
                                        'value' => $rewardsList['redeem_order'],
                                        'label' => false
                                    ]); ?>
                                </div>
                                <span class="orderErr"></span>
                            </div>

                        </div>
                        <div class="col-xs-12">
                            <button type="submit" class="btn btn-info m-r-15" onclick="return setReward();">Submit</button>
                            <a class="btn btn-default" href="<?= ADMIN_BASE_URL ?>rewards">Cancel</a>
                        </div>
                    </div>
                </div>

            <?= $this->Form->end();?>

        </div>
    </section>
</div>

<script>
    function setReward() {
        $(".error").html('');
        var reward_option = $.trim($("input[name='reward_option']:checked").val());
        var reward_amount = $.trim($("#reward_amount").val());
        var reward_point = $.trim($("#reward_point").val());
        var reward_totalpoint = $.trim($("#reward_totalpoint").val());
        var reward_percentage = $.trim($("#reward_percentage").val());
        var reward_validity = $.trim($("#reward_validity").val());
        var redeem_order = $.trim($("#redeem_order").val());

        if(reward_option == 'Yes') {

            if(reward_amount == '') {
                $(".rewardAmtErr").addClass('error').html('Please enter the amount');
                $("#reward_amount").focus();
                return false;

            }else if(reward_point == '') {
                $(".rewardAmtErr").addClass('error').html('Please enter the point');
                $("#reward_point").focus();
                return false;

            }else if(reward_totalpoint == '') {
                $(".rewardPercentageErr").addClass('error').html('Please enter percentage points');
                $("#reward_totalpoint").focus();
                return false;

            }else if(reward_percentage == '') {
                $(".rewardPercentageErr").addClass('error').html('Please enter percentage');
                $("#reward_percentage").focus();
                return false;

            }else if(reward_validity == '') {
                $(".validityErr").addClass('error').html('Please enter validity date');
                $("#reward_validity").focus();
                return false;

            }else if(redeem_order == '') {
                $(".orderErr").addClass('error').html('Please enter redeem order');
                $("#redeem_order").focus();
                return false;
            }

        }

    }
</script>

