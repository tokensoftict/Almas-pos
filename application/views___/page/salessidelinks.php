 <div class="be-left-sidebar">
        <div class="left-sidebar-wrapper"><a href="#" class="left-sidebar-toggle">Dashboard</a>
          <div class="left-sidebar-spacer">
            <div class="left-sidebar-scroll">
              <div class="left-sidebar-content">
					<ul class="sidebar-elements">
						<li> <a href="<?php echo base_url(); ?>"><span class="icon mdi mdi-home"></span>Dashboard</a></li>
						 <li><a href="<?php echo base_url('dashboard/pos')  ?>"><span class="icon mdi mdi-shopping-cart-plus"></span>Open POS</a></li>

							<li class="parent"><a href="#"><span class="icon mdi mdi-shopping-cart"></span>My Sales Report</a>
							<ul class="sub-menu">
								<li><a href="<?php echo base_url('dashboard/sales')  ?>">My Daily Sales</a></li>
								<li><a href="<?php echo base_url('dashboard/cash_ledger')  ?>">Cash At Hand</a></li>
								<li><a href="<?php echo base_url('dashboard/pos_payment')  ?>">Total POS Payment</a></li>
								<li> <a href="<?php echo base_url('dashboard/report_sales_rep'); ?>">My Monthly Sales</a></li>
							</ul>
							</li>
                        <!--
							<li class="parent"><a href="#"><span class="icon mdi mdi-format-list-numbered"></span>Expenses</a>
							<ul class="sub-menu">
							   <li> <a href="<?php echo base_url('dashboard/new_expenses'); ?>">New Expenses</a></li>
							   <li> <a href="<?php echo base_url('dashboard/expenses_report'); ?>">Today's Expenses</a></li>	
							</ul>
							</li>
						-->
						
							<li class="parent"><a href="#"><span class="icon mdi mdi-chart-donut"></span>Credit</a>
							<ul class="sub-menu">
								  <li> <a href="<?php echo base_url('dashboard/add_new_credit'); ?>">New Credit Sales</a></li>
								  <li> <a href="<?php echo base_url('dashboard/credit_report_sales_rep'); ?>">My Credit Sales</a></li>
							</ul>
							</li>	
							<li>
                            <div class="dropdown-tools">
                              <div class="btn-group xs-mt-5 xs-mb-10">
                                <a href="<?php  echo base_url('auth/logout') ?>"   class="btn btn-default"><span class="mdi mdi-power"></span></a>
                                <a href="<?php  echo base_url('dashboard/myprofile') ?>"  class="btn btn-default active"><span class="mdi mdi-face"></span></a>
                              </div>
                            </div>
                          </li>
						 
					</ul>
			  </div>
			</div>
		  </div>
		</div>
	</div>