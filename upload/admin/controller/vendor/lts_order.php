<?php

class ControllerVendorLtsOrder extends Controller {

    public function index() {
        $this->load->language('vendor/lts_order');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/order');

        $this->getList();
    }

    protected function getList() {
        if (isset($this->request->get['filter_order_id'])) {
            $filter_order_id = $this->request->get['filter_order_id'];
        } else {
            $filter_order_id = '';
        } 

        if (isset($this->request->get['filter_vendor_id'])) {
            $filter_vendor_id = $this->request->get['filter_vendor_id'];
        } else {
            $filter_vendor_id = '';
        }
 
        if (isset($this->request->get['filter_customer'])) {
            $filter_customer = $this->request->get['filter_customer'];
        } else {
            $filter_customer = '';
        }

        if (isset($this->request->get['filter_order_status'])) {
            $filter_order_status = $this->request->get['filter_order_status'];
        } else {
            $filter_order_status = '';
        }

        if (isset($this->request->get['filter_vendor_order_status'])) {
            $filter_vendor_order_status = $this->request->get['filter_vendor_order_status'];
        } else {
            $filter_vendor_order_status = null;
        }

        if (isset($this->request->get['filter_order_status_id'])) {
            $filter_order_status_id = $this->request->get['filter_order_status_id'];
        } else {
            $filter_order_status_id = null;
        }
 
        if (isset($this->request->get['filter_total'])) {
            $filter_total = $this->request->get['filter_total'];
        } else {
            $filter_total = '';
        }

        if (isset($this->request->get['filter_date_added'])) {
            $filter_date_added = $this->request->get['filter_date_added'];
        } else {
            $filter_date_added = '';
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $filter_date_modified = $this->request->get['filter_date_modified'];
        } else {
            $filter_date_modified = '';
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'o.order_id';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_vendor_order_status'])) {
            $url .= '&filter_vendor_order_status=' . $this->request->get['filter_vendor_order_status'];
        }

        if (isset($this->request->get['filter_order_status_id'])) {
            $url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total=' . $this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
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
            'href' => $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        $data['orders'] = array();

        $filter_data = array(
            'filter_order_id' => $filter_order_id,
            'filter_vendor_id' =>$filter_vendor_id,
            'filter_customer' => $filter_customer,
            'filter_order_status' => $filter_order_status,
            'filter_order_status_id' => $filter_order_status_id,
            'filter_total' => $filter_total,
            'filter_date_added' => $filter_date_added,
            'filter_date_modified' => $filter_date_modified,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $this->load->model('vendor/lts_order');

        $this->load->model('localisation/order_status');

        $order_total = $this->model_vendor_lts_order->getTotalOrders($filter_data);

        $results = $this->model_vendor_lts_order->getOrders($filter_data);

        foreach ($results as $result) { 

            $vendor_order_products = $this->model_vendor_lts_order->getVendorOrderProducts($result['order_id']);

            $vendor_order_products_data = array();
            foreach ($vendor_order_products as $vendor_order_product) {
                $all_vendor_products = $this->model_vendor_lts_order->getAllVendorOrderProducts($result['order_id'], $vendor_order_product['vendor_id']);
                $all_vendor_products_data = array();
                foreach ($all_vendor_products as $all_vendor_product) {
                    $all_vendor_products_data[] = $all_vendor_product['name'];
                }
               
                $vendor_info = $this->model_vendor_lts_order->getVendorStorename($vendor_order_product['vendor_id']);

                if($vendor_info) {
                    $vendor_store_name = $vendor_info['store_name'];
                } else {
                    $vendor_store_name = '';
                }

                $order_status_info = $this->model_localisation_order_status->getOrderStatus($vendor_order_product['order_status_id']);
                if($order_status_info) {
                    $order_status_name = $order_status_info['name'];
                } else {
                    $order_status_name = '';
                }

                $vendor_order_products_data[] = array(
                    'products'      => $all_vendor_products_data,
                    'vendor_id'   => $vendor_order_product['vendor_id'],
                    'store_name'    => $vendor_store_name,
                    'order_status'  => $order_status_name,
                    'sold_by_type'  => 'vendor',
                    // 'view'          => $this->url->link('vendor/lts_order/vendor_order_info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $vendor_order_product['order_id']. '&vendor_id=' . $vendor_order_product['vendor_id'], true),
                    'order_id'      => $vendor_order_product['order_id'],
                );
            }

            // Admin Order Products Only
            $admin_order_products = $this->model_vendor_lts_order->getAdminOrderProducts($result['order_id']);
            foreach ($admin_order_products as $admin_order_product) {
                $is_vendor_products = $this->model_vendor_lts_order->getVendorOrderProduct($admin_order_product['order_id'], $admin_order_product['order_product_id']);
                if(!$is_vendor_products) {
                    $vendor_store_name = $this->language->get('text_admin') .' - '. $result['store_name'];
                    $order_status_name   = $result['order_status'] ? $result['order_status'] : $this->language->get('text_missing');
                    $vendor_order_products_data[] = array(
                        'products'      => array($admin_order_product['name']),
                        'vendor_id'   => '',
                        'store_name'    => $vendor_store_name,
                        'order_status'  => $order_status_name,
                        'sold_by_type'  => 'admin',
                    );
                }
            }

            $data['orders'][] = array(
                'order_id'      => $result['order_id'],
                'customer'      => $result['customer'],
                'order_status'  => $result['order_status'] ? $result['order_status'] : $this->language->get('text_missing'),
                'total'         => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
                'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
                'shipping_code' => $result['shipping_code'],
                'view'          => $this->url->link('vendor/lts_order/info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $result['order_id'] . $url, true),
                'edit'          => $this->url->link('vendor/lts_order/edit', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $result['order_id'] . $url, true),
                'vendor_products' => $vendor_order_products_data,
            );

        } 

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


        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_vendor_order_status'])) {
            $url .= '&filter_vendor_order_status=' . $this->request->get['filter_vendor_order_status'];
        }

        if (isset($this->request->get['filter_order_status_id'])) {
            $url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total=' . $this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
        }

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }


        $data['sort_vendor'] = $this->url->link('vendor/lts_order', 'user_token=' . $this->session->data['user_token'] . '&sort=vname' . $url, true);

        $data['sort_order'] = $this->url->link('vendor/lts_order', 'user_token=' . $this->session->data['user_token'] . '&sort=o.order_id' . $url, true);

        $data['sort_customer'] = $this->url->link('vendor/lts_order', 'user_token=' . $this->session->data['user_token'] . '&sort=customer' . $url, true);

        $data['sort_product'] = $this->url->link('vendor/lts_order', 'user_token=' . $this->session->data['user_token'] . '&sort=lop.product' . $url, true);

        $data['sort_status'] = $this->url->link('vendor/lts_order', 'user_token=' . $this->session->data['user_token'] . '&sort=order_status' . $url, true);

        $data['sort_total'] = $this->url->link('vendor/lts_order', 'user_token=' . $this->session->data['user_token'] . '&sort=o.total' . $url, true);

        $data['sort_date_added'] = $this->url->link('vendor/lts_order', 'user_token=' . $this->session->data['user_token'] . '&sort=o.date_added' . $url, true);

        $data['sort_date_modified'] = $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . '&sort=o.date_modified' . $url, true);

        $url = '';

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_order_status_id'])) {
            $url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total=' . $this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

       

        $this->load->model('localisation/order_status');

        //$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
        // API login
        $data['catalog'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;

        // API login
        $this->load->model('user/api');

        $api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

        $pagination = new Pagination();
        $pagination->total = $order_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('vendor/lts_order', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

        $data['filter_order_id'] = $filter_order_id;
        $data['filter_customer'] = $filter_customer;
        $data['filter_order_status'] = $filter_order_status;
        $data['filter_order_status_id'] = $filter_order_status_id;
        $data['filter_total'] = $filter_total;
        $data['filter_date_added'] = $filter_date_added;
        $data['filter_date_modified'] = $filter_date_modified;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('vendor/lts_order_list', $data));
    }

    public function info() {
      $this->load->model('sale/order');

      if (isset($this->request->get['order_id'])) {
        $order_id = $this->request->get['order_id'];
      } else {
        $order_id = 0;
      }

      $order_info = $this->model_sale_order->getOrder($order_id);

      if ($order_info) {
        $this->load->language('vendor/lts_order');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['text_ip_add'] = sprintf($this->language->get('text_ip_add'), $this->request->server['REMOTE_ADDR']);
        $data['text_order'] = sprintf($this->language->get('text_order'), $this->request->get['order_id']);

        $url = '';

        if (isset($this->request->get['filter_order_id'])) {
          $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
          $url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
          $url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
        }
        
        if (isset($this->request->get['filter_order_status_id'])) {
          $url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
        }
        
        if (isset($this->request->get['filter_total'])) {
          $url .= '&filter_total=' . $this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
          $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
          $url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
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
          'href' => $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        $data['shipping'] = $this->url->link('sale/order/shipping', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . (int)$this->request->get['order_id'], true);
        $data['invoice'] = $this->url->link('sale/order/invoice', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . (int)$this->request->get['order_id'], true);
        $data['edit'] = $this->url->link('sale/order/edit', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . (int)$this->request->get['order_id'], true);
        $data['cancel'] = $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . $url, true);

        $data['user_token'] = $this->session->data['user_token'];

        $data['order_id'] = $this->request->get['order_id'];

        $data['store_id'] = $order_info['store_id'];
        $data['store_name'] = $order_info['store_name'];
        
        if ($order_info['store_id'] == 0) {
          $data['store_url'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;
        } else {
          $data['store_url'] = $order_info['store_url'];
        }

        if ($order_info['invoice_no']) {
          $data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
        } else {
          $data['invoice_no'] = '';
        }

        $data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));

        $data['firstname'] = $order_info['firstname'];
        $data['lastname'] = $order_info['lastname'];

        if ($order_info['customer_id']) {
          $data['customer'] = $this->url->link('customer/customer/edit', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $order_info['customer_id'], true);
        } else {
          $data['customer'] = '';
        }

        $this->load->model('customer/customer_group');

        $customer_group_info = $this->model_customer_customer_group->getCustomerGroup($order_info['customer_group_id']);

        if ($customer_group_info) {
          $data['customer_group'] = $customer_group_info['name'];
        } else {
          $data['customer_group'] = '';
        }

        $data['email'] = $order_info['email'];
        $data['telephone'] = $order_info['telephone'];

        $data['shipping_method'] = $order_info['shipping_method'];
        $data['payment_method'] = $order_info['payment_method'];

        // Payment Address
        if ($order_info['payment_address_format']) {
          $format = $order_info['payment_address_format'];
        } else {
          $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
        }

        $find = array(
          '{firstname}',
          '{lastname}',
          '{company}',
          '{address_1}',
          '{address_2}',
          '{city}',
          '{postcode}',
          '{zone}',
          '{zone_code}',
          '{country}'
        );

        $replace = array(
          'firstname' => $order_info['payment_firstname'],
          'lastname'  => $order_info['payment_lastname'],
          'company'   => $order_info['payment_company'],
          'address_1' => $order_info['payment_address_1'],
          'address_2' => $order_info['payment_address_2'],
          'city'      => $order_info['payment_city'],
          'postcode'  => $order_info['payment_postcode'],
          'zone'      => $order_info['payment_zone'],
          'zone_code' => $order_info['payment_zone_code'],
          'country'   => $order_info['payment_country']
        );

        $data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

        // Shipping Address
        if ($order_info['shipping_address_format']) {
          $format = $order_info['shipping_address_format'];
        } else {
          $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
        }

        $find = array(
          '{firstname}',
          '{lastname}',
          '{company}',
          '{address_1}',
          '{address_2}',
          '{city}',
          '{postcode}',
          '{zone}',
          '{zone_code}',
          '{country}'
        );

        $replace = array(
          'firstname' => $order_info['shipping_firstname'],
          'lastname'  => $order_info['shipping_lastname'],
          'company'   => $order_info['shipping_company'],
          'address_1' => $order_info['shipping_address_1'],
          'address_2' => $order_info['shipping_address_2'],
          'city'      => $order_info['shipping_city'],
          'postcode'  => $order_info['shipping_postcode'],
          'zone'      => $order_info['shipping_zone'],
          'zone_code' => $order_info['shipping_zone_code'],
          'country'   => $order_info['shipping_country']
        );

        $data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

        // Uploaded files
        $this->load->model('tool/upload');
        $this->load->model('vendor/lts_order');

        $data['products'] = array();

        // $products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);
        $products = $this->model_vendor_lts_order->getOrderProducts($this->request->get['order_id']);
        foreach ($products as $product) {
          $option_data = array();

          $options = $this->model_vendor_lts_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);

          foreach ($options as $option) {
            if ($option['type'] != 'file') {
              $option_data[] = array(
                'name'  => $option['name'],
                'value' => $option['value'],
                'type'  => $option['type']
              );
            } else {
              $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

              if ($upload_info) {
                $option_data[] = array(
                  'name'  => $option['name'],
                  'value' => $upload_info['name'],
                  'type'  => $option['type'],
                  'href'  => $this->url->link('tool/upload/download', 'user_token=' . $this->session->data['user_token'] . '&code=' . $upload_info['code'], true)
                );
              }
            }
          }

          $vendor_product_info = $this->model_vendor_lts_order->getVendorOrderProduct($this->request->get['order_id'], $product['order_product_id']);

          if(!empty($vendor_product_info)) {
            $product_store_name = $vendor_product_info['store_name'];
            $product_store_owner = $vendor_product_info['store_owner'];
            $product_order_status = $vendor_product_info['order_status'];
          } else {
            $product_store_name = $order_info['store_name'];
            $product_store_owner = $this->language->get('text_admin');
            $product_order_status = $order_info['order_status'];
          }

          $data['products'][] = array(
            'order_product_id' => $product['order_product_id'],
            'product_id'       => $product['product_id'],
            'name'           => $product['name'],
            'product_store_name'  => $product_store_name,
            'product_store_owner' => $product_store_owner,
            'status'      => $product_order_status,
            'model'          => $product['model'],
            'option'         => $option_data,
            'quantity'       => $product['quantity'],
            'price'          => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
            'total'          => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
            'href'           => $this->url->link('catalog/product/edit', 'user_token=' . $this->session->data['user_token'] . '&product_id=' . $product['product_id'], true)
          );
        }

        $data['vouchers'] = array();

        $vouchers = $this->model_sale_order->getOrderVouchers($this->request->get['order_id']);

        foreach ($vouchers as $voucher) {
          $data['vouchers'][] = array(
            'description' => $voucher['description'],
            'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
            'href'        => $this->url->link('sale/voucher/edit', 'user_token=' . $this->session->data['user_token'] . '&voucher_id=' . $voucher['voucher_id'], true)
          );
        }

        $data['totals'] = array();

        $totals = $this->model_sale_order->getOrderTotals($this->request->get['order_id']);

        foreach ($totals as $total) {
          $data['totals'][] = array(
            'title' => $total['title'],
            'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value'])
          );
        }

        $data['comment'] = nl2br($order_info['comment']);

        $this->load->model('customer/customer');

        $data['reward'] = $order_info['reward'];

        $data['reward_total'] = $this->model_customer_customer->getTotalCustomerRewardsByOrderId($this->request->get['order_id']);

        $data['affiliate_firstname'] = $order_info['affiliate_firstname'];
        $data['affiliate_lastname'] = $order_info['affiliate_lastname'];

        if ($order_info['affiliate_id']) {
          $data['affiliate'] = $this->url->link('customer/customer/edit', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $order_info['affiliate_id'], true);
        } else {
          $data['affiliate'] = '';
        }

        $data['commission'] = $this->currency->format($order_info['commission'], $order_info['currency_code'], $order_info['currency_value']);

        $this->load->model('customer/customer');

        $data['commission_total'] = $this->model_customer_customer->getTotalTransactionsByOrderId($this->request->get['order_id']);

        $this->load->model('localisation/order_status');

        $order_status_info = $this->model_localisation_order_status->getOrderStatus($order_info['order_status_id']);

        if ($order_status_info) {
          $data['order_status'] = $order_status_info['name'];
        } else {
          $data['order_status'] = '';
        }

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $data['order_status_id'] = $order_info['order_status_id'];

        $data['account_custom_field'] = $order_info['custom_field'];

        // Custom Fields
        $this->load->model('customer/custom_field');

        $data['account_custom_fields'] = array();

        $filter_data = array(
          'sort'  => 'cf.sort_order',
          'order' => 'ASC'
        );

        $custom_fields = $this->model_customer_custom_field->getCustomFields($filter_data);

        foreach ($custom_fields as $custom_field) {
          if ($custom_field['location'] == 'account' && isset($order_info['custom_field'][$custom_field['custom_field_id']])) {
            if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
              $custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($order_info['custom_field'][$custom_field['custom_field_id']]);

              if ($custom_field_value_info) {
                $data['account_custom_fields'][] = array(
                  'name'  => $custom_field['name'],
                  'value' => $custom_field_value_info['name']
                );
              }
            }

            if ($custom_field['type'] == 'checkbox' && is_array($order_info['custom_field'][$custom_field['custom_field_id']])) {
              foreach ($order_info['custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
                $custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($custom_field_value_id);

                if ($custom_field_value_info) {
                  $data['account_custom_fields'][] = array(
                    'name'  => $custom_field['name'],
                    'value' => $custom_field_value_info['name']
                  );
                }
              }
            }

            if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
              $data['account_custom_fields'][] = array(
                'name'  => $custom_field['name'],
                'value' => $order_info['custom_field'][$custom_field['custom_field_id']]
              );
            }

            if ($custom_field['type'] == 'file') {
              $upload_info = $this->model_tool_upload->getUploadByCode($order_info['custom_field'][$custom_field['custom_field_id']]);

              if ($upload_info) {
                $data['account_custom_fields'][] = array(
                  'name'  => $custom_field['name'],
                  'value' => $upload_info['name']
                );
              }
            }
          }
        }

        // Custom fields
        $data['payment_custom_fields'] = array();

        foreach ($custom_fields as $custom_field) {
          if ($custom_field['location'] == 'address' && isset($order_info['payment_custom_field'][$custom_field['custom_field_id']])) {
            if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
              $custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($order_info['payment_custom_field'][$custom_field['custom_field_id']]);

              if ($custom_field_value_info) {
                $data['payment_custom_fields'][] = array(
                  'name'  => $custom_field['name'],
                  'value' => $custom_field_value_info['name'],
                  'sort_order' => $custom_field['sort_order']
                );
              }
            }

            if ($custom_field['type'] == 'checkbox' && is_array($order_info['payment_custom_field'][$custom_field['custom_field_id']])) {
              foreach ($order_info['payment_custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
                $custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($custom_field_value_id);

                if ($custom_field_value_info) {
                  $data['payment_custom_fields'][] = array(
                    'name'  => $custom_field['name'],
                    'value' => $custom_field_value_info['name'],
                    'sort_order' => $custom_field['sort_order']
                  );
                }
              }
            }

            if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
              $data['payment_custom_fields'][] = array(
                'name'  => $custom_field['name'],
                'value' => $order_info['payment_custom_field'][$custom_field['custom_field_id']],
                'sort_order' => $custom_field['sort_order']
              );
            }

            if ($custom_field['type'] == 'file') {
              $upload_info = $this->model_tool_upload->getUploadByCode($order_info['payment_custom_field'][$custom_field['custom_field_id']]);

              if ($upload_info) {
                $data['payment_custom_fields'][] = array(
                  'name'  => $custom_field['name'],
                  'value' => $upload_info['name'],
                  'sort_order' => $custom_field['sort_order']
                );
              }
            }
          }
        }

        // Shipping
        $data['shipping_custom_fields'] = array();

        foreach ($custom_fields as $custom_field) {
          if ($custom_field['location'] == 'address' && isset($order_info['shipping_custom_field'][$custom_field['custom_field_id']])) {
            if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
              $custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($order_info['shipping_custom_field'][$custom_field['custom_field_id']]);

              if ($custom_field_value_info) {
                $data['shipping_custom_fields'][] = array(
                  'name'  => $custom_field['name'],
                  'value' => $custom_field_value_info['name'],
                  'sort_order' => $custom_field['sort_order']
                );
              }
            }

            if ($custom_field['type'] == 'checkbox' && is_array($order_info['shipping_custom_field'][$custom_field['custom_field_id']])) {
              foreach ($order_info['shipping_custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
                $custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($custom_field_value_id);

                if ($custom_field_value_info) {
                  $data['shipping_custom_fields'][] = array(
                    'name'  => $custom_field['name'],
                    'value' => $custom_field_value_info['name'],
                    'sort_order' => $custom_field['sort_order']
                  );
                }
              }
            }

            if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
              $data['shipping_custom_fields'][] = array(
                'name'  => $custom_field['name'],
                'value' => $order_info['shipping_custom_field'][$custom_field['custom_field_id']],
                'sort_order' => $custom_field['sort_order']
              );
            }

            if ($custom_field['type'] == 'file') {
              $upload_info = $this->model_tool_upload->getUploadByCode($order_info['shipping_custom_field'][$custom_field['custom_field_id']]);

              if ($upload_info) {
                $data['shipping_custom_fields'][] = array(
                  'name'  => $custom_field['name'],
                  'value' => $upload_info['name'],
                  'sort_order' => $custom_field['sort_order']
                );
              }
            }
          }
        }

        $data['ip'] = $order_info['ip'];
        $data['forwarded_ip'] = $order_info['forwarded_ip'];
        $data['user_agent'] = $order_info['user_agent'];
        $data['accept_language'] = $order_info['accept_language'];

        // Additional Tabs
        // $data['tabs'] = array();

        // if ($this->user->hasPermission('access', 'extension/payment/' . $order_info['payment_code'])) {
        //   if (is_file(DIR_CATALOG . 'controller/extension/payment/' . $order_info['payment_code'] . '.php')) {
        //     $content = $this->load->controller('extension/payment/' . $order_info['payment_code'] . '/order');
        //   } else {
        //     $content = '';
        //   }

        //   if ($content) {
        //     $this->load->language('extension/payment/' . $order_info['payment_code']);

        //     $data['tabs'][] = array(
        //       'code'    => $order_info['payment_code'],
        //       'title'   => $this->language->get('heading_title'),
        //       'content' => $content
        //     );
        //   }
        // }

        $this->load->model('setting/extension');

        $extensions = $this->model_setting_extension->getInstalled('fraud');

        foreach ($extensions as $extension) {
          if ($this->config->get('fraud_' . $extension . '_status')) {
            $this->load->language('extension/fraud/' . $extension, 'extension');

            $content = $this->load->controller('extension/fraud/' . $extension . '/order');

            if ($content) {
              $data['tabs'][] = array(
                'code'    => $extension,
                'title'   => $this->language->get('extension')->get('heading_title'),
                'content' => $content
              );
            }
          }
        }
        
        // The URL we send API requests to
        $data['catalog'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;
        
        // API login
        $this->load->model('user/api');

        $api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

        if ($api_info && $this->user->hasPermission('modify', 'sale/order')) {
          $session = new Session($this->config->get('session_engine'), $this->registry);
          
          $session->start();
          
          // $this->model_user_api->deleteApiSessionBySessionId($session->getId());
          
          $this->model_user_api->addApiSession($api_info['api_id'], $session->getId(), $this->request->server['REMOTE_ADDR']);
          
          $session->data['api_id'] = $api_info['api_id'];

          $data['api_token'] = $session->getId();
        } else {
          $data['api_token'] = '';
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('vendor/lts_order_info', $data));
      } else {
        return new Action('error/not_found');
      }
    }

    public function view() {
        $this->load->language('vendor/lts_order');

        $this->load->model('vendor/lts_order');

        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        $order_info = $this->model_vendor_lts_order->getOrder($order_id);

        if ($order_info) {
            $this->load->language('vendor/lts_order');

            $this->document->setTitle($this->language->get('heading_title'));

            $data['text_ip_add'] = sprintf($this->language->get('text_ip_add'), $this->request->server['REMOTE_ADDR']);
            $data['text_order'] = sprintf($this->language->get('text_order'), $this->request->get['order_id']);

            $url = '';

            if (isset($this->request->get['filter_order_id'])) {
                $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
            }

            if (isset($this->request->get['filter_customer'])) {
                $url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_order_status'])) {
                $url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
            }

            if (isset($this->request->get['filter_order_status_id'])) {
                $url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
            }

            if (isset($this->request->get['filter_total'])) {
                $url .= '&filter_total=' . $this->request->get['filter_total'];
            }

            if (isset($this->request->get['filter_date_added'])) {
                $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
            }

            if (isset($this->request->get['filter_date_modified'])) {
                $url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
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
                'href' => $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . $url, true)
            );

            $data['shipping'] = $this->url->link('sale/order/shipping', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . (int) $this->request->get['order_id'], true);
            $data['invoice'] = $this->url->link('sale/order/invoice', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . (int) $this->request->get['order_id'], true);
            $data['edit'] = $this->url->link('sale/order/edit', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . (int) $this->request->get['order_id'], true);
            $data['cancel'] = $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . $url, true);

            $data['user_token'] = $this->session->data['user_token'];

            if (isset($this->error['warning'])) {
                $data['error_warning'] = $this->error['warning'];
            } else {
                $data['error_warning'] = '';
            }

            $data['order_id'] = $this->request->get['order_id'];

            $data['store_id'] = $order_info['store_id'];
            $data['store_name'] = $order_info['store_name'];

            if ($order_info['store_id'] == 0) {
                $data['store_url'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;
            } else {
                $data['store_url'] = $order_info['store_url'];
            }


            // $results = $this->model_vendor_lts_order->getOrders($filter_data);
            // foreach ($results as $result) {
            // 	$data['orders'][] = array(
            // 		'order_id'      => $result['order_id'],
            // 		'customer'      => $result['customer'],
            // 		'order_status'  => $result['order_status'] ? $result['order_status'] : $this->language->get('text_missing'),
            // 		'total'         => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
            // 		'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
            // 		'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
            // 		'shipping_code' => $result['shipping_code'],
            // 		'view'          => $this->url->link('vendor/lts_order/view', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $result['order_id'], true)
            // 	);
            // }


            $data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));

            $data['firstname'] = $order_info['firstname'];
            $data['lastname'] = $order_info['lastname'];

            if ($order_info['customer_id']) {
                $data['customer'] = $this->url->link('customer/customer/edit', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $order_info['customer_id'], true);
            } else {
                $data['customer'] = '';
            }

            $this->load->model('customer/customer_group');

            $customer_group_info = $this->model_customer_customer_group->getCustomerGroup($order_info['customer_group_id']);

            if ($customer_group_info) {
                $data['customer_group'] = $customer_group_info['name'];
            } else {
                $data['customer_group'] = '';
            }

            $data['email'] = $order_info['email'];
            $data['telephone'] = $order_info['telephone'];

            $data['shipping_method'] = $order_info['shipping_method'];
            $data['payment_method'] = $order_info['payment_method'];

            // Payment Address
            if ($order_info['payment_address_format']) {
                $format = $order_info['payment_address_format'];
            } else {
                $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
            }

            $find = array(
                '{firstname}',
                '{lastname}',
                '{company}',
                '{address_1}',
                '{address_2}',
                '{city}',
                '{postcode}',
                '{zone}',
                '{zone_code}',
                '{country}'
            );

            $replace = array(
                'firstname' => $order_info['payment_firstname'],
                'lastname' => $order_info['payment_lastname'],
                'company' => $order_info['payment_company'],
                'address_1' => $order_info['payment_address_1'],
                'address_2' => $order_info['payment_address_2'],
                'city' => $order_info['payment_city'],
                'postcode' => $order_info['payment_postcode'],
                'zone' => $order_info['payment_zone'],
                'zone_code' => $order_info['payment_zone_code'],
                'country' => $order_info['payment_country']
            );

            $data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

            // Shipping Address
            if ($order_info['shipping_address_format']) {
                $format = $order_info['shipping_address_format'];
            } else {
                $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
            }

            $find = array(
                '{firstname}',
                '{lastname}',
                '{company}',
                '{address_1}',
                '{address_2}',
                '{city}',
                '{postcode}',
                '{zone}',
                '{zone_code}',
                '{country}'
            );

            $replace = array(
                'firstname' => $order_info['shipping_firstname'],
                'lastname' => $order_info['shipping_lastname'],
                'company' => $order_info['shipping_company'],
                'address_1' => $order_info['shipping_address_1'],
                'address_2' => $order_info['shipping_address_2'],
                'city' => $order_info['shipping_city'],
                'postcode' => $order_info['shipping_postcode'],
                'zone' => $order_info['shipping_zone'],
                'zone_code' => $order_info['shipping_zone_code'],
                'country' => $order_info['shipping_country']
            );

            $data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

            // Uploaded files
            $this->load->model('tool/upload');

            $data['products'] = array();

            $products = $this->model_vendor_lts_order->getOrderProducts($this->request->get['order_id']);

            foreach ($products as $product) {
                $data['products'][] = array(
                    'order_product_id' => $product['order_product_id'],
                    'product_id' => $product['product_id'],
                    'name' => $product['name'],
                    'model' => $product['model'],
                    'order_status' => $order_info['order_status'],
                    'quantity' => $product['quantity'],
                    'price' => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                    'total' => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
                );
            }
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');


        $this->response->setOutput($this->load->view('vendor/lts_order_view', $data));
    }
}