<div class="col-lg-12">
								<div class="panel">
								<div class="panel panel-heading">Update Customer</div>
								<div class="panel-body">
									<form action="" method="post" accept-charset="utf-8">
											<div class="form-group">
											<label>First Name</label>
												<input type="text" value="<?php echo $customer['firstname'] ?>" name="firstname" autocomplete="OFF"  id="firstname"   required="" placeholder="First Name" autocomplete="off" class="input-sm form-control">
											 </div>
											 <div class="form-group">
											<label>Last Name</label>
												<input type="text" value="<?php echo $customer['lastname'] ?>" name="lastname" autocomplete="OFF" value="" id="lastname"  required="" placeholder="Date" autocomplete="off" class="date input-sm form-control">
											 </div>
											  <div class="form-group">
											<label>Email Address</label>
												<input type="email" value="<?php echo $customer['email'] ?>" name="email" autocomplete="OFF" value="" id="bank_name"   placeholder="Email Address" autocomplete="off" class="input-sm form-control">
											 </div> 
											 
											<div class="form-group">
											<label>Phone Number</label>
												<input type="number" value="<?php echo $customer['phone'] ?>" name="phone" autocomplete="OFF" value="" id="bank_name"  required="" placeholder="Expenses Amount" autocomplete="off" class="input-sm form-control">
											 </div> 

											<div class="form-group">
											<label>Credit Limit Amount</label>
											<input type="number" value="<?php echo @$customer['credit_limit'] ?>" name="credit_limit" autocomplete="OFF"   placeholder="Credit Limit" autocomplete="off" class="input-sm form-control">
											</div>
											
											<div class="form-group">
											<label>Credit Time Range Settings</label><br/><br/>
												<span> Credit Time Payment of &nbsp;&nbsp;<input  value="<?php echo @$customer['weeks'] ?>" type="number" style="width:120px;display:inline-block;" class=" form-control input-sm" name="weeks" placeholder="No of Weeks"/>&nbsp;&nbsp; Weeks From &nbsp;&nbsp;<input type="text"  style="width:150px;display:inline-block;" value="<?php echo ($customer['date']=="0000-00-00" ? date('Y-m-d') : $customer['date']) ?>" name="date" placeholder="Date" data-min-view="2" data-date-format="yyyy-mm-dd" autocomplete="off" class=" date input-sm form-control"/></span>
											</div>
											
											
											<div class="form-group">
											<label>Address</label>
											<textarea class="form-control" name="address" placeholder="Address"><?php echo $customer['address'] ?></textarea>
											</div>
											
											<div class="form-group xs-pt-10">
												<input type="submit" value="Update Customer" class="btn btn-block btn-primary btn-xl">
											</div>
									</form>
								</div>
								</div>
							</div>