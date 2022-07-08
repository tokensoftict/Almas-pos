<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Stock extends CI_Model{

	public function add($data){
		$data['id_stock'] = $this->utils->generateUniqueID("stock","id_stock");
		$this->db->insert("stock",$data);
		$id = $this->db->insert_id();
		return true;
	}
	public function update($stcok_id,$data){
		return $this->db->where("SN",$stcok_id)->update("stock",$data);
	}

	public function addStock($product_id,$qty,$addtohistroy = TRUE){
		$stock =$this->getStockByID($product_id);
		$newqty = $stock['quantity']+$qty;
		$this->db->where("SN",$stock['SN']);
		$this->db->update("stock",array('quantity'=>$newqty));
		if($addtohistroy == TRUE){
			$this->addTohistory(array(
				"stock_id"=>$stock['SN'],
				"amt_in"=>$qty,
				"amt_out"=>0,
				"balance"=>$newqty,
				"sold"=>0
			));
		}
	}

	public function addTohistory($data){
		if($this->session->userdata("tracking_id")){
			$tr = $this->session->userdata("tracking_id");
		}else{
			$tr ="0";
		}
		$insert = array(
			"tracking_id"=>$tr,
			"stock_id"=>$data['stock_id'],
			"amt_in"=>$data['amt_in'],
			"amt_out"=>$data['amt_out'],
			"date_"=>date("Y-m-d"),
			"sold"=>$data['sold'],
			"balance"=>$data['balance'],
			"user"=>$this->users->get_user_by_username($this->session->userdata("username"))->id
		);
		$this->db->insert("tbl_transfer_recieved",$insert);
	}


	public function removeStock($product_id,$qty,$addtohistroy = TRUE){
		$stock =$this->getStockByID($product_id);
		$newqty = $stock['quantity']-$qty;
		$this->db->where("SN",$stock['SN']);
		$this->db->update("stock",array('quantity'=>$newqty));
		if($addtohistroy == TRUE){
			if($this->session->userdata("sold")){
				$this->addTohistory(array(
					"stock_id"=>$stock['SN'],
					"amt_in"=>0,
					"amt_out"=>0,
					"sold"=>$qty,
					"balance"=>$newqty,
				));
			}else{
				$this->addTohistory(array(
					"stock_id"=>$stock['SN'],
					"amt_in"=>0,
					"sold"=>0,
					"amt_out"=>$qty,
					"balance"=>$newqty,
				));
			}
		}
	}


	public function getStocks($array = false){
		$this->db->from("stock");
		if($array!=false){
			foreach($array as $key=>$value){
				if($key!="quantity" && $key!="quantity!="){
					$this->db->where($key,$value);
				}
			}
		}
		return $this->db->get()->result_array();
	}


	public function getSellable($array = false){
		$this->db->from("stock");
		$this->db->where("quantity >","0");
		if($array!=false){
			foreach($array as $key=>$value){
				if($key!="quantity" && $key!="quantity!="){
					$this->db->where($key,$value);
				}
			}
		}

		return $this->db->get()->result_array();
	}



	public function getStocksToRecieved($array = false){
		$this->db->from("stock");
		if($array!=false){
			foreach($array as $key=>$value){
				$this->db->where($key,$value);
			}
		}
		$history =$this->db->get();
		return $history->result_array();
	}

	public function getStocksToTransfer($array = false){
		$this->db->from("stock");
		if($array!=false){
			foreach($array as $key=>$value){
				$this->db->where($key,$value);
			}
		}
		$this->db->where('quantity >',0);
		$history =$this->db->get();
		return $history->result_array();
	}

	public function getBranch_id(){
		$get =$this->users->get_user_by_username($this->session->userdata("username"));
		return $get->branch_id;
	}

	public function getStock($stock_id){
		$this->db->select("*");
		$this->db->from("stock");
		$this->db->or_where('SN',$stock_id);
		$this->db->or_where('id_stock',$stock_id);
		$record =$this->db->get()->result_array();
		return $record;
	}


	public function getStocks_by_branch($branch_id){
		$this->db->select("*");
		$this->db->from("branch_product");
		$this->db->where('branch_id',$branch_id);
		$records =$this->db->get()->result_array();
		foreach($records as $key=>$record){
			$product = $this->getStockByID($record['product_id']);
			$records[$key] = array_merge($product,$records[$key]);
			$records[$key]['branch'] = $this->db->get_where('branch',['SN'=>$branch_id])->row()->branch_name;
		}
		$new_records = [];
		foreach($records as $record){
			if(isset($new_records[$record['product_id']])){
				$new_records[$record['product_id']]['qty'] += $record['qty'];
			}else{
				$new_records[$record['product_id']] = $record;
			}
		}

		return $new_records;
	}


	public function addStockToBranch($product_id,$qty,$branch_id){
		$st = $this->getStockByID($product_id);
		$br = $this->db->get_where('branch',['branch_name'=>$branch_id])->row();
		$this->db->select("*");
		$this->db->from("branch_product");
		$this->db->where('branch_id',$branch_id);
		$this->db->where('product_id',$product_id);
		$product = $this->db->get();
		if($product->num_rows() > 0){
			$stock = $product->row_array();
			$total = $stock['qty']+$qty;
			$this->db
				->where('branch_id',$branch_id)
				->where('product_id',$product_id)
				->update('branch_product',['qty'=>$total]);
		}else{
			$insert = ['branch_id'=>$br->SN,'product_id'=>$st['SN'],'qty'=>$qty];
			$this->db->insert('branch_product',$insert);
		}
	}

	public function getStockByID($stock_id){
		$this->db->from("stock");
		$this->db->or_where('SN',$stock_id);
		$this->db->or_where('id_stock',$stock_id);
		$record =$this->db->get();
		foreach($record->result_array() as $rec);
		return $rec;

	}

	public function stock_data_tables($limit=false,$offset=false,$search=false){
		$_POST['offset'] = ($_POST['offset']=="0" ? false : $_POST['offset']);
		$this->db->from("stock");
		$this->db->order_by("SN","DESC");
		if($limit!=false && $offset==false){
			$this->db->limit($limit);
		}
		if($limit!=false && $offset!=false){
			$this->db->limit($limit,($offset*$limit));
		}
		if($search!=false){
			$this->db->like('product_name',$search);
		}
		$history =$this->db->get();
		return $history->result_array();
	}


	public function addManufacturer($data){
		$this->db->insert("manufacturer",$data);
	}


	public function getManufacturers(){
		$this->db->from("manufacturer");
		$this->db->order_by("SN","DESC");
		$record =$this->db->get();
		return $record->result_array();
	}


	public function getManufacturer($id){
		$this->db->from("manufacturer");
		$this->db->order_by("SN","DESC");
		$this->db->where("SN",$id);
		$record =$this->db->get();
		return $record->result_array();
	}

	public function addcategory($data){
		$this->db->insert("category",$data);
	}

	public function getCategories(){
		$this->db->from("category");
		$this->db->order_by("SN","DESC");
		$record =$this->db->get();
		return $record->result_array();
	}


	public function getCategory($id){
		$this->db->from("category");
		$this->db->order_by("SN","DESC");
		$this->db->where("SN",$id);
		$record =$this->db->get();
		return $record->result_array();
	}

	public function addBank($data){
		$this->db->insert("tbl_bank",$data);
	}

	public function getBanks(){
		$this->db->from("tbl_bank");
		$this->db->order_by("SN","DESC");
		$record =$this->db->get();
		return $record->result_array();
	}


	public function getBank($id){
		$this->db->from("tbl_bank");
		$this->db->order_by("SN","DESC");
		$this->db->where("SN",$id);
		$record =$this->db->get();
		return $record->row_array();
	}



	public function addSupplier($data){
		$this->db->insert("supplier",$data);
	}


	public function getSuppliers(){
		$this->db->from("supplier");
		$this->db->order_by("SN","DESC");
		$record =$this->db->get();
		return $record->result_array();
	}


	public function getSupplier($id){
		$this->db->from("supplier");
		$this->db->order_by("SN","DESC");
		$this->db->where("SN",$id);
		$record =$this->db->get();
		foreach($record->result_array() as $info);
		return $info;
	}



	public function addBranch($data){
		$this->db->insert("branch",$data);
	}


	public function getBranches(){
		$this->db->from("branch");
		$this->db->where("delete_status","0");
		$this->db->order_by("SN","DESC");
		$record =$this->db->get();
		return $record->result_array();
	}


	public function getBranch($id){
		$this->db->from("branch");
		$this->db->order_by("SN","DESC");
		$this->db->where("SN",$id);
		$record =$this->db->get();
		foreach($record->result_array() as $br)
			return $br;
	}

	public function getBranch_name($id){
		$this->db->from("branch");
		$this->db->order_by("SN","DESC");
		$this->db->where("SN",$id);
		$record =$this->db->get();
		foreach($record->result_array() as $br)
			return $br['branch_name'];
	}


	public function update_barcode_status($bar_code,$value){
		// 0 = available
		// 1 = sold
		//2 = transfer
		//3 pickup
		return $this->db->where("bar_code",$bar_code)->update("product_bar_code",array('status'=>$value,'recieved_ref'=>''));
	}


	public function add_barcode($stock_id,$barcode,$ref_id=FALSE){
		foreach($barcode as $bar){
			$sti =$this->getStock($stock_id);
			$insert_array = array(
				'bar_code'=>$bar,
				'stock_id'=>$sti['SN'],
				'date_available'=>date("Y-m-d"),
				'added_by'=>$this->tank_auth->get_user_id(),
				'recieved_ref' => ($ref_id==FALSE ? '' : $ref_id)
			);
			$this->db->insert("product_bar_code",$insert_array);
		}
	}

	public function delete_barcode($bar_code){
		$this->db->where("bar_code",$bar_code)->delete("product_bar_code");
	}

	public function transfer_stock($data){
		$product = array();
		foreach($data['product'] as $key=>$trns_product){
			$p = $this->getStockByID($trns_product);
			$product[] = array(
				'qty'=>$data['qty'][$key],
				'product'=>$trns_product,
				'remark'=>'transfer',
				'product_barcodes'=>$data['bar_code'][$key],
				'price'=>$p['price'],
				'total'=>$p['price'] * $data['qty'][$key]
			);
		}
		$save_data = array();
		$save_data['products']= json_encode($product);
		$save_data['transfer_id'] = $this->utils->generateUniqueID("stock_transfer","transfer_id");
		$save_data['transfer_date'] = $data['recieved_date'];
		$save_data['branch'] = $data['branch'];
		$save_data['reciever_userfullname'] = $data['reciever_userfullname'];
		$save_data['transfer_user'] = $this->tank_auth->get_user_id();
		$save_data['note'] = $data['transfer_note'];
		$save_data['branch_id'] = $this->getBranch_id();
		$this->session->set_userdata("tracking_id",$save_data['transfer_id']);
		foreach($product as $key=>$id){
			//$this->removeStock($product[$key]['product'],$product[$key]['qty']);
			$this->addStockToBranch($product[$key]['product'],$product[$key]['qty'],$data['branch']);
			$product_barcodes = explode(",",$product[$key]['product_barcodes']);
			foreach($product_barcodes as $bar){
				$this->update_barcode_status($bar,2);
			}
		}
		$this->db->insert("stock_transfer",$save_data);
		return $save_data['transfer_id'];
	}

	public function update_stock($transfer_id,$data){
		//first lets reverse what we did to stock list before
		$transfer = $this->getTransferByTransferID($transfer_id);
		$products = json_decode($transfer['products'],TRUE);
		foreach($products  as $key=>$id){
			$this->addStock($products[$key]['product'],$products[$key]['qty']);
			$barcodes = $products[$key]['product_barcodes'];
			$barcodes= explode(",",$barcodes);
			foreach($barcodes as $barcode){
				$this->update_barcode_status($barcode,0);
			}
		}
		foreach($data['product'] as $key=>$trns_product){
			$product[] = array(
				'qty'=>$data['qty'][$key],
				'product'=>$trns_product,
				'remark'=>'transfer',
				'product_barcodes'=>$data['bar_code'][$key]
			);
		}
		$save_data = array();
		$save_data['products']= json_encode($product);
		$save_data['transfer_date'] = $data['transfer_date'];
		$save_data['branch'] = $data['branch'];
		$save_data['reciever_userfullname'] = $data['reciever_userfullname'];
		$save_data['transfer_user'] = $this->tank_auth->get_user_id();
		$save_data['note'] = $data['transfer_note'];
		$this->session->set_userdata("tracking_id",$transfer['transfer_id']);
		foreach($product as $key=>$id){
			$this->removeStock($product[$key]['product'],$product[$key]['qty']);
			$product_barcodes = explode(",",$product[$key]['product_barcodes']);
			foreach($product_barcodes as $bar){
				$this->update_barcode_status($bar,2);
			}
		}

		return $this->db->where("transfer_id",$transfer_id)->update("stock_transfer",$save_data);
	}




	public function getStocktransfers($array=FALSE){
		$br =$this->getBranch_id();
		$this->db->from("stock_transfer");
		if($array!=FALSE){
			foreach($array as $key=>$value){
				$this->db->where($key,$value);
			}
		}
		if($br!=0){
			$this->db->where("branch_id",$br);
		}
		$transfers = $this->db->get();
		return $transfers->result_array();
	}

	public function getStocktransfersBetween($from,$to,$array=FALSE){
		$br =$this->getBranch_id();
		$this->db->from("stock_transfer");
		if($array!=FALSE){
			foreach($array as $key=>$value){
				$this->db->where($key,$value);
			}
		}
		if($br==0){
			$this->db->where("(transfer_date BETWEEN '$from' AND '$to')");
		}else{
			$this->db->where("(transfer_date BETWEEN '$from' AND '$to') AND branch_id='$br'");
		}
		$transfers = $this->db->get();
		return $transfers->result_array();
	}

	public function outReport($from,$to,$array=FALSE){
		$br =$this->getBranch_id();
		$total_ =0;
		$this->db->from("stock_transfer");
		if($array!=FALSE){
			foreach($array as $key=>$value){
				$this->db->where($key,$value);
			}
		}
		if($br==0){
			$this->db->where("(transfer_date BETWEEN '$from' AND '$to')");
		}else{
			$this->db->where("(transfer_date BETWEEN '$from' AND '$to') AND branch_id='$br'");
		}
		$transfers = $this->db->get();
		foreach($transfers->result_array() as $trnx){
			foreach(json_decode($trnx['products'],true) as $pr){
				$total_ = $total_+$pr['qty'];
			}
		}
		return $total_;
	}

	public function inReport($from,$to,$array=FALSE){
		$br =$this->getBranch_id();
		$total_ =0;
		$this->db->from("stock_recieved");
		if($array!=FALSE){
			foreach($array as $key=>$value){
				$this->db->where($key,$value);
			}
		}
		if($br==0){
			$this->db->where("(recieved_date BETWEEN '$from' AND '$to')");
		}else{
			$this->db->where("(recieved_date BETWEEN '$from' AND '$to') AND branch_id='$br'");
		}
		$transfers = $this->db->get();
		foreach($transfers->result_array() as $trnx){
			foreach(json_decode($trnx['products'],true) as $pr){
				if(!is_numeric($pr['qty'])){
					$$pr['qty'] = 0;
				}
				$total_ = $total_+$pr['qty'];
			}
		}
		return $total_;
	}

	public function getStockrecievedBetween($from,$to,$array=FALSE){
		$br =$this->getBranch_id();
		$this->db->from("stock_recieved");
		if($array!=FALSE){
			foreach($array as $key=>$value){
				$this->db->where($key,$value);
			}
		}
		if($br==0){
			$this->db->where("(recieved_date BETWEEN '$from' AND '$to')");
		}else{
			$this->db->where("(recieved_date BETWEEN '$from' AND '$to')  AND branch_id='$br'");
		}
		$transfers = $this->db->get();
		return $transfers->result_array();
	}

	public function getStockrecieved($array=FALSE){
		$br =$this->getBranch_id();
		$this->db->from("stock_recieved");
		if($array!=FALSE){
			foreach($array as $key=>$value){
				$this->db->where($key,$value);
			}
		}
		if($br!=0){
			$this->db->where("branch_id",$br);
		}
		$transfers = $this->db->get();
		return $transfers->result_array();
	}


	public function getTransferByTransferID($transfer_id){
		$this->db->from("stock_transfer");
		$this->db->where("transfer_id",$transfer_id);
		$transfer =$this->db->get();
		foreach($transfer->result_array() as $trans);
		return $trans;
	}

	public function getReceiveByReceiveID($transfer_id){
		$this->db->from("stock_recieved");
		$this->db->where("recieved_id",$transfer_id);
		$transfer =$this->db->get();
		foreach($transfer->result_array() as $trans);
		return $trans;
	}

	public function recieve_stock($data){
		$product = array();
		$recieved_id= $this->utils->generateUniqueID("stock_recieved","recieved_id");
		foreach($data['product'] as $key=>$trns_product){
			if(isset($data['bar_code'][$key]) && !empty($data['bar_code'][$key])){
				$product[] = array(
					'qty'=>$data['qty'][$key],
					'product'=>$trns_product,
					'remark'=>'Received',
					'product_barcodes'=>$data['bar_code'][$key]
				);
				$this->add_barcode($trns_product,explode(",",$data['bar_code'][$key]),$recieved_id);
			}else{
				$product[] = array(
					'qty'=>$data['qty'][$key],
					'product'=>$trns_product,
					'remark'=>'Received'
				);

			}
		}
		$save_data = array();
		$save_data['products']= json_encode($product);
		$save_data['recieved_id'] = $recieved_id;
		$save_data['recieved_date'] = $data['recieved_date'];
		if(isset($data['branch'])){
			$save_data['branch'] = $data['branch'];
		}else{
			$save_data['supplier'] = $data['supplier'];
		}
		$save_data['branch_id'] = $this->getBranch_id();
		$save_data['reciever_userfullname'] = $this->tank_auth->get_user_id();;
		$save_data['transfer_user'] = $data['transfer_user'];
		$save_data['note'] = $data['transfer_note'];
		$save_data['department'] = $this->getUserDepartmentPos();
		$this->session->set_userdata("tracking_id",$recieved_id);
		foreach($data['product'] as $key=>$id){
			$this->addStock($data['product'][$key],$data['qty'][$key]);
		}
		return $this->db->insert("stock_recieved",$save_data);
	}

	public function update_stock_recieved($transfer_id,$data){
		//first lets reverse what we did to stock list before
		$transfer = $this->getReceiveByReceiveID($transfer_id);
		$products = json_decode($transfer['products'],TRUE);
		foreach($products  as $key=>$id){
			$this->removeStock($products[$key]['product'],$products[$key]['qty']);
		}
		foreach($data['product'] as $key=>$trns_product){
			$product[] = array(
				'qty'=>$data['qty'][$key],
				'product'=>$trns_product,
				'remark'=>'Received'
			);
		}
		$save_data = array();
		$save_data['products']= json_encode($product);
		$save_data['recieved_date'] = $data['recieved_date'];
		if(isset($data['branch'])){
			$save_data['branch'] = $data['branch'];
		}else{
			$save_data['supplier'] = $data['supplier'];
		}
		$save_data['reciever_userfullname'] = $this->tank_auth->get_user_id();
		$save_data['transfer_user'] = $data['transfer_user'];
		$save_data['note'] = $data['transfer_note'];

		$this->session->set_userdata("tracking_id",$transfer['recieved_id']);
		foreach($product as $key=>$id){
			$this->addStock($data['product'][$key],$data['qty'][$key]);
		}

		return $this->db->where("recieved_id",$transfer_id)->update("stock_recieved",$save_data);
	}


	function getStockDepartmentlist(){
		$user_id = $this->tank_auth->get_user_id();
		$user = $this->db->get_where("users",array("id"=>$user_id))->row_array();
		if($this->getUserDepartment() == "Administrator"){
			$user['department'] = $this->session->userdata('top_administrator_department');
		}
		$dpt = $this->db->get_where('department',array('department'=>$user['department']))->row();
		if($dpt->type == "Service"){
			return $this->getServiceProductList($user['department']);
		}
		return array();
	}

	public function getServiceProductList($department){
		$products = $this->db->get_where('services',array("status"=>"1","department"=>$department))->result_array();
		$product_array = [];
		foreach($products as $product){
			$product_array[] = array(
				'price'=>$product['price'],
				'SN'=>$product['SN'],
				'track'=>0,
				'product_name'=>$product['name'],
				'quantity'=>0,
				'image'=>$product['image']
			);
		}

		return $product_array;
	}

	public function getProductBySearchService($text){
		$department = $this->getUserDepartment();
		if ($department == 'Administrator') {
			$department = $this->session->userdata('top_administrator_department');
			if(!$department){
				$department = 'SALES';
			}
			$product = $this->db->query("SELECT * FROM services WHERE (department='{$department}') AND (name LIKE '%{$text}%' OR description LIKE '%{$text}%')");
		} else {
			$product = $this->db->query("SELECT * FROM services WHERE (department='{$department}') AND (name LIKE '%{$text}%' OR description LIKE '%{$text}%')");
		}

		if ($product->num_rows() > 0) {
			$products = array();
			foreach ($product->result_array() as $prod) {
				$products[] = array("id" => $prod['SN'], "name" => $prod['name']);
			}
			return $products;
		} else {
			return array();
		}
	}


	public function getProductAssociatedWithBarcode($barcode){
		$department = $this->getUserDepartment();
		if ($department == 'Administrator') {
			$department = $this->session->userdata('top_administrator_department');

			if(!$department){
				$department = 'SALES';
			}

			$query = "AND department='$department'";
		}else{
			$query = "AND department='$department'";
		}
		$query = "select * from stock where (bar_code_code ='$barcode' OR SN='$barcode') ".$query;
		$product = $this->db->query($query);
		if($product->num_rows() > 0){
			return $product->row_array();
		}else{
			return false;
		}
	}

	public function getServiceAssociatedWithBarcode($barcode){
		$department = $this->getUserDepartment();
		if ($department == 'Administrator') {
			$department = $this->session->userdata('top_administrator_department');
			$product = $this->db->get_where('services', array('SN' => $barcode, 'department' => $department));
		} else {
			$product = $this->db->get_where('services', array('SN' => $barcode, 'department' => $department));
		}
		if($product->num_rows() > 0){
			$product = $product->row_array();
			return [
				"SN"=> $product['SN'],
				"id_stock"=>$product['servicecode'],
				"product_name"=>$product['name'],
				"quantity"=>"Nil",
				"track"=>0,
				"price"=>$product['price'],
				"image"=>$product['image'],
			];

		}else{
			return false;
		}
	}


	public function getProductBySearch($text){
		$department = $this->getUserDepartment();
		if($department == 'Administrator'){
			$department = $this->session->userdata('top_administrator_department');
			if(!$department){
				$department = 'SALES';
			}
			$product = $this->db->query("SELECT * FROM stock WHERE (department='{$department}') AND (product_name LIKE '%{$text}%' OR model LIKE '%{$text}%')");
		}else {
			$product = $this->db->query("SELECT * FROM stock WHERE (department='{$department}') AND (product_name LIKE '%{$text}%' OR model LIKE '%{$text}%')");
		}
		if($product->num_rows() > 0){
			$products = array();
			foreach($product->result_array() as $prod){
				$products[] = array("id"=>$prod['SN'],"name"=>$prod['product_name'].'<span class="pull-right">'.number_format($prod['price'],2).'</span>');
			}
			return $products;
		}else{
			return array();
		}
	}
	public function getSale($recipt_id){
		$products =$this->db->from('sales')->or_where("reciept_id",$recipt_id)->or_where("SN",$recipt_id)->get()->result_array();
		if(count($products) >0){
			foreach($products as $product);
			return $product;
		}
		return false;
	}


	public function getSales($array=FALSE){
		$this->db->from('sales');
		if($array!=FALSE){
			foreach($array as $key=>$value){
				$this->db->where($key,$value);
			}
		}

		$this->db->order_by("SN","DESC");
		return $this->db->get()->result_array();
	}
	public function getSalesRange($from,$to,$array=FALSE){
		$this->db->from('sales');
		$this->db->where("date BETWEEN '$from' AND '$to'");
		if($array!=false){
			foreach($array as $key=>$value){
				$this->db->where($key,$value);
			}
		}
		$this->db->order_by("SN","DESC");
		return $this->db->get()->result_array();
	}

	public function getPaymentMethod(){
		return $this->db->get("payment_method")->result_array();
	}


	public function getFullDepositRange($from,$to,$array=FALSE){
		$this->db->from('deposit_payment_history');
		$this->db->where("date_added BETWEEN '$from' AND '$to'");
		if($array!=false){
			foreach($array as $key=>$value){
				$this->db->where($key,$value);
			}
		}
		$this->db->order_by("SN","DESC");
		$d_ps = $this->db->get()->result_array();
		foreach($d_ps as $key=>$d_p){
			$dep = $this->db->get_where('deposit',array('SN'=>$d_p['deposit_SN']))->result_array()[0];
			unset($d_ps[$key]['sn']);
			$d_ps[$key]['SN'] = $d_ps[$key]['SN'] = $dep['SN'];
		}

		return $d_ps;
	}
	public function getDepositRange($from,$to,$array=FALSE){
		$this->db->from('deposit');
		$this->db->where("date_added BETWEEN '$from' AND '$to'");
		if($array!=false){
			foreach($array as $key=>$value){
				$this->db->where($key,$value);
			}
		}
		$this->db->order_by("SN","DESC");
		return $this->db->get()->result_array();
	}
	public function getDeposithistoryRange($from,$to,$array=FALSE){
		$this->db->from('deposit_payment_history');
		$this->db->where("date_added BETWEEN '$from' AND '$to'");
		if($array!=false){
			foreach($array as $key=>$value){
				$this->db->where($key,$value);
			}
		}
		$this->db->order_by("SN","DESC");
		return $this->db->get()->result_array();
	}

	public function getTotalAmountDeposited($d_id,$formated = FALSE){
		$this->db->from("deposit_payment_history");
		$this->db->where("deposit_SN",$d_id);
		$rec = $this->db->get()->result_array();
		$total = 0;
		foreach($rec as $r){
			$total= $total + $r['amount'];
		}
		if($formated){
			return number_format($total,2);
		}
		return $total;
	}


	public function getDepositPayment($d_id){
		$this->db->from("deposit_payment_history");
		$this->db->where("SN",$d_id);
		return  $this->db->get()->result_array()[0];
	}

	public function getDepositall($d_id){
		$this->db->from("deposit_payment_history");
		$this->db->where("deposit_SN",$d_id);
		$rec = array();
		$rec['payment_history']=  $this->db->get()->result_array();
		$this->db->from("deposit");
		$this->db->where("SN",$d_id);
		$rec['deposit'] = $this->db->get()->result_array()[0];
		return $rec;
	}


	public function getCredit($recipt_id){
		$products =$this->db->from('tbl_credit_sales')->or_where("SN",$recipt_id)->or_where("credit_id",$recipt_id)->get()->result_array();
		if(count($products) >0){
			foreach($products as $product);
			return $product;
		}
		return false;
	}


	public function getCredits($array=FALSE){
		$this->db->from('tbl_credit_sales');
		if($array!=FALSE){
			foreach($array as $key=>$value){
				$this->db->where($key,$value);
			}
		}
		$this->db->order_by("SN","DESC");
		return $this->db->get()->result_array();
	}

	public function getCreditRange($from,$to,$array=FALSE){
		$this->db->from('tbl_credit_sales');
		$this->db->where("date BETWEEN '$from' AND '$to'");
		if($array!=false){
			foreach($array as $key=>$value){
				$this->db->where($key,$value);
			}
		}
		$this->db->order_by("SN","DESC");
		return $this->db->get()->result_array();
	}

	public function getTotalAmountCreditPaid($d_id,$formated = FALSE){
		$this->db->from("credit_payment_history");
		$this->db->where("credit_SN = '$d_id' OR credit_id='$d_id'");
		$rec = $this->db->get()->result_array();
		$total = 0;
		foreach($rec as $r){
			$total= $total + $r['amount'];
		}
		if($formated){
			return number_format($total,2);
		}
		return $total;
	}


	public function getCreditPayment($d_id){
		$this->db->from("credit_payment_history");
		$this->db->where("SN",$d_id);
		return  $this->db->get()->result_array()[0];
	}


	public function getCreditall($d_id){
		$this->db->from("credit_payment_history");
		$this->db->where("credit_SN = '$d_id' OR credit_id='$d_id'");
		$rec = array();
		$rec['payment_history']=  $this->db->get()->result_array();
		$this->db->from("tbl_credit_sales");
		$this->db->where("SN = '$d_id' OR credit_id='$d_id'");
		$rec['deposit'] = $this->db->get()->result_array()[0];
		return $rec;
	}


	public function getExpenses($array=FALSE){
		$this->db->from("tbl_expenses");
		if($array!=false){
			foreach($array as $key=>$value){
				$this->db->where($key,$value);
			}
		}
		$payments =$this->db->order_by("SN","DESC")->get();
		return $payments->result_array();
	}

	public function getExpensesRange($from=FALSE,$to=FALSE,$array=FALSE){
		$this->db->from("tbl_expenses");
		if($array!=false){
			foreach($array as $key=>$value){
				$this->db->where($key,$value);
			}
		}
		if($from != FALSE && $to != FALSE){
			$this->db->where("expense_date BETWEEN '$from' AND '$to'");
		}
		$payments =$this->db->order_by("SN","DESC")->get();
		return $payments->result_array();
	}

	public function addExpenses($data){
		$months = array("January"=>"1", "February"=>"2", "March"=>"3", "April"=>"4", "May"=>"5",
			"June"=>"6", "July"=>"7",
			"August"=>"8","September"=>"9","October"=>"10","November"=>"11","December"=>"12");
		$months = array_flip($months);
		$mnth = explode("-",$_POST['expense_date']);

		$m = $mnth[1];
		$y = $mnth[0];

		$data['month_number'] = $m;
		$data['year'] = $y;
		$data['month'] =$months[(int)$m];
		$this->db->insert("tbl_expenses",$data);
	}

	public function updateExpenses($data,$sn){
		$months = array("January"=>"1", "February"=>"2", "March"=>"3", "April"=>"4", "May"=>"5",
			"June"=>"6", "July"=>"7",
			"August"=>"8","September"=>"9","October"=>"10","November"=>"11","December"=>"12");
		$months = array_flip($months);
		$mnth = explode("-",$_POST['expense_date']);

		$m = $mnth[1];
		$y = $mnth[0];

		$data['month_number'] = $m;
		$data['year'] = $y;
		$data['month'] =$months[(int)$m];
		$this->db->where("SN",$sn)->update("tbl_expenses",$data);
	}

	public function delete_expenses($id){
		return $this->db->where("SN",$id)->delete("tbl_expenses");
	}


	public function getpaymentgenerated($limit=false,$type=false){
		$this->db->from('tbl_payment_payrole');
		if($limit!=false){
			$this->db->limit(4);
		}
		if($type!=false){
			$this->db->where("type",$type);
		}
		$this->db->order_by("SN","DESC");
		return $this->db->get()->result_array();
	}

	public function getpaymentgeneratedbyid($id){
		$payments= $this->db->from("tbl_payment_payrole")->where("SN",$id)->get()->result_array();
		foreach($payments as $pay);
		return $pay;
	}

	public function getpaymentgeneratedhistorybypaymentid($id){
		return $this->db->from("tbl_payment_payrole_history")->where("payment_id",$id)->get()->result_array();
	}


	public function numberofgetexpiredproduct(){
		$num = 0;
		$now = time();
		$stocks = $this->getStocks();
		foreach($stocks as $stock){
			$your_date = strtotime($stock['expired_date']);
			$datediff = $your_date - $now ;
			$number_of_days = round($datediff / (60 * 60 * 24));
			$extra_charges=$this->db->from("others")->where("SN","1")->get()->result_array()[0];
			if($stock['expired_date']!="0000-00-00"){
				if($number_of_days <= $extra_charges['track_expiry_date']){
					$num++;
				}
			}
		}
		return $num;
	}

	public function addSupplierInvoice($data){
		$product = array();
		$recieved_id= $this->utils->generateUniqueID("supplier_invoice","supplier_id");
		foreach($data['product'] as $key=>$trns_product){
			$product[] = array(
				'qty'=>$data['qty'][$key],
				'product'=>$trns_product,
				'remark'=>'Received'
			);
		}
		$save_data = array();
		$save_data['products']= json_encode($product);
		$save_data['supplier_id'] = $recieved_id;
		$save_data['supplier'] = $data['supplier'];
		$save_data['recieved_date'] = $data['recieved_date'];
		$save_data['branch_id'] = $this->getBranch_id();
		$save_data['note'] = $data['transfer_note'];
		$save_data['total_invoice_amount'] = $data['total_invoice_amount'];
		$save_data['status'] = $data['status'];
		return $this->db->insert("supplier_invoice",$save_data);
	}

	public function getInvoiceAmountpaid($d_id,$formated = FALSE){
		$this->db->from("invoice_payment_history");
		$this->db->where("Invoice_SN",$d_id);
		$rec = $this->db->get()->result_array();
		$total = 0;
		foreach($rec as $r){
			$total= $total + $r['amount'];
		}
		if($formated){
			return number_format($total,2);
		}
		return $total;
	}

	public function addInvoicePayment($invoice_id,$data){
		$save_data = array();
		$save_data['amount'] = $data['amount_paid'];
		$save_data['Invoice_SN'] = $invoice_id;
		$save_data['date_added'] = $data['recieved_date'];
		$save_data['supplier_id'] = $data['supplier_id'];
		$save_data['payment_method'] = $data['payment_method'];
		$save_data['invoice_id'] = $data['invoice_id'];
		return $this->db->insert("invoice_payment_history",$save_data);
	}

	public function getInvoicePaymentHistory($d_id){
		$this->db->from("invoice_payment_history");
		$this->db->where("Invoice_SN",$d_id);
		return  $this->db->get()->result_array();
	}

	public function getSupplierInvoiceID($transfer_id){
		$this->db->from("supplier_invoice");
		$this->db->where("supplier_id",$transfer_id);
		$transfer =$this->db->get();
		foreach($transfer->result_array() as $trans);
		return $trans;
	}

	public function getSupplierInvoiceBetween($from,$to,$array=FALSE){
		$br =$this->getBranch_id();
		$this->db->from("supplier_invoice");
		if($array!=FALSE){
			foreach($array as $key=>$value){
				$this->db->where($key,$value);
			}
		}
		if($br==0){
			$this->db->where("(recieved_date BETWEEN '$from' AND '$to')");
		}else{
			$this->db->where("(recieved_date BETWEEN '$from' AND '$to')  AND branch_id='$br'");
		}
		$this->db->order_by("SN","DESC");
		$transfers = $this->db->get();
		return $transfers->result_array();
	}


	public function getTotalDiscountByProduct($sales_id){
		$items = $this->db->get_where("sales",array("reciept_id"=>$sales_id))->row()->items;
		$items = json_decode($items,true);
		$total =0;
		foreach($items as $item){
			$total+=@$item['discount'];
		}
		return $total;
	}

	public function getUserDepartment(){
		$user_id = $this->tank_auth->get_user_id();

		$user = $this->db->get_where("users",array("id"=>$user_id))->row_array();
		if($user['role'] == "Administrator" || $user['role'] == "Superuser" || $user['role'] == "Auditor" || $user['role'] == "Accountant" || $user['role'] == "Manager"){
			return "Administrator";
		}else {
			return $user['department'];
		}
	}

	public function getUserDepartmentPos(){
		$user_id = $this->tank_auth->get_user_id();
		$user = $this->db->get_where("users",array("id"=>$user_id))->row_array();
		if($this->getUserDepartment() == "Administrator"){
			if($this->session->userdata("top_administrator_department")){
				return $this->session->userdata("top_administrator_department");
			}else{
				return $this->db->get('department')->row()->department;
			}
		}
		return $user['department'];
	}

	public function getDepartments(){
		return $this->db->from('department')->order_by('SN','DESC')->get()->result_array();
	}

	public function getServiceCategories(){
		return $this->db->from('service_category')->order_by('SN','DESC')->get()->result_array();
	}

	public function getServiceCategory($id){
		return $this->db->from('service_category')->where('SN',$id)->order_by('SN','DESC')->get()->row_array();
	}


	public function getService($service_id){
		$this->db->select("*");
		$this->db->from("services");
		$this->db->or_where('SN',$service_id);
		$this->db->or_where('servicecode',$service_id);
		$record =$this->db->get();
		return $record->row_array();
	}

	public function getCreditRangeNodaterange($array=FALSE){
		$this->db->from('tbl_credit_sales');
		if($array!=false){
            foreach($array as $key=>$value){
				$this->db->where($key,$value);
			}
		}
		$this->db->order_by("SN","DESC");
		return $this->db->get()->result_array();
	}


	public function getCreditTotals($customer_id){
		$ins = $this->getCreditRangeNodaterange(array('customer'=>$customer_id,'status'=>'PENDING'));
		$alltotal =0;
		$alldiscount =0;
		$allvat = 0;
		$allscharge = 0;
		$alltotalpaid =0;
		$allttp =0;
		$allbalp=0;
		foreach($ins as $in) {
			$alltotal += $in['total_amount'];
			$alldiscount += $in['discount'];
			$allvat += $in['vat_amount'];
			$allscharge += $in['s_charge_amt'];
			$alltotalpaid += $in['total_amount_paid'];
			$allttp += $this->stock->getTotalAmountCreditPaid($in['SN'], false);
			$allbalp += ($in['total_amount_paid'] - $this->stock->getTotalAmountCreditPaid($in['SN'], false));
		}

		return array('total_credit'=>$alltotal, 'total_amt_paid'=>$allttp,'balance'=>$allbalp);
	}


}


?>
