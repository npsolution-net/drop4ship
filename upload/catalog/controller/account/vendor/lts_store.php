<?php

class ControllerAccountVendorLtsStore extends Controller {

  private $error = [];

  public function index() {
    if (!$this->customer->isLogged()) {
      $this->session->data['redirect'] = $this->url->link('account/account', '', true);

      $this->response->redirect($this->url->link('account/login', '', true));
    }

    if(!$this->config->get('module_lts_vendor_status')) {
      $this->response->redirect($this->url->link('account/account', '', true));
    }

 

    $this->load->language('account/vendor/lts_store');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('account/vendor/lts_vendor');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {


      $this->model_account_vendor_lts_vendor->addVendorStoreInfo($this->request->post);

      $this->session->data['success'] = $this->language->get('text_success');

      $this->response->redirect($this->url->link('account/vendor/lts_dashboard')); 
    }

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

    if (isset($this->error['store_owner'])) {
      $data['error_store_owner'] = $this->error['store_owner'];
    } else {
      $data['error_store_owner'] = '';
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

    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_home'),
        'href' => $this->url->link('account/vendor/lts_dashboard')
    );

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('heading_title'),
        'href' => $this->url->link('account/vendor/lts_store')
    );

    $vendor_info = $this->model_account_vendor_lts_vendor->getVendorInfo($this->customer->getId());
    
    if (isset($this->request->post['description'])) {
      $data['description'] = $this->request->post['description'];
    } elseif (!empty($vendor_info)) {
      $data['description'] = $vendor_info['description'];
    } else {
      $data['description'] = '';
    }


    if (isset($this->request->post['meta_title'])) {
      $data['meta_title'] = $this->request->post['meta_title'];
    } elseif (!empty($vendor_info)) {
      $data['meta_title'] = $vendor_info['meta_title'];
    } else {
      $data['meta_title'] = '';
    }

    if (isset($this->request->post['meta_description'])) {
      $data['meta_description'] = $this->request->post['meta_description'];
    } elseif (!empty($vendor_info)) {
      $data['meta_description'] = $vendor_info['meta_description'];
    } else {
      $data['meta_description'] = '';
    }

    if (isset($this->request->post['meta_title'])) {
      $data['meta_title'] = $this->request->post['meta_title'];
    } elseif (!empty($vendor_info)) {
      $data['meta_title'] = $vendor_info['meta_title'];
    } else {
      $data['meta_title'] = '';
    }

    if (isset($this->request->post['meta_keyword'])) {
      $data['meta_keyword'] = $this->request->post['meta_keyword'];
    } elseif (!empty($vendor_info)) {
      $data['meta_keyword'] = $vendor_info['meta_keyword'];
    } else {
      $data['meta_keyword'] = '';
    }

    if (isset($this->request->post['store_owner'])) {
      $data['store_owner'] = $this->request->post['store_owner'];
    } elseif (!empty($vendor_info)) {
      $data['store_owner'] = $vendor_info['store_owner'];
    } else {
      $data['store_owner'] = '';
    }

    if (isset($this->request->post['store_name'])) {
      $data['store_name'] = $this->request->post['store_name'];
    } elseif (!empty($vendor_info)) {
      $data['store_name'] = $vendor_info['store_name'];
    } else {
      $data['store_name'] = '';
    }
  
    if (isset($this->request->post['address'])) {
      $data['address'] = $this->request->post['address'];
    } elseif (!empty($vendor_info)) {
      $data['address'] = $vendor_info['address'];
    } else {
      $data['address'] = '';
    }

    if (isset($this->request->post['email'])) {
      $data['email'] = $this->request->post['email'];
    } elseif (!empty($vendor_info)) {
      $data['email'] = $vendor_info['email'];
    } else {
      $data['email'] = $this->customer->getEmail();
    }

    if (isset($this->request->post['telephone'])) {
      $data['telephone'] = $this->request->post['telephone'];
    } elseif (!empty($vendor_info)) {
      $data['telephone'] = $vendor_info['telephone'];
    } else {
      $data['telephone'] = $this->customer->getTelephone();
    }

    if (isset($this->request->post['fax'])) {
      $data['fax'] = $this->request->post['fax'];
    } elseif (!empty($vendor_info)) {
      $data['fax'] = $vendor_info['fax'];
    } else {
      $data['fax'] = '';
    }

    if (isset($this->request->post['country_id'])) {
      $data['country_id'] = $this->request->post['country_id'];
    } elseif (!empty($vendor_info)) {
      $data['country_id'] = $vendor_info['country_id'];
    } else {
      $data['country_id'] = '';
    }

    $this->load->model('localisation/country');

    $data['countries'] = $this->model_localisation_country->getCountries();

    if (isset($this->request->post['zone_id'])) {
      $data['zone_id'] = $this->request->post['zone_id'];
    } elseif (!empty($vendor_info)) {
      $data['zone_id'] = $vendor_info['zone_id'];
    } else {
      $data['zone_id'] = '';
    }

    if (isset($this->request->post['city'])) {
      $data['city'] = $this->request->post['city'];
    } elseif (!empty($vendor_info)) {
      $data['city'] = $vendor_info['city'];
    } else {
      $data['city'] = '';
    }

    if (isset($this->request->post['logo'])) {
      $data['logo'] = $this->request->post['logo'];
    } elseif (!empty($vendor_info)) {
      $data['logo'] = $vendor_info['logo'];
    } else {
      $data['logo'] = '';
    }  

    $this->load->model('tool/image');

    if (isset($this->request->post['logo']) && is_file(DIR_IMAGE . $this->request->post['logo'])) {
      $data['logo_thumb'] = $this->model_tool_image->resize($this->request->post['logo'], 100, 100);
    } elseif (!empty($vendor_info) && is_file(DIR_IMAGE . $vendor_info['logo'])) {
      $data['logo_thumb'] = $this->model_tool_image->resize($vendor_info['logo'], 100, 100);
    } else {
      $data['logo_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
    }

    if (isset($this->request->post['banner'])) {
      $data['banner'] = $this->request->post['banner'];
    } elseif (!empty($vendor_info)) {
      $data['banner'] = $vendor_info['banner'];
    } else {
      $data['banner'] = '';
    }

    if (isset($this->request->post['banner']) && is_file(DIR_IMAGE . $this->request->post['banner'])) {
      $data['banner_thumb'] = $this->model_tool_image->resize($this->request->post['banner'], 100, 100);
    } elseif (!empty($vendor_info) && is_file(DIR_IMAGE . $vendor_info['banner'])) {
      $data['banner_thumb'] = $this->model_tool_image->resize($vendor_info['banner'], 100, 100);
    } else {
      $data['banner_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
    }

    if (isset($this->request->post['profile_image'])) {
      $data['profile_image'] = $this->request->post['profile_image'];
    } elseif (!empty($vendor_info)) {
      $data['profile_image'] = $vendor_info['profile_image'];
    } else {
      $data['profile_image'] = '';
    }  

    if (isset($this->request->post['profile_image']) && is_file(DIR_IMAGE . $this->request->post['profile_image'])) {
      $data['profile_image_thumb'] = $this->model_tool_image->resize($this->request->post['profile_image'], 100, 100);
    } elseif (!empty($vendor_info) && is_file(DIR_IMAGE . $vendor_info['profile_image'])) {
      $data['profile_image_thumb'] = $this->model_tool_image->resize($vendor_info['profile_image'], 100, 100);
    } else {
      $data['profile_image_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
    }

     $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);


    if (isset($this->request->post['facebook'])) {
      $data['facebook'] = $this->request->post['facebook'];
    } elseif (!empty($vendor_info)) {
      $data['facebook'] = $vendor_info['facebook'];
    } else {
      $data['facebook'] = '';
    }

    if (isset($this->request->post['instagram'])) {
      $data['instagram'] = $this->request->post['instagram'];
    } elseif (!empty($vendor_info)) {
      $data['instagram'] = $vendor_info['instagram'];
    } else {
      $data['instagram'] = '';
    }

    if (isset($this->request->post['youtube'])) {
      $data['youtube'] = $this->request->post['youtube'];
    } elseif (!empty($vendor_info)) {
      $data['youtube'] = $vendor_info['youtube'];
    } else {
      $data['youtube'] = '';
    }

    if (isset($this->request->post['twitter'])) {
      $data['twitter'] = $this->request->post['twitter'];
    } elseif (!empty($vendor_info)) {
      $data['twitter'] = $vendor_info['twitter'];
    } else {
      $data['twitter'] = '';
    }

    if (isset($this->request->post['pinterest'])) {
      $data['pinterest'] = $this->request->post['pinterest'];
    } elseif (!empty($vendor_info)) {
      $data['pinterest'] = $vendor_info['pinterest'];
    } else {
      $data['pinterest'] = '';
    }

    if (!empty($vendor_info)) {
      $data['status'] = $vendor_info['status'];
    } else {
      $data['status'] = '';
    }

    if (isset($this->request->post['approved'])) {
      $data['approved'] = $this->request->post['approved'];
    } elseif (!empty($vendor_info)) {
      $data['approved'] = $vendor_info['approved'];
    } else {
      $data['approved'] = '';
    }


    if (isset($this->request->post['vendor_seo_url'])) {
      $data['vendor_seo_url'] = $this->request->post['vendor_seo_url'];
    } else if($vendor_info) {
      $data['vendor_seo_url'] = $this->model_account_vendor_lts_vendor->getVendorSeoUrls($vendor_info['vendor_id']);
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

    $data['action'] = $this->url->link('account/vendor/lts_store');

   $data['vendor_info'] = $this->model_account_vendor_lts_vendor->getVendorStoreInfo($this->customer->isLogged());

   if($data['vendor_info']) {
       $data['lts_column_left'] = $this->load->controller('account/vendor/lts_column_left');
   } 
    
    $this->load->controller('account/vendor/lts_header/script');
    $data['already_apply']= $this->model_account_vendor_lts_vendor->getVendorApplyInfo($this->customer->isLogged());
    $data['vendor_status']= $this->model_account_vendor_lts_vendor->getVendorStatus($this->customer->isLogged());
    $data['account_link'] = $this->url->link('account/account', '', true);
    $data['footer'] = $this->load->controller('common/footer');
    $data['header'] = $this->load->controller('common/header');
   


    $this->response->setOutput($this->load->view('account/vendor/lts_store', $data));
  }

  public function country() {
    $json = array();

    $this->load->model('localisation/country');

    $country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

    if ($country_info) {
      $this->load->model('localisation/zone');

      $json = array(
          'country_id' => $country_info['country_id'],
          'name' => $country_info['name'],
          'iso_code_2' => $country_info['iso_code_2'],
          'iso_code_3' => $country_info['iso_code_3'],
          'address_format' => $country_info['address_format'],
          'postcode_required' => $country_info['postcode_required'],
          'zone' => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
          'status' => $country_info['status']
      );
    }

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }

  protected function validateForm() {


    $vendor_info = $this->model_account_vendor_lts_vendor->getVendorInfo($this->customer->getId());

    if ($vendor_info && empty($vendor_info['status'])) {
      $this->error['warning'] = $this->language->get('error_status_disabled');
    }

    $this->load->language('account/vendor/lts_store');
    if ((utf8_strlen($this->request->post['meta_title']) < 1) || (utf8_strlen($this->request->post['meta_title']) > 64)) {
      $this->error['meta_title'] = $this->language->get('error_meta_title');
    }

    if ((utf8_strlen($this->request->post['meta_description']) < 1) || (utf8_strlen($this->request->post['meta_description']) > 255)) {
      $this->error['meta_description'] = $this->language->get('error_meta_description');
    }

    if ((utf8_strlen($this->request->post['meta_keyword']) < 1) || (utf8_strlen($this->request->post['meta_keyword']) > 64)) {
      $this->error['meta_keyword'] = $this->language->get('error_meta_keyword');
    }

    if (!$this->request->post['store_owner']) {
      $this->error['store_owner'] = $this->language->get('error_store_owner');
    }

    if (!$this->request->post['address']) {
      $this->error['address'] = $this->language->get('error_address');
    }

    if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
      $this->error['email'] = $this->language->get('error_email');
    }

    $this->load->model('account/customer');

    if (($this->customer->getEmail() != $this->request->post['email']) && $this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
          $this->error['warning'] = $this->language->get('error_exists');
    }

    if (!$this->request->post['telephone']) {
      $this->error['telephone'] = $this->language->get('error_telephone');
    }

    
    if ($this->request->post['country_id'] == '') {
      $this->error['country'] = $this->language->get('error_country');
    }

    if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '' || !is_numeric($this->request->post['zone_id'])) {
      $this->error['zone'] = $this->language->get('error_zone');
    }

    if (!$this->request->post['city']) {
      $this->error['city'] = $this->language->get('error_city');
    }


    if ($this->error && !isset($this->error['warning'])) {
      $this->error['warning'] = $this->language->get('error_warning');
    }

    return !$this->error;
  }

}
