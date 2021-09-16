<?php

class ControllerAccountVendorLtsVisitStore extends Controller {

  public function index() {

    $this->load->language('account/vendor/lts_store_info');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('account/vendor/lts_vendor');

    $this->load->model('account/vendor/lts_product');

    $this->load->model('catalog/product');

    $this->load->model('tool/image');


    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_home'),
        'href' => $this->url->link('account/vendor/lts_dashboard')
    );

    $data['breadcrumbs'][] = array(
        'text' => $this->language->get('heading_title'),
        'href' => $this->url->link('account/vendor/lts_store_info')
    );

    if ($this->request->server['HTTPS']) {
      $server = $this->config->get('config_ssl');
    } else {
      $server = $this->config->get('config_url');
    }

    $vendor_product_info = $this->model_account_vendor_lts_product->getProducts();

    if(!empty($vendor_product_info)) {
      foreach($vendor_product_info as $product) {

        $product_info = $this->model_catalog_product->getProduct($product['product_id']);

        if ($product_info) {
          if ($product_info['image']) {
            $image = $this->model_tool_image->resize($product_info['image'], 220, 200);
          } else {
            $image = $this->model_tool_image->resize('placeholder.png', 220, 200);
          }

          if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
            $price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
          } else {
            $price = false;
          }

          if ((float)$product_info['special']) {
            $special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
          } else {
            $special = false;
          }

          if ($this->config->get('config_tax')) {
            $tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
          } else {
            $tax = false;
          }

          if ($this->config->get('config_review_status')) {
            $rating = $product_info['rating'];
          } else {
            $rating = false;
          }

          $data['products'][] = array(
            'product_id'  => $product_info['product_id'],
            'thumb'       => $image,
            'name'        => $product_info['name'],
            'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
            'price'       => $price,
            'special'     => $special,
            'tax'         => $tax,
            'rating'      => $rating,
            'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
          );
        }
        
      }

    }


    $store_info = $this->model_account_vendor_lts_vendor->getStoreInformation($this->customer->getId());

    if(!empty($store_info['store_name'])) {
      $data['name'] = $store_info['store_name'];
    }

     if (is_file(DIR_IMAGE . $store_info['logo'])) {
      $data['logo'] = $this->model_tool_image->resize($store_info['logo'], 150, 150);
    } else {
      $data['logo'] = '';
    }

    if ($store_info['banner']) {
      $data['banner'] = $this->model_tool_image->resize($store_info['banner'], 850, 150);
    } else {
      $data['banner'] = '';
    }

   
   $this->load->controller('account/vendor/lts_header/script');
    $data['footer'] = $this->load->controller('common/footer');
    $data['header'] = $this->load->controller('common/header');
    $data['lts_column_left'] = $this->load->controller('account/vendor/lts_column_left');

    $this->response->setOutput($this->load->view('account/vendor/lts_visit_store', $data));
  }
}
