<div class="row">
<div class="col-sm-12">
	
	 <div class="panel panel-default panel-table">
                <div class="panel-heading">Sales List(s)
                </div>
<div class="panel-body">
 <table id="table3" class="table table-striped table-hover table-fw-widget">
	<thead>
		<tr>
			<th>#</th>
			<th>Firstname</th>
			<th>Last name</th>
			<th>Email</th>
			<th>Phone No</th>
			<th>Address</th>
			<th>Credit Limit</th>
			<th>Credit Time Frame Expired</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
	<?php 
		$customers = $this->db->get('tbl_customers')->result_array();
		$num = 1;
		foreach($customers as $cus){
		?>
		<tr>
			<td><?php  echo $num; ?></td>
			<td><?php echo $cus['firstname'] ?></td>
			<td><?php echo $cus['lastname'] ?></td>
			<td><?php echo $cus['email'] ?></td>
			<td><?php echo $cus['phone'] ?></td>
			<td><?php echo $cus['address'] ?></td>
			<td><?php echo number_format($cus['credit_limit'],2) ?></td>
			<td><?php echo ($cus['expired_date']=="0000-00-00" ? '' : $cus['expired_date']); ?></th>
			<td>
			    	<?php
		$user_id = $this->tank_auth->get_user_id();
	
	$user = $this->db->get_where("users",array("id"=>$user_id))->row_array();
	?>
	 <?php if($user['role'] == "Administrator"){ ?>
			    <a href="<?php echo base_url("dashboard/edit_customer/".$cus['SN']) ?>" class="btn btn-primary btn-sm">Edit</a>
	<?php
	 }else{
	?>
	No Action
	<?php
	 }
	?>
			    </td>
		</tr>
		<?php $num++; } ?>
	</tbody>
</table>
</div>	</div>	</div>	</div>	