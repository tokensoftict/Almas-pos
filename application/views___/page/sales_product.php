<div class="row">
	<div class="col-sm-12">
		
		<div class="panel panel-default">
                <div class="panel-heading">Sales Report</div>
				<div class="tab-container">
				    <div class="tab-content">
                    <div id="home" class="tab-pane active cont">
					<div class="row">
					<div class="col-lg-12">
					<div class="panel panel-default panel-table">
                <div class="panel-heading">Daily Sales Report By Stock
                 <div class="tools">
					<form method="post" class="form-horizontal" id="change_branch" action="">
						<?php
						if(isset($_POST['from'])){
						?>
							<div class="col-md-10">
							<label>Date</label>
								<input value="<?php echo $_POST['from']  ?>" type="text" style="padding-left:10px;" data-min-view="2" data-date-format="yyyy-mm-dd" name="from" id="datetimepicker" placeholder="From" class="form-control input-sm datetimepicker" name="from required"/>							
							</div>
		
						<?php
						}else{
						?>
						<div class="col-md-10">
							<label>Date</label>
								<input value="<?php echo date('Y').'-'.date('m').'-'.date('d')  ?>" type="text" style="padding-left:10px;" data-min-view="2" data-date-format="yyyy-mm-dd" name="from" id="datetimepicker" placeholder="From" class="form-control input-sm datetimepicker" name="from required"/>							
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
					<th  > Receipt ID </th>
					<th  > Product Name </th>
					<th > Customer </th>
					<th > Quantity </th>
				    <th > Total Amount </th>
				    <th > Date </th>					
					<th > Sales Rep </th>
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
								}else{
									
								$from =date('Y').'-'.date('m').'-'.date('d') ;
								$to = date('Y').'-'.date('m').'-'.date('t');
								if(!isset($customers[0])){
								$customer_id = 0;	
								}else{
								$customer_id = $customers[0]['SN'];
								}
								}
					
					
						$ins = $this->stock->getSalesRange($from,$from,array("status"=>"COMPLETE"));
						$alltotal =0;
						$alldiscount =0;
						foreach($ins as $in){
						if(is_numeric($in['customer'])){
						$customer = $this->settings->getCustomer($in['customer']);
						$customer_name = $customer['firstname'].' '.$customer['lastname'];
						}else{
							$customer = $in['customer'];
						}
						

						$user =$this->users->get_user_by_id($in['user_id'],1);
						$products = json_decode($in['items'],true);
						foreach($products  as $product){
						$alltotal +=$product['item_price']*$product['item_qty'];
					?>
						<tr>
							<td><?php echo $in['reciept_id'] ?></td>
							<td><?php echo $this->stock->getStock($product['id'])[0]['product_name']; ?></td>
							<td><?php echo $customer_name ?></td>
							<td><?php echo $product['item_qty'] ?></td>
							<td><?php echo number_format(($product['item_price']*$product['item_qty']),2); ?></td>
							<td><?php echo $in['date'] ?></td>
							
							<td><?php echo $user->username; ?></td>
							<td>
								<div class="dropdown">
												  <button class="btn btn-sm btn-success dropdown-toggle" type="button" data-toggle="dropdown">Action
												  <span class="caret"></span></button>
												  <ul class="dropdown-menu">
												  <li><a href="<?php  echo base_url('dashboard/view_sales/'.$in['reciept_id']) ?>">View</a></li>
													<li><a onclick="window.open($(this).attr('href'),'width=400;hieght=400;','400','400'); return false;" href="<?php  echo base_url('dashboard/print_recipt/'.$in['reciept_id']) ?>/Thermal">Thermal Receipt</a></li>
													<li><a onclick="window.open($(this).attr('href'),'width=400;hieght=400;','400','400'); return false;" href="<?php  echo base_url('dashboard/print_recipt/'.$in['reciept_id']) ?>/Medium">Medium Receipt</a></li>
													<li><a onclick="window.open($(this).attr('href'),'width=400;hieght=400;','400','400'); return false;" href="<?php  echo base_url('dashboard/print_recipt/'.$in['reciept_id']) ?>/Big">Big Receipt</a></li>
												  </ul>
												</div>
							</td>
						</tr>
					<?php
						
						}
						}
					?>
				</tbody>
				<tfoot>
					<tr>
					<th></th>
					<th></th>
					<th></th> 
				    <th></th>
					<th>Total : <?php echo number_format($alltotal,2) ?></th>
				    <th></th>
					<th></th>
					<th></th>
				  </tr>
				</tfoot>
			</table>
				</div>
	
</div>
				  
				  
				</div>
		</div>
	</div>
</div>