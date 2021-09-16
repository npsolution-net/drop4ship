<?php

class ControllerAccountVendorLtsFilter extends Controller {

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
    
    $this->load->language("account/vendor/lts_filter");

    $this->document->setTitle($this->language->get("heading_title"));

    $this->load->model("account/vendor/lts_filter");

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
   
    $this->load->language("account/vendor/lts_filter");

    $this->document->setTitle($this->language->get("heading_title"));

    $this->load->model("account/vendor/lts_filter");

    if (($this->request->server["REQUEST_METHOD"] == "POST") && $this->validateForm()) {
      $this->model_account_vendor_lts_filter->addFilter($vendor_info['vendor_id'], $this->request->post);

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

      $this->response->redirect($this->url->link("account/vendor/lts_filter"));
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

    $this->load->language("account/vendor/lts_filter");

    $this->document->setTitle($this->language->get("heading_title"));

    $this->load->model("account/vendor/lts_filter");

    if (($this->request->server["REQUEST_METHOD"] == "POST") && $this->validateForm()) {
      $this->model_account_vendor_lts_filter->editFilter($this->request->get["filter_group_id"], $vendor_info['vendor_id'], $this->request->post);

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

      $this->response->redirect($this->url->link("account/vendor/lts_filter"));
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

    $this->load->language("account/vendor/lts_filter");

    $this->document->setTitle($this->language->get("heading_title"));

    $this->load->model("account/vendor/lts_filter");

    if (isset($this->request->post["selected"])) {
      foreach ($this->request->post["selected"] as $filter_group_id) {
        $this->model_account_vendor_lts_filter->deleteFilter($filter_group_id, $vendor_info['vendor_id']);
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

      $this->response->redirect($this->url->link("account/vendor/lts_filter"));
    }

    $this->getList();
  }

  protected function getList() {

    if (isset($this->request->get["sort"])) {
      $sort = $this->request->get["sort"];
    } else {
      $sort = "fgd.name";
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
        "href" => $this->url->link("account/vendor/lts_filter")
    );

    $data["add"] = $this->url->link("account/vendor/lts_filter/add");
    $data["delete"] = $this->url->link("account/vendor/lts_filter/delete");

    $data["filters"] = array();

     $this->load->model('account/vendor/lts_vendor');

    $vendor_info = $this->model_account_vendor_lts_vendor->getVendorStoreInfo($this->customer->isLogged());

    if(!$vendor_info) {
        $this->response->redirect($this->url->link('account/account', '', true));
    }

    $filter_data = array(
        "sort" => $sort,
        "order" => $order,
        "start" => ($page - 1) * $this->config->get("config_limit_admin"),
        "limit" => $this->config->get("config_limit_admin"),
        'vendor_id' => $vendor_info['vendor_id'],
    );

    $filter_total = $this->model_account_vendor_lts_filter->getTotalFilterGroups($filter_data);

    $results = $this->model_account_vendor_lts_filter->getFilterGroups($filter_data);

    foreach ($results as $result) {
      $data["filters"][] = array(
          "filter_group_id" => $result["filter_group_id"],
          "name" => $result["name"],
          "sort_order" => $result["sort_order"],
          "edit" => $this->url->link("account/vendor/lts_filter/edit", "&filter_group_id=" . $result["filter_group_id"] . $url, true)
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

    $data["sort_name"] = $this->url->link("account/vendor/lts_filter", "&sort=fgd.name" . $url, true);
    $data["sort_sort_order"] = $this->url->link("account/vendor/lts_filter", "&sort=fg.sort_order" . $url, true);

    $url = "";

    if (isset($this->request->get["sort"])) {
      $url .= "&sort=" . $this->request->get["sort"];
    }

    if (isset($this->request->get["order"])) {
      $url .= "&order=" . $this->request->get["order"];
    }

    $pagination = new Pagination();
    $pagination->total = $filter_total;
    $pagination->page = $page;
    $pagination->limit = $this->config->get("config_limit_admin");
    $pagination->url = $this->url->link("account/vendor/lts_filter", "&page={page}" . $url, true);

    $data["pagination"] = $pagination->render();

    $data["results"] = sprintf($this->language->get("text_pagination"), ($filter_total) ? (($page - 1) * $this->config->get("config_limit_admin")) + 1 : 0, ((($page - 1) * $this->config->get("config_limit_admin")) > ($filter_total - $this->config->get("config_limit_admin"))) ? $filter_total : ((($page - 1) * $this->config->get("config_limit_admin")) + $this->config->get("config_limit_admin")), $filter_total, ceil($filter_total / $this->config->get("config_limit_admin")));

    $data["sort"] = $sort;
    $data["order"] = $order;

    $this->load->controller("account/vendor/lts_header/script");
    $data["footer"] = $this->load->controller("common/footer");
    $data["header"] = $this->load->controller("common/header");
    $data["lts_column_left"] = $this->load->controller("account/vendor/lts_column_left");

    $this->response->setOutput($this->load->view("account/vendor/lts_filter_list", $data));
  }

  protected function getForm() {

    $data["text_form"] = !isset($this->request->get["filter_id"]) ? $this->language->get("text_add") : $this->language->get("text_edit");

    if (isset($this->error["warning"])) {
      $data["error_warning"] = $this->error["warning"];
    } else {
      $data["error_warning"] = "";
    }

    if (isset($this->error["group"])) {
      $data["error_group"] = $this->error["group"];
    } else {
      $data["error_group"] = array();
    }

    if (isset($this->error["filter"])) {
      $data["error_filter"] = $this->error["filter"];
    } else {
      $data["error_filter"] = array();
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
        "href" => $this->url->link("account/vendor/lts_filter")
    );

    if (!isset($this->request->get["filter_group_id"])) {
      $data["action"] = $this->url->link("account/vendor/lts_filter/add");
    } else {
      $data["action"] = $this->url->link("account/vendor/lts_filter/edit", "&filter_group_id=" . $this->request->get["filter_group_id"] . $url, true);
    }

    $data["cancel"] = $this->url->link("account/vendor/lts_filter");

    if (isset($this->request->get["filter_group_id"]) && ($this->request->server["REQUEST_METHOD"] != "POST")) {
      $filter_group_info = $this->model_account_vendor_lts_filter->getFilterGroup($this->request->get["filter_group_id"]);
    }

    $this->load->model("localisation/language");

    $data["languages"] = $this->model_localisation_language->getLanguages();

    if (isset($this->request->post["filter_group_description"])) {
      $data["filter_group_description"] = $this->request->post["filter_group_description"];
    } elseif (isset($this->request->get["filter_group_id"])) {
      $data["filter_group_description"] = $this->model_account_vendor_lts_filter->getFilterGroupDescriptions($this->request->get["filter_group_id"]);
    } else {
      $data["filter_group_description"] = array();
    }

    if (isset($this->request->post["sort_order"])) {
      $data["sort_order"] = $this->request->post["sort_order"];
    } elseif (!empty($filter_group_info)) {
      $data["sort_order"] = $filter_group_info["sort_order"];
    } else {
      $data["sort_order"] = "";
    }

    if (isset($this->request->post["filter"])) {
      $data["filters"] = $this->request->post["filter"];
    } elseif (isset($this->request->get["filter_group_id"])) {
      $data["filters"] = $this->model_account_vendor_lts_filter->getFilterDescriptions($this->request->get["filter_group_id"]);
    } else {
      $data["filters"] = array();
    }
    
    $this->load->controller("account/vendor/lts_header/script");
    $data["header"] = $this->load->controller("common/header");
    $data["lts_column_left"] = $this->load->controller("account/vendor/lts_column_left");
    $data["footer"] = $this->load->controller("common/footer");

    $this->response->setOutput($this->load->view("account/vendor/lts_filter_form", $data));
  }

  protected function validateForm() {

    foreach ($this->request->post["filter_group_description"] as $language_id => $value) {
      if ((utf8_strlen($value["name"]) < 1) || (utf8_strlen($value["name"]) > 64)) {
        $this->error["group"][$language_id] = $this->language->get("error_group");
      }
    }

    if (isset($this->request->post["filter"])) {
      foreach ($this->request->post["filter"] as $filter_id => $filter) {
        foreach ($filter["filter_description"] as $language_id => $filter_description) {
          if ((utf8_strlen($filter_description["name"]) < 1) || (utf8_strlen($filter_description["name"]) > 64)) {
            $this->error["filter"][$filter_id][$language_id] = $this->language->get("error_name");
          }
        }
      }
    }

    if ($this->error && !isset($this->error["warning"])) {
      $this->error["warning"] = $this->language->get("error_warning");
    }

    return !$this->error;
  }

  public function autocomplete() {
    $json = array();

    if (isset($this->request->get["filter_name"])) {
      $this->load->model("account/vendor/lts_filter");

      $filter_data = array(
          "filter_name" => $this->request->get["filter_name"],
          "start" => 0,
          "limit" => 5
      );

      $filters = $this->model_account_vendor_lts_filter->getFilters($filter_data);

      foreach ($filters as $filter) {
        $json[] = array(
            "filter_id" => $filter["filter_id"],
            "name" => strip_tags(html_entity_decode($filter["group"] . " &gt; " . $filter["name"], ENT_QUOTES, "UTF-8"))
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
