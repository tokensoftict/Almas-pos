<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller
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
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		}
	}

	function index()
	{
		
	    if($this->users->get_user_by_username($this->session->userdata("username"))->role=="Sales Representative"){
			redirect('dashboard/pos');
		}
		
			$data = array();
			$data['page'] = 'dashboard';
			$this->load->view('page/heading',$data);
			$this->load->view('page/footer',$data);
	
	}


    public function openPos($department){
       $dpt_type = $this->db->get_where('department',array('department'=>$department))->row();

        $this->session->set_userdata('top_administrator_department',$department);

        if($dpt_type->type == "Service"){

            if(file_exists('store_assets/'.strtolower($department).'.jpeg')){
                    $img = "store_assets/".strtolower($department).".jpeg";
                    $img = base_url($img);
            }else{
                $extra_charges=$this->db->from("others")->where("SN","1")->get()->result_array()[0];
                $img = $extra_charges['slogo'];
            }

            $data = ['endpoint'=>'serviceSave','placeholder'=>$img];

            $this->load->view("pos/terminal-others",$data);
        }

        if($dpt_type->type == "Sales"){
            $this->load->view("pos/terminal");
        }

    }

    function stock()
    {
        $data = array();
        $data['page'] = 'stock';
        $this->load->model("stock");
        if($this->stock->getUserDepartment() !="Administrator"){
            $data['stocks'] = $this->stock->getStocks(array('department'=>$this->stock->getUserDepartment()));
        }else{
            $data['stocks'] = $this->stock->getStocks();
        }
        $this->load->view('page/heading',$data);
        $this->load->view('page/footer',$data);

    }


    public function stock_by_branch($branch_id){
        $data = array();
        $data['page'] = 'stock_branch';
        $this->load->model("stock");
        $data['branch_id'] = $branch_id;
        $data['stocks'] = $this->stock->getStocks_by_branch($branch_id);
        $this->load->view('page/heading',$data);
        $this->load->view('page/footer',$data);
    }


    function stock_by_department()
    {
        $data = array();
        $data['page'] = 'stockby_department';
        $this->load->model("stock");
        if(isset($_POST['department'])){
            $department = $_POST['department'];
        }else{
            $de =[];
            $dpts = $this->stock->getDepartments();
            foreach($dpts as $dpt) {
                if ($dpt['type'] != "Service") {
                    $de[] = $dpt['department'];
                }
            }
            $department = $de[0];
            $_POST['department'] = $department;
        }
        $data['stocks'] = $this->stock->getStocks(array('department'=>$department));
        $this->load->view('page/heading',$data);
        $this->load->view('page/footer',$data);

    }
	
	
	function stock_expiry()
	{
			$data = array();
			$data['page'] = 'stock_expiry';
			$this->load->model("stock");
			$data['stocks'] = $this->stock->getStocks();
			$this->load->view('page/heading',$data);
			$this->load->view('page/footer',$data);
		
	}
	
	
	function out_of_stock()
	{
			$data = array();
			$data['page'] = 'out_of_stock';
			$this->load->model("stock");
			$data['stocks'] = $this->stock->getStocks();
			$this->load->view('page/heading',$data);
			$this->load->view('page/footer',$data);
		
	}
	
	function set_availability($product_id,$status){
		$this->db->where("SN",$product_id)->update("stock",array('status'=>$status));
		$this->session->set_flashdata("success","Operation Successfull!!...");
		redirect('dashboard/stock');
	}
	function new_stock()
	{
			$data = array();
			$this->load->model("stock");
			$data['page'] = 'new_stock';
			$this->load->view('page/heading',$data);
			$this->load->view('page/footer',$data);
		
	}
	
	
	function save_new_stock(){
		$data = $_POST;
		
		$data['image'] = '';
		$data['product_description'] = $_POST['product_description'];
		
		if($this->db->get_where("stock",array("product_name"=>$_POST['product_name']))->num_rows() > 0){
			$this->session->set_flashdata("error","Product Already Exist");
			redirect('dashboard/new_stock');
		}	
		
		$this->session->set_flashdata("success","Stock Added Successfully!!...");
		$this->load->model("stock");
		$this->stock->add($data);
		redirect('dashboard/new_stock');
	}
	
	function edit_stock($stock_id){
		$data = array();
		$this->load->model("stock");
		$data['page'] = 'edit_stock';
		$data['stock'] = $this->stock->getStock($stock_id)[0];
	
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
	}
	
	function update_stock($stock_id){
		$this->load->model("stock");
		$stock =$this->stock->getStock($stock_id);
		$pre_image = $stock['image'];
		if(!empty($stock['image'])){
			unlink('product_image/'.$stock['image']);
		}
		if(is_uploaded_file($_FILES['image']) && file_exists($_FILES['image']['tmp_name'])){
			$image = time()."-".date('d').'.'.(pathinfo($_FILES['image']['name'])['extension']);
			move_uploaded_file($_FILES['image']['tmp_name'],'product_image/'.$image);
		}else{
			$image = '';
		}
		
		$data = $_POST;
		
		$data['image'] = $image;
		
		$this->session->set_flashdata("success","Stock Updated Successfully!!...");
		
		$this->stock->update($stock_id,$data);
		redirect('dashboard/stock');
	}
	
	
	function checkifBarcodeExist($code,$dept){
		$code = $this->db->get_where("stock",array("bar_code_code"=>$code,'department'=>$dept));
		if($code->num_rows() > 0){
			die(json_encode(array("status"=>false)));
		}else{
			die(json_encode(array("status"=>true)));			
		}
	}
	
	function move_to_sales_arena($stock_id){
		$this->load->model("stock");
		if(count($_POST)){
			$stock =$this->stock->getStock($stock_id);
			$qty = ( $stock['quantity'] -$_POST['qty_to_move'] );
			$new_m_qty =  $stock['quantity_arena']+ $_POST['qty_to_move'] ;
			
			if(!($qty < 0)){
				$this->db->where("SN",$stock_id)->update("stock",array("quantity"=>$qty,"quantity_arena"=>$new_m_qty));
				$array_insert = array(
										'stock_id'=>$stock_id,
										'from'=>'Store',
										'to'=> 'Sales Arena',
										'date'=>date('Y-m-d'),
										'qty_moved'=>$_POST['qty_to_move'],
										'remaining_qty'=>$qty
									);
				$this->db->insert("moved_history",$array_insert);
				$this->session->set_flashdata("success","Stock Moved to Sales Arena Successfully!!..");
				redirect("dashboard/stock");
			}else{
				$this->session->set_flashdata("error ","Insufficient Quantity to Moved, Check and try Again..");
				redirect("dashboard/move_to_sales_arena/".$stock_id);
			}
		}
		$this->load->model("stock");
		$data['page'] = 'move_to_sales_arena';
		$data['stock'] = $this->stock->getStock($stock_id);
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
	}
	
	function move_to_store($stock_id){
		$this->load->model("stock");
		if(count($_POST)){
			$stock =$this->stock->getStock($stock_id);
			$qty = ($stock['quantity_arena'] - $_POST['qty_to_move']);
			$new_m_qty =  $stock['quantity']+ $_POST['qty_to_move'] ;
			if(!($qty < 0)){
				$this->db->where("SN",$stock_id)->update("stock",array("quantity"=>$new_m_qty,"quantity_arena"=>$qty));
				$array_insert = array(
										'stock_id'=>$stock_id,
										'from'=>'Sales Arena',
										'to'=> 'Store',
										'date'=>date('Y-m-d'),
										'qty_moved'=>$_POST['qty_to_move'],
										'remaining_qty'=>$new_m_qty
									);
				$this->db->insert("moved_history",$array_insert);
				$this->session->set_flashdata("success","Stock Moved back to Store Successfully!!..");
				redirect("dashboard/stock");
			}else{
				$this->session->set_flashdata("error","Insufficient Quantity to Moved, Check and try Again..");
				redirect("dashboard/move_to_store/".$stock_id);
			}
		}
		$this->load->model("stock");
		$data['page'] = 'move_to_store';
		$data['stock'] = $this->stock->getStock($stock_id);
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
	}
	
	function settings($user_id=FALSE){
	 if(isset($_GET['del']) && $user_id!=FALSE){
			$this->db->set('activated', $_GET['del']);
			$this->db->where('id', $user_id);
			$this->db->update('users');
			redirect('dashboard/settings');
		}
		
		if(count($_POST)>0){
			$this->load->library("tank_auth");		
			$user = $this->tank_auth->create_user($_POST['username'],$_POST['email'],$_POST['password'],0,$_POST['role'],$this->config->item('email_activation', 'tank_auth'));
			foreach($_POST['extra'] as $key=>$val){
				if($key!="bank_account_no"){
				$_POST['extra'][$key] = ucwords($val);
				}
			}
			$this->db->where("id",$user['user_id'])->update("users",$_POST['extra']);
			redirect('dashboard/settings');
		}
		$data = array();
		$data['page'] = 'settings';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
	}
	
	 public function edit_settings($user_id){
		if(count($_POST)>0){
			foreach($_POST['extra'] as $key=>$val){
				$_POST[$key] = ucwords($val);
			}
			unset($_POST['extra']);
			$this->db->where("id",$user_id)->update("users",$_POST);
			$this->session->set_flashdata("success","Profile was updated successfully!");
			redirect('dashboard/settings');
		}
		$data = array();
		$data['page'] = 'edit_settings';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
  		
  	}
	
	
	function user_manager(){
	redirect('dashboard/settings');
	}
	
	
	function manufacturer(){
		$this->load->model('stock');
		if(count($_POST)>0){
			$this->stock->addManufacturer($_POST);
			$this->session->set_flashdata("success","Manufacturer Added Successfully!!...");
			redirect('dashboard/manufacturer');
		}
		if(isset($_GET['del'])){
			$this->db->where("SN",$this->uri->segment(3))->delete("manufacturer");
			$this->session->set_flashdata("success","Manufacturer deleted Successfully!!...");
			redirect('dashboard/manufacturer');
		}
		$data = array();
		$data['page'] = 'manufacturer';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
	}
	
	
	function supplier(){
		
		$this->load->model('stock');
		if(count($_POST)>0){
			$this->stock->addSupplier($_POST);
			$this->session->set_flashdata("success","Supplier Added Successfully!!...");
			redirect('dashboard/supplier');
		}
		if(isset($_GET['del'])){
			$this->db->where("SN",$this->uri->segment(3))->delete("supplier");
			$this->session->set_flashdata("success","Supplier deleted Successfully!!...");
			redirect('dashboard/supplier');
		}
		
		$data = array();
		$data['page'] = 'supplier';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
		
	}
	
	
	function branch(){
			$this->load->model('stock');
		if(count($_POST)>0){
			$this->stock->addBranch($_POST);
			$this->session->set_flashdata("success","Branch Added Successfully!!...");
			redirect('dashboard/branch');
		}
		if(isset($_GET['del'])){
			$this->db->where("SN",$this->uri->segment(3))->update("branch",array("delete_status"=>"1"));
			$this->session->set_flashdata("success","Branch deleted Successfully!!...");
			redirect('dashboard/branch');
		}
		
		$data = array();
		$data['page'] = 'branch';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
		
	}
	
	
	public function stock_transfer(){
		$this->load->model("stock");
		$data = array();
		$data['page'] = 'stock_transfer';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
	}
	
	public function new_transfer(){
		$this->load->model("stock");
		if(count($_POST)){
            foreach($_POST as $key=>$value){
                if(!is_array($value) && $key!="transfer_note"){
                    if(empty($value)){
                        $this->session->set_flashdata("error","All Fields are required, Please check form and try again");
                        redirect('dashboard/new_transfer');
                    }
                }

            }

            if(!isset($_POST['product'])){
                $this->session->set_flashdata("error","Please select product to transfer");
                redirect('dashboard/new_transfer');
            }

			$id = $this->stock->transfer_stock($_POST);
			$this->session->set_flashdata("success","Stock Transfer has been added Successfully!!...");
			redirect('dashboard/print_transfer/'.$id);
		}
		$this->load->model("stock");
		$data = array();
		$data['page'] = 'new_transfer';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
	}
	
	
	public function viewtransfer(){
		$this->load->model("stock");
		$data = array();
		$data['page'] = 'view_transfer';
		$data['transfer'] = $this->stock->getTransferByTransferID($this->uri->segment(3));
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
	}

	public function print_transfer(){
        $data = array();
        $data['transfer'] = $this->stock->getTransferByTransferID($this->uri->segment(3));
        $this->load->view('print/transfer',$data);
    }



	public function edit_transfer(){
		$this->load->model("stock");
		$data = array();
		$data['page'] = 'edit_transfer';
		$data['transfer'] = $this->stock->getTransferByTransferID($this->uri->segment(3));
		
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
	}
	
	public function update_transfer($transfer_id){
		$this->load->model("stock");
		$this->stock->update_stock($transfer_id,$_POST);
		$this->session->set_flashdata("success","Stock Transfer has been updated Successfully!!...");
		redirect("dashboard/viewtransfer/".$transfer_id);
	}
	
	public function choose_dept_new_recieved_supplier($dept){
		 $this->session->set_userdata('top_administrator_department',$dept);
		 redirect('dashboard/new_recieved_supplier');
	}
	
	public function stock_recieved(){
		$this->load->model("stock");
		$data = array();
		$data['page'] = 'stock_recieved';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
	}
	
	
	public function new_recieved_supplier(){
		$this->load->model("stock");
		if(count($_POST) >0){
			foreach($_POST as $key=>$value){
				if(!is_array($value) && $key!="transfer_note"){
					if(empty($value)){
						$this->session->set_flashdata("error","All Fields are required, Please check form and try again");	
						redirect('dashboard/new_recieved_supplier');
					}
				}
				
			}
			
			if(isset($_POST['product']) && count($_POST['product']) >0){
			$this->stock->recieve_stock($_POST);
			$this->session->set_flashdata("success","Stock Received has been added Successfully!!...");
			}else{
			$this->session->set_flashdata("error","No Product added, Please check and try again..");
			}
			redirect('dashboard/new_recieved_supplier');
		}
		$data = array();
		$data['page'] = 'new_recieved_supplier';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
		
	}
	public function new_recieved_branch(){
		$this->load->model("stock");
		if(count($_POST) >0){
			if(isset($_POST['product']) && count($_POST['product']) >0){
			$this->stock->recieve_stock($_POST);
			$this->session->set_flashdata("success","Stock Received has been added Successfully!!...");
			}else{
			$this->session->set_flashdata("error","No Product added, Please check and try again..");
			}
			redirect('dashboard/new_recieved_branch');
		}
		$data = array();
		$data['page'] = 'new_recieved_branch';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
		
	}
	
	
	public function view_received(){
		$this->load->model("stock");
		$data = array();
		$data['page'] = 'view_received';
		$data['transfer'] = $this->stock->getReceiveByReceiveID($this->uri->segment(3));
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
	}
	
	
	
	public function edit_received(){
		$this->load->model("stock");
		$data = array();
		$data['page'] = 'edit_recieved';
		$data['transfer'] = $this->stock->getReceiveByReceiveID($this->uri->segment(3));
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
	}
	
	public function update_received($transfer_id){
		$this->load->model("stock");
		if(isset($_POST['product']) && count($_POST['product']) >0){
		$this->stock->update_stock_recieved($transfer_id,$_POST);
		$this->session->set_flashdata("success","Stock Received has been updated Successfully!!...");
			}else{
			$this->session->set_flashdata("error","No Product added, Please add product to update recieved.");
			}
		redirect("dashboard/view_received/".$transfer_id);
	}
	
	
	public function transfer_stock_report(){
		$this->load->model("stock");
		$data = array();
		$data['page'] = 'transfer_stock_report';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
	}
	public function recieved_stock_report(){
		$this->load->model("stock");
		$data = array();
		$data['page'] = 'recieved_stock_report';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
	}
	public function stock_pick_up_report(){
		$this->load->model("stock");
		$data = array();
		$data['page'] = 'stock_pick_up_report';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
	}
	
	public function rma(){
		$this->load->model("stock");
		$data = array();
		$data['page'] = 'rma';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
		
	}
	public function newrma(){
		$this->load->model("stock");
		if(count($_POST) >0){
			$this->stock->addRMA($_POST);
			$this->session->set_flashdata("success","RMA Data has been added Successfully!!...");
			redirect("dashboard/newrma");
		}
		$this->load->model("stock");
		$data = array();
		$data['page'] = 'newrma';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
		
	}
	
	
	public function perform_rma_action($rma_id,$action){
		$this->load->model("stock");
		$data = array();
		if(count($_POST) >0){
			if($_POST['btn'] =="Draft"){
				unset($_POST['btn']);
				$_POST['rma_action'] = $action;
				$this->db->where('rma_id',$rma_id)->update("rma_data",$_POST);
			}else{
				
			}
		}
		$data['page'] = 'rma_operation';
		$data['rma'] = $this->stock->getRMA($rma_id);
		$data['action']= $action;
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
	}
	
	public function rma_forms($rma_id){
		//0 ---> engineer Form
		//1 ---> Back to Supplier form
		//2 ---> Replaced for the customer form
		$data = array();
		$data['rma'] = $this->stock->getRMA($rma_id);
		$form_id = $_GET['id'];
		if($form_id == "1"){
			$this->load->view('page/rma_engineer',$data);
		}else{
			$this->load->view('page/rma_customer_form',$data);
		}
	
	}
	
	
	public function load_replaced_by_form($rma_id=0,$which = FALSE){
		$data = array();
		$data['rma'] = $this->stock->getRMA($rma_id);
		if($which != FALSE){
			if($which =="1"){
				$this->load->view('page/replace_by_femtechit',$data);
			}else{
				$this->load->view('page/replace_by_supplier',$data);
			}
		}
	}
	
	public function load_sent_to_engineer($rma_id=0,$which = FALSE){
		$data = array();
		$data['rma'] = $this->stock->getRMA($rma_id);
		if($which != FALSE){
			if($which =="1"){
				$this->load->view('page/femtechit_engineer',$data);
			}else{
				$this->load->view('page/warranty_engineer',$data);
			}
		}
	}
	
	public function getProductAssociatedWithBarcode(){
		$barcode= $_GET['barcode'];
		$product = $this->stock->getProductAssociatedWithBarcode($barcode);
		if($product!=false){
			if($product['status'] =="0"){
				die(json_encode(array('status'=>false,'message'=>'Product has been disabled Please contact admin!!...')));
			}else{
			die(json_encode($product));
			}
		}else{
			die(json_encode(array('status'=>false,'message'=>'Product not Found!!...')));
		}
	}
	public function getProductBySearch(){
		$barcode= $_GET['barcode'];
		$product = $this->stock->getProductBySearch($barcode);
		if($product!=false){
			die(json_encode($product));
		}else{
			die(json_encode(array('status'=>false,'message'=>'Product not Found!!...')));
		}
	}
    public function getProductBySearchService(){
        $barcode= $_GET['barcode'];
        $product = $this->stock->getProductBySearchService($barcode);
        if($product!=false){
            die(json_encode($product));
        }else{
            die(json_encode(array('status'=>false,'message'=>'Product not Found!!...')));
        }
    }
    public function getServiceAssociatedWithBarcode(){
        $barcode= $_GET['barcode'];
        $product = $this->stock->getServiceAssociatedWithBarcode($barcode);
        if($product!=false){
            die(json_encode($product));
        }else{
            die(json_encode(array('status'=>false,'message'=>'Product not Found!!...')));
        }
    }
	public function getProductAssociatedWithBarcodePickup(){
		$barcode= $_GET['barcode'];
		$product = $this->stock->getProductAssociatedWithBarcode($barcode);
		if($product!=false){
			if($product['stock_bar_status'] == "1"){
				die(json_encode(array('status'=>false,'message'=>'Product has been sold already')));
			}else if($product['stock_bar_status'] == "2"){
					die(json_encode(array('status'=>false,'message'=>'Product has already been transferred')));
			}else{
				$check_if_exist =$this->db->from("stock_pickup_items")->where("product_barcode",$barcode)->where("status","0")->get();
				if($check_if_exist->num_rows() >0){
					foreach($check_if_exist->result_array() as $exist);
					die(json_encode(array('status'=>false,'message'=>'Product has been pick by '.$exist['pickUpstaff'].' on '.$exist['pickup_date'])));
				}else{
					die(json_encode($product));
				}
			}
		}else{
			die(json_encode(array('status'=>false,'message'=>'Product not Found!!...')));
		}
	}
	
	
	public function view_stock_list($stock_id=0){
		$this->load->model("stock");
		$data = array();
		$data['page'] = 'view_stock_list';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);	
	}
	
	public function addPickUp(){
		$this->load->model("stock");
		$this->stock->addPickUp($_POST);
		die(json_encode(array("status"=>true, 'msg'=>"Stock pickup data was saved successfully")));
	}
	
	
	public function update_pick_up_status($sn=false){
		if($sn!=false){
			$this->load->model("stock");
			if($_POST['value'] == "returned"){				
				$pick =$this->stock->getPick($sn);
				$stock =$this->stock->getStock($pick['product']);
				$this->stock->addStock($pick['product'],1);
				$this->db->where("SN",$sn)->update("stock_pickup_items",array("status"=>"1"));
				if(!empty($pick['product_barcode'])){
					$this->db->where("bar_code")->update(array('status'=>0));
				}
				die(json_encode(array("status"=>true,"message"=>'<span class="label label-primary">Returned</span>')));
			}else if($_POST['value'] == "sold"){
				$pick =$this->stock->getPick($sn);
				$stock =$this->stock->getStock($pick['product']);
				$this->db->where("SN",$sn)->update("stock_pickup_items",array("status"=>"3"));
				if(!empty($pick['product_barcode'])){
					$this->db->where("bar_code")->update(array('status'=>1));
				}
				die(json_encode(array("status"=>true,"message"=>'<span class="label label-success">Sold</span>')));
			}else{
				$pick =$this->stock->getPick($sn);
				$stock =$this->stock->getStock($pick['product']);
				$this->stock->addArenaStock($pick['product'],1);
				$this->db->where("SN",$sn)->update("stock_pickup_items",array("status"=>"2"));
				if(!empty($pick['product_barcode'])){
					$this->db->where("bar_code")->update(array('status'=>0));
				}
				//status of pick up
											//0 ---> pending
											//1----> returned
											//2----> moved to arena
											//3----> sold
				die(json_encode(array("status"=>true,"message"=>'<span class="label label-primary">In-Arena</span>')));
			}
		}
	}
	
	
	public function mark_rma_as_completed($rma_id=FALSE,$extra){
		if($rma_id!=false){
			$this->db->where("rma_id",$rma_id)->update("rma",array("status"=>"1"));
				$this->session->set_flashdata("success","RMA Operation Marked Completed");
			redirect(base_url('dashboard/perform_rma_action/'.$rma_id.'/'.$extra));
		}
	}
	
	
	public function pos(){
	    $dept = $this->stock->getUserDepartmentPos();
	    $type = $this->db->get_where('department',array('department'=>$dept))->row();
	    if($type->type == "Service") {
            if(file_exists('store_assets/'.strtolower($dept).'.jpeg')){
                $img = "store_assets/".strtolower($dept).".jpeg";
                $img = base_url($img);
            }else{
                $extra_charges=$this->db->from("others")->where("SN","1")->get()->result_array()[0];
                $img = $extra_charges['slogo'];
            }
            $data = ['endpoint'=>'serviceSave','placeholder'=>$img];

            $this->load->view("pos/terminal-others",$data);
        }else{
            $this->load->view("pos/terminal");
        }
	}

    public function serviceSave(){
        $cart = $_POST['cart'];
        $user = $_POST['user_id'];
        $save_array['reciept_id'] = $this->utils->generateUniqueID('sales','reciept_id');
        $save_array['discount_type'] = $_POST['discount_type'];
        $save_array['comment'] = $_POST['comment'];
        $save_array['date'] = date('Y-m-d');
        $save_array['discount'] = $_POST['discount'];
        $save_array['user_id'] =$user;
        $save_array['sales_time'] = date("h:i a");
        $total = 0;
        $total_profit = 0;
        $savings = array();
        $error = array();
        $vat_cal_pro =  mt_rand(1,6);
        foreach($cart as $key=>$value){
            $item = $this->stock->getService($key);
            $savings[]=array(
                'item_name'=>$item['name'],
                'item_price'=>$item['price'],
                'item_qty'=>$value,
                'department'=>$item['department'],
                'total'=>($value*$item['price']),
                'cost_price'=>0,
                'total_cost_price'=>($value*0),
                'profit'=>(($value*$item['price']) - ($value*0)),
                'id'=>$key,
                'vat'=>$vat_cal_pro
            );
            $total+=($value*$item['price']);
            $total_profit+=(($value*$item['price']) - ($value*0));
        }
        $total_profit=$total_profit -$save_array['discount'];
        $vat = ($this->settings->getSettings()['vat']/100)*$total;
        $scharge =($this->settings->getSettings()['scharge']/100)*$total;
        $othertotal = $total - $save_array['discount'];
        $save_array['total_amount'] = $total;
        $save_array['items'] = json_encode($savings);
        $save_array['payment_method'] = $_POST['method'];
        if(isset($_POST['customer_id'])){
            $save_array['customer'] = $_POST['customer_id'];
        }else{
            $save_array['customer'] = 0;
        }
        $save_array['vat'] = $this->settings->getSettings()['vat'];
        $save_array['scharge'] = $this->settings->getSettings()['scharge'];
        $save_array['vat_amount'] = $vat_cal_pro;
        $save_array['s_charge_amt'] = (($save_array['scharge']/100)*$total);
        $save_array['total_amount_paid'] = $othertotal;
        $save_array['total_profit'] = $total_profit;
        $save_array['department'] = $this->stock->getUserDepartmentPos();
        if($this->input->post('sales_type')){
            $save_array['status'] = $this->input->post('sales_type');
        }else{
            $save_array['status'] = 'COMPLETE';
        }
        $this->db->insert("sales",$save_array);
        die(json_encode(array('status'=>true,'print'=>base_url('dashboard/print_recipt/'.$save_array['reciept_id'].'/'.$_POST['rec_size']))));

    }
	
	public function posSave(){
		$vat_cal = 0;
		$cart = $_POST['cart'];
		$user = $_POST['user_id'];
		//check if this transaction can go
        foreach($cart as $key=>$value) {
            $item = $this->stock->getStock($key)[0];
            if(!($value <= $item['quantity'])){
                die(json_encode(array('status'=>false,'message'=>'Not enough quantity for '.$item['product_name'])));
            }
        }
        //end of checking
		$save_array['reciept_id'] = $this->utils->generateUniqueID('sales','reciept_id');
		$save_array['discount_type'] = $_POST['discount_type'];
		$save_array['comment'] = $_POST['comment'];
		$save_array['date'] = date('Y-m-d');
		$save_array['discount'] = $_POST['discount'];
		$save_array['user_id'] =$user;
		$save_array['sales_time'] = date("h:i a");
		$total = 0;
		$total_profit = 0;
		$savings = array();
		$error = array();
		foreach($cart as $key=>$value){
			$item = $this->stock->getStock($key)[0];
			$this->session->set_userdata("tracking_id",$save_array['reciept_id']);
			$this->session->set_userdata("sold",true);
			$this->stock->removeStock($item['id_stock'],$value);
			$vat_cal_pro = 0;
			if($this->utils->checktaxable($item['category_id']) == true){
				$v = $this->settings->getSettings()['vat'];
				$total_cost = ($value*$item['cost_price']);
				$vat_cal_pro += ($v/100 * $total_cost);
			}else{
				$vat_cal_pro +=  0;
			}
			$vat_cal = $vat_cal+$vat_cal_pro;
			$savings[]=array(
							'item_name'=>$item['product_name'],
							'item_price'=>$item['price'],
							'item_qty'=>$value,
							'total'=>($value*$item['price']),
							'cost_price'=>$item['cost_price'],
							'total_cost_price'=>($value*$item['cost_price']),
							'profit'=>(($value*$item['price']) - ($value*$item['cost_price'])),
							'id'=>$key,	
							'vat'=>$vat_cal_pro
						);
			$total+=($value*$item['price']);
			$total_profit+=(($value*$item['price']) - ($value*$item['cost_price']));
		}
		$this->session->unset_userdata("sold");
		$this->session->unset_userdata("tracking_id");
		if($_POST['discount']!=0){
			if($_POST['discount_type']=="1"){
				$save_array['discount']=$_POST['discount'];
			}
		}
		$vat = ($this->settings->getSettings()['vat']/100)*$total;
		$scharge =($this->settings->getSettings()['scharge']/100)*$total;
		$othertotal = $total - $save_array['discount'];
		//$othertotal = $othertotal+$vat+$scharge; //remove vat from calculating total paid
		$othertotal = $othertotal;
		$save_array['total_amount'] = $total;
		$save_array['items'] = json_encode($savings);
		$save_array['payment_method'] = $_POST['method'];
		if(isset($_POST['customer_id'])){
			$save_array['customer'] = $_POST['customer_id'];
		}else{
			$save_array['customer'] = 0;
			//die(json_encode(array('status'=>false,'message'=>'Please Select a Customer')));
		}
		$save_array['vat'] = $this->settings->getSettings()['vat'];
		$save_array['scharge'] = $this->settings->getSettings()['scharge'];
		$save_array['vat_amount'] = $vat_cal;
		$save_array['s_charge_amt'] = (($save_array['scharge']/100)*$total);
		$save_array['total_amount_paid'] = $othertotal;
		$save_array['total_profit'] = $total_profit;
        $save_array['department'] = $this->stock->getUserDepartmentPos();
		if($this->input->post('sales_type')){
		$save_array['status'] = $this->input->post('sales_type');
		}else{
		$save_array['status'] = 'COMPLETE';	
		}
		$this->db->insert("sales",$save_array);
		die(json_encode(array('status'=>true,'print'=>base_url('dashboard/print_recipt/'.$save_array['reciept_id'].'/'.$_POST['rec_size']))));
	}	
	
	public function creditSales(){
		$vat_cal = 0;
		$cart = $_POST['cart'];
		$user = $_POST['user_id'];
		$save_array['credit_id'] = $this->utils->generateUniqueID('tbl_credit_sales','credit_id');
		$save_array['discount_type'] = $_POST['discount_type'];												 
		$save_array['comment'] = $_POST['comment'];
		$save_array['date'] = date('Y-m-d');
		$save_array['discount'] = $_POST['discount'];
		$save_array['user_id'] =$user;
		$save_array['sales_time'] = date("h:i a");
		$total = 0;
		$total_profit = 0;
		$savings = array();
		$error = array();
		
			foreach($cart as $key=>$value){
			$item = $this->stock->getStock($key)[0];
			$savings[]=array(
							'item_name'=>$item['product_name'],
							'item_price'=>$item['price'],
							'item_qty'=>$value,
							'total'=>($value*$item['price']),
							'cost_price'=>$item['cost_price'],
							'total_cost_price'=>($value*$item['cost_price']),
							'profit'=>(($value*$item['price']) - ($value*$item['cost_price'])),
							'id'=>$key,							
						);
			$total+=($value*$item['price']);
			$total_profit+=(($value*$item['price']) - ($value*$item['cost_price']));
		}
			$vat = ($this->settings->getSettings()['vat']/100)*$total;
		$scharge =($this->settings->getSettings()['scharge']/100)*$total;
		$othertotal = $total - $save_array['discount'];
		$othertotal = $othertotal+$vat+$scharge;
		//get credit limit from settings
        $credit_limit = $this->db->get_where("tbl_customers",array("SN"=>$_POST['customer_id']))->row()->credit_limit;
        if(!empty($credit_limit) && $credit_limit > 0){
            if($othertotal > $credit_limit){
                die(json_encode(array('status'=>false,'message'=>'Credit Sales Total can not be more than '.number_format($credit_limit,2) .', transaction not processed')));
            }
        }
        /*
		$extra_charges=$this->db->from("others")->where("SN","1")->get()->result_array()[0];
		if(!empty($extra_charges['credit_limit']) && $extra_charges['credit_limit'] > 0){
			if($othertotal > $extra_charges['credit_limit']){
				die(json_encode(array('status'=>false,'message'=>'Credit Sales Total can not be more than '.number_format($extra_charges['credit_limit'],2) .', transaction not processed')));
			}
		}
        */
		$total = 0;
		$total_profit = 0;
		$savings = array();
		$error = array();
		
			foreach($cart as $key=>$value){
			$item = $this->stock->getStock($key)[0];
			$this->session->set_userdata("tracking_id",$save_array['credit_id']);
			$this->session->set_userdata("sold",true);															 							 
			$this->stock->removeStock($item['id_stock'],$value);
			$vat_cal_pro = 0;
			if($this->utils->checktaxable($item['category_id']) == true){
				$v = $this->settings->getSettings()['vat'];
				$total_cost = ($value*$item['cost_price']);
				$vat_cal_pro += ($v/100 * $total_cost);
			}else{
				$vat_cal_pro +=  0;
			}
			$vat_cal = $vat_cal+$vat_cal_pro;
			$savings[]=array(
							'item_name'=>$item['product_name'],
							'item_price'=>$item['price'],
							'item_qty'=>$value,
							'total'=>($value*$item['price']),
							'cost_price'=>$item['cost_price'],
							'total_cost_price'=>($value*$item['cost_price']),
							'profit'=>(($value*$item['price']) - ($value*$item['cost_price'])),
							'id'=>$key,	
							'vat'=>$vat_cal_pro
						);
			/*
			$savings[]=array(
							'item_name'=>$item['product_name'],
							'item_price'=>$item['price'],
							'item_qty'=>$value,
							'total'=>($value*$item['price']),
							'cost_price'=>$item['cost_price'],
							'total_cost_price'=>($value*$item['cost_price']),
							'profit'=>(($value*$item['price']) - ($value*$item['cost_price'])),
							'id'=>$key,	
						);
			*/
			$total+=($value*$item['price']);
			$total_profit+=(($value*$item['price']) - ($value*$item['cost_price']));															   
		}
		$this->session->unset_userdata("sold");
		$this->session->unset_userdata("tracking_id");								 										
		if($_POST['discount']!=0){
			if($_POST['discount_type']=="1"){
				$save_array['discount']=$_POST['discount'];
			}
		}
		$vat = ($this->settings->getSettings()['vat']/100)*$total;
		$scharge =($this->settings->getSettings()['scharge']/100)*$total;
		$othertotal = $total - $save_array['discount'];
		//$othertotal = $othertotal+$vat+$scharge; //remove vat from calculating total paid
		$othertotal = $othertotal;
		$save_array['total_amount'] = $total;
		$save_array['items'] = json_encode($savings);
		$save_array['payment_method'] = $_POST['method'];
		if(isset($_POST['customer_id'])){
			$save_array['customer'] = $_POST['customer_id'];
		}else{
							   
			die(json_encode(array('status'=>false,'message'=>'Please Select a Customer')));
		}
		$save_array['vat'] = $this->settings->getSettings()['vat'];
		$save_array['scharge'] = $this->settings->getSettings()['scharge'];
		$save_array['vat_amount'] = $vat_cal;
		$save_array['s_charge_amt'] = (($save_array['scharge']/100)*$total);
		$save_array['total_amount_paid'] = $othertotal;
		$save_array['total_profit'] = $total_profit;
		$save_array['status'] = 'PENDING';
		$this->db->insert("tbl_credit_sales",$save_array);
		die(json_encode(array('status'=>true,'print'=>base_url('dashboard/print_credit_recipt/'.$save_array['credit_id'].'/'.$_POST['rec_size']))));	
		
	}
	
	public function depositSave($deposit_id){
		if(!$deposit_id){
			die(json_encode(array('status'=>false,'message'=>"Error locating Deposit Payment Invalid Transaction!!..")));
		}
		$cart = $_POST['cart'];
		$user = $_POST['user_id'];
		$save_array['reciept_id'] = $this->utils->generateUniqueID('sales','reciept_id');
		$save_array['discount_type'] = $_POST['discount_type'];
		$save_array['comment'] = $_POST['comment'];
		$save_array['date'] = date('Y-m-d');
		$save_array['discount'] = $_POST['discount'];
		$save_array['user_id'] =$user;
		$save_array['sales_time'] = date("h:i a");
		$total = 0;
		$total_profit= 0;
		$savings = array();
		$error = array();
			foreach($cart as $key=>$value){
			$item = $this->stock->getStock($key)[0];
			$this->session->set_userdata("tracking_id",$save_array['reciept_id']);
			$this->session->set_userdata("sold",true);
			$this->stock->removeStock($item['id_stock'],$value);
			$vat_cal_pro = 0;
			if($this->utils->checktaxable($item['category_id']) == true){
				$v = $this->settings->getSettings()['vat'];
				$total_cost = ($value*$item['cost_price']);
				$vat_cal_pro += ($v/100 * $total_cost);
			}else{
				$vat_cal_pro +=  mt_rand(1,10);
			}
			$vat_cal = $vat_cal+$vat_cal_pro;
			$savings[]=array(
							'item_name'=>$item['product_name'],
							'item_price'=>$item['price'],
							'item_qty'=>$value,
							'total'=>($value*$item['price']),
							'cost_price'=>$item['cost_price'],
							'total_cost_price'=>($value*$item['cost_price']),
							'profit'=>(($value*$item['price']) - ($value*$item['cost_price'])),
							'id'=>$key,	
							'vat'=>$vat_cal_pro
						);
			$total+=($value*$item['price']);
			$total_profit+=(($value*$item['price']) - ($value*$item['cost_price']));
		}
		$this->session->unset_userdata("sold");
		$this->session->unset_userdata("tracking_id");
		if($_POST['discount']!=0){
			if($_POST['discount_type']=="1"){
				$save_array['discount']=$_POST['discount'];
			}
		}
		$vat = ($this->settings->getSettings()['vat']/100)*$total;
		$scharge =($this->settings->getSettings()['scharge']/100)*$total;
		$othertotal = $total - $save_array['discount'];
		//$othertotal = $othertotal+$vat+$scharge; //remove vat from calculating total paid
		$othertotal = $othertotal;
		$save_array['total_amount'] = $total;
		$save_array['items'] = json_encode($savings);
		if(isset($_POST['invoice'])){
			$save_array['payment_type'] = "Invoice";
		}
		$save_array['payment_method'] = $_POST['method'];
		if(isset($_POST['customer_id'])){
			$save_array['customer'] = $_POST['customer_id'];
		}else{
			die(json_encode(array('status'=>false,'message'=>'Please Select a Customer')));
		}
		$save_array['vat'] = $this->settings->getSettings()['vat'];
		$save_array['scharge'] = $this->settings->getSettings()['scharge'];
		$save_array['vat_amount'] = $vat_cal;
		$save_array['s_charge_amt'] = (($save_array['scharge']/100)*$total);
		$save_array['total_amount_paid'] = $othertotal;
		$save_array['total_profit'] = $total_profit;
		$save_array['status'] = 'COMPLETE';	
		$this->db->insert("sales",$save_array);
		$s_id = $this->db->insert_id();
		//handle deposit
		$this->db->where("SN",$deposit_id)->update("deposit",array("deposit_status"=>"USED","sales_id"=>$s_id));
		die(json_encode(array('status'=>true,'redirect'=>base_url('dashboard/view_deposit/'.$deposit_id),'print'=>base_url('dashboard/print_recipt/'.$save_array['reciept_id'].'/'.$_POST['rec_size']))));
	}
	
  public function addCustomer(){
			$this->settings->addCustomer($_POST);
			$id =$this->db->insert_id();
			$customer =$this->settings->getCustomer($id);
			$customer['status'] = true;
			die(json_encode($customer));
  }
	public function refresh_stock_list(){
		$items = $this->stock->getSellable(array("quantity!="=>"0",'status'=>"1"));
	   if(count($items) >0){
	   foreach($items as $item){
		echo '<div data-qty="'.$item['quantity'].'" data-track="1" data-amount="'.$item['price'] .'" data-id="'.$item['SN'].'" data-item-name="'.$item['product_name'].'" onclick="addTocart(this)" class="col-lg-3 col-md-3 col-xs-6 shop-items filter-add-product noselect text-center" style="padding:5px; border-right: solid 1px #DEDEDE;border-top: solid 1px #DEDEDE;border-bottom: solid 1px #DEDEDE;" data-design="velvet v-neck body suit"><img data-original="'.$this->settings->getSettings()['slogo'].'" style="max-height: 64px; display: block;" class="img-responsive img-rounded lazy" src="'.$this->settings->getSettings()['slogo'] .'"><div class="caption text-center" style="padding:2px;overflow:hidden;"><strong class="item-grid-title"><span class="marquee_me">'.$item['product_name'] .'</span></strong><br><span class="align-center">&#8358;'. number_format($item['price'],2).'</span></div></div>'; 
	   }
	   }else{
		   echo '<h2 align="center" style="margin-top:28%;">No Available Product!..<h2>';
	   }
	}
	
	public function print_recipt($reciept_id,$receipt_type){
			$receipt_type =ucwords($receipt_type);
			$res = $this->stock->getSale($reciept_id);
			$data['payment'] = $res;
			$data['invoice'] = $res;
			if($receipt_type=="Thermal"){
			    if(empty($res['department'])){
                    $service_type = "Sales";
                }else{
                    $service_type = $this->db->get_where('department',array('department'=>$res['department']))->row()->type;
                }
			    if($service_type == "Service"){
                    if(file_exists('store_assets/'.strtolower($res['department']).'.jpeg')){
                        $img = "store_assets/".strtolower($res['department']).".jpeg";
                        $img = base_url($img);
                    }else{
                        $extra_charges=$this->db->from("others")->where("SN","1")->get()->result_array()[0];
                        $img = $extra_charges['slogo'];
                    }
                    $data['logo'] = $img;
                    $this->load->view('print/pos_recipt_print_service', $data);
                }else {
                    $this->load->view('print/pos_recipt_print', $data);
                }
			}else if($receipt_type=="Medium"){
				$this->load->view('print/medium',$data);
			}else if($receipt_type=="Big"){
				$this->load->view('print/big',$data);
			}else if($receipt_type=="WayBill"){
                $this->load->view('print/waybill',$data);
            }else if($receipt_type=="Communication"){
				$this->load->view('print/pos_recipt_print',$data);
			}else{
				$this->load->view('print/invoice',$data);
			}
	}
	
	
	public function print_credit_recipt($reciept_id,$receipt_type){
			$receipt_type =ucwords($receipt_type);
			$res = $this->stock->getCredit($reciept_id);
			$data['payment'] = $res;
			$data['invoice'] = $res;
			if($receipt_type=="Thermal"){
				$this->load->view('print/credit/pos_recipt_print',$data);
			}else if($receipt_type=="Medium"){
				$this->load->view('print/credit/pos_recipt_print',$data);
			}else if($receipt_type=="Big"){
				$this->load->view('print/credit/big',$data);
			}else if($receipt_type=="Communication"){
				$this->load->view('print/credit/pos_recipt_print',$data);
			}else{
				$this->load->view('print/credit/invoice',$data);
			}
	}
	
	
	
	public function print_add_depo_recipt($reciept_id,$receipt_type){
		$res = $this->stock->getDepositPayment($reciept_id);
		$data['payment'] = $res;
		if($receipt_type=="Thermal"){
				$this->load->view('print/deposit/pos_recipt_print',$data);
		}else if($receipt_type=="Medium"){
				$this->load->view('print/deposit/medium',$data);
		}else if($receipt_type=="Big"){
				$this->load->view('print/deposit/big',$data);
		}else if($receipt_type=="Communication"){
				$this->load->view('print/deposit/communication',$data);
		}else{
				$this->load->view('print/deposit/invoice',$data);
		}
	}
	public function print_add_depo_full_recipt($reciept_id,$receipt_type){
		$res = $this->stock->getDepositall($reciept_id);
		$data['payment'] = $res;
		if($receipt_type=="Thermal"){
				$this->load->view('print/deposit/deposit_all/pos_recipt_print',$data);
		}else if($receipt_type=="Medium"){
				$this->load->view('print/deposit/deposit_all/medium',$data);
		}else if($receipt_type=="Big"){
				$this->load->view('print/deposit/deposit_all/big',$data);
		}else if($receipt_type=="Communication"){
				$this->load->view('print/deposit/deposit_all/communication',$data);
		}else{
				$this->load->view('print/deposit/deposit_all/invoice',$data);
		}
	}
	
	
	public function sales(){
		$this->load->model("stock");
		$data = array();
		$data['page'] = 'sales';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
	}
	
	 public function Invoice($invoice_id=false){
	   if($invoice_id==false){
		   redirect('dashboard/invoices');
	   }
	   if(isset($_GET['markAspaid'])){
		 $invoice= $this->invoice->getInvoice($invoice_id);
		 $this->invoice->markAspaid($invoice_id);
		 $this->session->set_flashdata("success","Operation Successfull!!...");
		 redirect('dashboard/Invoice/'.$invoice_id);
	   }
	   $data = array();
	   $data['invoice'] = $this->invoice->getInvoice($invoice_id);
	   $this->load->view("print/invoice.print_canteen.php",$data);
   }
   
   
   public function invoices(){
	  $this->load->model("stock");
	  $data = array();
	  $data['page'] = 'invoices';
	  $this->load->view('page/heading',$data);
	  $this->load->view('page/footer',$data); 
   }
   
   
   public function sales_report(){
	  $data['page'] = 'sales_reports';
	  $this->load->view('page/heading',$data);
	  $this->load->view('page/footer',$data); 
   }  
   
   public function vat_report(){
	  $data['page'] = 'vat_report';
	  $this->load->view('page/heading',$data);
	  $this->load->view('page/footer',$data); 
   }  
   
   
   public function payment_method_sales_report(){
	  $data['page'] = 'payment_method_sales_report';
	  $this->load->view('page/heading',$data);
	  $this->load->view('page/footer',$data); 
   }

   public function refund_report(){
	  $data['page'] = 'refund_report';
	  $this->load->view('page/heading',$data);
	  $this->load->view('page/footer',$data); 
   }  
   
   public function admin_refund_report(){
	  $data['page'] = 'admin_refund_report';
	  $this->load->view('page/heading',$data);
	  $this->load->view('page/footer',$data); 
   } 
	   
   public function report_customer(){
	  $data['page'] = 'report_customer';
	  $this->load->view('page/heading',$data);
	  $this->load->view('page/footer',$data); 
   }
   public function report_stock(){
	    $data = array();
	  $data['page'] = 'report_stock';
	  $this->load->view('page/heading',$data);
	  $this->load->view('page/footer',$data); 
   }   
   public function sales_product(){
	   $data = array();
	  $data['page'] = 'sales_product';
	  $this->load->view('page/heading',$data);
	  $this->load->view('page/footer',$data); 
   }   
   
   public function report_sales_rep(){
	    $data = array();
	  $data['page'] = 'report_sales_rep';
	  $this->load->view('page/heading',$data);
	  $this->load->view('page/footer',$data); 
   }  
   
   public function report_payment_method(){
	  $data['page'] = 'report_payment_method';
	  $this->load->view('page/heading',$data);
	  $this->load->view('page/footer',$data); 
   }
   
public function bulk_report(){
	redirect(base_url('dashboard/report_stock'));
}
   
  public function myprofile(){
	   if(count($_POST) >0){
		   $this->db->where("id",$this->session->userdata("user_id"));
		   $this->db->update("users",array('email'=>$_POST['email']));
		   $this->session->set_flashdata("success","Operation Successful!!...");
		   if(isset($_POST['password']) && !(empty($_POST['password']))){
			   require_once(APPPATH.'libraries/phpass-0.1/PasswordHash.php');
				$password = $_POST['password'];
				$hasher = new PasswordHash(
								$this->config->item('phpass_hash_strength', 'tank_auth'),
								$this->config->item('phpass_hash_portable', 'tank_auth'));
				$hashed_password = $hasher->HashPassword($password);		
			   $this->users->change_password($this->session->userdata("user_id"),$hashed_password);
			   $this->session->set_flashdata("success","Operation Successful!!... Password has been Updated Successfully too.");
		   }
	   }
	   
	  $data['page'] = 'myprofile';
	  $this->load->view('page/heading',$data);
	  $this->load->view('page/footer',$data); 
	  
   }

  public function extra_charges(){
	  if(count($_POST)){		  
		if(!empty($_FILES['slogo']['name'])){ 
			if(getimagesize($_FILES['slogo']['tmp_name'])){
				$getImage = explode("/",$this->settings->getSettings()['slogo']);
				$getImage = $getImage[count($getImage)-1];
				if($getImage!="default.png"){
				@unlink("store_assets/".$getImage);
				}
				$extenstion = pathinfo($_FILES['slogo']['name'])['extension'];
				$store_image_name = time().'-'.'store_logo.'.$extenstion;
				move_uploaded_file($_FILES['slogo']['tmp_name'],'store_assets/'.$store_image_name);
				$_POST['slogo'] = base_url("store_assets/".$store_image_name);
			}
		}
		$this->db->where("SN","1")->update("others",$_POST);
		 $this->session->set_flashdata("success","Operation Successful!!...");
		redirect("dashboard/extra_charges");
	  }
	  $data['page'] = 'extra_charges';
	  $this->load->view('page/heading',$data);
	  $this->load->view('page/footer',$data); 
  }
  


  public function branch_has_been_deleted(){
	 $this->load->view('page/branch_has_been_deleted',$data);
  }
  
  
  public function backup(){
	 $data['page'] = 'backup';
	  $this->load->view('page/heading',$data);
	  $this->load->view('page/footer',$data);  
  }
  
 
  public function backupandUpload(){
	$path = 'db-backup-'.time().'.sql';
	
	$dbHost = $this->db->hostname;
	
	$dbUsername = $this->db->username;
	
	$dbPassword = $this->db->password;
	
	$database = $this->db->database;
	
	$this->utils->backupDatabaseTables($path,$dbHost,$dbUsername,$dbPassword,$database );
  }
 
  
  public function restore_database(){
		if(isset($_POST['restore_btn'])){
			if(file_exists($_FILES['restore']['tmp_name']) && is_uploaded_file($_FILES['restore']['tmp_name'])){
				$ext = pathinfo($_FILES['restore']['name']);
				if($ext['extension'] == "sql"){
					$filename=$_FILES['restore']['name'];
					$filepath=$_FILES['restore']['tmp_name'];	
					move_uploaded_file($filepath,'application/'.$filename);
					if($lines=file_get_contents('application/'.$filename)){
						$num =0;
						$sql ='';
						$clean_query =$lines;	
						$clean_query =explode(";",$clean_query);
						foreach($clean_query as $_query){
						if(!empty($_query)){
						$this->db->simple_query($_query);
						$num++;
						}
						}
						if($num > 0){
						$this->session->set_flashdata("success","Database Restore was Successful!... enjoy - (".$num.") query was executed successfully!!..");	
						redirect('dashboard/backup');						
						}else{
							$this->session->set_flashdata("error","Unable to restore database, seems you don't have any content in the file or you uploaded an invalid file !!.. Please try again");
							redirect('dashboard/backup');							
						}
					}else{
						$this->session->set_flashdata("error","Unable to restore database, we can not read the file you upload!!.. Please try again");
						redirect('dashboard/backup');						
					}
				}else{
						$this->session->set_flashdata("error","The file you uploaded seems not be an sql file... Please try again");
						redirect('dashboard/backup');						
				}
			}else{
						$this->session->set_flashdata("error","You did not upload anything!!..");
						redirect('dashboard/backup');						
			}
		}else{
			redirect('dashboard/backup');
		}
	 $data['page'] = 'backup';
	  $this->load->view('page/heading',$data);
	  $this->load->view('page/footer',$data);  

   }
  
  
	public function view_sales($id){
	 $data['page'] = 'view_sales';
	  $this->load->view('page/heading',$data);
	  $this->load->view('page/footer',$data); 
	}
	
	public function customerlist(){
	  $data['page'] = 'customerlist';
	  $this->load->view('page/heading',$data);
	  $this->load->view('page/footer',$data); 
	}


	public function customersalesreport(){
        $data['page'] = 'customersalesreport';
        $this->load->view('page/heading',$data);
        $this->load->view('page/footer',$data);
    }

    public function customersalesreportbreakdown(){
        $data['page'] = 'customersalesreport_break_down';
        $this->load->view('page/heading',$data);
        $this->load->view('page/footer',$data);
    }

	public function customercreditlog(){
        $data['page'] = 'customercreditlog';
        $this->load->view('page/heading',$data);
        $this->load->view('page/footer',$data);
    }

    public function payment_history($customer_id){
	    $data = [];
	    $data['customer_id'] = $customer_id;
        $data['page'] = 'customer_credit_payment_history';
        $this->load->view('page/heading',$data);
        $this->load->view('page/footer',$data);
    }


    public function clear_all_credit($customer_id,$amount){
        if(count($_POST)>0){
            $ins = $this->stock->getCreditRangeNodaterange(array('customer'=>$customer_id,'status'=>'PENDING'));
            foreach($ins as $in){
                $reciept_id = $this->utils->generateUniqueID('credit_payment_history','reciept_id');
                $credit_details = $this->stock->getCredit($in['credit_id']);
                $payment_b4 = $this->stock->getTotalAmountCreditPaid($credit_details['SN'],false);
                $bal = $credit_details['total_amount_paid'] - $payment_b4;
                $insert_array = array(
                        'customer_id'=>$customer_id,
                        'date_added'=>date('Y-m-d'),
                        'sales_rep'=>$this->users->get_user_by_username($this->session->userdata("username"))->id,
                        'payment_method'=>$_POST['payment_method'],
                        'credit_SN'=>$credit_details['SN'],
                        'branch_id'=>0,
                        'credit_id'=>$credit_details['credit_id'],
                        'reciept_id'=>$reciept_id,
                        'amount'=>$bal
                );
                $this->db->insert("credit_payment_history",$insert_array);
                $this->db->where("SN",$credit_details['SN'])->update("tbl_credit_sales",array("status"=>'COMPLETE'));
            }
            redirect('dashboard/customercreditlog');
        }
        $data = [];
        $data['customer_id'] = $customer_id;
        $data['amount'] = $amount;
        $data['page'] = 'clear_credit_payment';
        $this->load->view('page/heading',$data);
        $this->load->view('page/footer',$data);
    }

    public function credit_history($customer_id){
        $data = [];
        $data['customer_id'] = $customer_id;
        $data['page'] = 'credit_history_by_customer';
        $this->load->view('page/heading',$data);
        $this->load->view('page/footer',$data);
    }

	public function generateBarcode($code){
		$this->load->library('Zend');
		$this->zend->load('Zend/Barcode');
		Zend_Barcode::render('code39', 'image', array('text'=>$code), array());	
	}
	
	function barcode(){
		$this->load->view("page/barcodes");
	}
	function barcode_page(){
		$data = array();
		$data['page'] = 'barcode_page';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
	}
	
	function edit_sales($transaction_id){
		$data = array();
		$data['page'] = 'edit_sales';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
	}	
	function payment_method($id=false){
		if(count($_POST) >0){
			$_POST['defaults'] = "0";
			$_POST['payment_method'] = strtoupper($_POST['payment_method']);
			$this->db->insert("payment_method",$_POST);
			redirect("dashboard/payment_method");
		}
		if(isset($_GET['del'])){
			if($this->db->from("sales")->where("payment_method",$id)->get()->num_rows() > 0){
			$this->session->set_flashdata("error","Unable to delete Payment Method, transaction exists with this payment method");
			redirect("dashboard/payment_method");
			}else{
				$this->db->where("SN",$id)->delete("payment_method");
				redirect("dashboard/payment_method");
			}
		}
		$data = array();
		$data['page'] = 'payment_method';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
	}
	
	
	function deposits(){
		$data = array();
		$data['page'] = 'deposits';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);		
	}
	
	function add_deposit(){
		if(count($_POST)>0){
			$_POST['branch_id'] = $this->stock->getBranch_id();
			$_POST['reciept_id'] = $this->utils->generateUniqueID('deposit','reciept_id');
			$_POST['deposit_id'] = $this->utils->generateUniqueID('deposit','deposit_id');
			$this->db->insert("deposit",$_POST);
			$id = $this->db->insert_id();
			$_POST['deposit_SN'] = $id;
			unset($_POST['deposit_for']);
			$this->db->insert("deposit_payment_history",$_POST);
			redirect("dashboard/deposits");
		}
		$data = array();
		$data['page'] = 'add_deposits';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);			
	}
	
	function view_deposit($deposit_id=false){
		if($deposit_id){
			$data = array();
			$data['page'] = 'view_deposits';
			$this->load->view('page/heading',$data);
			$this->load->view('page/footer',$data);		
		}else{
			redirect("dashboard/deposit");
		}
	}
	
	function add_new_deposit_history($deposit_id){
		if($deposit_id){
			if(count($_POST)>0){
			$_POST['branch_id'] = $this->stock->getBranch_id();
			$_POST['reciept_id'] = $this->utils->generateUniqueID('deposit_payment_history','reciept_id');
			$this->db->insert("deposit_payment_history",$_POST);
			redirect("dashboard/view_deposit/".$deposit_id);
			}
			
			
			$data = array();
			$data['page'] = 'add_new_deposit_history';
			$this->load->view('page/heading',$data);
			$this->load->view('page/footer',$data);		
		}else{
			redirect("dashboard/deposit");
		}
	}
	
	function checkout_depsits($deposit_id){
		if($deposit_id){
			$data = array();
			$data['page'] = 'checkout_depsits';
			$this->load->view('page/heading',$data);
			$this->load->view('page/footer',$data);	
		}else{
			redirect("dashboard/deposit");
		}
	}
	
	function refund_deposit($deposit_id){
		if($deposit_id){
			
			if(count($_POST)){
				$this->db->where("SN",$deposit_id)->update("deposit",array('date_refunded'=>$_POST['date_refunded'],'reason_for_refund'=>$_POST['reason_for_refund'],'deposit_status'=>"REFUND"));
				redirect('dashboard/view_deposit/'.$deposit_id);
			}
			$data = array();
			$data['page'] = 'refund_depsits';
			$this->load->view('page/heading',$data);
			$this->load->view('page/footer',$data);	
		}else{
			redirect("dashboard/deposit");		
	}
	}
	function therminal_deposit($deposit_id){
		if($deposit_id){
			$data = array();
			$this->load->view('pos/therminal_deposit');
		}else{
			redirect("dashboard/deposit");
		}
	}



//deposit Report
   public function deposit_report(){
	  $data['page'] = 'deposit_reports';
	  $this->load->view('page/heading',$data);
	  $this->load->view('page/footer',$data); 
   } 
   public function deposit_report_customer(){
	  $data['page'] = 'deposit_report_customer';
	  $this->load->view('page/heading',$data);
	  $this->load->view('page/footer',$data); 
   }
    
   public function deposit_report_sales_rep(){
	  $data['page'] = 'deposit_report_sales_rep';
	  $this->load->view('page/heading',$data);
	  $this->load->view('page/footer',$data); 
   }  
   
   public function deposit_report_payment_method(){
	  $data['page'] = 'deposit_report_payment_method';
	  $this->load->view('page/heading',$data);
	  $this->load->view('page/footer',$data); 
   }
   
   
   public function credit(){
	  $data = array();
	  $data['page'] = 'credits';
	  $this->load->view('page/heading',$data);
	  $this->load->view('page/footer',$data);	   
   }
   
   public function view_credit($credit_id){
	  if(!$credit_id){
		redirect("dashboard/credit");
	  }else{
	  $data = array();
	  $data['page'] = 'view_credit';
	  $this->load->view('page/heading',$data);
	  $this->load->view('page/footer',$data);	    
	  }
   }
   
   public function add_new_credit(){
		$data = array();
		$data['page'] = 'preparing_credit_sales';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);	
   }
   
   
   public function therminal_credit(){
	$this->load->view('pos/therminal_credit');
   }
   
   	function new_payment_credit($credit_id){
		if($credit_id){
			if(count($_POST)>0){
			$_POST['reciept_id'] = $this->utils->generateUniqueID('credit_payment_history','reciept_id');
			$credit_details = $this->stock->getCredit($credit_id);
			$payment_b4 = $this->stock->getTotalAmountCreditPaid($credit_details['SN'],false);
			$payment_b4  = $payment_b4  +  $_POST['amount'];
			if($payment_b4 > $credit_details['total_amount_paid']){
				$this->session->set_flashdata('error','Unable to add Payment to History, Reason <br> Total Paid can not be greater than total amount!..');
				redirect("dashboard/new_payment_credit/".$credit_id);
			}
			$this->db->insert("credit_payment_history",$_POST);
			$payment_b4 = $this->stock->getTotalAmountCreditPaid($credit_id,false);
			if($payment_b4 == $credit_details['total_amount_paid']){
				$this->db->where("SN",$credit_id)->update("tbl_credit_sales",array("status"=>"COMPLETE"));
				$this->session->set_flashdata('success','Credit mark as finished successfully');
			}
			redirect("dashboard/view_credit/".$credit_id);
			}
			$data = array();
			$data['page'] = 'new_payment_credit';
			$this->load->view('page/heading',$data);
			$this->load->view('page/footer',$data);		
		}else{
			redirect("dashboard/credit");
		}
	}
	
	
	
	public function print_add_credit_recipt($reciept_id,$receipt_type){
		$res = $this->stock->getCreditPayment($reciept_id);
		$data['payment'] = $res;
		if($receipt_type=="Thermal"){
				$this->load->view('print/credit/pos_recipt_print',$data);
		}else if($receipt_type=="Medium"){
				$this->load->view('print/credit/medium',$data);
		}else if($receipt_type=="Big"){
				$this->load->view('print/credit/big',$data);
		}else if($receipt_type=="Communication"){
				$this->load->view('print/credit/communication',$data);
		}else if($receipt_type=="WayBill"){
				$this->load->view('print/credit/communication',$data);
		}else{
				$this->load->view('print/credit/invoice',$data);
		}
	}
	public function print_add_credit_full_recipt($reciept_id,$receipt_type){
		$res = $this->stock->getCreditall($reciept_id);
		$data['payment'] = $res;
		if($receipt_type=="Thermal"){
				$this->load->view('print/credit/deposit_all/pos_recipt_print',$data);
		}else if($receipt_type=="Medium"){
				$this->load->view('print/credit/deposit_all/medium',$data);
		}else if($receipt_type=="Big"){
				$this->load->view('print/credit/deposit_all/big',$data);
		}else if($receipt_type=="Communication"){
				$this->load->view('print/credit/deposit_all/communication',$data);
		}else{
				$this->load->view('print/credit/deposit_all/invoice',$data);
		}
	}

   public function invoice_report(){
	  $data['page'] = 'invoice_report/invoice_reports';
	  $this->load->view('page/heading',$data);
	  $this->load->view('page/footer',$data); 
   }
	
   public function invoice_report_customer(){
	  $data['page'] = 'invoice_report/invoice_report_customer';
	  $this->load->view('page/heading',$data);
	  $this->load->view('page/footer',$data); 
   }
    
   public function invoice_report_sales_rep(){
	  $data['page'] = 'invoice_report/invoice_report_sales_rep';
	  $this->load->view('page/heading',$data);
	  $this->load->view('page/footer',$data); 
   }  
   
   public function invoice_report_payment_method(){
	  $data['page'] = 'invoice_report/invoice_report_payment_method';
	  $this->load->view('page/heading',$data);
	  $this->load->view('page/footer',$data); 
   }

   
   public function credit_reports(){
	  $data['page'] = 'credit_report/credit_reports';
	  $this->load->view('page/heading',$data);
	  $this->load->view('page/footer',$data); 
   }

   
   public function credit_report_customer(){
	  $data['page'] = 'credit_report/credit_report_customer';
	  $this->load->view('page/heading',$data);
	  $this->load->view('page/footer',$data); 
   }
    
   public function credit_report_sales_rep(){
	  $data['page'] = 'credit_report/credit_report_sales_rep';
	  $this->load->view('page/heading',$data);
	  $this->load->view('page/footer',$data); 
   }  
   
   public function credit_report_payment_method(){
	  $data['page'] = 'credit_report/credit_report_payment_method';
	  $this->load->view('page/heading',$data);
	  $this->load->view('page/footer',$data); 
   }
   
	
  public function return_stock($prod,$id){
	$this->db->where("bar_code",$id)->update("product_bar_code",array("status"=>"0"));
	$branch_id = $this->stock->getBranch_id();
	$qty =$this->db->from("stock_branch")->where("branch_id",$branch_id)->where("stock_id",$prod)->get()->result_array()[0]['quantity'];
	$qty++;
	$this->db->where("branch_id",$branch_id)->where("stock_id",$prod)->update("stock_branch",array("quantity"=>$qty));
	redirect(base_url('dashboard/view_stock_list/'.$prod));
 }	

 function category(){
		$this->load->model('stock');
		if(count($_POST)>0){
			$this->stock->addcategory($_POST);
			$this->session->set_flashdata("success","Category Added Successfully!!...");
			redirect('dashboard/category');
		}
		if(isset($_GET['del'])){
			$this->db->where("SN",$this->uri->segment(3))->delete("category");
			$this->session->set_flashdata("success","Category deleted Successfully!!...");
			redirect('dashboard/category');
		}
		$data = array();
		$data['page'] = 'category';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
 }
 
  function bank_manager(){
	 	$this->load->model('stock');
		if(count($_POST)>0){
			$this->stock->addbank($_POST);
			$this->session->set_flashdata("success","Bank Added Successfully!!...");
			redirect('dashboard/bank_manager');
		}
		if(isset($_GET['del'])){
			$this->db->where("SN",$this->uri->segment(3))->delete("tbl_bank");
			$this->session->set_flashdata("success","Bank deleted Successfully!!...");
			redirect('dashboard/bank_manager');
		}
		$data = array();
		$data['page'] = 'bank_manager';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
	 
 }
 
 
 function suppliers(){
	 	$this->load->model('stock');
		if(count($_POST)>0){
			$this->stock->addSupplier($_POST);
			$this->session->set_flashdata("success","Supplier Added Successfully!!...");
			redirect('dashboard/supplier');
		}
		if(isset($_GET['del'])){
			$this->db->where("SN",$this->uri->segment(3))->delete("category");
			$this->session->set_flashdata("success","Supplier deleted Successfully!!...");
			redirect('dashboard/supplier');
		}
		$data = array();
		$data['page'] = 'supplier';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
	 
 } 
 function view_stock_movement($stock_id){
	 	$this->load->model('stock');
		$data = array();
		$data['page'] = 'view_stock_movement';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
	 
 }

 function expenses_report(){
	$this->load->model('stock');
	$data = array();
	$data['page'] = 'expenses';
	$this->load->view('page/heading',$data);
	$this->load->view('page/footer',$data); 
 }
 
 function new_expenses(){
	 if(count($_POST)>0){
			$this->stock->addExpenses($_POST);
			 $this->session->set_flashdata("success","Operation successfull!!..");			
		   
			redirect('dashboard/expenses_report');
	}
	$this->load->model('stock');
	$data = array();
	$data['page'] = 'newexpenses';
	$this->load->view('page/heading',$data);
	$this->load->view('page/footer',$data); 
	 
 } 
 
 function edit_expenses($sn){
	 if(count($_POST)>0){
		 	$ex = $this->db->get_where("tbl_expenses",array("SN"=>$sn))->row_array();
			if($ex['expense_purpose_title']=="Salary Payment"){
			$this->session->set_flashdata("error","Unable to edit Salary payment expenses");	   
	 		redirect('dashboard/expenses_report'); 
	 		}
			$this->stock->updateExpenses($_POST);
			 $this->session->set_flashdata("success","Operation successfull!!..");			
		   
			redirect('dashboard/expenses_report');
	}
	$ex = $this->db->get_where("tbl_expenses",array("SN"=>$sn))->row_array();
	if($ex['expense_purpose_title']=="Salary Payment"){
	$this->session->set_flashdata("error","Unable to edit Salary payment expenses");	   
	redirect('dashboard/expenses_report'); 
	}
	$this->load->model('stock');
	$data = array();
	$data['page'] = 'editexpenses';
	$this->load->view('page/heading',$data);
	$this->load->view('page/footer',$data); 
	 
 }

 function delete_expenses($sn){
	 $ex = $this->db->get_where("tbl_expenses",array("SN"=>$sn))->row_array();
	 if($ex['expense_purpose_title']=="Salary Payment"){
			$this->session->set_flashdata("error","Unable to delete Salary payment expenses");	   
	 		redirect('dashboard/expenses_report'); 
	 }else{
	 $this->db->where("SN",$sn)->delete("tbl_expenses");
	 $this->session->set_flashdata("success","Operation successfull!!..");	   
	 redirect('dashboard/expenses_report');
	 }
 }

 function expenses_monthly_report(){
	$this->load->model('stock');
	$data = array();
	$data['page'] = 'expenses_monthly_report';
	$this->load->view('page/heading',$data);
	$this->load->view('page/footer',$data); 
 }

 function staff_salary(){
	$this->load->model('stock');
	$data = array();
	$data['page'] = 'staff_salary';
	$this->load->view('page/heading',$data);
	$this->load->view('page/footer',$data); 
 }

public function new_salary_payment(){
	$this->load->model('stock');
	$data = array();
	$data['page'] = 'new_salary_payment';
	$this->load->view('page/heading',$data);
	$this->load->view('page/footer',$data); 	
}

public function processnewsalarypayment(){
		unset($_POST['employee_length']);
		$alltotal =0;
		$total = 0;
		$payment_array =array(
								'payment_id'=>$this->utils->generateUniqueID("tbl_payment_payrole","payment_id"),
								'month'=>$_POST['month'],
								'month_number'=>$_POST['month_no'],
								'year'=>$_POST['year'],
								'type'=>"Salary"
							);
		$this->db->insert("tbl_payment_payrole",$payment_array);
		$payment_id =$this->db->insert_id();
		foreach($_POST['salary'] as $emp_id=>$value){
			$payment_history = array(
									'employee_id'=>$emp_id,
									'payment_id'=>$payment_id,
									'month'=>$_POST['month'],
									'month_no'=>$_POST['month_no'],
									'year'=>$_POST['year'],
									'salary'=>$value,
									'addition'=>$_POST['addition'][$emp_id],
									'deduction'=>$_POST['deduction'][$emp_id],
									'loan_deduction'=>$_POST['loan_deduction'][$emp_id],
									'branch_id'=>0,
									'department_id'=>0,
								);
			$addition = (((float)$value) +((float)$_POST['addition'][$emp_id]));
			$deduction = (((float)$_POST['deduction'][$emp_id]) + ((float)$_POST['loan_deduction'][$emp_id]));
			$total = $addition - $deduction;
			$alltotal = $alltotal+$total;
			$this->db->insert("tbl_payment_payrole_history",$payment_history);
		}
		
		$this->db->where("SN",$payment_id)->update("tbl_payment_payrole",array("total_pay"=>$alltotal,'total_staff'=>count($_POST['salary'])));
		redirect("dashboard/staff_salary");
	}
	
	
	public function view_history_salary($payrole_id){
	$this->load->model('stock');
	$data = array();
	$data['page'] = 'view_history_salary';
	$this->load->view('page/heading',$data);
	$this->load->view('page/footer',$data); 
	}
	
	public function mark_fully_paid_salary($id){
		$payment = $this->stock->getpaymentgeneratedbyid($id);
		if($payment['status']!="1"){
		$history = $this->stock->getpaymentgeneratedhistorybypaymentid($id);
		foreach($history as $his){
			if($his['loan_deduction'] !="0"){
				$loan = $this->stock->getpendingloanbyemployeeID($his['employee_id']);
				if($loan !=false){
					$loan_save = array(
										'load_id'=>$loan['SN'],
										'employee_id'=>$his['employee_id'],
										'amount_paid'=>$his['loan_deduction'],
										'date'=>date('Y-m-d')
									);
					$this->db->insert("loan_payment_history",$loan_save);
					$amount_borrow = $loan['amount'];
					$total_paid = $this->stock->getTotalloanpayment($loan['SN']);
					if(($amount_borrow == $total_paid) || ($amount_borrow < $total_paid)){
						$this->db->where("SN",$loan['SN'])->update("tbl_loan",array('status'=>1));
					}
				}
			}
		}
		$this->db->where("SN",$id)->update("tbl_payment_payrole",array('pay_date'=>date('Y-m-d'),'status'=>1));
		$expense_payment = array(
									'expense_date'=>date('Y-m-d'),
									'month'=>$payment['month'],
									'month_number'=>$payment['month_number'],
									'year'=>$payment['year'],
									'expense_total_amount'=>$payment['total_pay'],
									'expense_purpose'=>'Salary Payment for '.$payment['month'].','.$payment['year'],
									'expense_purpose_title'=>'Salary Payment'
								);
		$this->db->insert("tbl_expenses",$expense_payment);
		}
		 $this->session->set_flashdata("success","Operation successfull!!..");	
		redirect("dashboard/view_history_salary/".$id);
	}
	
	
	public function delete_gen_pay_salary($id){
		$this->db->where("SN",$id)->delete("tbl_payment_payrole");
		$this->db->where("payment_id",$id)->delete("tbl_payment_payrole_history");
		redirect("dashboard/staff_salary");
	}
	
	public function print_payment_slip(){
		$this->load->view('page/payment_slip');
	}
	
	
	public function view_delete_sales($sales_id){
		$sales = $this->db->get_where("sales",array("reciept_id"=>$sales_id))->row_array();
		if($sales['status'] == "VOID"){
			$this->session->set_flashdata("error","Sales has already been canceled/voided");	
			redirect('dashboard/view_sales/'.$sales_id);
		}
		if($sales['status'] != "COMPLETE"){
			$this->session->set_flashdata("error","Sales has already been canceled/voided");	
			redirect('dashboard/view_sales/'.$sales_id);
		}
		$this->load->model('stock');
		$data = array();
		$data['page'] = 'void_sales';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data); 
	}
	
	public function delete_sales($sales_id){
		$sales = $this->db->get_where("sales",array("reciept_id"=>$sales_id))->row_array();
		$dpt = $this->db->get_where('department',array('department'=>$sales['department']))->row();
		if($sales['status'] == "VOID"){
			$this->session->set_flashdata("error","Sales has already been canceled/voided");	
			redirect('dashboard/view_sales/'.$sales_id);
		}
		if($sales['status'] != "COMPLETE"){
			$this->session->set_flashdata("error","Sales has already been canceled/voided");	
			redirect('dashboard/view_sales/'.$sales_id);
		}
		if($dpt->type == "Sales") {
            $products = json_decode($sales['items'], true);
            if (count($products) > 0) {
                foreach ($products as $product) {
                    $this->stock->addStock($product['id'], $product['item_qty'], TRUE);
                }
            }
        }
		$v = $this->users->get_user_by_username($this->session->userdata("username"))->id;
		$this->db->where("reciept_id",$sales_id)->update("sales",array("status"=>"VOID","reason"=>$this->input->post('reason'),"voided_by"=>$v,"date_voided"=>date("Y-m-d")));
		$this->session->set_flashdata("success","Operation complete");	
		redirect('dashboard/view_sales/'.$sales_id);
	}	
	
	
	//Profit / Loss
	
	public function todays_profit(){
		$this->load->model('stock');
		$data = array();
		$data['page'] = 'todays_profit';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data); 
	}
	
	public function by_sales_report(){
		$this->load->model('stock');
		$data = array();
		$data['page'] = 'profit_loss_general';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data); 
	}
	
	public function by_report_customer(){
		$this->load->model('stock');
		$data = array();
		$data['page'] = 'by_report_customer';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data); 
	}
	
	public function by_report_sales_rep(){
		$this->load->model('stock');
		$data = array();
		$data['page'] = 'by_report_sales_rep';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data); 
	}
	
	public function by_report_stock(){
		$this->load->model('stock');
		$data = array();
		$data['page'] = 'by_report_stock';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data); 
	}
	
	public function by_report_payment_method(){
		$this->load->model('stock');
		$data = array();
		$data['page'] = 'by_report_payment_method';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data); 
	}
	
	public function cash_ledger(){
		$this->load->model('stock');
		$data = array();
		$data['page'] = 'cash_ledger';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
	}
	
	public function pos_payment(){
		$this->load->model('stock');
		$data = array();
		$data['page'] = 'pos_payment';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
	}
	
	public function new_entry(){
		 if(count($_POST)>0){
			 $this->session->set_flashdata("success","Sales Transaction has been added successfully!.");
			 $this->db->insert("tbl_cashbook",$this->input->post(null));
			 redirect("dashboard/new_entry");
		 }
		$this->load->model('stock');
		$data = array();
		$data['page'] = 'new_bank_deposit_withdraw';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data); 
	}
	
	public function deposit_credit_report(){
		$this->load->model('stock');
		$data = array();
		$data['page'] = 'deposit_credit_report';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data); 	
	}
	
	public function ref(){
		$this->load->view("page/dailysales");
	}
	
	public function new_assets(){
		if(count($_POST) > 0){
			$this->db->insert("tbl_assets",$this->input->post(null));
			$this->session->set_flashdata("success","Assets as been added successfully!....");
			redirect("dashboard/new_assets");
		}
		$this->load->model('stock');
		$data = array();
		$data['page'] = 'new_assets';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data); 
	}
	
	public function view_assests($sn){
		if(count($_POST) > 0){
			$this->db->where("SN",$sn)->update("tbl_assets",$this->input->post(null));
			$this->session->set_flashdata("success","Assets as been updated successfully!....");
			redirect("dashboard/view_assests/".$sn);
		}
		$this->load->model('stock');
		$data = array();
		$data['page'] = 'edit_assets';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data); 
	}

	public function assets_list(){
		$this->load->model('stock');
		$data = array();
		$data['page'] = 'assets_list';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data); 
	}
	
	public function credit_payment_report(){
		$this->load->model('stock');
		$data = array();
		$data['page'] = 'credit_payment_report';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data); 
	}
	
	public function reset_password($user_id){
		require_once(APPPATH.'libraries/phpass-0.1/PasswordHash.php');
		$password = '123456';
		$hasher = new PasswordHash(
						$this->config->item('phpass_hash_strength', 'tank_auth'),
						$this->config->item('phpass_hash_portable', 'tank_auth'));
		$hashed_password = $hasher->HashPassword($password);		
		$this->db->where("id",$user_id)->update("users",array("password"=>$hashed_password));
		$this->session->set_flashdata("success","Password has been reset to default 123456");
		redirect("dashboard/settings");
	}
	
		public function new_invoice(){
		$this->load->model("stock");
		if(count($_POST) >0){
			
			if(empty($_POST['supplier'])){
				$this->session->set_flashdata("error","Please select Supplier");
				redirect('dashboard/new_invoice');
			}
			
			if(empty($_POST['total_invoice_amount'])){
				$this->session->set_flashdata("error","Please Input Total Invoice Amount");
				redirect('dashboard/new_invoice');
			}
		
			
			if(isset($_POST['product']) && count($_POST['product']) >0){
			$this->stock->addSupplierInvoice($_POST);
			$this->session->set_flashdata("success","Invoice has been saved Successfully!!...");
			}else{
			$this->session->set_flashdata("error","No Product added, Please check and try again..");
			}
			redirect('dashboard/new_invoice');
		}
		$data = array();
		$data['page'] = 'new_invoice';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
		
	}
	
	
	public function supplier_invoice_report(){
		$this->load->model('stock');
		$data = array();
		$data['page'] = 'supplier_invoice_report';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data); 		
	}
	
	public function view_invoice(){
		$this->load->model("stock");
		$data = array();
		$data['page'] = 'view_invoice';
		$data['transfer'] = $this->stock->getSupplierInvoiceID($this->uri->segment(3));
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
	}
	
	public function complete_invoice($id){
		$this->db->where("supplier_id",$id)->update("supplier_invoice",array("status"=>"complete"));
		$this->session->set_flashdata("success","Operation Successful");
		redirect('dashboard/view_invoice/'.$id);
		
	}
	
	public function add_new_invoice_history($invoice_id){
	if($invoice_id){
			if(count($_POST)>0){
			$credit_details = $this->db->get_where("supplier_invoice",array("supplier_id"=>$invoice_id))->row_array();
			$payment_b4 = $this->stock->getInvoiceAmountpaid($credit_details['SN'],false);
		
			$payment_b4  = $payment_b4  +  $_POST['amount'];
			if($payment_b4 > $credit_details['total_invoice_amount']){
				$this->session->set_flashdata('error','Unable to add Payment to History, Reason <br> Total Paid can not be greater than total invoice amount!..');
				redirect("dashboard/view_invoice/".$invoice_id);
			}
			unset($_POST[0]);
			$this->db->insert("invoice_payment_history",$_POST);
			if($payment_b4 == $credit_details['total_invoice_amount']){
				$this->db->where("SN",$credit_details['SN'])->update("supplier_invoice",array("status"=>"Complete"));
				$this->session->set_flashdata('success','Invoice Marked as finished successfully');
			}
			redirect("dashboard/view_invoice/".$invoice_id);
			}
			$data = array();
			$data['page'] = 'new_payment_invoice';
			$this->load->view('page/heading',$data);
			$this->load->view('page/footer',$data);		
		}else{
			redirect("dashboard/supplier_invoice_report");
		}	
		
	}
	
	public function edit_customer($sn){
		$customer =  $this->db->get_where("tbl_customers",array("SN"=>$sn))->row_array();
			if(count($_POST)>0){
			$from = strtotime($_POST['date']);
			$_POST['expired_date'] = date("Y-m-d",strtotime("+ ".$_POST['weeks']." weeks",$from));
			$this->db->where("SN",$sn)->update("tbl_customers",$_POST);
			$this->session->set_flashdata("success","Customer was updated successfully!");
			redirect('dashboard/customerlist');
		}
		$data = array();
		$data['customer'] = $customer;
		$data['page'] = 'edit_customer';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
	}
		
	public function due_payment_report(){
	  $data = array();
	  $data['page'] = 'due_payment_report';
	  $this->load->view('page/heading',$data);
	  $this->load->view('page/footer',$data);	   
		
	}
	
	
	public function total_income_report(){
	   	$this->load->model('stock');
		$data = array();
		$data['page'] = 'total_income_report';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);  
	}
	
	
	function get_recieved_stock($num){
	    $dept = $this->stock->getUserDepartmentPos();
		
		$products = $this->stock->getStocksToRecieved(array("status"=>"1","department"=>$dept));
		$html ='<select required class="form-control input-sm" id="select-'.$num.'" name="product[]">';
		foreach($products as $product){
			$html .='<option value="'.$product['id_stock'].'">'.$product['product_name'].'</option>';
		}
		$html.='</select>';
		
		echo $html;
	}

	function get_available_stock($num){
	    $dept = $this->stock->getUserDepartmentPos();
		$products = $this->stock->getStocksToTransfer(array("status"=>"1","department"=>$dept));
		$html ='<select required class="form-control input-sm handle_change" id="select-'.$num.'" name="product[]">';
		if(count($products)  === 0){
            $html ='No Product Available for Transfer';
        }else {
            $html .='<option value="">Select Product</option>';
            foreach ($products as $product) {
                $html .= '<option price="'.$product['price'].'" value="' . $product['id_stock'] . '">' . $product['product_name'] . '</option>';
            }
            $html .= '</select>';
        }
		echo $html;
	}
	
	
	public function recieved_stock_report_product(){
		$this->load->model("stock");
		$data = array();
		$data['page'] = 'recieved_stock_report_byproduct';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
	}
	
	public function daily_stock_report(){
		$this->load->model("stock");
		$data = array();
		$data['page'] = 'daily_stock_balance';
		$this->load->view('page/heading',$data);
		$this->load->view('page/footer',$data);
	
	}

    public function department(){
        if(count($_POST) > 0){
            $this->db->insert('department',$this->input->post(null));
            $this->session->set_flashdata("success","Department has been added successfully");
            redirect('dashboard/department');
        }
        $data = array();
        $data['page'] = 'department';
        $this->load->view('page/heading',$data);
        $this->load->view('page/footer',$data);
    }


    public function service_category(){
        if(count($_POST) > 0){
            $this->db->insert('service_category',$this->input->post(null));
            $this->session->set_flashdata("success","Category has been added successfully");
            redirect('dashboard/service_category');
        }
        $data = array();
        $data['page'] = 'service_category';
        $this->load->view('page/heading',$data);
        $this->load->view('page/footer',$data);
    }

    public function edit_service_category($sn){
        if(count($_POST) > 0){
            $this->db->where('SN',$sn)->update('service_category',$this->input->post(null));
            $this->session->set_flashdata("success","Category has been updated successfully");
            redirect('dashboard/service_category');
        }
        $data = array();
        $data['service'] = $this->stock->getServiceCategory($sn);
        $data['page'] = 'edit_service_category';
        $this->load->view('page/heading',$data);
        $this->load->view('page/footer',$data);
    }

    public function new_service(){
        if(count($_POST) > 0){
            if(is_uploaded_file($_FILES['spicture']['tmp_name'])){
                $product_image = mt_rand().".jpg";
                move_uploaded_file($_FILES['spicture']['tmp_name'],'product_image/'.$product_image);
                $_POST['image'] = $product_image;
            }
            $this->db->insert('services',$this->input->post(null));
            $this->session->set_flashdata("success","Congratulation, New Service has been created");
            redirect('dashboard/new_service');
        }
        $data = array();
        $data['page'] = 'new_service';
        $this->load->view('page/heading',$data);
        $this->load->view('page/footer',$data);
    }

    public function list_service(){
        $data = array();
        $data['page'] = 'list_service';
        $this->load->view('page/heading',$data);
        $this->load->view('page/footer',$data);
    }

    public function edit_service($sn){
        if(count($_POST) > 0){
            if(is_uploaded_file($_FILES['spicture']['tmp_name'])){
                $product_image = mt_rand().".jpg";
                move_uploaded_file($_FILES['spicture']['tmp_name'],'product_image/'.$product_image);
                $_POST['image'] = $product_image;
            }else{
                unset( $_POST['image']);
                unset( $_POST['spicture']);
            }
            $this->db->where('SN',$sn)->update('services',$this->input->post(null));
            $this->session->set_flashdata("success","Service has been updated");
            redirect('dashboard/list_service');
        }
        $data = array();
        $data['service'] = $this->db->from('services')->where('SN',$sn)->get()->row_array();
        $data['page'] = 'edit_service';
        $this->load->view('page/heading',$data);
        $this->load->view('page/footer',$data);
    }

    public function delete_service($sn){
        $this->db->where('SN',$sn)->delete('services');
        redirect('dashboard/list_service/');
    }


    public function onlineSync(){
        $data['page'] = 'backup2';
        $this->load->view('page/heading',$data);
        $this->load->view('page/footer',$data);
    }


    private function tableToJson($table,$field,$last_to_online_sync) {
        $this->db->where($field.' >=', $last_to_online_sync);
        $result = $this->db->get($table)->result_array();
        return $result;
    }

    public static function validate_live_url($url) { //Jamiu
        $status = '';
        if ($url != null) {
            $url_valid = @get_headers($url);
            if (stripos($url_valid[0], '200 OK') !== FALSE ) {
                $status = 1;
            }
            else{
                $status = 0;
            }
        }else {
            $status = 2;
        }

        return $status;
    }

    public function sync_data($param1 = '') {
        $data = [];
        $last_to_online_sync = $this->db->get_where('settingsnoedit',['sne_key'=>'last_to_online_sync','sne_branchid'=>1])->row('sne_value');
        $tables = $this->db->list_tables();
        foreach($tables as $table) {
            $nosync = ['settingsnoedit','user_profiles','login_attempts','ci_sessions'];
            if(!in_array($table,$nosync)) {
                $data[$table] = $this->tableToJson($table,'last_data_updated',$last_to_online_sync);
            }
        }
	
        $post = $this::httpPostRegular('http://olaniyibadmus.com.ng/api',json_encode($data));
        $response = json_decode($post);
        $this->db->set('sne_value', 'NOW()',false);
        $this->db->where('sne_key', 'last_to_online_sync');
        $this->db->where('sne_branchid', 1);
        $this->db->update('settingsnoedit');
        $this->session->set_flashdata("success","New Data Uploaded : ".$response->new."<br> Updated Data : ".$response->update);

        redirect('dashboard/onlineSync');
    }

    public function download_data($param1 = '') {

        $json_data = file_get_contents("http://olaniyibadmus.com.ng/api/sync_data_to_offline");
        $insert_count = $update_count = 0;
        if ($json_data != null) {
            $data_array = json_decode($json_data, true);
            foreach ($data_array as $table => $data) {
                foreach ($data as $d) {
                    $nosync = ['settingsnoedit', 'user_profiles','login_attempts','ci_sessions'];
                    if (!in_array($table, $nosync)) {
                        $check = $this->db->get_where($table, array('SN' => $d['SN']))->row('SN');
                        unset($d['updated']);
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
        }

        $this->session->set_flashdata("success","New Data Downloaded : ".$insert_count."<br> Update Downloaded : ".$update_count);
        redirect('dashboard/onlineSync');
    }

    private function checkExist($table,$field) {

    }

    public static function httpPostRegular($url,$params)  {
        $postData = $params;
        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $output=curl_exec($ch);

        curl_close($ch);
        return $output;

    }


    public function discount_manager(){

        if(count($_POST)>0){
            $this->db->insert("discount_manager",$_POST);
            $this->session->set_flashdata("success","Discount Added Successfully!!...");
            redirect('dashboard/discount_manager');
        }
        if(isset($_GET['del'])){
            $this->db->where("SN",$this->uri->segment(3))->delete("discount_manager");
            $this->session->set_flashdata("success","Discount deleted Successfully!!...");
            redirect('dashboard/discount_manager');
        }

        $data['page'] = 'discount_manager';
        $this->load->view('page/heading',$data);
        $this->load->view('page/footer',$data);
    }


 }
	
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
