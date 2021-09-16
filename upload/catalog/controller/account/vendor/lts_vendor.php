<?php

class ControllerVendorLtsVendor extends Controller {

  private $error = [];

  public function index() {

    if (!$this->config->get('module_lts_vendor_status')) {
      $this->response->redirect($this->url->link('error/not_found', '', true));
    }

    
    $data['footer'] = $this->load->controller('common/footer');
    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');

    $this->response->setOutput($this->load->view('vendor/lts_account', $data));
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

 

}
