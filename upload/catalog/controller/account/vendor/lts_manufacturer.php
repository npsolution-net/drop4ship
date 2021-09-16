<?php

class ControllerAccountVendorLtsManufacturer extends Controller {

  private $error = array();

  public function index() {


    if (!$this->customer->isLogged()) {
        $this->session->data['redirect'] = $this->url->link('account/account', '', true);

        $this->response->redirect($this->url->link('account/login', '', true));
    }

    if(!$this->config->get('module_lts_vendor_status')) {
        $this->response->redirect($this->url->link('account/account', '', true));
    }

    $this->load->model('account/vendor/lts_vendor');

    if($this->customer->isLogged()){
        $data['customer_id'] = $this->customer->getId();
    }
     
    $vendor_info = $this->model_account_vendor_lts_vendor->getVendorStoreInfo($this->customer->isLogged());

    if(!$vendor_info) {
        $this->response->redirect($this->url->link('account/account', '', true));
    }
  
    $this->load->language("account/vendor/lts_manufacturer");

    $this->document->setTitle($this->language->get("heading_title"));

    $this->load->model("account/vendor/lts_manufacturer");


    $this->getList();
  }

  public function add() {
    if (!$this->customer->isLogged()) {
        $this->session->data['redirect'] = $this->url->link('account/account', '', true);

        $this->response->redirect($this->url->link('account/login', '', true));
    }

    if(!$this->config->get('module_lts_vendor_status')) {
        $this->response->redirect($this->url->link('account/account', '', true));
    }

    $this->load->model('account/vendor/lts_vendor');

    if($this->customer->isLogged()){
        $data['customer_id'] = $this->customer->getId();
    }
     
    $vendor_info = $this->model_account_vendor_lts_vendor->getVendorStoreInfo($this->customer->isLogged());

    if(!$vendor_info) {
        $this->response->redirect($this->url->link('account/account', '', true));
    }

    $this->load->language("account/vendor/lts_manufacturer");

    $this->document->setTitle($this->language->get("heading_title"));

    $this->load->model("account/vendor/lts_manufacturer");

    if (($this->request->server["REQUEST_METHOD"] == "POST") && $this->validateForm()) {
      $this->model_account_vendor_lts_manufacturer->addManufacturer($vendor_info['vendor_id'], $this->request->post);

      $this->session->data["success"] = $this->language->get("text_success");

      $url = "";

      if (isset($this->request->get["sort"])) {
        $url .= "&sort=" . $this->request->get["sort"];
      }

      if (isset($this->request->get["order"])) {
        $url .= "&order=" . $this->request->get["order"];
      }

      if (isset($this->request->get["page"])) {
        $url .= "&page=" . $this->request->get["page"];
      }

      $this->response->redirect($this->url->link("account/vendor/lts_manufacturer"));
    }

    $this->getForm();
  }

  public function edit() {
    if (!$this->customer->isLogged()) {
        $this->session->data['redirect'] = $this->url->link('account/account', '', true);

        $this->response->redirect($this->url->link('account/login', '', true));
    }

    if(!$this->config->get('module_lts_vendor_status')) {
        $this->response->redirect($this->url->link('account/account', '', true));
    }

    $this->load->model('account/vendor/lts_vendor');

    if($this->customer->isLogged()){
        $data['customer_id'] = $this->customer->getId();
    }
     
    $vendor_info = $this->model_account_vendor_lts_vendor->getVendorStoreInfo($this->customer->isLogged());

    if(!$vendor_info) {
        $this->response->redirect($this->url->link('account/account', '', true));
    }

    $this->load->language("account/vendor/lts_manufacturer");

    $this->document->setTitle($this->language->get("heading_title"));

    $this->load->model("account/vendor/lts_manufacturer");

    if (($this->request->server["REQUEST_METHOD"] == "POST") && $this->validateForm()) {
      $this->model_account_vendor_lts_manufacturer->editManufacturer($this->request->get["manufacturer_id"], $vendor_info['vendor_id'], $this->request->post);

      $this->session->data["success"] = $this->language->get("text_success");

      $url = "";

      if (isset($this->request->get["sort"])) {
        $url .= "&sort=" . $this->request->get["sort"];
      }

      if (isset($this->request->get["order"])) {
        $url .= "&order=" . $this->request->get["order"];
      }

      if (isset($this->request->get["page"])) {
        $url .= "&page=" . $this->request->get["page"];
      }

      $this->response->redirect($this->url->link("account/vendor/lts_manufacturer"));
    }

    $this->getForm();
  }

  public function delete() {
     if (!$this->customer->isLogged()) {
        $this->session->data['redirect'] = $this->url->link('account/account', '', true);

        $this->response->redirect($this->url->link('account/login', '', true));
    }

    if(!$this->config->get('module_lts_vendor_status')) {
        $this->response->redirect($this->url->link('account/account', '', true));
    }

    $this->load->model('account/vendor/lts_vendor');

    if($this->customer->isLogged()){
        $data['customer_id'] = $this->customer->getId();
    }
     
    $vendor_info = $this->model_account_vendor_lts_vendor->getVendorStoreInfo($this->customer->isLogged());

    if(!$vendor_info) {
        $this->response->redirect($this->url->link('account/account', '', true));
    }

    $this->load->language("account/vendor/lts_manufacturer");

    $this->document->setTitle($this->language->get("heading_title"));

    $this->load->model("account/vendor/lts_manufacturer");

    
    if (isset($this->request->post["selected"]) && $this->validateDelete()) {
      foreach ($this->request->post["selected"] as $manufacturer_id) {
        $this->model_account_vendor_lts_manufacturer->deleteManufacturer($manufacturer_id, $vendor_info['vendor_id']);
      }

      $this->session->data["success"] = $this->language->get("text_success");

      $url = "";

      if (isset($this->request->get["sort"])) {
        $url .= "&sort=" . $this->request->get["sort"];
      }

      if (isset($this->request->get["order"])) {
        $url .= "&order=" . $this->request->get["order"];
      }

      if (isset($this->request->get["page"])) {
        $url .= "&page=" . $this->request->get["page"];
      }

      $this->response->redirect($this->url->link("account/vendor/lts_manufacturer", $url, true));
    }

    $this->getList();
  }

  protected function getList() {


    if (isset($this->request->get["sort"])) {
      $sort = $this->request->get["sort"];
    } else {
      $sort = "name";
    }

    if (isset($this->request->get["order"])) {
      $order = $this->request->get["order"];
    } else {
      $order = "ASC";
    }

    if (isset($this->request->get["page"])) {
      $page = $this->request->get["page"];
    } else {
      $page = 1;
    }

    $url = "";

    if (isset($this->request->get["sort"])) {
      $url .= "&sort=" . $this->request->get["sort"];
    }

    if (isset($this->request->get["order"])) {
      $url .= "&order=" . $this->request->get["order"];
    }

    if (isset($this->request->get["page"])) {
      $url .= "&page=" . $this->request->get["page"];
    }

    $data["breadcrumbs"] = array();

    $data["breadcrumbs"][] = array(
        "text" => $this->language->get("text_home"),
        "href" => $this->url->link("account/vendor/lts_dashboard")
    );

    $data["breadcrumbs"][] = array(
        "text" => $this->language->get("heading_title"),
        "href" => $this->url->link("account/vendor/lts_manufacturer", $url)
    );

    $data["add"] = $this->url->link("account/vendor/lts_manufacturer/add");
    $data["delete"] = $this->url->link("account/vendor/lts_manufacturer/delete");

    $this->load->model('account/vendor/lts_vendor');

    $vendor_info = $this->model_account_vendor_lts_vendor->getVendorStoreInfo($this->customer->isLogged());

    $data["manufacturers"] = array();

    $filter_data = array(
        "sort" => $sort,
        "order" => $order,
        "start" => ($page - 1) * $this->config->get("config_limit_admin"),
        "limit" => $this->config->get("config_limit_admin"),
        "vendor_id" => $vendor_info['vendor_id']
    );

    $manufacturer_total = $this->model_account_vendor_lts_manufacturer->getTotalManufacturers($filter_data);

    $results = $this->model_account_vendor_lts_manufacturer->getManufacturers($filter_data);

    foreach ($results as $result) {
      $data["manufacturers"][] = array(
          "manufacturer_id" => $result["manufacturer_id"],
          "name" => $result["name"],
          "sort_order" => $result["sort_order"],
          "edit" => $this->url->link("account/vendor/lts_manufacturer/edit", "&manufacturer_id=" . $result["manufacturer_id"])
      );
    }

    if (isset($this->error["warning"])) {
      $data["error_warning"] = $this->error["warning"];
    } else {
      $data["error_warning"] = "";
    }

    if (isset($this->session->data["success"])) {
      $data["success"] = $this->session->data["success"];

      unset($this->session->data["success"]);
    } else {
      $data["success"] = "";
    }

    if (isset($this->request->post["selected"])) {
      $data["selected"] = (array) $this->request->post["selected"];
    } else {
      $data["selected"] = array();
    }

    $url = "";

    if ($order == "ASC") {
      $url .= "&order=DESC";
    } else {
      $url .= "&order=ASC";
    }

    if (isset($this->request->get["page"])) {
      $url .= "&page=" . $this->request->get["page"];
    }

    $data["sort_name"] = $this->url->link("account/vendor/lts_manufacturer", "&sort=name" . $url);
    $data["sort_sort_order"] = $this->url->link("account/vendor/lts_manufacturer", "&sort=sort_order" . $url);

    $url = "";

    if (isset($this->request->get["sort"])) {
      $url .= "&sort=" . $this->request->get["sort"];
    }

    if (isset($this->request->get["order"])) {
      $url .= "&order=" . $this->request->get["order"];
    }

    $pagination = new Pagination();
    $pagination->total = $manufacturer_total;
    $pagination->page = $page;
    $pagination->limit = $this->config->get("config_limit_admin");
    $pagination->url = $this->url->link("account/vendor/lts_manufacturer", $url . "&page={page}", true);

    $data["pagination"] = $pagination->render();

    $data["results"] = sprintf($this->language->get("text_pagination"), ($manufacturer_total) ? (($page - 1) * $this->config->get("config_limit_admin")) + 1 : 0, ((($page - 1) * $this->config->get("config_limit_admin")) > ($manufacturer_total - $this->config->get("config_limit_admin"))) ? $manufacturer_total : ((($page - 1) * $this->config->get("config_limit_admin")) + $this->config->get("config_limit_admin")), $manufacturer_total, ceil($manufacturer_total / $this->config->get("config_limit_admin")));

    $data["sort"] = $sort;
    $data["order"] = $order;

    $this->load->controller("account/vendor/lts_header/script");
    $data["lts_column_left"] = $this->load->controller("account/vendor/lts_column_left");
    $data["footer"] = $this->load->controller("common/footer");
    $data["header"] = $this->load->controller("common/header");
    

    $this->response->setOutput($this->load->view("account/vendor/lts_manufacturer_list", $data));
  }

  protected function getForm() {

    $data["text_form"] = !isset($this->request->get["manufacturer_id"]) ? $this->language->get("text_add") : $this->language->get("text_edit");

    if (isset($this->error["warning"])) {
      $data["error_warning"] = $this->error["warning"];
    } else {
      $data["error_warning"] = "";
    }

    if (isset($this->error["name"])) {
      $data["error_name"] = $this->error["name"];
    } else {
      $data["error_name"] = "";
    }

    if (isset($this->error["keyword"])) {
      $data["error_keyword"] = $this->error["keyword"];
    } else {
      $data["error_keyword"] = "";
    }

    $url = "";

    if (isset($this->request->get["sort"])) {
      $url .= "&sort=" . $this->request->get["sort"];
    }

    if (isset($this->request->get["order"])) {
      $url .= "&order=" . $this->request->get["order"];
    }

    if (isset($this->request->get["page"])) {
      $url .= "&page=" . $this->request->get["page"];
    }

    $data["breadcrumbs"] = array();

    $data["breadcrumbs"][] = array(
        "text" => $this->language->get("text_home"),
        "href" => $this->url->link("account/vendor/lts_dashboard")
    );

    $data["breadcrumbs"][] = array(
        "text" => $this->language->get("heading_title"),
        "href" => $this->url->link("account/vendor/lts_manufacturer")
    );

    if (!isset($this->request->get["manufacturer_id"])) {
      $data["action"] = $this->url->link("account/vendor/lts_manufacturer/add", $url);
    } else {
      $data["action"] = $this->url->link("account/vendor/lts_manufacturer/edit", "&manufacturer_id=" . $this->request->get["manufacturer_id"] . $url, true);
    }

    $data["cancel"] = $this->url->link("account/vendor/lts_manufacturer", $url);

    if (isset($this->request->get["manufacturer_id"]) && ($this->request->server["REQUEST_METHOD"] != "POST")) {
      $manufacturer_info = $this->model_account_vendor_lts_manufacturer->getManufacturer($this->request->get["manufacturer_id"]);
    }

    if (isset($this->request->post["name"])) {
      $data["name"] = $this->request->post["name"];
    } elseif (!empty($manufacturer_info)) {
      $data["name"] = $manufacturer_info["name"];
    } else {
      $data["name"] = "";
    }

    $this->load->model("account/vendor/lts_store");

    $data["stores"] = array();

    $data["stores"][] = array(
        "store_id" => 0,
        "name" => $this->language->get("text_default")
    );

    $stores = $this->model_account_vendor_lts_store->getStores();

    foreach ($stores as $store) {
      $data["stores"][] = array(
          "store_id" => $store["store_id"],
          "name" => $store["name"]
      );
    }

    if (isset($this->request->post["manufacturer_store"])) {
      $data["manufacturer_store"] = $this->request->post["manufacturer_store"];
    } elseif (isset($this->request->get["manufacturer_id"])) {
      $data["manufacturer_store"] = $this->model_account_vendor_lts_manufacturer->getManufacturerStores($this->request->get["manufacturer_id"]);
    } else {
      $data["manufacturer_store"] = array(0);
    }

    if (isset($this->request->post["image"])) {
      $data["image"] = $this->request->post["image"];
    } elseif (!empty($manufacturer_info)) {
      $data["image"] = $manufacturer_info["image"];
    } else {
      $data["image"] = "";
    }

    $this->load->model("tool/image");

    if (isset($this->request->post["image"]) && is_file(DIR_IMAGE . $this->request->post["image"])) {
      $data["thumb"] = $this->model_tool_image->resize($this->request->post["image"], 100, 100);
    } elseif (!empty($manufacturer_info) && is_file(DIR_IMAGE . $manufacturer_info["image"])) {
      $data["thumb"] = $this->model_tool_image->resize($manufacturer_info["image"], 100, 100);
    } else {
      $data["thumb"] = $this->model_tool_image->resize("no_image.png", 100, 100);
    }

    $data["placeholder"] = $this->model_tool_image->resize("no_image.png", 100, 100);

    if (isset($this->request->post["sort_order"])) {
      $data["sort_order"] = $this->request->post["sort_order"];
    } elseif (!empty($manufacturer_info)) {
      $data["sort_order"] = $manufacturer_info["sort_order"];
    } else {
      $data["sort_order"] = "";
    }

    $this->load->model("account/vendor/lts_language");

    $data["languages"] = $this->model_account_vendor_lts_language->getLanguages();

    if (isset($this->request->post["manufacturer_seo_url"])) {
      $data["manufacturer_seo_url"] = $this->request->post["manufacturer_seo_url"];
    } elseif (isset($this->request->get["manufacturer_id"])) {
      $data["manufacturer_seo_url"] = $this->model_account_vendor_lts_manufacturer->getManufacturerSeoUrls($this->request->get["manufacturer_id"]);
    } else {
      $data["manufacturer_seo_url"] = array();
    }

    $this->load->controller("account/vendor/lts_header/script");
    $data["footer"] = $this->load->controller("common/footer");
    $data["header"] = $this->load->controller("common/header");
    $data["lts_column_left"] = $this->load->controller("account/vendor/lts_column_left");

    $this->response->setOutput($this->load->view("account/vendor/lts_manufacturer_form", $data));
  }


  protected function validateForm() {

    if ((utf8_strlen($this->request->post["name"]) < 1) || (utf8_strlen($this->request->post["name"]) > 64)) {
      $this->error["name"] = $this->language->get("error_name");
    }

    if ($this->request->post["manufacturer_seo_url"]) {
      $this->load->model("account/vendor/lts_seo_url");

      foreach ($this->request->post["manufacturer_seo_url"] as $store_id => $language) {
        foreach ($language as $language_id => $keyword) {
          if (!empty($keyword)) {
            if (count(array_keys($language, $keyword)) > 1) {
              $this->error["keyword"][$store_id][$language_id] = $this->language->get("error_unique");
            }

            $seo_urls = $this->model_account_vendor_lts_seo_url->getSeoUrlsByKeyword($keyword);

            foreach ($seo_urls as $seo_url) {
              if (($seo_url["store_id"] == $store_id) && (!isset($this->request->get["manufacturer_id"]) || (($seo_url["query"] != "manufacturer_id=" . $this->request->get["manufacturer_id"])))) {
                $this->error["keyword"][$store_id][$language_id] = $this->language->get("error_keyword");
              }
            }
          }
        }
      }
    }

    return !$this->error;
  }

  protected function validateDelete() {

    $this->load->model("account/vendor/lts_product");

    foreach ($this->request->post["selected"] as $manufacturer_id) {
      $product_total = $this->model_account_vendor_lts_product->getTotalProductsByManufacturerId($manufacturer_id);

      if ($product_total) {
        $this->error["warning"] = sprintf($this->language->get("error_product"), $product_total);
      }
    }

    return !$this->error;
  }

  public function autocomplete() {
    $json = array();

    if (isset($this->request->get["filter_name"])) {
      $this->load->model("account/vendor/lts_manufacturer");

      $filter_data = array(
          "filter_name" => $this->request->get["filter_name"],
          "start" => 0,
          "limit" => 5
      );

      $results = $this->model_account_vendor_lts_manufacturer->getManufacturers($filter_data);

      foreach ($results as $result) {
        $json[] = array(
            "manufacturer_id" => $result["manufacturer_id"],
            "name" => strip_tags(html_entity_decode($result["name"], ENT_QUOTES, "UTF-8"))
        );
      }
    }

    $sort_order = array();

    foreach ($json as $key => $value) {
      $sort_order[$key] = $value["name"];
    }

    array_multisort($sort_order, SORT_ASC, $json);

    $this->response->addHeader("Content-Type: application/json");
    $this->response->setOutput(json_encode($json));
  }
}
