<?php

class ControllerAccountVendorLtsPincode extends Controller {

  public function check() {

    $json = array();

    $this->load->model('account/vendor/lts_product');

    if(isset($this->request->get['product_id'])) {
      $product_id = $this->request->get['product_id'];
    } else {
      $product_id = '';
    }

    $pincodes = $this->model_account_vendor_lts_product->getProductPincode($product_id);

    if(!empty($pincodes)) {
      
        foreach ($pincodes as $pincode_id) {
          $pincode_info = $this->model_account_vendor_lts_product->getPincodeStatus($pincode_id, $this->request->post['pincode']);

          if(!empty($pincode_info)) {
            $json = array(
              'status' => 1,
              'message' => $this->language->get('Available')
            );
          } else {
            $json = array(
              'status' => 0,
              'message' => $this->language->get('Not available')
            );
          }
        }

    }

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }
}