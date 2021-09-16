<?php
class ControllerVendorLtsVendor extends Controller {
	public function index() {
		$this->load->language('vendor/lts_vendor');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('vendor/lts_vendor');

		$this->getList();
	}

	public function edit() {
		$this->load->language('vendor/lts_vendor');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('vendor/lts_vendor');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$this->model_vendor_lts_vendor->editVendorStoreInfo($this->request->get['vendor_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_store_owner'])) {
				$url .= '&filter_store_owner=' . urlencode(html_entity_decode($this->request->get['filter_store_owner'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_store_name'])) {
				$url .= '&filter_store_name=' . urlencode(html_entity_decode($this->request->get['filter_store_name'], ENT_QUOTES, 'UTF-8'));
			}	

			if (isset($this->request->get['filter_email'])) {
				$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_approved'])) {
				$url .= '&filter_approved=' . $this->request->get['filter_approved'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('vendor/lts_vendor', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();

	}



	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['vendor_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

	    if (isset($this->error['warning'])) {
	      $data['error_warning'] = $this->error['warning'];
	    } else { 
	      $data['error_warning'] = '';
	    }

	    if (isset($this->error['meta_title'])) {
	      $data['error_meta_title'] = $this->error['meta_title'];
	    } else {
	      $data['error_meta_title'] = '';
	    }

	    if (isset($this->error['meta_description'])) {
	      $data['error_meta_description'] = $this->error['meta_description'];
	    } else {
	      $data['error_meta_description'] = '';
	    }

	    if (isset($this->error['meta_keyword'])) {
	      $data['error_meta_keyword'] = $this->error['meta_keyword'];
	    } else {
	      $data['error_meta_keyword'] = '';
	    }

	    if (isset($this->error['owner_name'])) {
	      $data['error_owner_name'] = $this->error['owner_name'];
	    } else {
	      $data['error_owner_name'] = '';
	    }

	    if (isset($this->error['store_name'])) {
	      $data['error_store_name'] = $this->error['store_name'];
	    } else {
	      $data['error_store_name'] = '';
	    }

	    if (isset($this->error['address'])) {
	      $data['error_address'] = $this->error['address'];
	    } else {
	      $data['error_address'] = '';
	    }

	    if (isset($this->error['email'])) {
	      $data['error_email'] = $this->error['email'];
	    } else {
	      $data['error_email'] = '';
	    }

	    if (isset($this->error['telephone'])) {
	      $data['error_telephone'] = $this->error['telephone'];
	    } else {
	      $data['error_telephone'] = '';
	    }
	    

	     if (isset($this->error['country'])) {
	      $data['error_country'] = $this->error['country'];
	    } else {
	      $data['error_country'] = '';
	    }
	    
	    if (isset($this->error['zone'])) {
	      $data['error_zone'] = $this->error['zone'];
	    } else {
	      $data['error_zone'] = '';
	    }

	    if (isset($this->error['city'])) {
	      $data['error_city'] = $this->error['city'];
	    } else {
	      $data['error_city'] = '';
	    }

		$url = '';

		if (isset($this->request->get['filter_store_owner'])) {
			$url .= '&filter_store_owner=' . urlencode(html_entity_decode($this->request->get['filter_store_owner'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_store_name'])) {
			$url .= '&filter_store_name=' . urlencode(html_entity_decode($this->request->get['filter_store_name'], ENT_QUOTES, 'UTF-8'));
		}	

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_approved'])) {
			$url .= '&filter_approved=' . $this->request->get['filter_approved'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}


		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('vendor/lts_vendor', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['cancel'] = $this->url->link('vendor/lts_vendor', 'user_token=' . $this->session->data['user_token'] . $url, true);

		if (isset($this->request->get['vendor_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$store_info = $this->model_vendor_lts_vendor->getVendorInfo($this->request->get['vendor_id']);
		}

	    $data['user_token'] = $this->session->data['user_token'];

	    if (isset($this->request->post['description'])) {
	      $data['description'] = $this->request->post['description'];
	    } elseif (!empty($store_info)) {
	      $data['description'] = $store_info['description'];
	    } else {
	      $data['description'] = '';
	    }

	    if (isset($this->request->post['meta_title'])) {
	      $data['meta_title'] = $this->request->post['meta_title'];
	    } elseif (!empty($store_info)) {
	      $data['meta_title'] = $store_info['meta_title'];
	    } else {
	      $data['meta_title'] = '';
	    }

	    if (isset($this->request->post['meta_description'])) {
	      $data['meta_description'] = $this->request->post['meta_description'];
	    } elseif (!empty($store_info)) {
	      $data['meta_description'] = $store_info['meta_description'];
	    } else {
	      $data['meta_description'] = '';
	    }

	    if (isset($this->request->post['meta_title'])) {
	      $data['meta_title'] = $this->request->post['meta_title'];
	    } elseif (!empty($store_info)) {
	      $data['meta_title'] = $store_info['meta_title'];
	    } else {
	      $data['meta_title'] = '';
	    }

	    if (isset($this->request->post['meta_keyword'])) {
	      $data['meta_keyword'] = $this->request->post['meta_keyword'];
	    } elseif (!empty($store_info)) {
	      $data['meta_keyword'] = $store_info['meta_keyword'];
	    } else {
	      $data['meta_keyword'] = '';
	    }

	    if (isset($this->request->post['status'])) {
	      $data['status'] = $this->request->post['status'];
	    } elseif (!empty($store_info)) {
	      $data['status'] = $store_info['status'];
	    } else {
	      $data['status'] = '';
	    }

	    if (isset($this->request->post['approved'])) {
	      $data['approved'] = $this->request->post['approved'];
	    } elseif (!empty($store_info)) {
	      $data['approved'] = $store_info['approved'];
	    } else {
	      $data['approved'] = '';
	    }

	    if (isset($this->request->post['store_owner'])) {
	      $data['store_owner'] = $this->request->post['store_owner'];
	    } elseif (!empty($store_info)) {
	      $data['store_owner'] = $store_info['store_owner'];
	    } else {
	      $data['store_owner'] = '';
	    }

	    if (isset($this->request->post['store_name'])) {
	      $data['store_name'] = $this->request->post['store_name'];
	    } elseif (!empty($store_info)) {
	      $data['store_name'] = $store_info['store_name'];
	    } else {
	      $data['store_name'] = '';
	    }

	    if (isset($this->request->post['address'])) {
	      $data['address'] = $this->request->post['address'];
	    } elseif (!empty($store_info)) {
	      $data['address'] = $store_info['address'];
	    } else {
	      $data['address'] = '';
	    }

	    if (isset($this->request->post['email'])) {
	      $data['email'] = $this->request->post['email'];
	    } elseif (!empty($store_info)) {
	      $data['email'] = $store_info['email'];
	    } else {
	      $data['email'] = '';
	    }

	    if (isset($this->request->post['telephone'])) {
	      $data['telephone'] = $this->request->post['telephone'];
	    } elseif (!empty($store_info)) {
	      $data['telephone'] = $store_info['telephone'];
	    } else {
	      $data['telephone'] = '';
	    }

	    if (isset($this->request->post['fax'])) {
	      $data['fax'] = $this->request->post['fax'];
	    } elseif (!empty($store_info)) {
	      $data['fax'] = $store_info['fax'];
	    } else {
	      $data['fax'] = '';
	    }


	    if (isset($this->request->post['country_id'])) {
	      $data['country_id'] = $this->request->post['country_id'];
	    } elseif (!empty($store_info)) {
	      $data['country_id'] = $store_info['country_id'];
	    } else {
	      $data['country_id'] = '';
	    }

	    $this->load->model('localisation/country');

	    $data['countries'] = $this->model_localisation_country->getCountries();

	    if (isset($this->request->post['zone_id'])) {
	      $data['zone_id'] = $this->request->post['zone_id'];
	    } elseif (!empty($store_info)) {
	      $data['zone_id'] = $store_info['zone_id'];
	    } else {
	      $data['zone_id'] = '';
	    }

	    if (isset($this->request->post['city'])) {
	      $data['city'] = $this->request->post['city'];
	    } elseif (!empty($store_info)) {
	      $data['city'] = $store_info['city'];
	    } else {
	      $data['city'] = '';
	    }

	    if (isset($this->request->post['logo'])) {
	      $data['logo'] = $this->request->post['logo'];
	    } elseif (!empty($store_info)) {
	      $data['logo'] = $store_info['logo'];
	    } else {
	      $data['logo'] = '';
	    }  

	    $this->load->model('tool/image');

	    if (isset($this->request->post['logo']) && is_file(DIR_IMAGE . $this->request->post['logo'])) {
	      $data['logo_thumb'] = $this->model_tool_image->resize($this->request->post['logo'], 100, 100);
	    } elseif (!empty($store_info) && is_file(DIR_IMAGE . $store_info['logo'])) {
	      $data['logo_thumb'] = $this->model_tool_image->resize($store_info['logo'], 100, 100);
	    } else {
	      $data['logo_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
	    }

	    if (isset($this->request->post['banner'])) {
	      $data['banner'] = $this->request->post['banner'];
	    } elseif (!empty($store_info)) {
	      $data['banner'] = $store_info['banner'];
	    } else {
	      $data['banner'] = '';
	    }
	   
	    if (isset($this->request->post['banner']) && is_file(DIR_IMAGE . $this->request->post['banner'])) {
	      $data['banner_thumb'] = $this->model_tool_image->resize($this->request->post['banner'], 100, 100);
	    } elseif (!empty($store_info) && is_file(DIR_IMAGE . $store_info['banner'])) {
	      $data['banner_thumb'] = $this->model_tool_image->resize($store_info['banner'], 100, 100);
	    } else {
	      $data['banner_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
	    }

	    if (isset($this->request->post['profile_image'])) {
	      $data['profile_image'] = $this->request->post['profile_image'];
	    } elseif (!empty($store_info)) {
	      $data['profile_image'] = $store_info['profile_image'];
	    } else {
	      $data['profile_image'] = '';
	    }  

	    if (isset($this->request->post['profile_image']) && is_file(DIR_IMAGE . $this->request->post['profile_image'])) {
	      $data['profile_image_thumb'] = $this->model_tool_image->resize($this->request->post['profile_image'], 100, 100);
	    } elseif (!empty($store_info) && is_file(DIR_IMAGE . $store_info['profile_image'])) {
	      $data['profile_image_thumb'] = $this->model_tool_image->resize($store_info['profile_image'], 100, 100);
	    } else {
	      $data['profile_image_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
	    }


	     $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);


	    if (isset($this->request->post['facebook'])) {
	      $data['facebook'] = $this->request->post['facebook'];
	    } elseif (!empty($store_info)) {
	      $data['facebook'] = $store_info['facebook'];
	    } else {
	      $data['facebook'] = '';
	    }

	    if (isset($this->request->post['instagram'])) {
	      $data['instagram'] = $this->request->post['instagram'];
	    } elseif (!empty($store_info)) {
	      $data['instagram'] = $store_info['instagram'];
	    } else {
	      $data['instagram'] = '';
	    }

	    if (isset($this->request->post['youtube'])) {
	      $data['youtube'] = $this->request->post['youtube'];
	    } elseif (!empty($store_info)) {
	      $data['youtube'] = $store_info['youtube'];
	    } else {
	      $data['youtube'] = '';
	    }

	    if (isset($this->request->post['twitter'])) {
	      $data['twitter'] = $this->request->post['twitter'];
	    } elseif (!empty($store_info)) {
	      $data['twitter'] = $store_info['twitter'];
	    } else {
	      $data['twitter'] = '';
	    }

	    if (isset($this->request->post['pinterest'])) {
	      $data['pinterest'] = $this->request->post['pinterest'];
	    } elseif (!empty($store_info)) {
	      $data['pinterest'] = $store_info['pinterest'];
	    } else {
	      $data['pinterest'] = '';
	    }

	    if (isset($this->request->post['commission_rate'])) {
	      $data['commission_rate'] = $this->request->post['commission_rate'];
	    } elseif (!empty($store_info)) {
	      $data['commission_rate'] = $store_info['commission_rate'];
	    } else {
	      $data['commission_rate'] = '';
	    }

	    if (isset($this->request->post['vendor_seo_url'])) {
	      $data['vendor_seo_url'] = $this->request->post['vendor_seo_url'];
	    } else if($store_info) {
	      $data['vendor_seo_url'] = $this->model_vendor_lts_vendor->getVendorSeoUrls($store_info['vendor_id']);
	    } else {
	      $data['vendor_seo_url'] = array();
	    }

	    $data['error_warning_message'] = '';

	    if(isset($vendor_info)) {
	      if($data['status']) {
	        if($data['approved']) {
	          $data['error_warning_message'] = '';
	        } else {
	          $data['error_warning_message'] = $this->language->get('message_need_approval');
	        }
	      } else {
	        $data['error_warning_message'] = $this->language->get('message_status_disabled');
	      }
	    }

	    $this->load->model('localisation/language');
	      $data['languages'] = $this->model_localisation_language->getLanguages();

	      $this->load->model('setting/store');
	      $data['stores'] = array();

	      $data['stores'][] = array(
	        'store_id' => 0,
	        'name'     => $this->language->get('text_default')
	      );

	      $stores = $this->model_setting_store->getStores();

	      foreach ($stores as $store) {
	        $data['stores'][] = array(
	          'store_id' => $store['store_id'],
	          'name'     => $store['name']
	        );
	      }

	      if(!empty($store_info)) {
	      	$data['product']	= $this->url->link('vendor/lts_product', 'user_token=' . $this->session->data['user_token'] . '&filter_vendor_id=' . $this->request->get['vendor_id'], true);
	    	$data['order']	    = $this->url->link('vendor/lts_order', 'user_token=' . $this->session->data['user_token'] . '&filter_vendor_id=' . $this->request->get['vendor_id'], true);
	      } else {
	      	$data['product']	= $this->url->link('vendor/lts_product', 'user_token=' . $this->session->data['user_token'], true);
	      	$data['order']		= $this->url->link('vendor/lts_order', 'user_token=' . $this->session->data['user_token'], true);
	      }
	    
	       
		$data['header'] 	 = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer']		 = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('vendor/lts_vendor_form', $data));

	}


	protected function getList() { 

		if (isset($this->request->get['filter_store_owner'])) {
			$filter_store_owner = $this->request->get['filter_store_owner'];
		} else {
			$filter_store_owner = '';
		}

		if (isset($this->request->get['filter_store_name'])) {
			$filter_store_name = $this->request->get['filter_store_name'];
		} else {
			$filter_store_name = '';
		}

		if (isset($this->request->get['filter_email'])) {
			$filter_email = $this->request->get['filter_email'];
		} else {
			$filter_email = '';
		}	

		if (isset($this->request->get['filter_approved'])) {
			$filter_approved = $this->request->get['filter_approved'];
		} else {
			$filter_approved = '';
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = '';
		}
 
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = ''; 
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'lv.store_owner';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];  
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_store_owner'])) {
			$url .= '&filter_store_owner=' . urlencode(html_entity_decode($this->request->get['filter_store_owner'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_store_name'])) {
			$url .= '&filter_store_name=' . urlencode(html_entity_decode($this->request->get['filter_store_name'], ENT_QUOTES, 'UTF-8'));
		}	

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_approved'])) {
			$url .= '&filter_approved=' . $this->request->get['filter_approved'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}


		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('vendor_id/lts_vendor', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$filter_data = array(
			'filter_store_owner'	  => $filter_store_owner,
			'filter_store_name'    => $filter_store_name,
			'filter_email'    	  => $filter_email,
			'filter_approved'     => $filter_approved,
			'filter_status'  	  => $filter_status,
			'filter_date_added'   => $filter_date_added,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		); 

		$data['user_token'] = $this->session->data['user_token'];

		$vendor_total = $this->model_vendor_lts_vendor->getTotalVendors($filter_data);

		$results = $this->model_vendor_lts_vendor->getVendors($filter_data);



		$this->load->model('customer/customer');
  
		foreach($results as $result) {
			$login_info = $this->model_customer_customer->getTotalLoginAttempts($result['email']);

			if ($login_info && $login_info['total'] >= $this->config->get('config_login_attempts')) {
				$unlock = $this->url->link('customer/customer/unlock', 'user_token=' . $this->session->data['user_token'] . '&email=' . $result['email'] . $url, true);
			} else {
				$unlock = '';
			}

			$login = $this->url->link('customer/customer/login', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $result['customer_id'] . '&store_id=0', true);


			$data['vendors'][] = array( 
				'vendor_id'  	=> $result['vendor_id'],
				'store_owner'  	=> $result['store_owner'] ,
				'store_name'    => $result['store_name'],
				'total_products'=> $result['total_products'],
				'email'			=> $result['email'],
				'date_added'	=> $result['date_added'],
				'unlock'        => $unlock,
				'login'         => $login,
				'approved'      => $result['approved'],
				'status'	 	=> $result['approved'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'edit'		 	=> $this->url->link('vendor/lts_vendor/edit', 'user_token=' . $this->session->data['user_token'] . '&vendor_id=' . $result['vendor_id'], true),
				'approve'		 	=> $this->url->link('vendor/lts_vendor/approve', 'user_token=' . $this->session->data['user_token'] . '&vendor_id=' . $result['vendor_id'], true)
			);
		} 


		$data['vendor_request'] = $this->model_vendor_lts_vendor->vendorRequest();

		$url = '';

		$data['user_token'] = $this->session->data['user_token'];

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

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['store_owner'])) {
			$url .= '&store_owner=' . urlencode(html_entity_decode($this->request->get['store_owner'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['store_name'])) {
			$url .= '&store_name=' . urlencode(html_entity_decode($this->request->get['store_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_approved'])) {
			$url .= '&filter_approved=' . $this->request->get['filter_approved'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_store_owner'] = $this->url->link('vendor/lts_vendor', 'user_token=' . $this->session->data['user_token'] . '&sort=lv.store_owner' . $url, true);
		$data['sort_store_name'] = $this->url->link('vendor/lts_vendor', 'user_token=' . $this->session->data['user_token'] . '&sort=lv.store_name' . $url, true);
		$data['sort_total_products'] = $this->url->link('vendor/lts_vendor', 'user_token=' . $this->session->data['user_token'] . '&sort=lp.total_products' . $url, true);
		$data['sort_email'] = $this->url->link('vendor/lts_vendor', 'user_token=' . $this->session->data['user_token'] . '&sort=lv.email' . $url, true);
		$data['sort_date_added'] = $this->url->link('vendor/lts_vendor', 'user_token=' . $this->session->data['user_token'] . '&sort=lv.date_added' . $url, true);
		$data['sort_status'] = $this->url->link('vendor/lts_vendor', 'user_token=' . $this->session->data['user_token'] . '&sort=lv.status' . $url, true);

		// $data['sort_order'] = $this->url->link('vendor/lts_vendor', 'user_token=' . $this->session->data['user_token'] . '&sort=p.sort_order' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}


		$pagination = new Pagination();
		$pagination->total = $vendor_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('vendor/lts_vendor', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($vendor_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($vendor_total - $this->config->get('config_limit_admin'))) ? $vendor_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $vendor_total, ceil($vendor_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['add'] = $this->url->link('vendor/lts_vendor/add', 'user_token=' . $this->session->data['user_token'], true);
		$data['delete'] = $this->url->link('vendor/lts_vendor/delete', 'user_token=' . $this->session->data['user_token'], true);

		$data['header'] 	 = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer']		 = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('vendor/lts_vendor_list', $data));
	}


	public function request() {
		$this->load->language('vendor/lts_vendor');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('vendor/lts_vendor');

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];  
		} else {
			$page = 1;
		}

		$filter_data = array(
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		); 

		$vendor_total = $this->model_vendor_lts_vendor->getTotalRequestVendors($filter_data);

		$results = $this->model_vendor_lts_vendor->vendorRequestLists($filter_data);

		$data['cancel'] = $this->url->link('vendor/lts_vendor', 'user_token=' . $this->session->data['user_token'] , true);

		foreach($results as $result) {
			$data['vendors'][] = array(
				'vendor_id'  => $result['vendor_id'],
				'name'  	 => $result['firstname'] .' ' . $result['lastname'] ,
				'email'		 => $result['email'],
				'telephone'  => $result['telephone'],
				'approve'	 => $this->url->link('vendor/lts_vendor/approve', 'user_token=' . $this->session->data['user_token'] . '&vendor_id=' . $result['vendor_id'], true)
			);
		} 

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('vendor/lts_vendor', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url, true);
		$data['sort_email'] = $this->url->link('vendor/lts_vendor', 'user_token=' . $this->session->data['user_token'] . '&sort=c.email' . $url, true);
		$data['sort_telephone'] = $this->url->link('vendor/lts_vendor', 'user_token=' . $this->session->data['user_token'] . '&sort=c.telephone' . $url, true);
	

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $vendor_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('vendor/lts_vendor/request', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($vendor_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($vendor_total - $this->config->get('config_limit_admin'))) ? $vendor_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $vendor_total, ceil($vendor_total / $this->config->get('config_limit_admin')));

		$data['header'] 	 = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer']		 = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('vendor/lts_request', $data));
	}


	 public function delete() {
        $this->load->language('vendor/lts_vendor');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('vendor/lts_vendor');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $vendor_id) {
                
                // $this->model_vendor_lts_vendor->deleteVendor($vendor_id);
            }


            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('vendor/lts_vendor', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->getList();
    }

    protected function validateForm() {
    	if (!$this->user->hasPermission('modify', 'vendor/lts_vendor')) {
    	    $this->error['warning'] = $this->language->get('error_permission');
    	}

    	return !$this->error;
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'vendor/lts_vendor')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateApprove() {
    	if (!$this->user->hasPermission('modify', 'vendor/lts_vendor')) {
    		$this->error['warning'] = $this->language->get('error_permission');
    	}

    	return !$this->error;
    }

	public function approve() {
		$this->load->language('vendor/lts_vendor');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('vendor/lts_vendor');

		$vendors = array();

		if (isset($this->request->post['selected'])) {
			$vendors = $this->request->post['selected'];
		} elseif (isset($this->request->get['vendor_id'])) {
			$vendors[] = $this->request->get['vendor_id'];
		}

		if ($vendors && $this->validateApprove()) {
			$this->model_vendor_lts_vendor->approve($this->request->get['vendor_id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_store_owner'])) {
				$url .= '&filter_store_owner=' . urlencode(html_entity_decode($this->request->get['filter_store_owner'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_store_name'])) {
				$url .= '&filter_store_name=' . urlencode(html_entity_decode($this->request->get['filter_store_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_email'])) {
				$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['filter_approved'])) {
				$url .= '&filter_approved=' . $this->request->get['filter_approved'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}



			$this->response->redirect($this->url->link('vendor/lts_vendor', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		// $this->getList();
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_store_owner'])) {
			$this->load->model('vendor/lts_vendor');

			$filter_data = array(
				'filter_store_owner' => $this->request->get['filter_store_owner'],
				'sort'        => 'name',
				'order'       => 'ASC',
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->model_vendor_lts_vendor->getVendors($filter_data);
		
			foreach ($results as $result) {
				$json[] = array(
					'vendor_id' => $result['vendor_id'],
					'store_owner'        => strip_tags(html_entity_decode($result['store_owner'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['store_owner'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}