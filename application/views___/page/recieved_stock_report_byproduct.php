<div class="row">
    <div class="col-sm-12">

        <div class="panel panel-default panel-table">
            <div class="panel-heading">Daily Stock Received List
                <div class="tools">
                    <form method="post" class="form-horizontal" id="change_branch" action="">
                        <?php
                        ?>
                        <?php
                        if(isset($_POST['from'])){
                            ?>
                            <div class="col-md-5">
                                <label>From</label>
                                <input value="<?php echo $_POST['from']  ?>" type="text" style="padding-left:10px;" data-min-view="2" data-date-format="yyyy-mm-dd" name="from" id="datetimepicker" placeholder="From" class="form-control input-sm datetimepicker" name="from required"/>
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
                            }else{

                                ?>
                                <input type="hidden" name="department" value="<?php echo $this->stock->getUserDepartment() ?>"/>
                                <?php
                            }
                            ?>
                            <?php
                        }else{
                            ?>
                            <div class="col-md-5">
                                <label>From</label>
                                <input value="<?php echo date('Y').'-'.date('m').'-'.date('d')  ?>" type="text" style="padding-left:10px;" data-min-view="2" data-date-format="yyyy-mm-dd" name="from" id="datetimepicker" placeholder="From" class="form-control input-sm datetimepicker" name="from required"/>
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
                            ?>
                            <?php
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
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Cost Price</th>
                        <th>Selling Price</th>

                        <th>Received Date</th>

                        <th>Receiver</th>

                        <th>Command</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $filter = [];
                    if(isset($_POST['from'])){
                        $from = $_POST['from'];
                    }else{
                        $from =date('Y').'-'.date('m').'-'.date('d') ;
                    }
                    if($this->stock->getUserDepartment() =="Administrator") {
                        if(isset($_POST['from'])){
                            $filter['department'] = $_POST['department'];
                        }
                    }else{
                        $filter['department'] = $this->stock->getUserDepartment();
                    }


                    $num=1;
                    $array = array(
                        '0'=>'<span class="label label-danger">Disabled</span>',
                        '1'=>'<span class="label label-success">Enabled</span>'
                    );


                    $recievers = $this->stock->getStockrecievedBetween($from,$from,$filter);
                    foreach($recievers as $transfer){
                        $user = $this->users->get_user_by_id($transfer['reciever_userfullname'],1);
                        $product_s = json_decode($transfer['products'],true);
                        foreach($product_s as $product_s){
                            $pro = @$this->stock->getStock($product_s['product'])[0];
                            ?>
                            <tr>
                                <td><?php  echo $num ?></td>
                                <td><?php  echo $transfer['recieved_id'] ?></td>
                                <td><?php  echo @$pro['product_name'] ?></td>
                                <td><?php  echo $product_s['qty'] ?></td>
                                <td><?php  echo number_format(@$pro['cost_price'],2) ?></td>
                                <td><?php  echo number_format(@$pro['price'],2) ?></td>

                                <td><?php  echo $transfer['recieved_date'] ?></td>

                                <td><?php  echo $user->username ?></td>

                                <td><a href="<?php echo base_url('dashboard/view_received/'.$transfer['recieved_id']) ?>" class="btn btn-sm btn-primary">View Received</a></td>
                            </tr>
                            <?php
                            $num++;
                        }

                    }
                    ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>