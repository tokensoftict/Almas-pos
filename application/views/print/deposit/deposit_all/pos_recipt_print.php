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
	 <br/>
	  <div class="info" align="center" style="margin-top:-26px;margin-bottom:3px;">
		 <span style="font-size:13px;;margin-bottom:10px;"><?php echo $this->settings->getSettings()['saddress_1']  ?>
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
	  </span>

	<div class="info" align="center"> 
        <span style="font-weight:bolder;font-size:15px;margin-bottom:10px; display:block;">Deposit Receipt.</span>

            <span style="float:left;font-size:13px;;margin-bottom:6px;">Receipt ID : <?php echo $payment['deposit']['reciept_id']  ?></span> <span style="float:right; font-size:13px;;margin-bottom:6px;">Date   : <?php echo $payment['deposit']['date_added'];  ?></span>
           </br>
			<span style="float:left;font-size:13px;;margin-bottom:6px;">Method : <?php echo $this->db->get_where('payment_method',array('SN'=>$payment['deposit']['payment_method']))->result_array()[0]['payment_method'];; ?></span>   <span style="float:right;font-size:13px;;margin-bottom:6px;">Sales Rep  : <?php echo $this->users->get_user_by_id($payment['deposit']['sales_rep'],1)->username; ?></span>
			<?php if(is_numeric($payment['deposit']['customer_id'])){  ?>
			<?php
			if($payment['deposit']['customer_id'] > 0){
			$cuustomer = $this->settings->getCustomer($payment['deposit']['customer_id']); 
			?>
			</br>
			<span style="float:left;font-size:12px;">Customer: <?php echo ucwords(strtolower($cuustomer['firstname']))  ?>  <?php echo ucwords(strtolower($cuustomer['lastname']));  ?></span>  <span style="float:right;font-size:12px;">Phone: <?php echo $cuustomer['phone'] ?></span>
			<?php  } 
			} ?>
			
	<br/>
	<hr/>  
    </div>
	<?php $description = $this->db->get_where('deposit',array('SN'=>$payment['deposit']['SN']))->result_array()[0]['deposit_for'] ?>
	<!--End Invoice Mid-->
	
    <div id="bot">
		<div id="table">
		<h2 align="center">Description</h2>
		<p align="center"><?php echo $description; ?></p>
		<br/>
	
		<table style="margin-top:-22px;">
							<tr class="tabletitle">
								<td class="item" width="33%"><h2>Receipt ID</h2></td>
								<td class="item" width="33%"><h2>Date</h2></td>
								<td class="item" width="33%"><h2>Amount</h2></td>
							</tr>
							<?php
							$total =0;
							foreach($payment['payment_history'] as $hit){	
							?>
							<tr class="service">
								<td class="tableitem itemtext"><?php echo $hit['reciept_id']  ?></td>
								<td class="tableitem itemtext"><?php echo $hit['date_added'];   ?></td>
								<td class="tableitem itemtext">&#8358;<?php $total+=$hit['amount']; echo number_format($hit['amount'],2);   ?></td>
							</tr>
							<?php
							}	
							?>
							<tr class="tabletitle">
								<td></td>
								<td class="Rate"><h2>Sub Total</h2></td>
								<td class="payment"><h2>&#8358;<?php echo number_format(($total),2); ?></h2></td>
								
							</tr>
							<tr class="tabletitle">
								<td></td>
								<td class="Rate"><h2> Total</h2></td>
								<td class="payment"><h2>&#8358;<?php echo number_format(($total),2); ?></h2></td>
								
							</tr>
						</table>
		<div align="center" style="font-size:13px;"><?php
			$settings = $this->db->get_where("others",array("SN"=>"1"))->row_array();
			echo $settings['footer_rec'];
		?></div>	
	</div>
	</div>
  </div>
</body>
<script>
window.onload = function(){
window.print();
//window.close()
}
</script>

</html>
