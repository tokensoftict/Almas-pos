<div class="row">
	<div class="col-sm-12">
		
		<div class="panel panel-default">
                <div class="panel-heading">Sales Report</div>
				<div class="tab-container">
                  <ul class="nav nav-tabs">
                    <li><a href="<?php  echo base_url('dashboard/sales_report') ?>">General Report</a></li>
					<li><a href="<?php  echo base_url('dashboard/report_customer') ?>">Rep By Customer</a></li>
					<li><a href="<?php  echo base_url('dashboard/report_sales_rep') ?>">Rep By Sales Rep</a></li>
					<li  class="active"><a href="<?php  echo base_url('dashboard/report_payment_method') ?>">Rep Payment Method</a></li>
					<li><a href="<?php  echo base_url('dashboard/bulk_report') ?>">Bulk Rep By Product/Stock</a></li>
                  </ul>
				    <div class="tab-content">
                    <div id="home" class="tab-pane active cont">
					<div class="row">
					<div class="col-lg-12">
					<div class="panel panel-default panel-table">
                <div class="panel-heading">Report By Payment Method
                 <div class="tools">
					<form method="post" class="form-horizontal" id="change_branch" action="">
						<?php
						if(isset($_POST['to']) && isset($_POST['from'])){
						?>
							<div class="col-md-3">
							<label>From</label>
								<input value="<?php echo $_POST['from']  ?>" type="text" style="padding-left:10px;" data-min-view="2" data-date-format="yyyy-mm-dd" name="from" id="datetimepicker" placeholder="From" class="form-control input-sm datetimepicker" name="from required"/>							
							</div>
							<div class="col-md-3">
								<label>To</label>
								<input type="text" value="<?php echo $_POST['to']  ?>" style="padding-left:10px;" data-min-view="2" data-date-format="yyyy-mm-dd" name="to" id="datetimepicker" placeholder="To" class="form-control input-sm datetimepicker" name="to" required/>
							</div>
							<div class="col-md-4">
								<label>Payment Method</label>
								<select name="customer_id" class="form-control select2_demo_2 input-sm" id="customer_id">
								<option class="bs-title-option" value="">Select</option>
								<?php
									$stocks = $this->db->get("payment_method")->result_array();
									foreach($stocks as $stock){
								?>
									<option <?php echo ($stock['SN']==$_POST['customer_id'] ? 'selected' : '') ?> value="<?php echo $stock['SN'] ?>"><?php echo $stock['payment_method'] ?></option>
								<?php
									}
								?>
								</select>
							</div>
						<?php
						}else{
						?>
						<div class="col-md-3">
							<label>From</label>
								<input value="<?php echo date('Y').'-'.date('m').'-'.date('01')  ?>" type="text" style="padding-left:10px;" data-min-view="2" data-date-format="yyyy-mm-dd" name="from" id="datetimepicker" placeholder="From" class="form-control input-sm datetimepicker" name="from required"/>							
							</div>
							<div class="col-md-3">
								<label>To</label>
								<input type="text" value="<?php echo date('Y').'-'.date('m').'-'.date('t')  ?>" style="padding-left:10px;" data-min-view="2" data-date-format="yyyy-mm-dd" name="to" id="datetimepicker" placeholder="To" class="form-control input-sm datetimepicker" name="to" required/>
							</div>
							<div class="col-md-4">
								<label>Payment Method</label>
								<select name="customer_id" class="form-control select2_demo_2 input-sm" id="customer_id">
								<?php
									$stocks = $this->db->get("payment_method")->result_array();
									foreach($stocks as $stock){
								?>
									<option  value="<?php echo $stock['SN'] ?>"><?php echo $stock['payment_method'] ?></option>
								<?php
									}
								?>
								</select>
							</div>
								
						<?php
						}
						?>
							<div class="col-md-1"><label style="visibility: hidden;">To</label>
								<button class="btn btn-primary">Go</button>
								
							</div>
							
						</form>
						
				</div>
                </div>
				<div class="panel-body">
				<br/><br/><br/>
				<table id="table3" class="table table-striped table-hover">
				<thead>
				 <tr>
					<th  > Receipt ID </th>
					<th > Customer </th>
				    <th > Sub Total </th>
				    <th > Discount </th>
				    <th > Date </th>
					<th > Sales Rep </th>
					<th>VAT</th>
					<th>S.Charge</th>
					<th>Total Paid</th>
					<th></th>
					<th>Action</th>
				  </tr>
				</thead>
				<tbody>
					<?php
					$status = array(
								"1"=>'<div class="btn-group project-list-ad-bl"><button class="btn btn-success btn-xs">Paid</button></div>',
								"0"=>'<div class="btn-group project-list-ad-bl"><button class="btn btn-white btn-xs">Pending</button></div>',
								);
							if(isset($_POST['from'])){
								$from = $_POST['from'];
								$to = $_POST['to'];
								$customer_id = $_POST['customer_id'];
								}else{
									
								$from =date('Y').'-'.date('m').'-'.date('01') ;
								$to = date('Y').'-'.date('m').'-'.date('t');
								if(!isset($stocks[0])){
								$customer_id = $stocks[0]['SN'];
								}else{
								$customer_id = $stocks[0]['SN'];
								}
								}
					
					
						$ins = $this->stock->getSalesRange($from,$to,array('payment_method'=>$customer_id,"status"=>"COMPLETE"));
							$alltotal =0;
						$alldiscount =0;
						$allvat = 0;
						$allscharge = 0;
						$alltotalpaid =0;
						foreach($ins as $in){
						if(is_numeric($in['customer'])){
						$customer = $this->settings->getCustomer($in['customer']);
						$customer_name = $customer['firstname'].' '.$customer['lastname'];
						}else{
							$customer = $in['customer'];
						}
							$alltotal +=$in['total_amount'];
						$alldiscount +=$in['discount'];
							$allvat +=$in['vat_amount'];
						$allscharge+=$in['s_charge_amt'];
						$alltotalpaid += $in['total_amount_paid']; 
						$user =$this->users->get_user_by_id($in['user_id'],1);
						$in['payment_method'] = $this->db->get_where('payment_method',array('SN'=>$in['payment_method']))->result_array()[0]['payment_method'];
					?>
						<tr>
							<td><?php echo $in['reciept_id'] ?></td>
							<td><?php echo $customer_name; ?></td>
							<td><?php echo number_format($in['total_amount'],2); ?></td>
							<td><?php echo number_format($in['discount'],2); ?></td>
							<td><?php echo $in['date'] ?></td>
							<td><?php echo $user->username; ?></td>
							<td><?php echo number_format($in['vat_amount'],2) ?></td>
							<td><?php echo number_format($in['s_charge_amt'],2) ?></td>
							
							<td><?php echo number_format($in['total_amount_paid'],2) ?></td>
							<td>
							<td>
									<div class="dropdown">
												  <button class="btn btn-sm btn-success dropdown-toggle" type="button" data-toggle="dropdown">Action
												  <span class="caret"></span></button>
												  <ul class="dropdown-menu">
												  <li><a href="<?php  echo base_url('dashboard/view_sales/'.$in['reciept_id']) ?>">View</a></li>
													<li><a onclick="window.open($(this).attr('href'),'width=400;hieght=400;','400','400'); return false;" href="<?php  echo base_url('dashboard/print_recipt/'.$in['reciept_id']) ?>/Thermal">Thermal Receipt</a></li>
													
													<li><a onclick="window.open($(this).attr('href'),'width=400;hieght=400;','400','400'); return false;" href="<?php  echo base_url('dashboard/print_recipt/'.$in['reciept_id']) ?>/Big">Big Receipt</a></li>
												  </ul>
												</div>
							</td>
							
						</tr>
					<?php
						}
					?>
				</tbody>
				<tfoot>
				<tr>
					<th></th>
				    <th></th>
					<th>Total : <?php echo number_format($alltotal,2) ?></th>
				   
				    <th>Discount : <?php echo number_format($alldiscount,2) ?></th>
					<th></th> <th></th>
					<th>Total VAT : <?php echo number_format($allvat,2) ?></th>
					<th>Total S.Charge : <?php echo number_format($allscharge,2) ?></th>
					<th>Total Paid : <?php echo number_format($alltotalpaid,2) ?></th>
					<th></th><th></th>
				
				  </tr>
				</tfoot>
			</table>
				</div>
	
</div>
				  
				  
				</div>
		</div>
	</div>
</div>