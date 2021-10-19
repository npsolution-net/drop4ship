<?php
class ModelExtensionShippingGhn extends Model {
	function getQuote($address) {
		$this->load->language('extension/shipping/ghn');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('shipping_ghn_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('shipping_ghn_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$quote_data = array();

			$quote_data['ghn'] = array(
				'code'         => 'ghn.ghn',
				'title'        => 'ghn',
				'cost'         => $this->config->get('shipping_ghn_cost'),
				'tax_class_id' => $this->config->get('shipping_ghn_tax_class_id'),
				'text'         => $this->currency->format($this->tax->calculate($this->config->get('shipping_ghn_cost'), $this->config->get('shipping_ghn_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
			);

			$method_data = array(
				'code'       => 'ghn',
				'title'      => $this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('shipping_ghn_sort_order'),
				'error'      => false
			);
		}

		return $method_data;
	}

	public function getActiveShipping($code){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "shipping_courier WHERE shipping_courier_code = '" . $code . "' AND shipping_courier_active = 1");
		return $query->row;
	}

	public function getServicesShipping($api = array()){
		$curl = curl_init();
		$data = json_encode(array(
			"shop_id" => (int)$api['shipping_courier_shop_id'],
			"from_district" => 1447,
			"to_district" => 1442
		));

		curl_setopt($curl, CURLOPT_URL, $api['shipping_courier_url'] . "/shipping-order/available-services");
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array( 
			"Content-Type: application/json",
			"Token: ". $api['shipping_courier_token'],
		));
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

		$result = curl_exec($curl);
		curl_close($curl);
		return json_decode($result);
	}

	public function getProvinces($data =array()){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "https://dev-online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/available-services");
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array( 
			"Content-Type: application/json",
			"Token: ". "615c1360-ec80-11eb-9388-d6e0030cbbb7",
		));
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

		$result = curl_exec($curl);
		curl_close($curl);
		return json_decode($result);
	}

	public function getEstimateCost($service_id, $service_type_id, $vendor, $shipping){
		$api = $this->config->get("ghn_shipping_api");
		$ward_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_ward WHERE name = '" . $shipping['ward_id'] . "' AND district_id = '" . $shipping['district_id'] . "'");

		$curl = curl_init();
		$data = json_encode(array(			
			"shop_id" => (int)$api['shop_id'],
			"from_district_id"=> (int)$vendor['district_id'],
			"service_id"=>(int)$service_id,
			"service_type_id"=>null,
			"to_district_id"=>(int)$shipping['district_id'],
			"to_ward_code"=>$ward_query->row['ward_id'],
			"height"=>1,
			"length"=>10,
			"weight"=>100,
			"width"=>5,
			"insurance_fee"=>10000,
			"coupon"=> null
		));
		
		curl_setopt($curl, CURLOPT_URL, $api['develop']['url'] . "/v2/shipping-order/fee");
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array( 
			"Content-Type: application/json",
			"Token: ". $api['develop']['token'],
		));
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

		$result = json_decode(curl_exec($curl));
		curl_close($curl);

		if($result && $result->data && $result->code == 200)
			return $result->data;

		return null;
	}
}