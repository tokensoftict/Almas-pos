<div class="row">
<div class="col-sm-12">
	
	 <div class="panel panel-default panel-table">
                <div class="panel-heading">Sales List(s)
                </div>
<div class="panel-body">
 <table id="table3" class="table table-striped table-hover table-fw-widget" style="font-size:12px;">
				<thead>
				  <tr>
					 <th  > Receipt ID </th>
					  <th > Customer </th>
					   <th > Department </th>
				    <th > Sub Total </th>
				    <th > Discount </th>
				    <th > Date </th>
					<th > Sales Rep </th>
					<th>VAT</th>
					<th>S.Charge</th>
					<th>Total Paid</th>
					<th>Action</th>
				  </tr>
				</thead>
				<tbody>
					<?php
					$status = array(
								"1"=>'<div class="btn-group project-list-ad-bl"><button class="btn btn-success btn-xs">Paid</button></div>',
								"0"=>'<div class="btn-group project-list-ad-bl"><button class="btn btn-white btn-xs">Pending</button></div>',
								);
						$filter = array('date'=>date('Y-m-d'),'status'=>"COMPLETE");
						
						if($this->users->get_user_by_username($this->session->userdata("username"))->role=="Sales Representative"){
							$filter['user_id'] = $this->users->get_user_by_username($this->session->userdata("username"))->id;
						}else if($this->users->get_user_by_username($this->session->userdata("username"))->department!="Administrator"){
							$filter['department'] = $this->users->get_user_by_username($this->session->userdata("username"))->department;
						}
						$ins = $this->stock->getSales($filter);
						$alltotal =0;
						$alldiscount =0;
						$allvat = 0;
						$allscharge = 0;
						$alltotalpaid =0;
						foreach($ins as $in){
						if(is_numeric($in['customer'])){
						$customer = $this->settings->getCustomer($in['customer']);
						$customer = $customer['firstname'].' '.$customer['lastname'];
						}else{
							$customer = $in['customer'];
						}
						$alltotal +=$in['total_amount'];
						$alldiscount +=$in['discount'];
						$allvat +=$in['vat_amount'];
						$allscharge+=$in['s_charge_amt'];
						$alltotalpaid += $in['total_amount_paid']; 
						$user =$this->users->get_user_by_id($in['user_id'],1);
					?>
						<tr>
							<td><?php echo $in['reciept_id'] ?></td>
							<td><?php echo $customer ?></td>
							<td><?php echo $in['department'] ?></td>
							<td><?php echo number_format($in['total_amount'],2); ?></td>
							<td><?php echo number_format($in['discount'],2); ?></td>
							<td><?php echo $in['date'] ?></td>
							<td><?php echo $user->username; ?></td>
							<td><?php echo number_format($in['vat_amount'],2) ?></td>
							<td><?php echo number_format($in['s_charge_amt'],2) ?></td>
							
							<td><?php echo number_format($in['total_amount_paid'],2) ?></td>
							<?php if($in['payment_type']=="Direct"){  ?>
							<td>
							<div class="dropdown">
												  <button class="btn btn-sm btn-success dropdown-toggle" type="button" data-toggle="dropdown">Action
												  <span class="caret"></span></button>
												<ul class="dropdown-menu">
													<li><a href="<?php echo base_url('dashboard/view_sales/'.$in['reciept_id']) ?>">View</a></li>
													<li><a onclick="window.open($(this).attr('href'),'width=400;hieght=400;','400','400'); return false;" href="<?php  echo base_url('dashboard/print_recipt/'.$in['reciept_id']) ?>/thermal">Thermal Receipt</a></li>
													
													<li><a onclick="window.open($(this).attr('href'),'width=400;hieght=400;','400','400'); return false;" href="<?php  echo base_url('dashboard/print_recipt/'.$in['reciept_id']) ?>/Big">Big Receipt</a></li>
												  </ul>
												</div>
							</td>
							<?php  }else{ ?>
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
							<?php  } ?>
							
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
					<th></th>
				
				  </tr>
				</tfoot>
			</table>
</div>	</div>	</div>	</div>			