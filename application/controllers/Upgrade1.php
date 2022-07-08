<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Upgrade extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('tank_auth');
        $this->load->model("utils");
        $this->load->model("users");
        $this->load->model("stock");
        $this->load->model("settings");
        $this->load->model("invoice");
    }


    public function index(){
        $this->db->query('CREATE TABLE `department` (
                          `SN` int(11) NOT NULL,
                          `department` varchar(300) NOT NULL,
                          `type` enum(\'Service\',\'Sales\',\'Others\') NOT NULL,
                          `created` timestamp NOT NULL DEFAULT current_timestamp(),
                          `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
                        ) ENGINE=InnoDB DEFAULT CHARSET=latin1');

        $this->db->query('ALTER TABLE `department` ADD PRIMARY KEY (`SN`)');
        $this->db->query('ALTER TABLE `department` MODIFY `SN` int(11) NOT NULL AUTO_INCREMENT');
        $this->db->query('CREATE TABLE `services` (
                                      `SN` int(11) NOT NULL,
                                      `servicecode` varchar(100) NOT NULL,
                                      `name` varchar(500) NOT NULL,
                                      `price` decimal(12,3) NOT NULL,
                                      `category` int(11) NOT NULL,
                                      `department` varchar(100) NOT NULL,
                                      `description` text NOT NULL,
                                      `status` int(11) NOT NULL DEFAULT 1,
                                      `created` timestamp NOT NULL DEFAULT current_timestamp(),
                                      `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
                                    ) ENGINE=InnoDB DEFAULT CHARSET=latin1'
        );

        $this->db->query('CREATE TABLE `service_category` (
                          `SN` int(11) NOT NULL,
                          `name` tinytext NOT NULL,
                          `vat` int(11) NOT NULL,
                          `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                          `created` timestamp NOT NULL DEFAULT current_timestamp()
                        ) ENGINE=InnoDB DEFAULT CHARSET=latin1');

        $this->db->query('ALTER TABLE `services` ADD PRIMARY KEY (`SN`)');

        $this->db->query('ALTER TABLE `service_category` ADD PRIMARY KEY (`SN`)');

        $this->db->query('ALTER TABLE `services` MODIFY `SN` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1');

        $this->db->query('ALTER TABLE `service_category` MODIFY `SN` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1');

        $this->db->query('ALTER TABLE `sales` ADD `department` VARCHAR(120) NOT NULL AFTER `user_id`');

        $this->db->query('ALTER TABLE `stock` ADD `department` VARCHAR(120) NOT NULL AFTER `quantity`');

        $this->db->query('ALTER TABLE `users` ADD `department` VARCHAR(120) NOT NULL AFTER `salary`');

        $this->db->query("UPDATE stock set department='SuperMarket'");

        $this->db->query("ALTER TABLE `services` ADD `service_type` ENUM('Hourly Service','Normal Service') NOT NULL DEFAULT 'Normal Service' AFTER `department`");

        $this->db->query('ALTER TABLE `services` ADD `image` VARCHAR(1000) NOT NULL AFTER `service_type`');

        $this->db->query('ALTER TABLE `others` ADD `footer_rec_service` TEXT NOT NULL AFTER `footer_rec`');

        $users = $this->db->get('users');

        foreach($users->result() as $result){
            if($result->role == "Sales Representative" || $result->role == "Stock Officer" || $result->role == "Others"){
                $this->db->where('id',$result->id)->update('users',['department'=>'SuperMarket']);
            }else{
                $this->db->where('id',$result->id)->update('users',['department'=>'Administrator']);
            }
        }
        //make sure all previous sales are sent to supermarket

        $this->db->query("Update sales set department='SuperMarket'");

        $this->db->query("ALTER TABLE `stock_recieved` ADD `department` VARCHAR(30) NOT NULL AFTER `products`");

        $this->db->query("ALTER TABLE `users` CHANGE `role` `role` ENUM('Sales Representative','Administrator','Manager','Accountant','Auditor','Stock Officer','Others','Superuser','Service Administrator') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL");

        $this->db->query("INSERT INTO `department` (`SN`, `department`, `type`, `created`, `updated`) VALUES (1, 'SuperMarket', 'Sales', '2020-10-21 10:27:25', '2020-10-21 10:27:25'), (2, 'Games', 'Service', '2020-10-21 10:27:30', '2020-10-21 10:27:30'), (3, 'Whole-Sales', 'Sales', '2020-10-21 10:27:42', '2020-10-21 10:27:42'), (4, 'Massage', 'Service', '2020-10-21 11:17:48', '2020-10-27 11:23:07'),(5, 'Saloon', 'Service', '2020-10-21 11:17:50', '2020-10-27 11:23:22')");

        $this->db->query("Update stock_recieved set department='SuperMarket'");
    }




}