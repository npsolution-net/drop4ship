<?php
class ControllerExtensionModuleLtsVendor extends Controller {
	private $error = array();

	public function index() {

		$data = array();

		$this->load->language('extension/module/lts_vendor');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			$this->model_setting_setting->editSetting('module_lts_vendor', $this->request->post);
			$this->model_setting_setting->editSetting('lts_vendor', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		$error_array = array(
			'warning'
			);
 
		foreach ($error_array as $error) {
			if (isset($this->error[$error])) {
				$data['error_' . $error] = $this->error[$error];
			} else {
				$data['error_' . $error] = '';
			}
		}


		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/lts_vendor', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/lts_vendor', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
 
		if (isset($this->request->post['module_lts_vendor_status'])) {
			$data['module_lts_vendor_status'] = $this->request->post['module_lts_vendor_status'];
		} else {
			$data['module_lts_vendor_status'] = $this->config->get('module_lts_vendor_status');
		}	

		if (isset($this->request->post['module_lts_vendor_registration'])) {
			$data['module_lts_vendor_registration'] = $this->request->post['module_lts_vendor_registration'];
		} else {
			$data['module_lts_vendor_registration'] = $this->config->get('module_lts_vendor_registration');
		}

		if (isset($this->request->post['module_lts_vendor_approval'])) {
			$data['module_lts_vendor_approval'] = $this->request->post['module_lts_vendor_approval'];
		} else {
			$data['module_lts_vendor_approval'] = $this->config->get('module_lts_vendor_approval');
		}
		
		if (isset($this->request->post['module_lts_vendor_product_approval'])) {
			$data['module_lts_vendor_product_approval'] = $this->request->post['module_lts_vendor_product_approval'];
		} else {
			$data['module_lts_vendor_product_approval'] = $this->config->get('module_lts_vendor_product_approval');
		}

		if (isset($this->request->post['module_lts_vendor_category_approval'])) {
			$data['module_lts_vendor_category_approval'] = $this->request->post['module_lts_vendor_category_approval'];
		} else {
			$data['module_lts_vendor_category_approval'] = $this->config->get('module_lts_vendor_category_approval');
		}
		
		if (isset($this->request->post['module_lts_vendor_review_action'])) {
			$data['module_lts_vendor_review_action'] = $this->request->post['module_lts_vendor_review_action'];
		} else {
			$data['module_lts_vendor_review_action'] = $this->config->get('module_lts_vendor_review_action');
		}
		
		if (isset($this->request->post['module_lts_vendor_multi_product'])) {
			$data['module_lts_vendor_multi_product'] = $this->request->post['module_lts_vendor_multi_product'];
		} else {
			$data['module_lts_vendor_multi_product'] = $this->config->get('module_lts_vendor_multi_product');
		}

		if (isset($this->request->post['module_lts_vendor_multi_product'])) {
			$data['module_lts_vendor_multi_product'] = $this->request->post['module_lts_vendor_multi_product'];
		} else {
			$data['module_lts_vendor_multi_product'] = $this->config->get('module_lts_vendor_multi_product');
		}

		if (isset($this->request->post['module_lts_vendor_name_in_cart'])) {
			$data['module_lts_vendor_name_in_cart'] = $this->request->post['module_lts_vendor_name_in_cart'];
		} else {
			$data['module_lts_vendor_name_in_cart'] = $this->config->get('module_lts_vendor_name_in_cart');
		}	



		if (isset($this->request->post['module_lts_vendor_vendor_can_change_order_status'])) {
			$data['module_lts_vendor_vendor_can_change_order_status'] = $this->request->post['module_lts_vendor_vendor_can_change_order_status'];
		} else {
			$data['module_lts_vendor_vendor_can_change_order_status'] = $this->config->get('module_lts_vendor_vendor_can_change_order_status');
		}

		if (isset($this->request->post['module_lts_vendor_order_status_change_by_vendor'])) {
			$data['module_lts_vendor_order_status_change_by_vendor'] = $this->request->post['module_lts_vendor_order_status_change_by_vendor'];
		} else {
			$data['module_lts_vendor_order_status_change_by_vendor'] = $this->config->get('module_lts_vendor_order_status_change_by_vendor');
		}

		if (isset($this->request->post['module_lts_vendor_receive_mail_product_purchase'])) {
			$data['module_lts_vendor_receive_mail_product_purchase'] = $this->request->post['module_lts_vendor_receive_mail_product_purchase'];
		} else {
			$data['module_lts_vendor_receive_mail_product_purchase'] = $this->config->get('module_lts_vendor_receive_mail_product_purchase');
		}

		if (isset($this->request->post['module_lts_vendor_customer_can_see_vendor_email'])) {
			$data['module_lts_vendor_customer_can_see_vendor_email'] = $this->request->post['module_lts_vendor_customer_can_see_vendor_email'];
		} else {
			$data['module_lts_vendor_customer_can_see_vendor_email'] = $this->config->get('module_lts_vendor_customer_can_see_vendor_email');
		}


		if (isset($this->request->post['module_lts_vendor_customer_can_see_vendor_telephone'])) {
			$data['module_lts_vendor_customer_can_see_vendor_telephone'] = $this->request->post['module_lts_vendor_customer_can_see_vendor_telephone'];
		} else {
			$data['module_lts_vendor_customer_can_see_vendor_telephone'] = $this->config->get('module_lts_vendor_customer_can_see_vendor_telephone');
		}



		if($this->config->get('module_lts_vendor_pincode_checker')) {
			$pincode_checker = $this->config->get('module_lts_vendor_pincode_checker');
		}

		if(!empty($pincode_checker)) {
			if(isset($pincode_checker['admin'])) {
				$data['admin_pincode'] = $pincode_checker['admin'];
			}

			// if(isset($pincode_checker['vendor'])) {
			// 	$data['vendor_pincode'] = $pincode_checker['vendor'];
			// }
		}

		if($this->config->get('module_lts_vendor_pincode_checker_required')) {
			$pincode_checker_required = $this->config->get('module_lts_vendor_pincode_checker_required');
		}

		if(!empty($pincode_checker_required)) {
			if(isset($pincode_checker_required['admin'])) {
				$data['admin_pincode_required'] = $pincode_checker_required['admin'];
			}

			if(isset($pincode_checker_required['vendor'])) {
				$data['vendor_pincode_required'] = $pincode_checker_required['vendor'];
			}
		}

		if (isset($this->request->post['module_lts_vendor_category_required'])) {
			$data['module_lts_vendor_category_required'] = $this->request->post['module_lts_vendor_category_required'];
		} else {
			$data['module_lts_vendor_category_required'] = $this->config->get('module_lts_vendor_category_required');
		}

		if (isset($this->request->post['module_lts_vendor_admin_receive_mail_product_add'])) {
			$data['module_lts_vendor_admin_receive_mail_product_add'] = $this->request->post['module_lts_vendor_admin_receive_mail_product_add'];
		} else {
			$data['module_lts_vendor_admin_receive_mail_product_add'] = $this->config->get('module_lts_vendor_admin_receive_mail_product_add');
		}

		if (isset($this->request->post['module_lts_vendor_delete_product'])) {
			$data['module_lts_vendor_delete_product'] = $this->request->post['module_lts_vendor_delete_product'];
		} else {
			$data['module_lts_vendor_delete_product'] = $this->config->get('module_lts_vendor_delete_product');
		}

		if($this->config->get('module_lts_vendor_product_tab')) {
			$tabs = $this->config->get('module_lts_vendor_product_tab');
		}

		if(!empty($tabs)) {
			if(isset($tabs['links'])) {
				$data['links'] = $tabs['links'];
			}

			if(isset($tabs['attribute'])) {
				$data['attribute'] = $tabs['attribute'];
			}

			if(isset($tabs['option'])) {
				$data['option'] = $tabs['option'];
			}

			if(isset($tabs['discount'])) {
				$data['discount'] = $tabs['discount'];
			}

			if(isset($tabs['special'])) {
				$data['special'] = $tabs['special'];
			}

			if(isset($tabs['image'])) {
				$data['image'] = $tabs['image'];
			}

			if(isset($tabs['reward'])) {
				$data['reward'] = $tabs['reward'];
			}

			if(isset($tabs['seo'])) {
				$data['seo'] = $tabs['seo'];
			}
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['module_lts_vendor_received_commission_status_id'])) {
			$data['module_lts_vendor_received_commission_status_id'] = $this->request->post['module_lts_vendor_received_commission_status_id'];
		} else {
			$data['module_lts_vendor_received_commission_status_id'] = $this->config->get('module_lts_vendor_received_commission_status_id');
		}
		
		if (isset($this->request->post['module_lts_vendor_default_commission'])) {
			$data['module_lts_vendor_default_commission'] = $this->request->post['module_lts_vendor_default_commission'];
		} else {
			$data['module_lts_vendor_default_commission'] = $this->config->get('module_lts_vendor_default_commission');
		}

		$data['header'] 	 = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer']		 = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/lts_vendor', $data));
	}

	public function install() {
		$this->load->model('vendor/lts_module');
 
		$this->model_vendor_lts_module->addPermission();

		$this->model_vendor_lts_module->createTables();
	}

	public function uninstall() {
		$this->load->model('setting/setting');

		$this->load->model('vendor/lts_module');

		$this->model_vendor_lts_module->deletTables();
		$this->model_vendor_lts_module->removePermission();

		$this->model_setting_setting->editSettingValue('module_lts_vendor', 'module_lts_vendor_status', "0");
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/lts_vendor')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
