<div class="row">
	<div class="col-sm-12">
		<form action="<?php  echo base_url('dashboard/save_new_stock')  ?>" enctype="multipart/form-data" onsubmit="$('#sub').attr('disabled','disabled')" id="myform" method="post" >
		<div class="panel">
			 <div class="panel-heading">Add New Stock
                 <div class="tools"><button type="submit" id="sub" class="btn btn-sm btn-primary" ><i class="mdi mdi-save"></i> Save</button></div>
              </div>
			  <div class="tab-container">
                  <ul class="nav nav-tabs">
                    <li class="active"><a href="#home" data-toggle="tab">Data</a></li>
					<li><a href="#scan" data-toggle="tab">Scan Bar Code</a></li>
                    <li><a href="#profile" data-toggle="tab">General Description</a></li>
                  </ul>
                  <div class="tab-content">
                    <div id="home" class="tab-pane active cont">
					<div class="form-horizontal">
					
					<div class="form-group xs-mt-10">
                      <label for="product_name" class="col-sm-2 control-label">Product Name</label>
                      <div class="col-sm-10">
                        <input id="product_name" name="product_name" type="text" required   placeholder="Product Name" class="form-control input-sm">
                      </div>
                    </div>
					
					<div class="form-group xs-mt-10">
                      <label for="price" class="col-sm-2 control-label">Date Available</label>
                      <div class="col-sm-10">
                        <input id="date_available" type="text" readonly value="<?php echo date('Y-m-d'); ?>" name="date_available" data-min-view="2" data-date-format="yyyy-mm-dd"  placeholder="Date Available" class="form-control input-sm">
                      </div>
                    </div>
					<div class="form-group xs-mt-10">
                      <label for="model" class="col-sm-2 control-label">Model</label>
                      <div class="col-sm-10">
                        <input id="model" name="model" type="text"  placeholder="Model" class="form-control input-sm">
                      </div>
                    </div>
					<div class="form-group xs-mt-10">
                      <label for="model" class="col-sm-2 control-label">Product Expiry Date</label>
                      <div class="col-sm-10">
                        <input id="datetimepicker"  data-min-view="2" data-date-format="yyyy-mm-dd" name="expired_date" type="text"  placeholder="Product Expiry Date" class="date form-control input-sm">
                      </div>
                    </div>
                        <?php
                        $d = $this->stock->getUserDepartment();
                        if($d =="Administrator") {
                            ?>
                            <div class="form-group xs-mt-10">
                                <label for="model" class="col-sm-2 control-label">Department</label>
                                <div class="col-sm-10">
                                    <?php
                                    $dpts = $this->db->get_where("department",array("type"=>"Sales"))->result_array();
                                    foreach($dpts as $dpt){
                                        $department[$dpt['department']] = array('Sales Representative','Administrator');
                                    }

                                    ?>
                                    <select required class="form-control input-sm" id="department__" name="department">
                                       <!-- <option value="">-Select Department-</option>-->
                                        <?php
                                        foreach($dpts as $key=>$dpt){
                                            ?>
                                            <option value="<?php echo $dpt['department'] ?>"><?php echo $dpt['department'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <?php
                        }else {
                            ?>
                            <input type="hidden" id="department__" name="department" value="<?php echo $this->stock->getUserDepartment()  ?>"/>
                            <?php
                        }
                        ?>
					<div class="form-group xs-mt-10">
                      <label for="model" class="col-sm-2 control-label">Product Code(Optional)</label>
                      <div class="col-sm-10">
                        <input id="model" name="product_code" type="text"  placeholder="Product Code" class="form-control input-sm">
                      </div>
                    </div>
					<div class="form-group xs-mt-10">
                      <label for="price" class="col-sm-2 control-label">Cost Price</label>
                      <div class="col-sm-10">
                        <input id="price" type="number" onkeyup="$('.selling_price').attr('min',parseFloat($(this).val()))" required name="cost_price" step=".00001"  placeholder="Cost Price" class="form-control input-sm">
                      </div>
                    </div>
					<div class="form-group xs-mt-10">
                      <label for="price" class="col-sm-2 control-label">Selling Price</label>
                      <div class="col-sm-10">
                        <input id="price" type="number" required name="price" step=".00001" placeholder="Price" id="selling_price" class="selling_price form-control input-sm">
                      </div>
                    </div>
					
					<div class="form-group xs-mt-10">
                      <label for="price" class="col-sm-2 control-label">Manufacturer</label>
                      <div class="col-sm-10">
						<select class="form-control input-sm" name="manufacturer">
								<option selected value="">Select One</option>
                            <?php
                            $manu = $this->stock->getManufacturers();
                            foreach($manu as $man){
                                ?>
                                <option value="<?php echo $man['SN']  ?>"><?php echo $man['manufacturer']  ?></option>
                                <?php
                            }
                            ?>
						</select>
						</div>
					  </div>
					<div class="form-group xs-mt-10">
                      <label for="price" class="col-sm-2 control-label">Product Category</label>
                      <div class="col-sm-10">
						<select class="form-control input-sm" name="category_id">
								<option selected value="">Select One</option>
								<?php
									$manu = $this->stock->getcategories();
									foreach($manu as $man){
								?>
									<option value="<?php echo $man['SN']  ?>"><?php echo $man['category']  ?></option>
								<?php
									}
								?>
						</select>
						</div>
					  </div>
					
					
					<div class="form-group xs-mt-10">
                      <label for="price" class="col-sm-2 control-label">Status</label>
                      <div class="col-sm-10">
						<select class="form-control input-sm" readonly required name="status">
							<option value="1">Enabled</option>
						</select>
					  </div>
                    </div>
					</div>
					</div>
                    <div id="scan" class="tab-pane cont"> 
						<div class="well">
							<h1 align="center" style="font-weight:bolder;font-size:110px; height:150px;" id="bar_code"></h1>
							<input type="hidden" name="bar_code_code" id="bar_code_code"/>
						</div>
					</div>
					<div id="profile" class="tab-pane cont"> 
					  <div class="row">
						<div class="col-md-12">
						  <div class="panel panel-default panel-border-color panel-border-color-primary">
							<div class="panel-heading panel-heading-divider">Product Description<span class="panel-subtitle">Describe Specification</span></div>
							<div class="panel-body">
							  <textarea placeholder="Product Description" style="height:200px" class="form-control col-lg-12" name="product_description"></textarea>
							</div>
						  </div>
						</div>
					  </div>
						
					</div>
                    
                  </div>
                </div>
		</div>
		</form>
	</div>
</div>