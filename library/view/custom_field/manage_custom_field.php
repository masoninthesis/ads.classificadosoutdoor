<div class="group" id="of-option-ccfield">
    <div class="section section-text ">
        <h3 class="heading"><?php echo MNG_FIELDS; ?></h3>
        <a class="pn-view-a" href="<?php echo admin_url("admin.php?page=field&ref=custom_field#of-option-customfields"); ?>"><?php echo AD_CUSTOM_FIELD; ?></a>
    </div>
    <div class="section section-text ">
        <div class="option">
            <table id="tblspacer" class="widefat fixed">
                <thead>
                    <tr>
                        <th scope="col"><?php echo MNG_FIELDS; ?></th>
                        <th scope="col"><?php echo TYPE; ?></th>
                        <th scope="col"><?php echo ACTIVE; ?></th>
                        <th scope="col"><?php echo ACTION; ?></th> 
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($cfields as $cfield):
                        $id = $cfield->field_id;
                        if ($cfield->field_type !== '' && $cfield->field_type !== 'cc_map' && $cfield->field_type !== 'cc_map_input') {
                            ?>
                            <tr>
                                <td><?php echo $cfield->field_title; ?></td>
                                <td><?php echo $cfield->field_type; ?></td>
                                <td><?php if ($cfield->is_active == 1) echo "Yes"; if ($cfield->is_active == 0) echo "No"; ?></td>
                                <td><a href="<?php echo admin_url("admin.php?page=field&ref=cedit&action=edit&fid=$id"); ?>" class="edit">  <img src="<?php echo TEMPLATEURL . '/images/edit.png' ?>"/></a>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                        <?php
                                        if($cfield->field_var == "cc_city" || $cfield->field_var == "cc_state" || $cfield->field_var == "cc_street" || $cfield->field_var == "cc_country" || $cfield->field_var == "cc_zipcode"){
                                        ?>
                                        <img title="<?php echo FIELD_CANNOT_DELETE; ?>" src="<?php echo TEMPLATEURL . '/images/undelete.png' ?>"/>
                                        <?php }else{ ?>
                                        <a title="Delete" href="<?php echo admin_url("admin.php?page=field&action=del&fid=$id"); ?>" class="delete"><img src="<?php echo TEMPLATEURL . '/images/delete.png' ?>"/></a>
                                        <?php } ?>
                                    </td>

                            </tr>
                        <?php } endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>                        
    <div class="clear"> </div>
</div> 