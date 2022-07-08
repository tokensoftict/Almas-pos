<div class="row">
<div class="col-sm-12">
	
	 <div class="panel panel-default panel-table">
                <div class="panel-heading">Vat Payment Report
                 <div class="tools">
					<form method="post" class="form-horizontal" id="change_branch" action="">
									<?php
		?>
						<?php
						if(isset($_POST['to']) && isset($_POST['from'])){
						?>
							<div class="col-md-5">
							<label>From</label>
								<input value="<?php echo $_POST['from']  ?>" type="text" style="padding-left:10px;" data-min-view="2" data-date-format="yyyy-mm-dd" name="from" id="datetimepicker" placeholder="From" class="form-control input-sm datetimepicker" name="from required"/>							
							</div>
							<div class="col-md-5">
								<label>To</label>
								<input type="text" value="<?php echo $_POST['to']  ?>" style="padding-left:10px;" data-min-view="2" data-date-format="yyyy-mm-dd" name="to" id="datetimepicker" placeholder="To" class="form-control input-sm datetimepicker" name="to" required/>
							</div>
						<?php
						}else{
						?>
						<div class="col-md-5">
							<label>From</label>
								<input value="<?php echo date('Y').'-'.date('m').'-'.date('01')  ?>" type="text" style="padding-left:10px;" data-min-view="2" data-date-format="yyyy-mm-dd" name="from" id="datetimepicker" placeholder="From" class="form-control input-sm datetimepicker" name="from required"/>							
							</div>
							<div class="col-md-5">
								<label>To</label>
								<input type="text" value="<?php echo date('Y').'-'.date('m').'-'.date('t')  ?>" style="padding-left:10px;" data-min-view="2" data-date-format="yyyy-mm-dd" name="to" id="datetimepicker" placeholder="To" class="form-control input-sm datetimepicker" name="to" required/>
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
				 <table id="table3" class="table table-striped table-hover table-fw-widget">
				<thead>
				  <tr>
					 <th > Receipt ID </th>
					 <th> Customer </th>
				    <th> Date </th>
					
					<th>Cost Price</th>
					<th>Selling Price</th>
					<th>Profit</th>
					<th>VAT</th>
					
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
								}else{
								$from =date('Y').'-'.date('m').'-'.date('01') ;
								$to = date('Y').'-'.date('m').'-'.date('t');
								}
					
					
						$ins = $this->stock->getSalesRange($from,$to,array("status"=>"COMPLETE"));
						$alltotal =0;
						$alldiscount =0;
						$allvat = 0;
						$allscharge = 0;
						$alltotalpaid =0;
						$total_profit = 0;
						$cost =0;
						foreach($ins as $in){
						if(is_numeric($in['customer'])){
						$customer = $this->settings->getCustomer($in['customer']);
						$customer_name = $customer['firstname'].' '.$customer['lastname'];
						}else{
							$customer_name = $in['customer'];
						}
						$items = json_decode($in['items'],true);
						$c_cost =0;
						foreach($items as $item){
							$c_cost += $item['total_cost_price'];
						}
						$cost += $c_cost;
						$alltotal +=$in['total_amount'];
						$alldiscount +=$in['discount'];
							$allvat +=$in['vat_amount'];
						$allscharge+=$in['s_charge_amt'];
						$alltotalpaid += $in['total_amount_paid']; 
						$total_profit+=$in['total_profit'];
						$user =$this->users->get_user_by_id($in['user_id'],1);
					?>
						<tr>
							<td><?php echo $in['reciept_id'] ?></td>
							<td><?php echo $customer_name; ?></td>
							<td><?php echo $in['date'] ?></td>
							<td><?php echo number_format($c_cost,2) ?></td>
							<td><?php echo number_format($in['total_amount_paid'],2) ?></td>
							<td><?php echo number_format($in['total_profit'],2) ?></td>
							<td><?php echo number_format($in['vat_amount'],2) ?></td>
							
						</tr>
					<?php
						}
					?>
				</tbody>
				<tfoot>
				<tr>
				    <th></th>
					<th></th>
					<th></th>
					<th>Total Cost : <?php echo number_format($cost,2) ?></th>
				    <th>Total Selling : <?php echo number_format($alltotalpaid,2) ?></th>
					<th>Total Profit :  <?php echo number_format($total_profit,2) ?></th>
					<th>Total Vat : <?php echo number_format($allvat,2) ?></th>
					
				  </tr>
				</tfoot>
			</table>
				</div>
	
</div>
</div>