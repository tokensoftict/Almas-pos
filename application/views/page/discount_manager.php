<div class="row">
    <div class="col-sm-12">

        <div class="panel panel-default">
            <div class="panel-heading">Application Settings</div>
            <div class="tab-container">
                <div class="tab-content">
                    <div id="home" class="tab-pane active cont">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="panel">
                                    <div class="panel panel-heading">Discount Manager</div>
                                    <div class="panel-body">
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th>SN</th>
                                                <th>Name</th>
                                                <th>Value</th>
                                                <td>Action</td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $num = 1;
                                            $discounts =$this->db->get("discount_manager")->result_array();
                                            foreach($discounts as $discount){
                                                ?>
                                                <tr>
                                                    <td><?php echo $num; ?></td>
                                                    <td><?php echo $discount['name'] ?></td>
                                                    <td><?php echo $discount['value'] ?></td>

                                                        <td>
                                                            <?php
                                                            if($this->users->get_user_by_username($this->session->userdata("username"))->role=="Administrator") {
                                                            ?>
                                                            <a href="<?php echo base_url('dashboard/discount_manager/' . $discount['SN'] . '?del=true') ?>"
                                                               class="btn btn-danger">Delete</a>
                                                                <?php
                                                            }else {
                                                                ?>
                                                                    No Action
                                                                <?php
                                                            }
                                                            ?>
                                                        </td>

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

                            <div class="col-lg-4">
                                <div class="panel">
                                    <div class="panel panel-heading">Add New Discount</div>
                                    <div class="panel-body">
                                        <form action=""  method="post">

                                            <div class="form-group xs-pt-10">
                                                <br/>
                                                <label for="branch_name" class="col-sm-12 control-label">Discount name</label>
                                                <div class="col-sm-12">
                                                    <input id="branch_name" required type="text" name="name" placeholder="Discount Name" class="form-control input-sm">
                                                </div>
                                            </div>

                                            <div class="form-group xs-pt-10">
                                                <br/><br/><br/>
                                                <label for="branch_address" class="col-sm-12 control-label">Discount Value</label>
                                                <div class="col-sm-12">
                                                    <input id="value" type="text" name="value" placeholder="Discount value" class="form-control input-sm">
                                                </div>
                                            </div>



                                            <div class="col-sm-12">
                                                <br/>
                                                <button class="btn btn-primary" type="submit">Add Discount</button>
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
