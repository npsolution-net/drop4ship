<?php
class ModelVendorLtsVendor extends Model {
	public function editVendorStoreInfo($vendor_id, $data) {

		$sql = "UPDATE ". DB_PREFIX ."lts_vendor SET commission_rate = '". (int)$data['commission_rate'] ."', status = '". (int)$data['status'] ."', approved = '". (int)$data['approved'] ."', description =  '". $this->db->escape($data['description']) ."' , meta_title = '". $this->db->escape($data['meta_title']) ."', meta_description = '". $this->db->escape($data['meta_description']) ."',  meta_keyword = '". $this->db->escape($data['meta_keyword']) ."', store_name = '" . $this->db->escape($data['store_name']) . "', store_name= '" . $this->db->escape($data["store_name"]) . "' , address = '" . $this->db->escape($data['address']) . "' , email= '" . $this->db->escape($data['email']) . "' , telephone= '" . $this->db->escape($data['telephone']) . "' ,  fax= '" . $this->db->escape($data['fax']) . "' ,  country_id= '" . (int)$data['country_id'] . "' ,   zone_id= '" . $this->db->escape($data['zone_id']) . "' ,  city= '" . $this->db->escape($data['city']) . "', profile_image= '" . $this->db->escape($data['profile_image']) . "', logo= '" . $this->db->escape($data['logo']) . "',  banner= '" . $this->db->escape($data['banner']) . "',  facebook= '" . $this->db->escape($data['facebook']) . "', instagram= '" . $this->db->escape($data['instagram']) . "', youtube= '" . $this->db->escape($data['youtube']) . "', twitter= '" . $this->db->escape($data['twitter']) . "', pinterest= '" . $this->db->escape($data['pinterest']) . "' WHERE vendor_id = '". (int)$vendor_id ."'";

		$this->db->query($sql);


		// SEO URL
		$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'vendor_id=" . (int)$vendor_id . "'");

		if (isset($data['vendor_seo_url'])) {

			foreach ($data['vendor_seo_url']as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if (!empty($keyword)) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'vendor_id=" . (int)$vendor_id . "', keyword = '" . $this->db->escape($keyword) . "'");
					}
				}
			}
		}


	}

	public function getVendors($data = array()) {
		
		$sql = "SELECT *, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "lts_product lp WHERE lp.vendor_id = lv.vendor_id) AS total_products FROM " . DB_PREFIX . "lts_vendor lv";

		$implode = array();

		if (!empty($data['filter_store_owner'])) {
			$implode[] ="lv.store_owner LIKE '%" . $this->db->escape($data['filter_store_owner']) . "%'";
		}

		if (!empty($data['filter_store_name'])) {
			$implode[] ="lv.store_name LIKE '%" . $this->db->escape($data['filter_store_name']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (isset($data['filter_approved']) && $data['filter_approved'] !== '') {
			$implode[] = "lv.status = '" . (int)$data['filter_approved'] . "'";
		}	

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$implode[] = "lv.status = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(lv.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sql .= " GROUP BY lv.vendor_id";

		$sort_data = array(
			'lv.store_owner',
			'lv.store_name',
			'lv.email',
			'lv.status',
			'lv.approved',
			'total_products'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY store_name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getStoreName($vendor_id) {
		$sql = "SELECT store_name FROM " . DB_PREFIX . "lts_vendor WHERE vendor_id = '". (int)$vendor_id ."'";
		
		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getStoreOwner($vendor_id) {
		$sql = "SELECT store_owner FROM " . DB_PREFIX . "lts_vendor WHERE vendor_id = '". (int)$vendor_id ."'";
		
		$query = $this->db->query($sql);

		return $query->row;
	}

	public function vendorRequest() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM ". DB_PREFIX ."lts_vendor WHERE status = '" . (int)0 . "'");

		return $query->row['total'];
	}

	

	public function getVendor($vendor_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "lts_vendor WHERE vendor_id = '" . (int)$vendor_id . "'");

		return $query->row;
	}

	public function getVendorInfo($vendor_id) {
		$query = $this->db->query("SELECT * FROM ". DB_PREFIX ."lts_vendor WHERE vendor_id = '". (int)$vendor_id ."'");

		return $query->row;
	}

	
	public function getVendorSeoUrls($vendor_id) {
		$vendor_seo_url_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'vendor_id=" . (int)$vendor_id . "'");

		foreach ($query->rows as $result) {
			$vendor_seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
		}

		return $vendor_seo_url_data;
	}
 
	public function approve($vendor_id) {
		$vendor_info = $this->getVendor($vendor_id);

		if ($vendor_info) {
			$this->db->query("UPDATE " . DB_PREFIX . "lts_vendor SET approved = '1' WHERE vendor_id = '" . (int)$vendor_id . "'");
		}
	}

	public function getVendorName($vendor_id) {
		$query = $this->db->query("SELECT CONCAT(c.firstname, ' ', c.lastname) AS name FROM " . DB_PREFIX . "customer c RIGHT JOIN  ". DB_PREFIX ."lts_vendor lv ON(c.customer_id = lv.vendor_id) WHERE lv.vendor_id = '" . (int)$vendor_id . "'");

		return $query->row;
	}

	public function getTotalVendors($data = array()) {

		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "lts_vendor lv";
		$implode = array();

		if (!empty($data['filter_vendorname'])) {
			$implode[] ="CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_vendorname']) . "%'";
		}

		if (!empty($data['filter_storename'])) {
			$implode[] ="lvs.store_name LIKE '%" . $this->db->escape($data['filter_storename']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (isset($data['filter_approved']) && $data['filter_approved'] !== '') {
			$implode[] = "lv.status = '" . (int)$data['filter_approved'] . "'";
		}	

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$implode[] = "lv.status = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(lv.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}


	public function getTotalRequestVendors($data = array()) {
		$sql = "SELECT COUNT(vendor_id) AS total FROM " . DB_PREFIX . "lts_vendor WHERE status ='". (int)0 ."' ";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}



	public function getStoreInformation($vendor_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "lts_vendor_store`  WHERE  vendor_id = '" . (int)$vendor_id . "'");

		return $query->row;
	}

    public function getProducts($vendor_id, $data = array()) {

	    $sql = "SELECT * FROM " . DB_PREFIX . "lts_vendor_product lvp LEFT JOIN " . DB_PREFIX . "product_description pd ON (lvp.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product p ON(p.product_id = lvp.product_id)  WHERE  pd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND lvp.vendor_id = '" . (int)$vendor_id . "'";

	    $sort_data = array(
	        'pd.name',
	        'p.model',
	        'p.price',
	        'p.quantity',
	        'p.status',
	        'p.sort_order'
	    );

	    if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
	      $sql .= " ORDER BY " . $data['sort'];
	    } else {
	      $sql .= " ORDER BY pd.name";
	    }

	    if (isset($data['order']) && ($data['order'] == 'DESC')) {
	      $sql .= " DESC";
	    } else {
	      $sql .= " ASC";
	    }

	    if (isset($data['start']) || isset($data['limit'])) {
	      if ($data['start'] < 0) {
	        $data['start'] = 0;
	      }

	      if ($data['limit'] < 1) {
	        $data['limit'] = 20;
	      }

	      $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
	    }

	    $query = $this->db->query($sql);

	    return $query->rows;
	  }

  public function getTotalProducts($vendor_id) {
    $sql = "SELECT COUNT(DISTINCT lvp.product_id) AS total FROM " . DB_PREFIX . "lts_vendor_product lvp LEFT JOIN " . DB_PREFIX . "product p ON (lvp.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (lvp.product_id = pd.product_id) ";


    $sql .= " WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND lvp.vendor_id='" . (int) $vendor_id . "'";

    $query = $this->db->query($sql);

    return $query->row['total'];
  }

	public function getOrders($vendor_id, $data = array()) {
		
		$sql = "SELECT o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM ". DB_PREFIX ."order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . $this->config->get('config_language_id') . "') AS order_status, o.shipping_code, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . "order` o RIGHT JOIN `" . DB_PREFIX . "lts_vendor_order_product` lvop ON (o.order_id = lvop.order_id) WHERE lvop.vendor_id='". (int) $vendor_id ."'";

		$query = $this->db->query($sql);

		return $query->rows;
	}

  	public function getTotalOrders($data = array()) {
		
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "lts_vendor_order_product`";

		if (!empty($data['filter_order_status'])) {
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} elseif (isset($data['filter_order_status_id']) && $data['filter_order_status_id'] !== '') {
			$sql .= " WHERE order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE order_status_id > '0'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND total = '" . (float)$data['filter_total'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function deleteVendor($vendor_id) {
				
	}
}