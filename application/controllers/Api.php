<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Api extends CI_Controller {
    public function index() {
        $json_data = file_get_contents("php://input");
        if ($json_data != null) {
            $data_array = json_decode($json_data, true);
            $insert_count = $update_count = 0;
            foreach ($data_array as $table => $data) {
                foreach ($data as $d) {
                    $nosync = ['settingsnoedit', 'user_profiles','login_attempts'];
                    if (!in_array($table, $nosync)) {
                        $check = $this->db->get_where($table, array('SN' => $d['SN']))->row('SN');unset($d['updated']);
                        if ($check == null) {
                            $insert = $this->db->insert($table, $d);
                            $insert_count++;
                        } else {
                            $update = $this->db->where('SN', $d['SN']);
                            $update = $this->db->update($table, $d);
                            $update_count++;
                        }
                    }
                }
            }
            echo json_encode(['new' => $insert_count, 'update' => $update_count]);
        }
    }

    private function tableToJson($table,$field,$last_to_offline_sync) {
        $this->db->where($field.' >=', $last_to_offline_sync);
        $result = $this->db->get($table)->result_array();
        return $result;
    }


    public function sync_data_to_offline() {
        $data = [];
        $last_to_offline_sync = $this->db->get_where('settingsnoedit',['sne_key'=>'last_to_offline_sync',
            'sne_branchid'=>1])->row('sne_value');
        $tables = $this->db->list_tables();
        foreach($tables as $table) {
            $nosync = ['settingsnoedit', 'user_profiles','login_attempts'];
            if(!in_array($table,$nosync)) $data[$table] = $this->tableToJson($table,'updated',$last_to_offline_sync);
        }
        $this->db->set('sne_value', date('Y-m-d H:i:s'));
        $this->db->where('sne_key', 'last_to_offline_sync');
        $this->db->where('sne_branchid', 1);
        $this->db->update('settingsnoedit');

        echo json_encode($data);
        exit;
    }



    private function filterDuplicateData($table,$field,$value) {
        $query = $this->db->get_where($table,array($field=>$value))->row($field);
        if($query == null){
            return true;
        }

        return false;
    }
 }
