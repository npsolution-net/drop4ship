<?php

class ControllerAccountVendorLtsCommission extends controller {

  public function index() {
     if (!$this->customer->isLogged()) {
          $this->session->data["redirect"] = $this->url->link("account/account", "", true);

          $this->response->redirect($this->url->link("account/login", "", true));
      }

      if(!$this->config->get("module_lts_vendor_status")) {
          $this->response->redirect($this->url->link("account/account", "", true));
      }

      $this->load->model("account/vendor/lts_vendor");

      if($this->customer->isLogged()){
          $data["customer_id"] = $this->customer->getId();
      }
       
      $vendor_info = $this->model_account_vendor_lts_vendor->getVendorStoreInfo($this->customer->isLogged());

      if(!$vendor_info) {
          $this->response->redirect($this->url->link("account/account", "", true));
      }

    $this->load->language("account/vendor/lts_commission");

    $this->document->setTitle($this->language->get("heading_title"));

    $this->load->model('account/vendor/lts_commission');



    if (isset($this->request->get['sort'])) {
      $sort = $this->request->get['sort'];
    } else {
      $sort = 'name';
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
 
    if (isset($this->request->get['page'])) {
      $url .= '&page=' . $this->request->get['page'];
    }


    $data["breadcrumbs"] = array();

    $data["breadcrumbs"][] = array(
        "text" => $this->language->get("text_home"),
        "href" => $this->url->link("account/vendor/lts_dashboard")
    );

    $data["breadcrumbs"][] = array(
        "text" => $this->language->get("heading_title"),
        "href" => $this->url->link("account/vendor/lts_commission")
    );

    $data['commissions'] = array();

    $filter_data = array(
      // 'filter_name'    => $filter_name,
      'sort'            => $sort,
      'order'           => $order,
      'vendor_id'       => $vendor_info['vendor_id'],
      'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
      'limit'           => $this->config->get('config_limit_admin')
    );

    $commission_report_total = $this->model_account_vendor_lts_commission->getTotalCommissions($filter_data);

    $results = $this->model_account_vendor_lts_commission->getCommissions($filter_data);
  // echo '<pre>'; print_r( $results);die;

    foreach ($results as $result) {

        $data['commissions'][] = array(
            'vendor_commission_id'    => $result['vendor_commission_id'],
            'vendor_id'               => $result['vendor_id'],
            'order_product_id'        => $result['order_product_id'],
            'product_id'              => $result['product_id'],
            'order_id'                => $result['order_id'],
            'name'                    => $result['name'],
            'price'                   => $this->currency->format($result['price'], $this->config->get('config_currency')),
            'quantity'                => $result['quantity'],
            'total'                   => $result['total'],
            'amount'                  => $result['amount'],
        );

    }

    $pagination = new Pagination();
    $pagination->total = $commission_report_total;
    $pagination->page = $page;
    $pagination->limit = $this->config->get('config_limit_admin');
    $pagination->url = $this->url->link('account/vendor/lts_product', '&page={page}', true);

    $data['pagination'] = $pagination->render();

    $data['results'] = sprintf($this->language->get('text_pagination'), ($commission_report_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($commission_report_total - $this->config->get('config_limit_admin'))) ? $commission_report_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $commission_report_total, ceil($commission_report_total / $this->config->get('config_limit_admin')));



    $this->load->controller("account/vendor/lts_header/script");
    $data["footer"] = $this->load->controller("common/footer");
    $data["header"] = $this->load->controller("common/header");
    $data["lts_column_left"] = $this->load->controller("account/vendor/lts_column_left");
    
    $this->response->setOutput($this->load->view("account/vendor/lts_commission", $data));
  }

}
