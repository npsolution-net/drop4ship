<?php
class ControllerExtensionGhtkApi extends Controller {
	public function index() {
		$this->load->model('api/ghtk');
		$json =array();
		$mapStatusGhtkOpenCart = array(-1 => array('text'=>'Hủy đơn hàng','op_status_id'=>16),
										1=> array('text'=>'Chưa tiếp nhận','op_status_id'=>1),
										2 => array('text'=>'Đã tiếp nhận','op_status_id'=>2),
										3 => array('text'=>'Đã lấy hàng/Đã nhập kho','op_status_id'=>2),
										4 => array('text'=>'Đã điều phối giao hàng/Đang giao hàng','op_status_id'=>2),
										5 => array('text'=>'Đã giao hàng/Chưa đối soát','op_status_id'=>2),
										6 => array('text'=>'Đã đối soát','op_status_id'=>2),
										7 => array('text'=>'Không lấy được hàng','op_status_id'=>10),
										8 => array('text'=>'Hoãn lấy hàng','op_status_id'=>8),
										9 => array('text'=>'Không giao được hàng','op_status_id'=>10),
										10 => array('text'=>'Delay giao hàng','op_status_id'=>2),
										11 => array('text'=>'Đã đối soát công nợ trả hàng','op_status_id'=>2),
										12 => array('text'=>'Đã điều phối lấy hàng/Đang lấy hàng','op_status_id'=>2),
										20 => array('text'=>'Đang trả hàng (COD cầm hàng đi trả)','op_status_id'=>2),
										21 => array('text'=>'Đã trả hàng (COD đã trả xong hàng)','op_status_id'=>3),
										123 => array('text'=>'Shipper báo đã lấy hàng','op_status_id'=>2),
										127 => array('text'=>'Shipper (nhân viên lấy/giao hàng) báo không lấy được hàng','op_status_id'=>10),
										128 => array('text'=>'Shipper báo delay lấy hàng','op_status_id'=>2),
										45 => array('text'=>'Shipper báo đã giao hàng','op_status_id'=>5),
										49 => array('text'=>'Shipper báo không giao được giao hàng','op_status_id'=>10),
										410 => array('text'=>'Shipper báo delay giao hàng','op_status_id'=>2),
										);
		if (!isset($this->request->post['label_id'])||!isset($this->request->post['partner_id'])||!isset($this->request->post['status_id'])) {
	      $json['error']['warning'] = $this->language->get('error');
	    } else {
	    	$order_id = $this->request->post['partner_id'];
	    	$op_order_status_id = $mapStatusGhtkOpenCart[$this->request->post['status_id']]['op_status_id'];
	    	$messenage = $mapStatusGhtkOpenCart[$this->request->post['status_id']]['text'];
	    	$messenage ="test";
	    	$shipCourier = $this->model_api_ghtk->updateOrder($order_id,$op_order_status_id,$messenage,1);
	        $json['success'] =$messenage;
	        //$json['data'] =array('label_id'=>$this->request->post['label_id'] , 'partner_id'=>$this->request->post['partner_id'] );
	    }
	    if (isset($this->request->server['HTTP_ORIGIN'])) {
	      $this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
	      $this->response->addHeader('Access-Control-Allow-Methods: POST'); // GET, PUT, , DELETE, OPTIONS
	      $this->response->addHeader('Access-Control-Max-Age: 1000');
	      $this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
	    }
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}


