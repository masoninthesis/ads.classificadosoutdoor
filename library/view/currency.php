<div class="group" id="of-option-currency">
    <div class="section section-text ">
    <h3 class="heading"><?php echo SET_DFLT_SETTING; ?></h3>
    <div class="option">
        <div class="controls">
            <select name="currency_symbol">
                <?php               
                $symbol = get_option('currency_code');
                foreach($currencys as $curren) { 
                    if($curren->c_code != '') {?>
                <option <?php if($symbol == $curren->c_code) echo 'selected="selected"' ?> value="<?php echo $curren->c_code; ?>"><?php echo $curren->c_name; ?>&nbsp;(<?php echo $curren->c_code; ?>)</option>
                <?php } } ?>
            </select>
        </div>
        <div class="explain"><?php echo SLT_CURRENTY_CODE; ?></div>
        <div class="clear"> </div>
    </div>
</div>
<div class="section section-text ">
    <h3 class="heading"><?php echo SPRT_CURRENCY_LST; ?></h3>
    <div class="option">
        <table id="tblspacer" class="widefat fixed">
            <thead>
                <tr>
                    <th style="width:30px;" scope="col">ID</th>
                    <th scope="col"><?php echo CURRENCY; ?></th>
                    <th scope="col"><?php echo CODE; ?></th>
                    <th scope="col"><?php echo SYMBOL; ?></th> 
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($currencys as $currency):
                    if($currency->c_code != ''){
                    ?>
                    <tr>
                        <td><?php echo $currency->c_id; ?></td>
                        <td><?php echo $currency->c_name; ?></td>
                        <td><?php echo $currency->c_code; ?></td>
                       <td><?php echo $currency->c_symbol; ?></td>
                       
                    </tr>
                <?php } endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</div>    