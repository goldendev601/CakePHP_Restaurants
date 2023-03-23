<?php if($action == 'menuStatus' && $field == 'status') { ?>
    <?php if($status == 'active'){?>
        <button class="btn btn-icon-toggle active" type="button" onclick="changeStatus('<?= $id ?>', '0', '<?= $field ?>', 'menus/ajaxaction', 'menuStatus')">
            <i class="fa fa-check"></i>
        </button>
    <?php }else {?>
        <button class="btn btn-icon-toggle active" type="button" onclick="changeStatus('<?= $id ?>', '1', '<?= $field ?>', 'menus/ajaxaction', 'menuStatus')">
            <i class="fa fa-close"></i>
        </button>
    <?php }?>
<?php exit();} ?>


<?php if($action == 'Menu') { ?>
    <table id="menuTable" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Menu Name</th>
                <th>Category</th>
                <th>Added Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody> 
                <?php if(!empty($menulist)) {
                      foreach($menulist as $key => $value) { ?>             
                <tr>                                   
                    <!--<td><input type="checkbox" class="minimal"></td>-->
                    <td><?= $key + 1 ?></td>
                    <td><?= $value['menu_name'] ?></td>
                    <td><?= $value['category']['category_name'] ?></td>
                    <td><?= date('Y-m-d', strtotime($value['created'])) ?></td>
                    <!--<td><a class="buttonStatus"><i class="fa fa-check"></i></a></td>-->
                    <td id="status_<?php echo $value['id']; ?>">
                        <?php if ($value['status'] == '0') { ?>
                            <button class="btn btn-icon-toggle deactive" href="javascript:;"
                                    onclick="changeStatus('<?= $value['id'] ?>', '1', 'status', 'menus/ajaxaction', 'menuStatus')">
                                <i class="fa fa-close"></i>
                            </button>
                        <?php } else { ?>
                            <button class="btn btn-icon-toggle active" href="javascript:;"
                                    onclick="changeStatus('<?= $value['id'] ?>', '0', 'status', 'menus/ajaxaction', 'menuStatus')">
                                <i class="fa fa-check"></i>
                            </button>
                        <?php } ?>
                    </td>
                    <td>
                        <a href="<?php echo REST_BASE_URL ?>menus/edit/<?php echo $value['id'] ?>">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <a id="<?php echo $value['id']; ?>"
                           onclick="return deleteRecord(<?php echo $value['id']; ?>, 'menus/deletemenu', 'Menu', '', 'menuTable')"
                           href="javascript:;">
                            <i class="fa fa-trash-o"></i>
                        </a>
                        <!--<span class="">
                            <a href="<?php /*echo REST_BASE_URL; */?>menus/edit" class="buttonEdit"><i class="fa fa-pencil-square-o"></i></a>
                            <a class="buttonAction"><i class="fa fa-trash-o"></i></a>
                        </span>-->
                    </td>               
            </tr>
            <?php }
              }
            ?>            
        </tbody>
    </table>
<?php die(); } ?>
<!-- Get Addons details -->
<?php if($action == 'getAddons') {
    $priceAppend = 1;
    $j = '';
    //pr($addonsList);die();
    foreach($addonsList as $keyword => $value) { ?>
        <div class="form-group">
            <label class="col-md-3 control-label">&nbsp;</label>

            <div class="col-md-9">
                <div class="mainProductHead mainAdd bold" data-category="<?= $value['id'] ?>"><?php echo $value['mainaddons_name'] ?> <span class="caret"></span></div>

                <span class="subaddonErr_<?= $value['id'] ?>"></span>
                <input type="hidden" value="<?= $value['mainaddons_mini_count'] ?>" id="mini_<?= $value['id'] ?>">

                <?php
                echo $this->Form->input('Mainaddon.id', [
                    'type' => 'hidden',
                    'name' => 'data[MenuAddon][' . $keyword . '][mainaddons_id]',
                    'value' => $value['id']
                ]);
                $i = (!empty($j)) ? $j + 1 : 1;
                foreach ($value['subaddons'] as $key => $val) { ?>
                <div class="col-xs-12 productAddonsMenu"
                     id="data[MenuAddon][<?php echo $keyword; ?>][Subaddon][<?php echo $key; ?>][subaddons_price]">
                    <div class="row">
                        <div class="col-md-3 col-lg-3">
                            <input type="checkbox" id="data[MenuAddon][<?php echo $keyword ?>][Subaddon][<?php echo $key ?>][subaddons_id]" name="data[MenuAddon][<?php echo $keyword ?>][Subaddon][<?php echo $key ?>][subaddons_id]" value="<?php echo $val['id'];?>" class="checkboxes test appendMultipleSubAddons subAdd_<?= $value['id'] ?>" <?php echo ((in_array($val['id'], $selectedAddons) == 1) ? 'checked' : '');?> >
                            <label for="data[MenuAddon][<?php echo $keyword ?>][Subaddon][<?php echo $key ?>][subaddons_id]"><?php echo $val['subaddons_name']?></label>
                        </div>

                        <div class="appendMultiplePrice" id="appendMultiplePrice_<?php echo $i; ?>"> <?php
                            if (!empty($menuID)) {
                                if ($priceOption == 'multiple') {
                                    if (!empty($val['menuAddons'])) {
                                        $len = '';
                                        foreach ($val['menuAddons'] as $k => $v) { ?>
                                            <div class="col-md-3 col-lg-2 removeAppendAddon_<?php echo $k; ?>"><?php
                                                echo $this->Form->input('', [
                                                    'class' => 'form-control singleValidate',
                                                    'placeholder' => 'Price',
                                                    'type' => 'text',
                                                    'name' => 'data[MenuAddon][' . $keyword . '][Subaddon][' . $key . '][subaddons_price][]',
                                                    'value' => $v['subaddons_price'],
                                                    'label' => false
                                                ]); ?>
                                            </div> <?php
                                        }
                                    } else {
                                        //echo 'commee';
                                        for ($menu = 1; $menu <= $menuLength; $menu++) { ?>
                                            <div
                                                    class="col-md-3 col-lg-2 removeAppendAddon_<?php echo $menu - 1; ?>"><?php
                                                echo $this->Form->input('', [
                                                    'class' => 'form-control singleValidate',
                                                    'placeholder' => 'Price',
                                                    'type' => 'text',
                                                    'name' => 'data[MenuAddon][' . $keyword . '][Subaddon][' . $key . '][subaddons_price][]',
                                                    'value' => $val['subaddons_price'],
                                                    'label' => false
                                                ]); ?>
                                            </div> <?php
                                        }
                                    }
                                } else {
                                    if (!empty($val['menuAddons'])) {
                                        foreach ($val['menuAddons'] as $singlekey => $singleValue) {
                                            ?>
                                            <div class="col-md-3 col-lg-2"><?php
                                                echo $this->Form->input('', [
                                                    'class' => 'form-control singleValidate',
                                                    'placeholder' => 'Price',
                                                    'type' => 'text',
                                                    'name' => 'data[MenuAddon][' . $keyword . '][Subaddon][' . $key . '][subaddons_price][]',
                                                    'value' => $singleValue['subaddons_price'],
                                                    'label' => false
                                                ]); ?>
                                            </div> <?php
                                        }
                                    } else { ?>
                                        <div class="col-md-3 col-lg-2"><?php
                                            echo $this->Form->input('', [
                                                'class' => 'form-control singleValidate',
                                                'placeholder' => 'Price',
                                                'type' => 'text',
                                                'name' => 'data[MenuAddon][' . $keyword . '][Subaddon][' . $key . '][subaddons_price][]',
                                                'value' => $val['subaddons_price'],
                                                'label' => false
                                            ]); ?>
                                        </div> <?php
                                    }
                                }
                            } else {
                                if ($priceOption == 'multiple') { ?>
                                    <div class="col-md-3 col-lg-2"><?php
                                        echo $this->Form->input('', [
                                            'class' => 'form-control singleValidate',
                                            'placeholder' => 'Price',
                                            'type' => 'text',
                                            'name' => 'data[MenuAddon][' . $keyword . '][Subaddon][' . $key . '][subaddons_price][]',
                                            'value' => $val['subaddons_price'],
                                            'label' => false
                                        ]); ?>
                                    </div> <?php
                                } else { ?>
                                    <div class="col-md-3 col-lg-2"><?php
                                        echo $this->Form->input('', [
                                            'class' => 'form-control singleValidate',
                                            'placeholder' => 'Price',
                                            'type' => 'text',
                                            'name' => 'data[MenuAddon][' . $keyword . '][Subaddon][' . $key . '][subaddons_price]',
                                            'value' => $val['subaddons_price'],
                                            'label' => false
                                        ]); ?>
                                    </div> <?php
                                }
                            } ?>
                        </div>
                    </div>
                    </div><?php
                    $j = $i++;
                } ?>
            </div>
        </div>

    <?php } die(); } ?>
