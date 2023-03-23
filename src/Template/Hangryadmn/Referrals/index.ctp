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
                            <label class="reward-label">Referral Option :</label>
                        </div>
                        <div class="col-sm-7 reward_input">
                            <label class="radio-inline no-padding-left">
                                <input id="reward_yes" type="radio" name="referral_option" class="minimal" <?= ($referralList['referral_option'] == 'Yes') ? 'checked' : '' ?> value="Yes">
                                Yes
                            </label>
                            <label class="radio-inline">
                                <input id="reward_no" type="radio" name="referral_option" class="minimal" <?= ($referralList['referral_option'] == 'No') ? 'checked' : '' ?> value="No">
                                No
                            </label>
                        </div>
                    </div>

                    <div id="showReward" style="<?= ($referralList['referral_option'] == 'No') ? 'display:none' : '' ?>">
                        <div class="form-group clearfix">
                            <div class="col-sm-3">
                                <label class="reward-label">Inivite Amount :</label>
                            </div>
                            <div class="col-sm-3 reward_input">
                                <input type="text" class="form-control" id="invite_amount" name="invite_amount" value="<?= $referralList['invite_amount'] ?>">
                                <span class="dollar"><?= $siteSettings['site_currency'] ?></span>
                            </div>
                            <span class="InviteAmtErr"></span>
                        </div>

                        <div class="form-group clearfix">
                            <div class="col-sm-3">
                                <label class="reward-label">Receive Amount :</label>
                            </div>
                            <div class="col-sm-3 reward_input">
                                <input type="text" class="form-control" id="receive_amount" name="receive_amount" value="<?= $referralList['receive_amount'] ?>">
                                <span class="dollar"><?= $siteSettings['site_currency'] ?></span>
                            </div>
                            <span class="receiveAmtErr"></span>
                        </div>

                    </div>
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-info m-r-15" onclick="return setReferral();">Submit</button>
                        <a class="btn btn-default" href="<?= ADMIN_BASE_URL ?>referrals">Cancel</a>
                    </div>
                </div>
            </div>

            <?= $this->Form->end();?>

        </div>
    </section>
</div>

<script>
    function setReferral() {
        $(".error").html('');
        var referral_option = $.trim($("input[name='referral_option']:checked").val());
        var invite_amount = $.trim($("#invite_amount").val());
        var receive_amount = $.trim($("#receive_amount").val());

        if(referral_option == 'Yes') {
            if(invite_amount == '') {
                $(".InviteAmtErr").addClass('error').html('Please enter the amount');
                $("#invite_amount").focus();
                return false;

            }else if(receive_amount == '') {
                $(".receiveAmtErr").addClass('error').html('Please enter the point');
                $("#receive_amount").focus();
                return false;
            }
        }
    }
</script>

