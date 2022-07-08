<div class="row">
	<div class="col-sm-12">
		
		<div class="panel panel-default">
                <div class="panel-heading">Application Settings</div>
                <div class="tab-container">
				  <div class="tab-content">
                    <div id="home" class="tab-pane active cont">
					<div class="row">
						<?php 
							$user =$this->users->get_user_by_id($this->uri->segment(3),1);
						?>
						<div class="col-lg-12">
								<div class="panel">
								<div class="panel panel-heading">Update User(s)</div>
								<?php 
									
								?>
								<div class="panel-body">
									<form action="<?php  echo base_url('dashboard/edit_settings/'.$this->uri->segment(3));  ?>" method="post" accept-charset="utf-8">
											<div class="form-group">
											<label>First Name</label>
												<input type="text" name="extra[firstname]" autocomplete="OFF" value="<?php echo $user->firstname ?>" id="firstname" maxlength="20" size="30" required="" placeholder="First Name" autocomplete="off" class="input-sm form-control">
											 </div>
											 <div class="form-group">
											<label>Last Name</label>
												<input type="text" name="extra[lastname]" autocomplete="OFF" value="<?php echo $user->lastname ?>" id="lastname" maxlength="20" size="30" required="" placeholder="Last Name" autocomplete="off" class="input-sm form-control">
											 </div>
											  <div class="form-group">
											<label>Bank Name</label>
												<input type="text" name="extra[bank_name]" autocomplete="OFF" value="<?php echo $user->bank_name ?>" id="bank_name" maxlength="20" size="30" required="" placeholder="Bank Name" autocomplete="off" class="input-sm form-control">
											 </div>
											<div class="form-group">
											<label>Account Name</label>
												<input type="text" name="extra[bank_account_name]" autocomplete="OFF" value="<?php echo $user->bank_account_name ?>" id="account_name" maxlength="20" size="30" required="" placeholder="Account Name" autocomplete="off" class="input-sm form-control">
											 </div>
											 <div class="form-group">
											<label>Account Number</label>
												<input type="text" name="extra[bank_account_no]" autocomplete="OFF" value="<?php echo $user->bank_account_no ?>" id="account_number" maxlength="20" size="30" required="" placeholder="Account Number" autocomplete="off" class="input-sm form-control">
											 </div>
											 <div class="form-group">
											<label>Salary</label>
												<input type="number" name="extra[salary]" autocomplete="OFF" value="<?php echo $user->salary ?>" id="salary" maxlength="20" size="30" required="" placeholder="Salary" autocomplete="off" class="input-sm form-control">
											 </div>
											<div class="form-group">
											<label>Username</label>
												<input type="text" name="username" value="<?php echo $user->username ?>" autocomplete="OFF" value="" id="username" maxlength="20" size="30" required="" placeholder="Username" autocomplete="off" class="input-sm form-control">
											 </div>
											<div class="form-group">
											<label>Email Address</label>
												 <input type="text" name="email" value="<?php echo $user->email?>" autocomplete="OFF"  id="email" maxlength="80" size="30" required="" placeholder="E-mail" autocomplete="off" class="input-sm form-control">
											</div>

											<?php
										if($user->role!="0"){
										?>
												<div class="form-group">
														<label>Role</label>
														 <select required class="form-control input-sm" id="select-role" required name="role">
															<option <?php echo ($user->role=="Sales Representative" ? 'selected' : '');  ?> value="Sales Representative">Sales Representative</option>
															<option <?php echo ($user->role=="Administrator" ? 'selected' : '');  ?> value="Administrator">Administrator</option>											
															<option <?php echo ($user->role=="Manager" ? 'selected' : '');  ?> value="Manager">Manager</option>
															<option <?php echo ($user->role=="Accountant" ? 'selected' : '');  ?> value="Accountant">Accountant</option>
															<option <?php echo ($user->role=="Auditor" ? 'selected' : '');  ?> value="Auditor">Auditor</option>
															<option <?php echo ($user->role=="Stock Officer" ? 'selected' : '');  ?> value="Stock Officer">Stock Officer</option>
                                                            <option <?php echo ($user->role=="Service Administrator" ? 'selected' : '');  ?> value="Service Administrator">Service Administrator</option>
															<?php  if($user->role == "Superuser"){ ?>
															<option <?php echo ($user->role=="Superuser" ? 'selected' : '');  ?> value="Superuser">Superuser</option>
															<?php } ?>
															
															<option <?php echo ($user->role=="Others" ? 'selected' : '');  ?> value="Others">Other Staff</option>
														 </select>
													</div>
										<?php
										}
										?>
                                        <div class="form-group">
                                            <label>Department</label>
                                            <select required class="form-control input-sm" id="select_department"  name="extra[department]">
                                                <?php
                                                $dpts = $this->stock->getDepartments();

                                                foreach($dpts as $dpt){
                                                    $department[$dpt['department']] = array('Sales Representative','Administrator');
                                                }
                                                ?>
                                                <option>-Select Department-</option>
                                                <?php
                                                foreach($department as $key=>$dpt){
                                                    ?>
                                                    <option <?php echo $user->department==$key ? 'selected' : '' ?> value="<?php echo $key ?>"><?php echo $key ?></option>
                                                <?php } ?>
                                            </select>
                                            <input type="hidden" id="admin_selected" name="extra[department]" value="Administrator" disabled="disabled" readonly="readonly" style="display: none" />

                                        </div>
										
									
													  </div>
													  <div class="form-group xs-pt-10">
														<input type="submit" value="Update User" class="btn btn-block btn-primary btn-xl">
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
              </div>
		
	</div>
</div>

<script>
    window.onload = function(){
        $(document).ready(function(){
            $('#select-role').on('change',function(){
                var val = $(this).val();
                if(val == "Administrator" || val == "Manager" || val == "Accountant" || val == "Auditor" || val == "Superuser"){
                    $('#select_department').attr('disabled','disabled');
                    $('#admin_selected').removeAttr('disabled').removeAttr('style','display:none');
                }else{
                    $('#select_department').removeAttr('disabled');
                    $('#admin_selected').attr('disabled','disabled').removeAttr('style');
                }
            });

            $('#select-role').trigger('change');

        })
    }
</script>