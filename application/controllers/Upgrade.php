<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Upgrade extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->helper('security');
        $this->load->model('stock');
        $this->load->model('settings');
        $this->load->library('tank_auth');
        $this->lang->load('tank_auth');
    }

    public function index(){

        $tables = $this->db->list_tables();
        foreach ($tables as $table)
        {
            $this->db->query('ALTER TABLE `'.$table.'` ADD `last_data_updated` DATETIME on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        }

        $this->db->query('CREATE TABLE `settingsnoedit` (
          `sne_id` int(11) NOT NULL,
          `sne_key` varchar(200) NOT NULL,
          `sne_value` varchar(200) NOT NULL,
          `sne_branchid` int(11) DEFAULT NULL,
          `settingsNoEdit_code` varchar(20) DEFAULT NULL,
          `lastupdated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
        ) ENGINE=MyISAM DEFAULT CHARSET=latin1;');


        $this->db->query("
        INSERT INTO `settingsnoedit` (`sne_id`, `sne_key`, `sne_value`, `sne_branchid`, `settingsNoEdit_code`, `lastupdated`) VALUES
        (1, 'last_to_online_sync', '0', 1, NULL, '2021-04-07 07:31:16'),
        (2, 'last_to_offline_sync', '0', 1, NULL, '2021-04-07 06:19:56');
        ");

        $this->db->query("
            ALTER TABLE `settingsnoedit`
              ADD PRIMARY KEY (`sne_id`),
              ADD KEY `sne_branchid` (`sne_branchid`);
        ");

        $this->db->query("
            ALTER TABLE `settingsnoedit`
            MODIFY `sne_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
        ");

    }

}
