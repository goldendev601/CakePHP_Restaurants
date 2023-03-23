<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Language Edit File
        </h1>
        <ol class="breadcrumb">
            <li>
              <a href="<?php echo ADMIN_BASE_URL ;?>dashboard">
                 <i class="fa fa-dashboard"></i> Home</a>
            </li>           
            <li class="active">
               <a href="<?php echo ADMIN_BASE_URL ;?>LanguageSettings">Manage Language Setting</a>
            </li>
        </ol>
    </section>

    <section class="content clearfix">
        <div class="col-xs-12">
            <div class="row">
                <div class="box my-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            Language Edit File
                        </h3>
                    </div>
                        <?php
                            if(!empty($languageList)) {
                                echo $this->Form->create($languageList, [
                                    'id' => 'languageEditFileForm'                                
                                ]);                                                          
                             } else {
                                echo $this->Form->create('editFile', [
                                    'id' => 'languageEditFileForm'
                                ]);
                            } 
                            echo $this->Form->input('editid',[
                                'type' => 'hidden',
                                'id'   => 'editid',
                                'class' => 'form-control',
                                'value' => !empty($id) ? $id : '',
                                'label' => false
                            ]); 
                            echo $this->Form->input('langCode',[
                                'type' => 'hidden',
                                'id'   => 'langCode',
                                'class' => 'form-control',
                                'value' => !empty($langCode) ? $langCode : '',
                                'label' => false
                            ]);                        
                        ?>
                        <div class="addmore">
                        <?php if (!empty($languageList)) { 
                            foreach ($languageList as $key => $val) {
                                ?>                    
                        <div class="box-body">
                            <div class="form-group">
                                <div class="col-sm-4">
                                    <?= $this->Form->input('lang['.$key.'][msgid]',[
                                            'type' => 'text',
                                            'id'   => 'msgid_'.$key,
                                            'class' => 'form-control',
                                            'placeholder' => 'Please Enter msg id',
                                            'value' => !empty($val['msgid']) ? $val['msgid'] : '',
                                            'label' => false
                                        ]) ?>
                                      <span class="msgIdErr"></span>    
                                </div>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('lang['.$key.'][msgstr]',[
                                            'type' => 'text',
                                            'id'   => 'msgstr',
                                            'class' => 'form-control',
                                            'placeholder' => 'Please Enter msg str',
                                            'value' => !empty($val['msgstr']) ? $val['msgstr'] : '',
                                            'label' => false
                                        ]) ?>
                                      <span class="msgStrErr"></span>    
                                </div>
                            </div>
                            <?php if($key != 0) { ?>
                                <div class="col-sm-2">
                                    <a class="remove_field btn btn-danger minus" id="remove<?php echo $key; ?>" onclick="remove(this)">-</a>
                                </div>
                            <?php } ?>
                            <?php if($key == 0) {?> 
                            <div class="col-sm-2">
                                <a class="btn btn-primary plus add_more_button" onclick="return addmore();">+</a>
                            </div>
                            <?php }?>
                        </div>
                        <?php } } else { ?>
                            <div class="box-body">
                            <div class="form-group">
                                <div class="col-sm-4">
                                    <?= $this->Form->input('lang[0][msgid]',[
                                            'type' => 'text',
                                            'id'   => 'msgid',
                                            'class' => 'form-control',
                                            'placeholder' => 'Please Enter msg id',
                                            'label' => false
                                        ]) ?>
                                      <span class="msgIdErr"></span>    
                                </div>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('lang[0][msgstr]',[
                                            'type' => 'text',
                                            'id'   => 'msgstr',
                                            'class' => 'form-control',
                                            'placeholder' => 'Please Enter msg str',
                                            'label' => false
                                        ]) ?>
                                      <span class="msgStrErr"></span>    
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <a class="btn btn-primary plus add_more_button" onclick="return addmore();">+</a>
                            </div>
                        </div>
                        <?php  }?>
                        </div>
                        <div class="col-xs-12 no-padding m-t-20">
                             <button type="submit" class="btn btn-info m-r-15" onclick=" languageEditFile();">Submit</button>
                            <a class="btn btn-default" href="<?php echo ADMIN_BASE_URL?>LanguageSettings/">Cancel</a>
                           
                        </div>
                    <?= $this->Form->end();?>
                </div>
            </div>
        </div>
    </section>
</div>


<script type="text/javascript">
  var i=1;
function addmore() {
    var msg = $('#msgid_'+i+'').length;
    if($('#msgid_'+i+'').length > 0) {
        i++;
        addmore();  
        return false;
    } else {
        var addmore_text = '<div class="box-body"><div class="form-group"><div class="col-sm-4"><input type="text" class="form-control" name="lang['+i+'][msgid]" id="msgid_'+i+'" placeholder="Please Enter msgid"/></div><div class="col-sm-4"><input type="text" class="form-control" name="lang['+i+'][msgstr]" placeholder="Please Enter msgstr"/></div><div class="col-sm-2"><a href="javascript:void(0);" class="remove_field btn btn-danger" id="remove'+i+'" onclick="return remove(this);" style="margin-left:10px;">-</a></div></div></div>';
        $('.addmore').append(addmore_text);
        i++;
    }
    
    return false;
}
function remove(id) {
    $(id).closest('.box-body').remove();
    return false;
}
/*function languageEditFile(){
    
    var msgid = $.trim($("#msgid").val());        
    var msgstr = $.trim($("#msgstr").val());        
    var editid        = $.trim($("#editid").val());    
    var langCode        = $.trim($("#langCode").val());    
    if(msgid == '') {
        $('.msgIdErr').addClass('error').html('Please Enter msgid');
        $("#msgid").focus();
        return false;
    } else if (msgstr == ''){
        $('.msgStrErr').addClass('error').html('Please Enter msgstr');
        $("#msgstr").focus();
        return false;
    }
    
}*/
</script>