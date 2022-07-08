<div class="row">
<div class="col-sm-12">
	<?php
	$user_id = $this->tank_auth->get_user_id();
	$user = $this->db->get_where("users",array("id"=>$user_id))->row_array();
	$dpt = $this->stock->getUserDepartment();
	$this->db->from('services');
    if($dpt !='Administrator') {
        $this->db->where('department',$user['department']);
    }
    $services = $this->db->order_by('SN', 'DESC')->get()->result_array();
	?>
	 <div class="panel panel-default panel-table">
                <div class="panel-heading"><?php echo $user['department'] ?> Service List(s)
                </div>
				<div class="panel-body" style="padding: 30px;" style="width: 100%;">
				 <table id="table3"  class="table table-striped table-hover table-fw-widget">
                    <thead>
                      <tr>
					  <th style="min-width:5% ">#</th>
                      <th style="min-width:10% ">Code</th>
					  <th style="min-width:10% ">Name</th>
                      <th style="min-width:10% ">Price</th>
                      <th style="min-width:10% ">Category</th>
                      <th style="min-width:10% ">Department</th>
                      <th style="min-width:10% ">Last Updated</th>
					  <th style="min-width:10% ">Command</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                    $num = 1;
                    foreach($services as $service) {
                        ?>
                        <tr>
                            <td><?php echo $num; ?></td>
                            <td><?php echo $service['servicecode']; ?></td>
                            <td><?php echo $service['name']; ?></td>
                            <td>N<?php echo number_format($service['price'],1); ?></td>
                            <td><?php echo $this->db->get_where('service_category',array('SN'=>$service['category']))->row()->name  ?></td>
                            <td><?php echo $service['department']; ?></td>
                            <td><?php echo $service['updated']; ?></td>
                            <td>
                                <div class="btn-group btn-space">
                                    <button type="button" class="btn btn-default">Action</button>
                                    <button type="button" data-toggle="dropdown" class="btn btn-success dropdown-toggle" aria-expanded="false"><span class="mdi mdi-chevron-down"></span><span class="sr-only">Toggle Dropdown</span></button>
                                    <ul role="menu" class="dropdown-menu">
                                        <li><a href="<?php echo base_url('dashboard/') ?>edit_service/<?php echo $service['SN'] ?>">Edit</a></li>
                                        <li><a href="<?php echo base_url('dashboard/') ?>delete_service/<?php echo $service['SN'] ?>">Delete</a></li>
                                    </ul>
                                </div>
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
