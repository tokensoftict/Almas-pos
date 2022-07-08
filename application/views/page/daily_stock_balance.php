<div class="row">
<div class="col-sm-12">
					
				<div class="panel panel-default">
					<div class="panel-heading">
					Daily Stock Balancing
					<div class="tools" style="margin-right:80px;">
					<form action="" method="post">
								<div class="row">
								<?php
								if(!isset($_POST['from'])){
								?>
									<div class="col-md-9">
										<div><b>Date :</b> <input required placeholder="Received Date" data-min-view="2" data-date-format="yyyy-mm-dd"  class="datetimepicker date form-control input-sm" value="<?php echo date('Y-m-d') ?>" type="text" name="from">
                                           </div>
									</div>
								<?php
								}else{
								?>
							<div class="col-md-9">
										<div><b>Date :</b> <input required placeholder="Received Date" data-min-view="2" data-date-format="yyyy-mm-dd"  class="datetimepicker date form-control input-sm" value="<?php echo $_POST['from'] ?>" type="text" name="from">
                                           </div>
									</div>
							<?php
								}
							?>
									<div class="col-md-2">
										 <button class="btn  btn-primary" type="submit"> Go</button>
									</div>
									</div>
				 	</form>
					</div>
					</div>
					<div class="panel-body">
						<div class="row">
							
									<table class="table  table-striped" id="table3">
										<thead>
											<th>#</th>
											<th>Product Name</th>
											<th >Date</th>
											<th >Stock In</th>
											<!--<th class="text-center">Stock Out</th>-->
											<th>Stock Sold</th>
											<th >Stock Balance</th>
											<th >Move By</th>
										</thead>
										<tbody id="material_holder">
											<?php
												if(isset($_POST['from'])){
													$from = $_POST['from'];
												}else{
													$from = date("Y-m-d");
												}
												$this->db->from("tbl_transfer_recieved");
												$this->db->where("date_",$from);
												$bincards = $this->db->get();	
												$tt_in =0;
												$tt_out =0;
												$tt_tt =0;
												$sold =0;
												$num = 1;
												foreach($bincards->result_array() as $bincard){
												$user = $this->db->get_where("users",array("id"=>$bincard['user']))->row_array();
												$product = $this->stock->getStock($bincard['stock_id'])[0];
											?>
											<tr>
												<td><?php echo $num; ?></td>
												<td ><?php echo $product['product_name'] ?></td>
												<td ><?php echo  $bincard['date_'] ?></td>
												<td ><?php echo ($bincard['amt_in']=="" ? '' : $bincard['amt_in']); $tt_in+=$bincard['amt_in'] ?></td>
												<!--<td class="text-center"><?php echo ($bincard['amt_out']=="" ? '' : $bincard['amt_out']); $tt_out +=$bincard['amt_out']?></td>-->
												<td ><?php echo ($bincard['sold']=="" ? '' : $bincard['sold']); $sold +=$bincard['sold']?></td>
												<td ><?php echo $bincard['balance']; ?></td>
												<td ><?php echo $user['firstname'] ?> <?php echo $user['lastname'] ?></td>
												
											</tr>
											<?php
												$num++;
												}
											?>
										</tbody>
										<!--<tfoot>
											<tr>
												<td></td>
												<th class="text-center"><?php echo $tt_in; ?></th>
												<th class="text-center"><?php echo $tt_out; ?></th>
											
												<th class="text-center"><?php echo $sold; ?></th>
												<td></td>
												<td></td>
												
											</tr>
										</tfoot>-->
									</table>
						</div>
						
						
						
					</div>

			
</div>

</div>