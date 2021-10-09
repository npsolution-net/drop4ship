<?php
class ControllerExtensionModuleLatest extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/latest');

		$this->load->model('catalog/product');

		$this->load->model('dropship/dropship');

		$this->load->model('tool/image');

		$data['customer_group_id'] = array();

		$dropship_filter = array(
			'customer_id' => $this->customer->getId()
		);
		$dropship_groups = $this->model_dropship_dropship->getDropshipGroup($dropship_filter);

		foreach($dropship_groups as $dropship_group)
			$data['dropship_group'][]  = $dropship_group['customer_group_id'];	

		$data['products'] = array();

		$product_filter = array(
			'limit' => $setting['limit'],
		);
		
		if(isset($data['dropship_group']))
			$product_filter['customer_group'] = $data['dropship_group'];

		// if($this->customer->isLogged())
		// 	$product_filter['customer_id'] = $this->customer->isLogged();
		
		$results = $this->model_catalog_product->getLatestProducts($product_filter);

		if ($results) {
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if (!is_null($result['special']) && (float)$result['special'] >= 0) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					$tax_price = (float)$result['special'];
				} else {
					$special = false;
					$tax_price = (float)$result['price'];
				}
	
				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format($tax_price, $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $result['rating'];
				} else {
					$rating = false;
				}
				$item = array(
					'product_id'  => $result['product_id'],						
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'rating'      => $rating,
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id']),
				);

				if(isset($result['customer_id']))
					$item['customer_id']  = $result['customer_id'];
				if(isset($result['customer_group_id']))
					$item['customer_group_id']  = $result['customer_group_id'];		
				if(isset($result['customer_group_id']) && isset($data['dropship_group']) && in_array($result['customer_group_id'], $data['dropship_group']))
					$item['is_in_group']  = true;
				
				$data['products'][] = $item;
			}
			$data['isLogged'] = $this->customer->isLogged();
			return $this->load->view('extension/module/latest', $data);
		}
	}
}
