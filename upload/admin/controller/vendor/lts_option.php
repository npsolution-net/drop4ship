<?php
class ControllerVendorLtsOption extends Controller {
	private $error;

	public function index() {
		$this->load->language('vendor/lts_option');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('vendor/lts_option');

		$this->getList();
	}

	public function add() {

		$this->load->language('vendor/lts_option');

		$this->load->model('vendor/lts_option');

		$this->document->setTitle($this->language->get('heading_title'));

		if(($this->request->server['REQUEST_METHOD'] == 'POST' ) && $this->validateForm()) {

			$this->model_vendor_lts_option->addOptionMapping($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('vendor/lts_option', 'user_token=' . $this->session->data['user_token'], true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('vendor/lts_option');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('vendor/lts_option');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_vendor_lts_option->editOptionMapping($this->request->get['option_mapping_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('vendor/lts_option', 'user_token=' . $this->session->data['user_token'] , true));
		}

		$this->getForm();
	}

	public function delete() {
        
        $this->load->language('vendor/lts_option');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('vendor/lts_option');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $option_mapping_id) {

                $this->model_vendor_lts_option->deleteOptionMapping($option_mapping_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('vendor/lts_option', 'user_token=' . $this->session->data['user_token'], true));
        } 

        $this->getList();
    }

	protected function getList() {
		$this->load->model('catalog/option');

		$this->load->model('catalog/category');

		$data['user_token'] = $this->session->data['user_token'];

		$option_info = $this->model_vendor_lts_option->getOptionMapping();

		foreach ($option_info as $option) {
			$e_options = explode(',',$option['option']);

			$option_names = array();

			$category_name = $this->model_catalog_category->getCategory($option['category_id']);

			foreach($e_options as $option_id) {
				$option_name = $this->model_catalog_option->getOption($option_id);

				$option_names[] = $option_name;
			}

			$data['options'][] = array(
				'option_mapping_id'  => $option['option_mapping_id'],
				'name'		=> $category_name['name'],	
				'option_names'	=> $option_names,
				'edit'				=> $this->url->link('vendor/lts_option/edit', 'user_token=' . $this->session->data['user_token'] . '&option_mapping_id=' . $option['option_mapping_id'], true)
			);
		}

		$data['add']	= $this->url->link('vendor/lts_option/add', 'user_token=' . $this->session->data['user_token'], true);
		$data['delete']	= $this->url->link('vendor/lts_option/delete', 'user_token=' . $this->session->data['user_token'], true);

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['header']			= $this->load->controller('common/header');
		$data['column_left']	= $this->load->controller('common/column_left');
		$data['footer']			= $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('vendor/lts_option_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['option_mapping_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		if(isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if(isset($this->error['category'])) {
			$data['error_category'] = $this->error['category'];
		} else {
			$data['error_category'] = '';
		}

		if(isset($this->error['options'])) {
			$data['error_options'] = $this->error['options'];
		} else {
			$data['error_options'] = '';
		}

		$data['user_token'] = $this->session->data['user_token'];

		if (!isset($this->request->get['option_mapping_id'])) {
			$data['action'] = $this->url->link('vendor/lts_option/add', 'user_token=' . $this->session->data['user_token'], true);
		} else {
			$data['action'] = $this->url->link('vendor/lts_option/edit', 'user_token=' . $this->session->data['user_token'] . '&option_mapping_id=' . $this->request->get['option_mapping_id'], true);
		}	

		$data['cancel'] = $this->url->link('vendor/lts_option', 'user_token=' . $this->session->data['user_token'], true);

		if (isset($this->request->get['option_mapping_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$option_mapping_info = $this->model_vendor_lts_option->getOptionMappingById($this->request->get['option_mapping_id']);
		}

		$this->load->model('catalog/category');


		if (isset($this->request->post['category_id'])) {
			$data['category_id'] = $this->request->post['category_id'];
		} elseif (!empty($option_mapping_info)) {
			$data['category_id'] = explode(',', $option_mapping_info['category_id']);
		} else {
			$data['category_id'] = '';
		}

		if (isset($this->request->post['category'])) {
			$data['category'] = $this->request->post['category'];
		} elseif (!empty($option_mapping_info)) {
			$data['category'] = $this->model_catalog_category->getCategory($option_mapping_info['category_id']);
		} else {
			$data['category'] = '';
		}

		if (isset($this->request->get['option_mapping_id'])) {
			$data['option_mapping_id'] = $this->request->get['option_mapping_id'];
		} else {
			$data['option_mapping_id'] = '';
		}

		if (isset($this->request->post['options'])) {
			$options = $this->request->post['options'];
		} elseif (!empty($option_mapping_info)) {
			$options = explode(',', $option_mapping_info['option']);
		} else {
			$options = array();
		}

		$this->load->model('catalog/option');

		$data['options'] = array();

		foreach ($options as $option_id) {
			$option_name = $this->model_catalog_option->getOption($option_id);
			if ($option_name) {
				$data['options'][] = array(
					'option_id' => $option_name['option_id'],
					'name'         => $option_name['name']
				);
			}
		}

		$data['header']			= $this->load->controller('common/header');
		$data['column_left']	= $this->load->controller('common/column_left');
		$data['footer']			= $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('vendor/lts_option_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'vendor/lts_option')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['category_id']) {
			$this->error['category'] = $this->language->get('error_category');
		}

		// if(!isset($this->request->get['option_mapping_id'])) {	
		// 	if ($this->model_vendor_lts_option->getTotalOptionMappingById($this->request->post['category_id'])) {
		// 		$this->error['warning'] = $this->language->get('error_category_exists');
		// 	}
		// }

		if (!isset($this->request->post['options'])) {
			$this->error['options'] = $this->language->get('error_options');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	public function validateDelete() {
        if (!$this->user->hasPermission('modify', 'vendor/lts_option')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

	public function optionMapping() {
		if (isset($this->request->get['category_id'])) {
			$category_id =  $this->request->get['category_id'];
		} else {
			$category_id = 0;
		}

		$json = array();;

		$this->load->model("vendor/lts_option");

		$this->load->model("catalog/option");

		$this->load->model('tool/image');

		$option_info = $this->model_vendor_lts_option->getOptionMappingByCategoryId($this->request->get['category_id']);

		if(!empty($option_info)) {
			$options_id = explode(',', $option_info['option']);

			foreach ($options_id as $option_id) {
				$option_name[] = $this->model_catalog_option->getOption($option_id);
			}

			foreach($option_name as $option) {

				if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') {

					$option_values = $this->model_catalog_option->getOptionValues($option['option_id']);

					foreach ($option_values as $option_value) {
						if (is_file(DIR_IMAGE . $option_value['image'])) {
							$image = $this->model_tool_image->resize($option_value['image'], 50, 50);
						} else {
							$image = $this->model_tool_image->resize('no_image.png', 50, 50);
						}

						$option_value_data[] = array(
							'option_value_id' => $option_value['option_value_id'],
							'name'            => strip_tags(html_entity_decode($option_value['name'], ENT_QUOTES, 'UTF-8')),
							'image'           => $image
						);
					}

					$sort_order = array();

					foreach ($option_value_data as $key => $value) {
						$sort_order[$key] = $value['name'];
					}

					array_multisort($sort_order, SORT_ASC, $option_value_data);
				}

				$type = '';

				if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox') {
					$type = $this->language->get('text_choose');
				}

				if ($option['type'] == 'text' || $option['type'] == 'textarea') {
					$type = $this->language->get('text_input');
				}

				if ($option['type'] == 'file') {
					$type = $this->language->get('text_file');
				}

				if ($option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
					$type = $this->language->get('text_date');
				}

				$json[] = array(
					'option_id'    => $option['option_id'],
					'name'         => strip_tags(html_entity_decode($option['name'], ENT_QUOTES, 'UTF-8')),
					'category'     => $type,
					'type'         => $option['type'],
					'option_value' => isset($option_value_data) ? $option_value_data : ''
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
