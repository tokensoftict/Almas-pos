<div class="row">
<div class="col-sm-12">
	
	 <div class="panel panel-default panel-table">
                <div class="panel-heading"><?php echo $this->db->get_where('branch',['SN'=>$branch_id])->row()->branch_name ?>'s Stock List(s)
                </div>
				<div class="panel-body">
				 <table id="table3" class="table table-striped table-hover table-fw-widget">
                    <thead>
                      <tr>
					  <th>#</th>
                      <th>Product Name</th>
					  <th>Model</th>
                      <th>Branch</th>
					  <th>Quantity</th>
                      <th>Price</th>
                      <th>Total</th>
					  <th>Manufacturer</th>
					  <th>Category</th>
                      <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
					<?php
						$num=1;
						$array = array(
										'0'=>'<span class="label label-danger">Disabled</span>',
										'1'=>'<span class="label label-success">Enabled</span>'
									);
						$total_cost_price =0;
						$total_selling_price =0;
						foreach($stocks as $stock){
                            $total_selling_price+=$stock['price'] * $stock['qty'];
					?>
					<tr>
						<td><?php echo $num ?></td>
						<td><?php echo $stock['product_name'] ?></td>
						<td><?php echo $stock['model'] ?></td>
                        <td><?php echo $stock['branch'] ?></td>
						<td><?php echo $stock['qty'] ?></td>
                        <td><?php echo number_format($stock['price'] ,2)?></td>
                        <td><?php echo number_format(($stock['price'] * $stock['qty']) ,2)?></td>
						<td><?php
							if($stock['manufacturer'] == "0"){
								echo "No Manufacturer"; 
							}else{
								$m = $this->stock->getManufacturer($stock['manufacturer']);
								if(count($m) == 0){
									echo "No Manufacturer";
								}else{
									echo $m[0]['manufacturer'];
								}
							}
						?></td>
						<td><?php 
							if($stock['category_id'] == "0"){
								echo "Un-Categorized"; 
							}else{
								$c = $this->stock->getcategory($stock['category_id']);
								if(count($c) == 0){
									echo "Un-Categorized";
								}else{
									echo $c[0]['category'];
								}
							}
						?></td>
						<td><?php echo $array[$stock['status']] ?></td>
					</tr>
					<?php
						$num++;
						}
					?>
					</tbody>
                     <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Total</th>
                            <th><?php echo number_format($total_selling_price,2) ?></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                     </tfoot>
				</table>
				</div>
	
</div>
</div>
