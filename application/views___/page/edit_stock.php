
<div class="row">
	<div class="col-sm-12">
		<form action="<?php  echo base_url('dashboard/update_stock/'.$this->uri->segment(3))  ?>" id="edit" enctype="multipart/form-data" method="post"  onsubmit="$('#sub').attr('disabled','disabled')" >
		<div class="panel">
			 <div class="panel-heading">Edit Stock
                 <div class="tools"><button type="submit" form="edit" id="sub" class="btn btn-sm btn-primary"><i class="mdi mdi-edit"></i> Update Stock</button></div>
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
                        <input id="product_name" value="<?php  echo $stock['product_name'] ?>" name="product_name" type="text" required  max="255" placeholder="Product Name" class="form-control input-sm">
                      </div>
                    </div>
					
					<div class="form-group xs-mt-10">
                      <label for="price" class="col-sm-2 control-label">Date Available</label>
                      <div class="col-sm-10">
                        <input id="date_available" type="text" value="<?php  echo $stock['date_available'] ?>" name="date_available" data-min-view="2" data-date-format="yyyy-mm-dd"  placeholder="Date Available" class="date form-control input-sm">
                      </div>
                    </div>
					<div class="form-group xs-mt-10">
                      <label for="model" class="col-sm-2 control-label">Model</label>
                      <div class="col-sm-10">
                        <input id="model" name="model" value="<?php  echo $stock['model'] ?>" type="text" required min="3" max="255" placeholder="Model" class="form-control input-sm">
                      </div>
                    </div>
					<div class="form-group xs-mt-10">
                      <label for="model" class="col-sm-2 control-label">Product Expiry Date</label>
                      <div class="col-sm-10">
                        <input id="datetimepicker"  value="<?php  echo $stock['expired_date'] ?>" data-min-view="2" data-date-format="yyyy-mm-dd" name="expired_date" type="text"  placeholder="Product Expiry Date" class="date form-control input-sm">
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
                                    <select required class="form-control input-sm" name="department">
                                        <option>-Select Department-</option>
                                        <?php
                                        foreach($dpts as $key=>$dpt){
                                            ?>
                                            <option <?php echo $dpt['department']==$stock['department'] ? 'selected' : ''  ?> value="<?php echo $dpt['department'] ?>"><?php echo $dpt['department'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <?php
                        }else {
                            ?>
                            <input type="hidden" name="department" value="<?php echo $this->stock->getUserDepartment()  ?>"/>
                            <?php
                        }
                        ?>
					<div class="form-group xs-mt-10">
                      <label for="model" class="col-sm-2 control-label">Product Code(Optional)</label>
                      <div class="col-sm-10">
                        <input id="model" name="product_code" value="<?php echo $stock['product_code'] ?>" type="text"   placeholder="Product Code" class="form-control input-sm">
                      </div>
                    </div>
						<div class="form-group xs-mt-10">
                      <label for="price" class="col-sm-2 control-label">Cost Price</label>
                      <div class="col-sm-10">
                        <input id="price" type="number" onkeyup="$('.selling_price').attr('min',parseFloat($(this).val()))" value="<?php  echo $stock['cost_price'] ?>" step=".00001" required name="cost_price"  placeholder="Cost Price" class="form-control input-sm">
                      </div>
                    </div>
					<div class="form-group xs-mt-10">
                      <label for="price" class="col-sm-2 control-label">Selling Price</label>
                      <div class="col-sm-10">
                        <input id="price" value="<?php  echo $stock['price'] ?>" type="number" step=".00001" name="price" id="selling_price" placeholder="Selling Price" class="selling_price form-control input-sm">
                      </div>
                    </div>
					
					<div class="form-group xs-mt-10">
                      <label for="price" class="col-sm-2 control-label">Manufacturer</label>
                      <div class="col-sm-10">
						<select required class="form-control input-sm" name="manufacturer">
								
								<?php
									$manu = $this->stock->getManufacturers();
									foreach($manu as $man){
								?>
									<option <?php echo (($man['SN']==$stock['manufacturer']) ? 'selected' : '0') ?>  value="<?php echo $man['SN']  ?>"><?php echo $man['manufacturer']  ?></option>
								<?php
									}
								?>
						</select>
						</div>
					  </div>
					
					<div class="form-group xs-mt-10">
                      <label for="price" class="col-sm-2 control-label">Category</label>
                      <div class="col-sm-10">
						<select required class="form-control input-sm" name="category_id">
								
								<?php
									$manu = $this->stock->getcategories();
									foreach($manu as $man){
								?>
									<option <?php echo (($man['SN']==$stock['category_id']) ? 'selected' : '') ?>  value="<?php echo $man['SN']  ?>"><?php echo $man['category']  ?></option>
								<?php
									}
								?>
						</select>
						</div>
					  </div>
					
					<div class="form-group xs-mt-10">
                      <label for="price" class="col-sm-2 control-label">Status</label>
                      <div class="col-sm-10">
						<select class="form-control input-sm" required name="status">
							<option <?php echo ($stock['status']=="1" ? 'selected' : '') ?> value="1">Enabled</option>
							<option <?php echo ($stock['status']=="0" ? 'selected' : '') ?>value="0">Disabled</option>
						</select>
					  </div>
                    </div>
					
				
					
					
					</div>
					
					</div>
                    <div id="scan" class="tab-pane cont"> 
						<div class="well">
							<h1 align="center" style="font-weight:bolder;font-size:110px; height:150px;" id="bar_code"><?php echo $stock['bar_code_code'] ?></h1>
							<input type="hidden" name="bar_code_code" value="<?php echo $stock['bar_code_code'] ?>" id="bar_code_code"/>
						</div>
					</div>
					<div id="profile" class="tab-pane cont"> 
					  <div class="row">
						<div class="col-md-12">
						  <div class="panel panel-default panel-border-color panel-border-color-primary">
							<div class="panel-heading panel-heading-divider">Product Description<span class="panel-subtitle">Describe Specification</span></div>
							<div class="panel-body">
							   <textarea placeholder="Product Description" style="height:200px" class="form-control col-lg-12" name="product_description"><?php echo $stock['product_description'] ?></textarea>
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