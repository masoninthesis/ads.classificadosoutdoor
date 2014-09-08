<?php
global $user_ID, $post, $posted, $wpdb, $posted_value;
$posted = unserialize(base64_decode($_POST['posted']));
$paypalamount = $_POST['total_price'];
$post_title = unserialize(base64_decode($posted['cc_title']));
$paymentOpts = cc_get_payment_optins($_REQUEST['pay_method']);
$merchantid = $paymentOpts['merchantid'];
$returnUrl = $paymentOpts['returnUrl'];
$currency_code = get_option('currency_code');
$pay_method = $_REQUEST['pay_method'];
//Payment status page
$page_id = get_option('cc_payment_stat');
$returnUrl = site_url("?page_id=$page_id&post_id=$post_id&post_title=$post_title&pay_method=$pay_method");
$notify_url = site_url("?post_id=$post_id&post_title=$post_title&pay_method=$pay_method");
$cancel_return = site_url("?post_id=$post_id&post_title=$post_title&pay_method=$pay_method");
$is_recurring = $paymentOpts['is_recurring'];//not showing
$f_period = $_POST['f_period'];
$f_cycle = $_POST['f_cycle'];
$installment = $_POST['installment'];
$s_price = $_POST['s_price'];
$s_period = $_POST['s_period'];
$s_cycle = $_POST['s_cycle'];
$billing = $_POST['billing'];
$recurring = $_POST['billing'];
$billing_time = cc_get_billingtime();

$paypalurl = '';
if(isset($_REQUEST['paypal_mode']) && $_REQUEST['paypal_mode'] == 'sandbox'){
    $paypalurl = "https://www.sandbox.paypal.com/cgi-bin/webscr";
}else{
    $paypalurl = "https://www.paypal.com/cgi-bin/webscr";
}
    
?> 
 <form name="frm_payment_method" action="<?php echo $paypalurl; ?>" method="post">
 <input type="hidden" name="business" value="<?php echo $merchantid; ?>" />
 <!-- Instant Payment Notification & Return Page Details -->
 <input type="hidden" name="notify_url" value="<?php echo $notify_url; ?>" />
 <input type="hidden" name="cancel_return" value="<?php echo $cancel_return; ?>" />
 <input type="hidden" name="return" value="<?php echo $returnUrl; ?>" />
 <input type="hidden" name="rm" value="2" />
 <!-- Configures Basic Checkout Fields -->
 <input type="hidden" name="lc" value="" />
 <input type="hidden" name="no_shipping" value="1" />
 <input type="hidden" name="no_note" value="1" />
<!-- <input type="hidden" name="custom" value="localhost" />-->
 <input type="hidden" name="currency_code" value="<?php echo $currency_code; ?>" />
 <input type="hidden" name="page_style" value="paypal" />
 <input type="hidden" name="charset" value="utf-8" />
 <input type="hidden" name="item_name" value="<?php echo $post_title; ?>" />
 <?php if($recurring == 1) { ?>
<!-- <input type="hidden" name="amount" value="<?php echo $paypalamount; ?>" />-->
<!-- <input type="hidden" name="item_number" value="2" />-->
 <input type="hidden" name="cmd" value="_xclick-subscriptions" />
 <!-- Customizes Prices, Payments & Billing Cycle -->
 <input type="hidden" name="src" value="1" />
<!-- Value for each installments -->
<?php if($billing_time[0]->rebill_time == 1){ ?>
 <input type="hidden" name="srt" value="<?php echo $installment; ?>" /> 
 <?php } ?>
<!-- <input type="hidden" name="sra" value="5" />-->
<!-- First Price -->
 <input type="hidden" name="a1" value="<?php echo $paypalamount; ?>" />
<!-- First Period -->
 <input type="hidden" name="p1" value="<?php echo $f_period; ?>" />
<!-- First Period Cycle e.g: Days,Months-->
 <input type="hidden" name="t1" value="<?php echo $f_cycle; ?>" />
<!-- Second Period Price-->
 <input type="hidden" name="a3" value="<?php echo $s_price; ?>" />
<!-- Second Period -->
 <input type="hidden" name="p3" value="<?php echo $s_period; ?>" />
<!-- Second Period Cycle -->
 <input type="hidden" name="t3" value="<?php echo $s_cycle; ?>" />
 <!-- Displays The PayPal® Image Button -->
 <?php  } else{ ?>
 <input type="hidden" value="_xclick" name="cmd"/>
 <input type="hidden" name="amount" value="<?php echo $paypalamount; ?>" />
 <?php } ?>
</form>
<script>
    setTimeout("document.frm_payment_method.submit()",30); 
</script> 