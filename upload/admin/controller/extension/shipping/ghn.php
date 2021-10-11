<?php
class ControllerExtensionShippingGhn extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/shipping/ghn');

		$this->document->setTitle($this->language->get('ghn'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('shipping_ghn', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('ghn'),
			'href' => $this->url->link('extension/shipping/ghn', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/shipping/ghn', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true);

		if (isset($this->request->post['shipping_ghn_cost'])) {
			$data['shipping_ghn_cost'] = $this->request->post['shipping_ghn_cost'];
		} else {
			$data['shipping_ghn_cost'] = $this->config->get('shipping_ghn_cost');
		}

		if (isset($this->request->post['shipping_ghn_tax_class_id'])) {
			$data['shipping_ghn_tax_class_id'] = $this->request->post['shipping_ghn_tax_class_id'];
		} else {
			$data['shipping_ghn_tax_class_id'] = $this->config->get('shipping_ghn_tax_class_id');
		}

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['shipping_ghn_geo_zone_id'])) {
			$data['shipping_ghn_geo_zone_id'] = $this->request->post['shipping_ghn_geo_zone_id'];
		} else {
			$data['shipping_ghn_geo_zone_id'] = $this->config->get('shipping_ghn_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['shipping_ghn_status'])) {
			$data['shipping_ghn_status'] = $this->request->post['shipping_ghn_status'];
		} else {
			$data['shipping_ghn_status'] = $this->config->get('shipping_ghn_status');
		}

		if (isset($this->request->post['shipping_ghn_sort_order'])) {
			$data['shipping_ghn_sort_order'] = $this->request->post['shipping_ghn_sort_order'];
		} else {
			$data['shipping_ghn_sort_order'] = $this->config->get('shipping_ghn_sort_order');
		}

		if (isset($this->request->post['shipping_ghn_token'])) {
			$data['shipping_ghn_token'] = $this->request->post['shipping_ghn_token'];
		} else {
			$data['shipping_ghn_token'] = $this->config->get('shipping_ghn_token');
		}



		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/shipping/ghn', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/shipping/ghn')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function install() {
	}

	public function uninstall() {
	}

	public function createOrder() {
		$this->load->model('sale/order');
		$this->load->model('extension/shipping/courier');

		$order_id = $this->request->get['order_id'];
	//	echo $this->request->get['order_id'];
		if (isset($this->request->get['order_id'])) {
			$order_info = $this->model_sale_order->getOrder($order_id );
			$products = $this->model_sale_order->getOrderProducts($order_id);
			$totals = $this->model_sale_order->getOrderTotals($this->request->get['order_id']);
		}
		$shipCourier = $this->model_extension_shipping_courier->getCourierByCode("ghtk");
		$shipCourierId = $shipCourier['shipping_courier_id'];
/*echo "<pre>";
		print_r($shipCourier);
		echo "</pre>";die;*/
	/*	echo "<pre>";
		print_r($order_info);
		echo "</pre>";
		echo "<pre>";
		print_r($products);
		echo "</pre>";
echo "<pre>";
		print_r($totals);
		echo "</pre>";*/
		$shippingCost=0.00;
		foreach ($totals as $key => $total) {
			if ($total['code']=="shipping"&&$total['title']=="ghtk") {
				$shippingCost=$total['value'];
			}
			/*if ($total['code']=="sub_total") {
				$shippingCost=$total['value'];
			}*/
		}
		$pick_district =   $this->config->get('config_district');
		$pick_tel = $this->config->get('config_telephone'); 
		$this->load->model('localisation/zone');
		$zone = $this->model_localisation_zone->getZone($this->config->get('config_zone_id'));
		$pick_province = $zone['name'];
		$pick_zone_code = $zone['code'];
		$pick_address =  $this->config->get('config_address'); 
		$orderArr =array();
		$orderArr['products'] = array();
		$orderArr['order']['pick_money'] = $order_info['total'];
		$orderArr['order']['id'] = $order_id;
		$orderArr['order']['pick_address'] = $pick_address ; // $order_info['shipping_address_1'];
		$orderArr['order']['pick_province'] = $pick_province  ;//$order_info['shipping_zone'];
		$orderArr['order']['pick_district'] = $pick_district ;// $order_info['shipping_city'];
		$orderArr['order']['pick_tel'] = $pick_tel ;// '123456';
		$orderArr['order']['pick_name'] = $pick_zone_code."-nội thành";//$order_info['shipping_zone_code']

		$orderArr['order']['address'] = $order_info['payment_address_1'];
		$orderArr['order']['province'] = $order_info['payment_zone'];
		$orderArr['order']['district'] = $order_info['payment_city'];
		$orderArr['order']['tel'] = '123456';
		$orderArr['order']['name'] = $order_info['payment_zone_code']."-nội thành";

		$orderArr['order']['is_freeship'] = 1;
		$orderArr['order']['value'] = $shippingCost;
		$orderArr['order']['note'] = $order_info['comment'];
		$orderArr['order']['pick_date'] =date("Y-m-d");

		foreach ($products as $key => $product) {
			$orderArr['products'][] = array( "name"=> $product['name'],"weight"=>$product["weight"]);
		}
		$order = json_encode($orderArr);//echo $order;

		//die;
		//$order =  $order  ;//<<<HTTP_BODY HTTP_BODY
	/*	$order = <<<HTTP_BODY
{
    "products": [{
        "name": "bút",
        "weight": 0.1
    }, {
        "name": "tẩy",
        "weight": 0.2
    }],
    "order": {
        "id": "hoangss",
        "pick_name": "HCM-nội thành",
        "pick_address": "590 CMT8 P.11",
        "pick_province": "TP. Hồ Chí Minh",
        "pick_district": "Quận 3",
        "pick_tel": "0911222333",
        "tel": "0911222333",
        "name": "GHTK - HCM - Noi Thanh",
        "address": "123 nguyễn chí thanh",
        "province": "TP. Hồ Chí Minh",
        "district": "Quận 1",
        "is_freeship": "1",
        "pick_date": "2016-09-30",
        "pick_money": 47000,
        "note": "Khối lượng tính cước tối đa: 1.00 kg",
        "value": 3000000
    }
}
HTTP_BODY;*/

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://dev.ghtk.vn/services/shipment/order",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $order,
    CURLOPT_HTTPHEADER => array(
        "Content-Type: application/json",
        "Token: ". $this->config->get('shipping_ghtk_token'),
        "Content-Length: " . strlen($order),
    ),
));

$response = curl_exec($curl);
curl_close($curl);
$responseArr = json_decode($response, true);//var_dump($responseArr);
$data =array();
$data['order_id'] = $order_id;
$data['shipping_courier_id'] = $shipCourierId;
$data['tracking_number'] = $responseArr["order"]['label']  ;
$shipCourier = $this->model_extension_shipping_courier->createShipmentLabel($data);
$json['success'] =1;



$this->response->addHeader('Content-Type: application/json');
$this->response->setOutput(json_encode($json));
//echo 'Response: ' . $response;
/*	Response: {"success":true,"message":"","order":{"partner_id":"1","label":"S61162.70619028","area":"1","fee":"1590000","insurance_fee":"0","estimated_pick_time":"S\u00e1ng 2018-03-21","estimated_deliver_time":"S\u00e1ng 2018-03-22","products":[]}}*/
	//	die;
	}
	public function getStatus() {
		$this->load->model('sale/order');
		$this->load->model('extension/shipping/courier');
		$order_id = $this->request->get['order_id'];
		$shipCourier = $this->model_extension_shipping_courier->getLabelByOrderId($order_id);
		$label = $shipCourier["tracking_number"];
		//var_dump($shipCourier);
		$curl = curl_init();
		curl_setopt_array($curl, array(
	    CURLOPT_URL => "https://dev.ghtk.vn/services/shipment/v2/".$label,
	    CURLOPT_RETURNTRANSFER => true,
	    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	    CURLOPT_HTTPHEADER => array(
	        "Token: ". $this->config->get('shipping_ghtk_token'),
	    ),
		));

		$response = curl_exec($curl);
		curl_close($curl);
		$responseArr = json_decode($response, true);
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

		$ghtkStatus = $responseArr["order"]["status"];
		$ghtk_status_text = $mapStatusGhtkOpenCart[$ghtkStatus]['text'];
		//var_dump($responseArr["order"]["status"]);
		$json['success'] =1;
		$json['content'] = $ghtk_status_text;

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

		//echo 'Response: ' . $response;
	}

	public function cancelOrder() {
		$this->load->model('sale/order');
		$this->load->model('extension/shipping/courier');
		$order_id = $this->request->get['order_id'];
		$shipCourier = $this->model_extension_shipping_courier->getLabelByOrderId($order_id);
		if(isset($shipCourier["tracking_number"])){
		$label = $shipCourier["tracking_number"];
		//var_dump($shipCourier);
		$curl = curl_init();
		curl_setopt_array($curl, array(
		    CURLOPT_URL => "https://dev.ghtk.vn/services/shipment/cancel/".$label ,
		    CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_CUSTOMREQUEST => "POST",
		    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		    CURLOPT_HTTPHEADER => array(
		        "Token: ". $this->config->get('shipping_ghtk_token'),
		    ),
		));

		$response = curl_exec($curl);
		curl_close($curl);
		$responseArr = json_decode($response, true);
		//var_dump($responseArr);
		$shipCourier = $this->model_extension_shipping_courier->updateOrder($order_id,16,"",1);  //16 is voided status in opencart system
		$this->model_extension_shipping_courier->deleteShipmentLabel($order_id);

		$json['success'] =1;
	}else{
		$json['success'] =1;

	}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
		//echo 'Response: ' . $response;
	}


	public function getLabel() {
		$this->load->model('sale/order');
		$this->load->model('extension/shipping/courier');
		$order_id = $this->request->get['order_id'];
		$shipCourier = $this->model_extension_shipping_courier->getLabelByOrderId($order_id);
		$label = $shipCourier["tracking_number"];
		$curl = curl_init();

		curl_setopt_array($curl, array(
		    CURLOPT_URL => "https://dev.ghtk.vn/services/label/".$label ,
		    CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_CUSTOMREQUEST => "GET",
		    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		    CURLOPT_HTTPHEADER => array(
		        "Token: ". $this->config->get('shipping_ghtk_token'),
		    ),
		));

		$response = curl_exec($curl);
		curl_close($curl);

		echo 'Response: ' . $response;


		//echo 'Response: ' . $response;
	}

	public function webhookUpdatestatus(){

	}

	public function testApi() {
		$data = array(
		  'label_id' => "S61162.70619030",
		  'partner_id' => "3",
		  'action_time' => '2016-11-02T12:18:39+07:00',
		  'status_id' =>12,
		  'reason_code'=> '',
		  'reason'=> '',
		  'weight'=>2.4,
		  'fee'=>1500,
		);
		$curl = curl_init();
		curl_setopt_array($curl, array(
		    CURLOPT_URL => "http://localhost/opencart3/upload/index.php?route=ghtk/api",
		    CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_CUSTOMREQUEST => "POST",
		    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		    CURLOPT_POSTFIELDS => $data,
		));

		$response = curl_exec($curl);
		curl_close($curl);
		echo 'Response: ' . $response;
		//echo 'Response: ' . $response;
	}
}