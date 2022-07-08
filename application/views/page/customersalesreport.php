<div class="row">
<div class="col-sm-12">
	
	 <div class="panel panel-default panel-table">
         <div class="panel-heading">Sales Report By Customer
             <div class="tools">
                 <form method="post" class="form-horizontal" id="change_branch" action="">
                     <?php
                     if(isset($_POST['to']) && isset($_POST['from'])){
                         ?>
                         <div class="col-md-4">
                             <label>From</label>
                             <input value="<?php echo $_POST['from']  ?>" type="text" style="padding-left:10px;" data-min-view="2" data-date-format="yyyy-mm-dd" name="from" id="datetimepicker" placeholder="From" class="form-control input-sm datetimepicker" name="from required"/>
                         </div>
                         <div class="col-md-4">
                             <label>To</label>
                             <input type="text" value="<?php echo $_POST['to']  ?>" style="padding-left:10px;" data-min-view="2" data-date-format="yyyy-mm-dd" name="to" id="datetimepicker" placeholder="To" class="form-control input-sm datetimepicker" name="to" required/>
                         </div>
                         <?php
                     }else{
                         ?>
                         <div class="col-md-4">
                             <label>From</label>
                             <input value="<?php echo date('Y').'-'.date('m').'-'.date('01')  ?>" type="text" style="padding-left:10px;" data-min-view="2" data-date-format="yyyy-mm-dd" name="from" id="datetimepicker" placeholder="From" class="form-control input-sm datetimepicker" name="from required"/>
                         </div>
                         <div class="col-md-4">
                             <label>To</label>
                             <input type="text" value="<?php echo date('Y').'-'.date('m').'-'.date('t')  ?>" style="padding-left:10px;" data-min-view="2" data-date-format="yyyy-mm-dd" name="to" id="datetimepicker" placeholder="To" class="form-control input-sm datetimepicker" name="to" required/>
                         </div>
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
 <table id="table3" class="table table-striped table-hover table-fw-widget">
	<thead>
		<tr>
			<th>#</th>
			<th>First name</th>
			<th>Last name</th>
			<th>Phone No</th>
            <th>Total Sales</th>
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
		$customers = $this->db->get('tbl_customers')->result_array();
		$num = 1;

        $alltotal = 0;
		foreach($customers as $cus){
            $ins = $this->stock->getSalesRange($from,$to,array('customer'=>$cus['SN'],"status"=>"COMPLETE"));
            $total = 0;
            foreach ($ins as $in){
                $total+=$in['total_amount'];
            }
            $alltotal+=$total;
		?>
		<tr>
			<td><?php  echo $num; ?></td>
			<td><?php echo $cus['firstname'] ?></td>
			<td><?php echo $cus['lastname'] ?></td>
			<td><?php echo $cus['phone'] ?></td>
			<td><?php echo number_format($total,2) ?></td>
		</tr>
		<?php $num++; } ?>
	</tbody>
     <tfoot>
        <tr>
            <td></td>
            <td></td>
            <td></td> <th class="text-right">Total</th>
            <th><?php echo number_format($alltotal,2) ?></th>
        </tr>
     </tfoot>
</table>
</div>	</div>	</div>	</div>	
