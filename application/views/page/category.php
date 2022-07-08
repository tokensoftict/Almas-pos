<div class="row">
	<div class="col-sm-12">
		
		<div class="panel panel-default">
                <div class="panel-heading">Application Settings</div>
                <div class="tab-container">
				  <div class="tab-content">
                    <div id="home" class="tab-pane active cont">
					<div class="row">
						<div class="col-lg-6">
							<div class="panel">
								<div class="panel panel-heading">Product Category List(s)</div>
								<div class="panel-body">
									<table class="table table-bordered">
										<thead>
											<tr>
												<th>SN</th>
												<th>Category Name</th>
												<th>Tax Status</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$num = 1;
											$manufac = $this->stock->getCategories();
											$tax = array("0"=>"Non Taxable","1"=>"Taxable");
											foreach($manufac as $manu){
												
											?>
											<tr>
												<td><?php echo $num; ?></td>
												<td><?php echo $manu['category'] ?></td>
												<td><?php echo $tax[$manu['tax']]; ?></td>
												<td><a href="<?php echo base_url('dashboard/category/'.$manu['SN'].'?del=true') ?>" class="btn btn-danger">Delete</a></td>
											</tr>
											<?php
											$num++;
											}
											?>
										</tbody>
									</table>
									</div>
								</div>
							</div>
						
						<div class="col-lg-6">
						<div class="panel">
								<div class="panel panel-heading">Add New Category</div>
								<div class="panel-body">
						<form action=""  method="post">
						
							<div class="form-group">
							  <label for="manufacturer" class="col-sm-12 control-label">Category</label>
							  <div class="col-sm-12">
								<input id="manufacturer" type="text" required name="category" placeholder="Category" class="form-control input-sm">
							  </div>
							</div>
							<br/><br/><br/><br/>
							<div class="form-group">
							  <label for="manufacturer" class="col-sm-12 control-label">Taxable</label>
							  <div class="col-sm-12">
								<select class="form-control input-sm" name="tax">
									<option value="0">No</option>
									<option value="1">Yes</option>
								</select>
							  </div>
							</div>
							<div class="col-sm-12">
								<br/>
								<button class="btn btn-primary" type="submit">Add Category</button>
							</div>

						</form>
						
						</div>
							</div>
						</div>
					</div>
                  </div>
                </div>
              </div>
		
	</div>
