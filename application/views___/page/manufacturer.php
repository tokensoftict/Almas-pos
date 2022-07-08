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
								<div class="panel panel-heading">Manufacturer List(s)</div>
								<div class="panel-body">
									<table class="table table-bordered">
										<thead>
											<tr>
												<th>SN</th>
												<th>Manufacturer</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$num = 1;
											$manufac = $this->stock->getManufacturers();
											foreach($manufac as $manu){
											?>
											<tr>
												<td><?php echo $num; ?></td>
												<td><?php echo $manu['manufacturer'] ?></td>
												<td><a href="<?php echo base_url('dashboard/manufacturer/'.$manu['SN'].'?del=true') ?>" class="btn btn-danger">Delete</a></td>
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
								<div class="panel panel-heading">Add New Manufacturer</div>
								<div class="panel-body">
						<form action=""  method="post">
						
							<div class="form-group">
							  <label for="manufacturer" class="col-sm-12 control-label">Manufacturer</label>
							  <div class="col-sm-12">
								<input id="manufacturer" type="text" required name="manufacturer" placeholder="Manufacturer" class="form-control input-sm">
							  </div>
							</div>
							
							<div class="col-sm-12">
								<br/>
								<button class="btn btn-primary" type="submit">Add Manufacturer</button>
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
