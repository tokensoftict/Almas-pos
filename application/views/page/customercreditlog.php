<div class="row">
<div class="col-sm-12">
	
	 <div class="panel panel-default panel-table">
                <div class="panel-heading">Credit List By Customer
                </div>
<div class="panel-body">
 <table id="table3" class="table table-striped table-hover table-fw-widget">
	<thead>
		<tr>
			<th>#</th>
			<th>Full Name</th>
			<th>Total Credit</th>
			<th>Total Paid</th>
            <th>Total Balance</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
	<?php 
		$customers = $this->db->get('tbl_customers')->result_array();
		$num = 1;
		$total =0;
		$total_paid = 0;
		$total_credit =0;
		foreach($customers as $cus){
		    $log = $this->stock->getCreditTotals($cus['SN']);
		    if($log['balance'] >0){
		        $total+= $log['balance'];
                $total_paid+=$log['total_amt_paid'];
                $total_credit+=$log['total_credit'];
		?>
		<tr>
			<td><?php  echo $num; ?></td>
			<td><?php echo $cus['firstname'] ?> <?php echo $cus['lastname'] ?></td>
			<td><?php echo number_format($log['total_credit'],2) ?></td>
            <td><?php echo number_format($log['total_amt_paid'],2) ?></td>
            <td><?php echo number_format($log['balance'],2) ?></td>
			    	<?php
		$user_id = $this->tank_auth->get_user_id();
	
	$user = $this->db->get_where("users",array("id"=>$user_id))->row_array();
	?><td>
                <a href="<?php echo base_url("dashboard/payment_history/".$cus['SN']) ?>" class="btn btn-primary btn-sm">Payment History</a>
                <a href="<?php echo base_url("dashboard/credit_history/".$cus['SN']) ?>" class="btn btn-primary btn-sm">Credit History</a>
                <!--<a href="<?php //echo base_url("dashboard/clear_all_credit/".$cus['SN'].'/'.$log['balance']) ?>" class="btn btn-primary btn-sm">Clear All Credit</a>-->
			    </td>
		</tr>
		<?php $num++;
		    }
		}
		?>
	</tbody>
     <tfoot>
        <tr>
            <th></th>
            <th></th>
            <th><?php echo number_format($total_credit,2); ?></th>
            <th><?php echo number_format($total_paid,2); ?></th>
            <th><?php echo number_format($total,2); ?></th>
            <th></th>
        </tr>
     </tfoot>
</table>
</div>	</div>	</div>	</div>	
