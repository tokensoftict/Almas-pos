<div class="row">
    <div class="col-sm-12">

        <div class="panel panel-default">
            <div class="panel-heading">Application Settings</div>
            <div class="tab-container">
                <div class="tab-content">
                    <div id="home" class="tab-pane active cont">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel">
                                    <div class="panel panel-heading">User List(s)</div>
                                    <div class="panel-body panel-table">
                                        <table class="table table-bordered" id="table3" style="font-size: 11px;">
                                            <thead>
                                            <tr>

                                                <th>Username</th>
                                                <th>Name</th>
                                                <th>Bank Name</th>
                                                <th>Account Name</th>
                                                <th>Account No.</th>
                                                <th>Salary</th>
                                                <th>Email</th>
                                                <th>Department</th>
                                                <th>Role</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                            <thead>
                                            <?php
                                            $num =1;
                                            $status = array(
                                                '0'=>'<span class="label label-danger">Disabled</span>',
                                                '1'=>'<span class="label label-success">Enabled</span>'
                                            );
                                            $num = 1;
                                            $users = $this->db->from("users")->where("role !=","Superuser")->get();
                                            foreach($users->result_array() as $user){
                                                ?>
                                                <tr>

                                                    <td><?php echo $user['username'] ?></td>
                                                    <td><?php echo $user['firstname'] ?> <?php echo $user['lastname'] ?></td>
                                                    <td><?php echo $user['bank_name'] ?></td>
                                                    <td><?php echo $user['bank_account_name'] ?></td>
                                                    <td><?php echo $user['bank_account_no'] ?></td>
                                                    <td><?php echo number_format((empty($user['salary']) ? 0 : $user['salary']) ,2); ?></td>
                                                    <td><?php echo $user['email'] ?></td>
                                                    <td><?php echo $user['department'] ?></td>
                                                    <td><?php echo $user['role'] ?></td>
                                                    <td><?php echo $status[$user['activated']] ?></td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <?php  if($user['activated']=="1"){ ?>
                                                                <a href="<?php echo base_url('dashboard/settings/'.$user['id'].'?del=0') ?>" class="btn btn-sm btn-danger">Disable</a><br/>
                                                            <?php }else{ ?>
                                                                <a href="<?php echo base_url('dashboard/settings/'.$user['id'].'?del=1') ?>" class="btn btn-sm btn-success">Enable</a><br/>
                                                            <?php } ?>
                                                            <a href="<?php echo base_url('dashboard/edit_settings/'.$user['id']) ?>" class="btn btn-sm btn-primary">Edit User</a><br/>
                                                            <a href="<?php echo base_url('dashboard/reset_password/'.$user['id']) ?>" onclick="return confirm('Are you sure you want reset this password ?');" class="btn btn-sm btn-primary">Password</a>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <?php
                                                $num++;
                                            }
                                            ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="panel">
                                    <div class="panel panel-heading">Add User(s)</div>
                                    <div class="panel-body">
                                        <form action="<?php  echo base_url('dashboard/settings');  ?>" method="post" accept-charset="utf-8">
                                            <div class="form-group">
                                                <label>First Name</label>
                                                <input type="text" name="extra[firstname]" autocomplete="OFF" value="" id="firstname"  required="" placeholder="First Name" autocomplete="off" class="input-sm form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Last Name</label>
                                                <input type="text" name="extra[lastname]" autocomplete="OFF" value="" id="lastname" required="" placeholder="Last Name" autocomplete="off" class="input-sm form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Bank Name</label>
                                                <input type="text" name="extra[bank_name]" autocomplete="OFF" value="" id="bank_name" required="" placeholder="Bank Name" autocomplete="off" class="input-sm form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Account Name</label>
                                                <input type="text" name="extra[bank_account_name]" autocomplete="OFF" value="" id="account_name"  required="" placeholder="Account Name" autocomplete="off" class="input-sm form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Account Number</label>
                                                <input type="text" name="extra[bank_account_no]" autocomplete="OFF" value="" id="bank_account_no"  required="" placeholder="Account Number" autocomplete="off" class="input-sm form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Salary</label>
                                                <input type="number" name="extra[salary]" autocomplete="OFF" value="" id="salary"  required="" placeholder="Salary" autocomplete="off" class="input-sm form-control">
                                            </div>

                                            <div class="form-group">
                                                <label>Username</label>
                                                <input type="text" name="username" autocomplete="OFF" value="" id="username"  required="" placeholder="Username" autocomplete="off" class="input-sm form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="text" name="email" value="" autocomplete="OFF"  id="email"  required="" placeholder="E-mail" autocomplete="off" class="input-sm form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Password</label>
                                                <input type="password" name="password" value="" id="password"  required="" class="input-sm form-control" placeholder="Password">
                                            </div>
                                            <?php
                                            $user_id = $this->tank_auth->get_user_id();
                                            $user = $this->db->get_where("users",array("id"=>$user_id))->row_array();
                                            ?>
                                            <div class="form-group">
                                                <label>Role</label>
                                                <select required class="form-control input-sm" id="select-role" required name="role">
                                                    <option>-Select Role-</option>
                                                    <option value="Sales Representative">Sales Representative</option>
                                                    <option value="Administrator">Administrator</option>
                                                    <option value="Manager">Manager</option>
                                                    <option value="Accountant">Accountant</option>
                                                    <option value="Auditor">Auditor</option>
                                                    <option value="Stock Officer">Stock Officer</option>
                                                    <option value="Service Administrator">Service Administrator</option>
                                                    <?php  if($user['role'] == "Superuser"){ ?>
                                                        <option value="Superuser">Superuser</option>
                                                    <?php } ?>
                                                    <option value="Others">Other Staff</option>
                                                </select>
                                            </div>
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
                                                        <option value="<?php echo $key ?>"><?php echo $key ?></option>
                                                    <?php } ?>
                                                </select>
                                                <input type="hidden" id="admin_selected" name="extra[department]" value="Administrator" disabled="disabled" readonly="readonly" style="display: none" />
                                            </div>
                                            <div class="form-group xs-pt-10">
                                                <input type="submit" value="Add User" class="btn btn-block btn-primary btn-xl">
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

