<div class="be-left-sidebar">
    <div class="left-sidebar-wrapper"><a href="#" class="left-sidebar-toggle">Dashboard</a>
        <div class="left-sidebar-spacer">
            <div class="left-sidebar-scroll">
                <div class="left-sidebar-content">
                    <ul class="sidebar-elements">
                        <li> <a href="<?php echo base_url(); ?>"><span class="icon mdi mdi-home"></span>Dashboard</a></li>
                        <li class="parent"><a href="#"><span class="icon mdi mdi-shopping-cart"></span>Daily Sales Report</a>
                            <ul class="sub-menu">
                                <li><a href="<?php echo base_url('dashboard/sales')  ?>">Daily Sales List</a></li>
                            </ul>
                        </li>
                        <li class="parent"><a href="#"><span class="icon mdi mdi-account-box-o"></span>Service Management</a>
                            <ul class="sub-menu">
                                <li><a href="<?php echo base_url('dashboard/service_category')  ?>">Service Category</a></li>
                                <li><a href="<?php echo base_url('dashboard/new_service')  ?>">New Service</a></li>
                                <li><a href="<?php echo base_url('dashboard/list_service')  ?>">List Service</a></li>
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