<!DOCTYPE html><html class=''>
<head>
<link rel="stylesheet" href="<?php echo base_url('application/posfont/stylesheet.css'); ?>"/>
<style class="cp-pen-styles">
#invoice-POS {
  box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
  padding: 2mm;
  margin: 0 auto;
  width: 76mm;
  background: #FFF;
  font-family: 'MyWebFont', Arial, sans-serif;
}
#invoice-POS ::selection {
  background: #f31544;
  color: #000;
}
#invoice-POS ::moz-selection {
  background: #f31544;
  color: #000;
  font-family: 'MyWebFont', Arial, sans-serif;
}
#invoice-POS h1 {
  font-size: 1.0em;
  color: #000;
  font-family: 'MyWebFont', Arial, sans-serif;
}
#invoice-POS h2 {
  font-size: 0.9em;
  font-family: 'MyWebFont', Arial, sans-serif;
  padding:0px;
  margin-top:0px;
  margin-bottom:6px;
}
#invoice-POS h3 {
  font-size: 1.2em;
  font-weight: 300;
  line-height: 2em;
  font-family: 'MyWebFont', Arial, sans-serif;
}
#invoice-POS p {
  font-size: 1.2em;
  color:#000;
  line-height: 1.2em;
  font-family: 'MyWebFont', Arial, sans-serif;
}
#invoice-POS #top, #invoice-POS #mid, #invoice-POS #bot {
  /* Targets all id with 'col-' */

  font-family: 'MyWebFont', Arial, sans-serif;
}
#invoice-POS #top {

  font-family: 'MyWebFont', Arial, sans-serif;
}
#invoice-POS #mid {
  min-height: 80px;
  font-family: 'MyWebFont', Arial, sans-serif;
}
#invoice-POS #bot {
  min-height: 50px;
  font-family: 'MyWebFont', Arial, sans-serif;
}
#invoice-POS #top .logo {

  width: 60px;
  background: url(images/head.png) no-repeat;
  background-size: 60px 60px;
  font-family: 'MyWebFont', Arial, sans-serif;
}
#invoice-POS .clientlogo {
  float: left;
  height: 60px;
  width: 60px;
  background: url(images/head.png) no-repeat;
  background-size: 60px 60px;
  border-radius: 50px;
  font-family: 'MyWebFont', Arial, sans-serif;
}
#invoice-POS .info {
  display: block;
  margin-left: 0;
  font-family: 'MyWebFont', Arial, sans-serif;
}
#invoice-POS .title {
  float: right;
  font-family: 'MyWebFont', Arial, sans-serif;
}
#invoice-POS .title p {
  text-align: right;
  font-family: 'MyWebFont', Arial, sans-serif;
}
#invoice-POS table {
  width: 100%;
  border-collapse: collapse;
  font-family: 'MyWebFont', Arial, sans-serif;
}
#invoice-POS .tabletitle {
  font-size: .90em;
  font-family: 'MyWebFont', Arial, sans-serif;
  margin:0px;
  padding:0px;
}
#invoice-POS .service {
  font-family: 'MyWebFont', Arial, sans-serif;
 
}
#invoice-POS .item {
  width: 24mm;
  font-family: 'MyWebFont', Arial, sans-serif;
}
#invoice-POS .itemtext {
  font-size: 0.80em;
  font-weight: 200;
  text-align:left;
  padding-bottom:10px;
  font-family: 'MyWebFont', Arial, sans-serif;
}
#invoice-POS #legalcopy {
  margin-top: 5mm;
  font-family: 'MyWebFont', Arial, sans-serif;
}
</style></head><body>

  <div id="invoice-POS">
     <div class="logo"><center><img style="width:58mm;" src="<?php echo $this->settings->getSettings()['slogo'] ?>" alt="" width="60" /></center></div>
     <center id="top">
	  <div class="info">
	<p align="left">
		<br/><?php echo $this->settings->getSettings()['saddress_1']  ?>
		<?php
		if(!empty($this->settings->getSettings()['saddress_2'])){
		?><br/><?php echo $this->settings->getSettings()['saddress_2']  ?>
		<?php
		}
		?>
		<?php
		if(!empty($this->settings->getSettings()['scontact_no'])){
		?><br/><?php echo $this->settings->getSettings()['scontact_no']  ?>
		<?php
		}
		?>
	  </p>
	  </div>
	  </center>
	  <center id="top">
	  <div class="info"> 
        <h2>Deposit Receipt.</h2>
      <div class="info" >
        <h2>Transaction Information</h2>
        <p align="left" > 
            Receipt ID : <?php echo $payment['reciept_id']  ?></br>
            Date   : <?php echo $payment['date_added']  ?></br>
            Sales Rep  : <?php echo $this->users->get_user_by_id($payment['sales_rep'],1)->username; ?></br>
			<?php if(is_numeric($payment['customer_id'])){  ?>
			<?php  $cuustomer = $this->settings->getCustomer($payment['customer_id']); ?>
			Payment Method : <?php echo $this->db->get_where('payment_method',array('SN'=>$payment['payment_method']))->result_array()[0]['payment_method']; ?> <br/>
			Customer Name :  <?php echo $cuustomer['firstname']  ?>  <?php echo $cuustomer['lastname']  ?></br>
			Address : <?php echo $cuustomer['address'] ?><br>
    		Phone No : <?php echo $cuustomer['phone'] ?><br>
			<?php  } ?>
		 </p>
      </div>
    </div>
	</center>
	<?php $description = $this->db->get_where('deposit',array('SN'=>$payment['deposit_SN']))->result_array()[0]['deposit_for'] ?>
	<!--End Invoice Mid-->
    <div id="bot">
		<div id="table">
		<h3 align="center">Description</h3>
		<p align="center"><?php echo $description; ?></p>					
	</div>
	</div>
  </div>
</body>
<script>
window.onload = function(){
//window.print();
}
</script>

</html>
