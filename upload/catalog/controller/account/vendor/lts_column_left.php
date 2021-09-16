<?php

class ControllerAccountVendorLtsColumnLeft extends Controller {

      public function index() {

        if($this->config->get('module_lts_vendor_status') && $this->customer->isLogged()) {
            $data = array();

            $this->load->language('account/vendor/lts_column_left');

            $data['menus'][] = array(
                'id'       => 'menu-dashboard',
                'icon'     => 'fa-dashboard',
                'name'     => $this->language->get('text_dashboard'),
                'href'     => $this->url->link('account/vendor/lts_dashboard', '', true),
                'children' => array()
            );
            
            // Catalog
            $catalog = array();
            
            $catalog[] = array(
                'icon'     => 'fa-briefcase', 
                'name'     => $this->language->get('text_product'),
                'href'     => $this->url->link('account/vendor/lts_product', '', true),
                'children' => array()       
            );

            $catalog[] = array(
                'icon'     => 'fa-tags', 
                'name'     => $this->language->get('text_category'),
                'href'     => $this->url->link('account/vendor/lts_category', '', true),
                'children' => array()       
            );

            $catalog[] = array(
                'icon'     => 'fa-filter', 
                'name'     => $this->language->get('text_filter'),
                'href'     => $this->url->link('account/vendor/lts_filter', '', true),
                'children' => array()       
            );

            if ($this->config->get('module_lts_vendor_review_action')) {
                $catalog[] = array(
                   'icon'     => 'fa-star', 
                   'name'     => $this->language->get('text_review'),
                   'href'     => $this->url->link('account/vendor/lts_review', '', true),
                   'children' => array()       
                );
            }

            $catalog[] = array(
                'icon'     => 'fa-list-alt', 
                'name'     => $this->language->get('text_option'),
                'href'     => $this->url->link('account/vendor/lts_option', '', true),
                'children' => array()       
            ); 

            $catalog[] = array(
                'icon'     => 'fa-industry', 
                'name'     => $this->language->get('text_manufacturer'),
                'href'     => $this->url->link('account/vendor/lts_manufacturer', '', true),
                'children' => array()       
            );

            $catalog[] = array(
                'icon'     => 'fa-download', 
                'name'     => $this->language->get('text_download'),
                'href'     => $this->url->link('account/vendor/lts_download', '', true),
                'children' => array()       
            );

            $catalog[] = array(
                'icon'     => 'fa-list-alt', 
                'name'     => $this->language->get('text_attribute'),
                'href'     => $this->url->link('account/vendor/lts_attribute', '', true),
                'children' => array()       
            );

             $catalog[] = array(
                'icon'     => 'fa-industry', 
                'name'     => $this->language->get('text_attribute_group'),
                'href'     => $this->url->link('account/vendor/lts_attribute_group', '', true),
                'children' => array()       
            );

           
           
            if ($catalog) {
                $data['menus'][] = array(
                    'id'       => 'menu-catalog',
                    'icon'     => 'fa-tags', 
                    'name'     => $this->language->get('text_catalog'),
                    'href'     => '',
                    'children' => $catalog
                );      
            }
            
            // sales
            $sales = array();


            $sales[] = array(
                'icon'     => 'fa-shopping-cart', 
                'name'     => $this->language->get('text_order'),
                'href'     => $this->url->link('account/vendor/lts_order', '', true),
                'children' => array()       
            );

            $sales[] = array(
                'icon'     => 'fa-gift', 
                'name'     => $this->language->get('text_coupon'),
                'href'     => $this->url->link('account/vendor/lts_coupon', '', true),
                'children' => array()       
            );
              $sales[] = array(
                'icon'     => 'fa-percent', 
                'name'     => $this->language->get('text_commission'),
                'href'     => $this->url->link('account/vendor/lts_commission', '', true),
                'children' => array()       
            );

            if ($sales) {
                $data['menus'][] = array(
                    'id'       => 'menu-sales',
                    'icon'     => 'fa-shopping-cart', 
                    'name'     => $this->language->get('text_sales'),
                    'href'     => '',
                    'children' => $sales
                );      
            }

             $subscription = array();

             $subscription[] = array(
                'icon'     => 'fa fa-diamond', 
                'name'     => $this->language->get('text_subscription_info'),
                'href'     => $this->url->link('account/vendor/lts_subscription', '', true),
                'children' => array()       
            ); 


            if ($subscription) {
                $data['menus'][] = array(
                    'id'       => 'menu-store',
                    'icon'     => 'fa fa-ticket', 
                    'name'     => $this->language->get('text_subscription'),
                    'href'     => '',
                    'children' => $subscription
                );      
            }

            $store = array();

             $store[] = array(
                'icon'     => 'fa-info', 
                'name'     => $this->language->get('text_store_info'),
                'href'     => $this->url->link('account/vendor/lts_store', '', true),
                'children' => array()       
            ); 

            $store[] = array(
                'icon'     => 'fa-gift', 
                'name'     => $this->language->get('text_setting'),
                'href'     => $this->url->link('account/vendor/lts_setting', '', true),
                'children' => array()       
            );

            $this->load->model('account/vendor/lts_vendor');

            $vednor_info = $this->model_account_vendor_lts_vendor->getVendorStoreInfo($this->customer->isLogged());
            if(!empty($vednor_info)) {
                $data['has_vendor_profile'] = true;
                $store[] = array(
                    'icon'     => 'fa-external-link', 
                    'name'     => $this->language->get('text_visit_store'),
                    'href'     => $this->url->link('vendor/lts_visit', 'vendor_id='. $vednor_info['vendor_id'], true),
                    'children' => array()       
                );

            } else {
                $data['has_vendor_profile'] = false;
            }

           
            if ($store) {
                $data['menus'][] = array(
                    'id'       => 'menu-store',
                    'icon'     => 'fa-cog', 
                    'name'     => $this->language->get('text_store'),
                    'href'     => '',
                    'children' => $store
                );      
            }

            if($data['has_vendor_profile']) {
                return $this->load->view('account/vendor/lts_column_left', $data);
            }
        }  
    }
}
