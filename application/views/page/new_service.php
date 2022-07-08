<div class="row">
    <div class="col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">New Service</div>
            <div class="panel-body">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-control-label">Service Code</label>
                                <input type="text" required readonly value="SER-<?php echo mt_rand(); ?>" placeholder="Service Code" class="form-control input-sm" name="servicecode"/>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Name</label>
                                <input type="text" required placeholder="Name" class="form-control input-sm" name="name"/>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Price</label>
                                <input type="text" required placeholder="Price" class="form-control input-sm" name="price"/>
                            </div>
                            <input type="hidden" name="service_type" value="Normal Service"/>
                            <!--
                            <div class="form-group">
                                <label class="form-control-label">Service Type</label>
                                <select name="service_type" required class="form-control input-sm">
                                    <option selected value="Normal Service">Normal Service</option>
                                    <option value="Hourly Service">Hourly Service</option>
                                </select>
                            </div>
                            -->
                            <div class="form-group">
                                <label class="form-control-label">Category</label>
                                <select name="category" required class="form-control input-sm">
                                    <option value="">Select Category</option>
                                    <?php
                                    foreach($this->stock->getServiceCategories() as $category) {
                                        ?>
                                        <option value="<?php echo $category['SN'] ?>"><?php echo $category['name'] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <?php
                            if($this->stock->getUserDepartment() =='Administrator'){
                            ?>
                            <div class="form-group">
                                <label>Department</label>
                                <select required class="form-control input-sm" name="department">
                                    <option value="">Select Department</option>
                                    <?php
                                        $departments = $this->db->get_where('department',array('type'=>'Service'))->result_array();
                                        foreach($departments as $department) {
                                            ?>
                                            <option value="<?php echo $department['department'] ?>"><?php echo $department['department'] ?></option>
                                            <?php
                                        }
                                    ?>
                                </select>
                            </div>
                            <?php
                                }else {
                                ?>
                                <input type="hidden" value="<?php echo $this->stock->getUserDepartment() ?>" name="department"/>
                                <?php
                            }
                            ?>
                            <div class="form-group">
                                <label>Service Image</label>
                                <div class="col-sm-12">
                                    <?php
                                    $extra_charges=$this->db->from("others")->where("SN","1")->get()->result_array()[0];
                                    if($extra_charges['slogo']!=""){
                                        ?>
                                        <br/><br/>
                                        <img src="<?php echo $extra_charges['slogo'] ?>"  style="width:60%"/>
                                        <?php
                                    }
                                    ?>

                                </div>
                                <input type="file" name="spicture" class="input-sm form-control">
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                               <textarea class="form-control input-sm" name="description"></textarea>
                            </div>
                            <button class="btn btn-lg btn-primary" type="submit">Add Service</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
