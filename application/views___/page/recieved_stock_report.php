<div class="row">
    <div class="col-sm-12">

        <div class="panel panel-default panel-table">
            <div class="panel-heading">Stock Received Report List(s)
                <div class="tools">
                    <form method="post" class="form-horizontal" id="change_branch" action="">
                        <?php
                        ?>
                        <?php
                        if(isset($_POST['to']) && isset($_POST['from'])){
                            ?>
                            <div class="col-md-3">
                                <label>From</label>
                                <input value="<?php echo $_POST['from']  ?>" type="text" style="padding-left:10px;" data-min-view="2" data-date-format="yyyy-mm-dd" name="from" id="datetimepicker" placeholder="From" class="form-control input-sm datetimepicker" name="from required"/>
                            </div>
                            <div class="col-md-3">
                                <label>To</label>
                                <input type="text" value="<?php echo $_POST['to']  ?>" style="padding-left:10px;" data-min-view="2" data-date-format="yyyy-mm-dd" name="to" id="datetimepicker" placeholder="To" class="form-control input-sm datetimepicker" name="to" required/>
                            </div>
                            <?php
                            if($this->stock->getUserDepartment() =="Administrator") {
                                ?>
                                <div class="col-md-3">
                                    <label>Department</label>
                                    <select required class="form-control input-sm" name="department">
                                        <?php
                                        $dpts = $this->db->get_where("department", array("type" => "Sales"))->result_array();
                                        $department = array();
                                        foreach ($dpts as $dpt) {
                                            $department[$dpt['department']] = array('Sales Representative', 'Administrator');
                                        }
                                        ?>
                                        <option value="all">-Select-</option>
                                        <?php
                                        foreach ($department as $key => $dpt) {
                                            if ($key != "Administrator") {
                                                ?>
                                                <option <?php echo $_POST['department'] == $key ? 'selected' : '' ?>
                                                        value="<?php echo $key ?>"><?php echo $key ?></option>
                                            <?php }
                                        } ?>
                                    </select>
                                </div>
                                <?php
                            }else {
                                ?>
                                <input type="hidden" name="department" value="<?php echo $this->stock->getUserDepartment() ?>"/>
                                <?php
                            }
                        }else{
                            ?>
                            <div class="col-md-3">
                                <label>From</label>
                                <input value="<?php echo date('Y').'-'.date('m').'-'.date('01')  ?>" type="text" style="padding-left:10px;" data-min-view="2" data-date-format="yyyy-mm-dd" name="from" id="datetimepicker" placeholder="From" class="form-control input-sm datetimepicker" name="from required"/>
                            </div>
                            <div class="col-md-3">
                                <label>To</label>
                                <input type="text" value="<?php echo date('Y').'-'.date('m').'-'.date('t')  ?>" style="padding-left:10px;" data-min-view="2" data-date-format="yyyy-mm-dd" name="to" id="datetimepicker" placeholder="To" class="form-control input-sm datetimepicker" name="to" required/>
                            </div>
                            <?php
                            if($this->stock->getUserDepartment() =="Administrator") {
                                ?>
                                <div class="col-md-3">
                                    <label>Department</label>
                                    <select required class="form-control input-sm" name="department">
                                        <?php
                                        $dpts = $this->db->get_where("department", array("type" => "Sales"))->result_array();
                                        $department = array();
                                        foreach ($dpts as $dpt) {
                                            $department[$dpt['department']] = array('Sales Representative', 'Administrator');
                                        }
                                        ?>
                                        <option value="all">-Select-</option>
                                        <?php
                                        foreach ($department as $key => $dpt) {
                                            if ($key != "Administrator") {
                                                ?>
                                                <option value="<?php echo $key ?>"><?php echo $key ?></option>
                                            <?php }
                                        } ?>
                                    </select>
                                </div>
                                <?php
                            }else{

                                ?>
                                <input type="hidden" name="department" value="<?php echo $this->stock->getUserDepartment() ?>"/>
                        <?php
                            }
                        }
                        ?>
                        <div class="col-md-1"><label style="visibility: hidden;">To</label>
                            <button class="btn btn-primary">Go</button>

                        </div>

                    </form>

                </div>
            </div>
            <div class="panel-body">
                <br/><br/><br/>
                <table id="table3" class="table table-striped table-hover table-fw-widget">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Received ID</th>
                        <th>Received Date</th>
                        <th>Branch/Supplier</th>
                        <th>Department</th>
                        <th>Receiver</th>
                        <th>Transfer User</th>
                        <th>Command</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(isset($_POST['from'])){
                        $from = $_POST['from'];
                        $to = $_POST['to'];
                    }else{
                        $from =date('Y').'-'.date('m').'-'.date('01') ;
                        $to = date('Y').'-'.date('m').'-'.date('t');
                    }
                    $filter = [];
                    $num=1;
                    $array = array(
                        '0'=>'<span class="label label-danger">Disabled</span>',
                        '1'=>'<span class="label label-success">Enabled</span>'
                    );
                    if($this->stock->getUserDepartment() =="Administrator") {
                        if(isset($_POST['from'])){
                            $filter['department'] = $_POST['department'];
                        }
                    }else{
                        $filter['department'] = $this->stock->getUserDepartment();
                    }
                    $recievers = $this->stock->getStockrecievedBetween($from,$to,$filter);
                    foreach($recievers as $transfer){
                        $user = $this->users->get_user_by_id($transfer['reciever_userfullname'],1);
                        ?>
                        <tr>
                            <td><?php  echo $num ?></td>
                            <td><?php  echo $transfer['recieved_id'] ?></td>
                            <td><?php  echo $transfer['recieved_date'] ?></td>
                            <td><?php
                                if(!empty($transfer['branch'])){
                                    echo $transfer['branch'];
                                }else{
                                    $supp = $this->stock->getSupplier($transfer['supplier']);
                                    echo $supp['supplier_name'];
                                }
                                ?></td>
                            <td><?php echo $transfer['department']; ?></td>
                            <td><?php  echo $user->username ?></td>
                            <td><?php  echo $transfer['transfer_user'] ?></td>
                            <td><a href="<?php echo base_url('dashboard/view_received/'.$transfer['recieved_id']) ?>" class="btn btn-sm btn-primary">View Received</a></td>
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