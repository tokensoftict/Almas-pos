<div class="be-left-sidebar">
    <div class="left-sidebar-wrapper"><a href="#" class="left-sidebar-toggle">Dashboard</a>
        <div class="left-sidebar-spacer">
            <div class="left-sidebar-scroll">
                <div class="left-sidebar-content">
                    <ul class="sidebar-elements">
                        <li> <a href="<?php echo base_url(); ?>"><span class="icon mdi mdi-home"></span>Dashboard</a></li>
                        <li class="parent"><a href="#"><span class="icon mdi mdi-shopping-cart"></span>Open POS</a>
                            <ul class="sub-menu">
                                <?php
                                $dpts = $this->stock->getDepartments();
                                foreach($dpts as $dpt){
                                    $department[$dpt['department']] = array('Sales Representative','Administrator');
                                }
                                ?>
                                <?php
                                foreach($department as $key=>$dpt){
                                    ?>
                                    <li><a href="<?php echo base_url('dashboard/openPos/'.$key)  ?>"><?php echo $key ?></a></li>
                                <?php } ?>

                            </ul>
                        </li>
                        <li class="parent"><a href="#"><span class="icon mdi mdi-shopping-cart"></span>Daily Sales Report</a>
                            <ul class="sub-menu">
                                <li><a href="<?php echo base_url('dashboard/sales')  ?>">Daily Sales List</a></li>
                                <li><a href="<?php echo base_url('dashboard/sales_product')  ?>">Daily Product Sales Report</a></li>
                                <!--<li><a href="<?php echo base_url('dashboard/invoices')  ?>">Daily Invoice List</a></li>-->
                            </ul>
                        </li>
                        <!--
                        <li class="parent"><a href="#"><span class="icon mdi mdi-assignment"></span>Credit & Deposit</a>
                            <ul class="sub-menu">
                                <li><a href="<?php echo base_url('dashboard/deposits')  ?>">Deposits</a></li>
                                <li><a href="<?php echo base_url('dashboard/credit')  ?>">Daily Credit Sales</a></li>
                                <li><a href="<?php echo base_url('dashboard/due_payment_report')  ?>">Due Payment List</a></li>
                            </ul>
                        </li>-->
                        <li class="parent"><a href="#"><span class="icon mdi mdi-format-list-numbered"></span>Stock Manager</a>
                            <ul class="sub-menu">
                                <li> <a href="<?php echo base_url('dashboard/stock'); ?>">Manage Stock</a></li>
                             <!--   <li> <a href="<?php echo base_url('dashboard/stock_transfer'); ?>">Transfer Stock</a></li>-->
                                <li> <a href="<?php echo base_url('dashboard/stock_expiry'); ?>">Expired Stock List</a></li>
                                <li> <a href="<?php echo base_url('dashboard/stock_recieved'); ?>">Received Stock</a></li>
                             <!--   <li> <a href="<?php echo base_url('dashboard/stock_by_department'); ?>">List Stock By Department</a></li> -->
                                <li> <a href="<?php echo base_url('dashboard/out_of_stock'); ?>">Out of Stock</a></li>
                              <!--  <li> <a href="<?php echo base_url('dashboard/daily_stock_report'); ?>">Daily Stock Balance</a></li>-->
                            </ul>
                        </li>
                     <!--   <li class="parent"><a href="#"><span class="icon mdi mdi-format-list-numbered"></span>Branch Stock</a>
                            <ul class="sub-menu">
                                <?php
                                $branches = $this->db->get('branch')->result_array();
                                foreach($branches as $branch) {
                                    ?>
                                    <li> <a href="<?php echo base_url('dashboard/stock_by_branch/'.$branch['SN']); ?>"><?php echo $branch['branch_name'] ?>'s Product/Stock</a></li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </li>-->
                        <li class="parent"><a href="#"><span class="icon mdi mdi-face"></span>Customer Manager</a>
                            <ul class="sub-menu">
                                <li> <a href="<?php echo base_url('dashboard/customerlist'); ?>">Customers List</a></li>
                               <!-- <li> <a href="<?php echo base_url('dashboard/customercreditlog'); ?>">Customers Credit Log</a></li>-->
                            </ul>
                        </li>
                        <!--    <li class="parent"><a href="#"><span class="icon mdi mdi-format-list-numbered"></span>Assets Management</a>
                            <ul class="sub-menu">
                                <li><a href="<?php echo base_url('dashboard/new_assets')  ?>">New Assests</a></li>
                                <li><a href="<?php echo base_url('dashboard/assets_list')  ?>">Assets List</a></li>
                            </ul>
                        </li>

                        <li class="parent"><a href="#"><span class="icon mdi mdi-account-box-o"></span>Service Management</a>
                            <ul class="sub-menu">
                                <li><a href="<?php echo base_url('dashboard/service_category')  ?>">Service Category</a></li>
                                <li><a href="<?php echo base_url('dashboard/new_service')  ?>">New Service</a></li>
                                <li><a href="<?php echo base_url('dashboard/list_service')  ?>">List Service</a></li>
                            </ul>
                        </li>

                        <li class="parent"><a href="#"><span class="icon mdi mdi-chart"></span>Sales Report</a>
                            <ul class="sub-menu">
                                <li> <a href="<?php echo base_url('dashboard/sales_report'); ?>">General Report</a></li>
                                <li> <a href="<?php echo base_url('dashboard/payment_method_sales_report'); ?>">Report By Sales and Payment method</a></li>
                                <li> <a href="<?php echo base_url('dashboard/refund_report'); ?>">Refund Report</a></li>
                                <li> <a href="<?php echo base_url('dashboard/report_customer'); ?>">Report By Customer</a></li>
                                <li> <a href="<?php echo base_url('dashboard/report_sales_rep'); ?>">Report By Sales Rep</a></li>
                                <li> <a href="<?php echo base_url('dashboard/report_stock'); ?>">Report By Product/Stock</a></li>
                                <li> <a href="<?php echo base_url('dashboard/report_payment_method'); ?>">Report Payment Method</a></li>
                            </ul>
                        </li>
                        -->
                        <!--
							<li class="parent"><a href="#"><span class="icon mdi mdi-chart-donut"></span>Invoice Report</a>
							<ul class="sub-menu">
								  <li> <a href="<?php echo base_url('dashboard/invoice_report'); ?>">General Report</a></li>
								  <li> <a href="<?php echo base_url('dashboard/invoice_report_customer'); ?>">Report By Customer</a></li>
								  <li> <a href="<?php echo base_url('dashboard/invoice_report_sales_rep'); ?>">Report By Sales Rep</a></li>
							</ul>
							</li>

                        <li class="parent"><a href="#"><span class="icon mdi mdi-format-list-numbered"></span>Expenses & Salary</a>
                            <ul class="sub-menu">
                                <li> <a href="<?php echo base_url('dashboard/new_expenses'); ?>">New Expenses</a></li>
                                <li> <a href="<?php echo base_url('dashboard/expenses_report'); ?>">Today's Expenses</a></li>
                                <li> <a href="<?php echo base_url('dashboard/staff_salary'); ?>">Staff Salary</a></li>
                                <li> <a href="<?php echo base_url('dashboard/expenses_monthly_report'); ?>">Monthly Expenses</a></li>

                            </ul>
                        </li>-->
                        <!-- <li class="parent"><a href="#"><span class="icon mdi mdi-format-list-numbered"></span>Bank Credit & Debit</a>
                            <ul class="sub-menu">
                                <li> <a href="<?php echo base_url('dashboard/new_entry'); ?>">Bank Deposit/Withdraw</a></li>
                                <li> <a href="<?php echo base_url('dashboard/deposit_credit_report'); ?>">Bank Deposit/Withdraw Report</a></li>
                            </ul>
                        </li>
                        <li class="parent"><a href="#"><span class="icon mdi mdi-chart-donut"></span>Stock Report</a>
                            <ul class="sub-menu">
                                <li> <a href="<?php echo base_url('dashboard/recieved_stock_report'); ?>">Received Stock Report</a></li>
                                <li> <a href="<?php echo base_url('dashboard/recieved_stock_report_product'); ?>">Daily Stock Received</a></li>
                                <li> <a href="<?php echo base_url('dashboard/transfer_stock_report'); ?>">Transfer Stock Report</a></li>
                            </ul>
                        </li>

							<li class="parent"><a href="#"><span class="icon mdi mdi-chart-donut"></span>Deposit Report</a>
							<ul class="sub-menu">
								  <li> <a href="<?php echo base_url('dashboard/deposit_report'); ?>">General Report</a></li>
								  <li> <a href="<?php echo base_url('dashboard/deposit_report_customer'); ?>">Report By Customer</a></li>
								  <li> <a href="<?php echo base_url('dashboard/deposit_report_sales_rep'); ?>">Report By Sales Rep</a></li>
								  <li> <a href="<?php echo base_url('dashboard/deposit_report_payment_method'); ?>">Report Payment Method</a></li>
							</ul>
							</li>

                        <li class="parent"><a href="#"><span class="icon mdi mdi-chart-donut"></span>Credit Report</a>
                            <ul class="sub-menu">
                                <li> <a href="<?php echo base_url('dashboard/credit_reports'); ?>">General Report</a></li>
                                <li> <a href="<?php echo base_url('dashboard/credit_report_customer'); ?>">Report By Customer</a></li>
                                <li> <a href="<?php echo base_url('dashboard/credit_report_sales_rep'); ?>">Report By Sales Rep</a></li>
                                <li> <a href="<?php echo base_url('dashboard/credit_payment_report'); ?>">Credit Payment Report</a></li>
                            </ul>
                        </li>
                        -->
                        <li class="parent"><a href="#"><span class="icon mdi mdi-settings"></span>Settings</a>
                            <ul class="sub-menu">
                              <!--  <li> <a href="<?php echo base_url('dashboard/discount_manager'); ?>">Discount Manager</a></li>-->
                                <li> <a href="<?php echo base_url('dashboard/backup'); ?>">Database Backup</a></li>
                                <li> <a href="<?php echo base_url('dashboard/onlineSync'); ?>">Sync To Online</a></li>
                                <!-- <li> <a href="<?php echo base_url('dashboard/barcode_page'); ?>">Generate Barcode</a></li>
                                  <li> <a href="<?php echo base_url('dashboard/department'); ?>">Department</a></li>
                               <li> <a href="<?php echo base_url('dashboard/branch'); ?>">Branch</a></li>-->
                                <li> <a href="<?php echo base_url('dashboard/manufacturer'); ?>">Product Manufacturer</a></li>
                                <li> <a href="<?php echo base_url('dashboard/category'); ?>">Product Category</a></li>
                                <li> <a href="<?php echo base_url('dashboard/suppliers'); ?>">Suppliers</a></li>
                                <!--   <li> <a href="<?php echo base_url('dashboard/payment_method'); ?>">Payment Method</a></li>
                              <li> <a href="<?php echo base_url('dashboard/bank_manager'); ?>">Bank List</a></li>-->

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
