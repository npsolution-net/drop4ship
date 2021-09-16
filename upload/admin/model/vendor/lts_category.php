<?php
class ModelVendorLtsCategory extends Model {

	public function addAssignedCategory($data) {
		if($data['vendor_category']) {
			$i_category_id = implode(',', $data['vendor_category']);
		}

		foreach($data['vendor_category'] as $category_id) {
			$this->db->query("INSERT INTO ". DB_PREFIX ."lts_category SET vendor_id='". (int)$data['vendor_id'] ."', category_id='". $category_id ."', assigned='". (int)1 ."',approved='". (int)1 ."',status='". (int)1 ."'");
		}

		if(!empty($i_category_id)) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "lts_assigned_category SET vendor_id = '" . (int)$data['vendor_id'] . "', category_id = '" . $i_category_id . "'");
		}
	}

	public function editAssignedCategory($vendor_id, $data) {
		$category = $this->getVendorAssignedCategories($vendor_id);

		$categories = explode(',',$category['category_id']);

		foreach($categories as $category_id) {
			$this->deleteVendorCategory($category['vendor_id'], $category_id);
		}

		foreach($data['vendor_category'] as $category_id) {
			$this->db->query("INSERT INTO ". DB_PREFIX ."lts_category SET vendor_id='". (int)$data['vendor_id'] ."', category_id='". (int)$category_id ."', assigned='". (int)1 ."',approved='". (int)1 ."',status='". (int)1 ."'");
		}

		if($data['vendor_category']) {
			$category_id = implode(',', $data['vendor_category']);
		}

		if(!empty($category_id)) {
			$this->db->query("UPDATE ". DB_PREFIX ."lts_assigned_category SET category_id='". $category_id ."' WHERE vendor_id='". (int)$vendor_id ."'");
		}

	}

	public function  deleteAssignedCategory($vendor_id) {
		$category = $this->getVendorAssignedCategories($vendor_id);

		$categories = explode(',',$category['category_id']);

		foreach($categories as $category_id) {
			$this->deleteVendorCategory($category['vendor_id'], $category_id);
		}

		$this->db->query("DELETE FROM ". DB_PREFIX ."lts_assigned_category WHERE vendor_id = '". (int)$category['vendor_id'] ."'");
	}

	public function getVendorCategory($category_id) {
		$query = $this->db->query("SELECT category_id FROM ". DB_PREFIX ."lts_category WHERE category_id='". (int)$category_id ."' AND assigned='". (int)0 ."' ");
		return $query->row;
	}	

	public function getTotalVendorAssignById($vendor_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "lts_assigned_category WHERE vendor_id = '" . (int)$vendor_id . "'");

		return $query->row;
	}

	public function deleteVendorCategory($vendor_id, $category_id) {
		if(!empty($vendor_id) && !empty($category_id)) {
			$this->db->query("DELETE FROM ". DB_PREFIX ."lts_category WHERE vendor_id = '". (int)$vendor_id ."' AND category_id ='". (int)$category_id ."' AND assigned='". (int)1 ."'");
		}
	}

	public function getAssignedCategory($vendor_id) {
		$query = $this->db->query("SELECT * FROM ". DB_PREFIX ."lts_assigned_category WHERE vendor_id ='". (int)$vendor_id ."'");

		return  $query->row;
	}

	public function getTotalVendorAssignedCategory($vendor_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "lts_assigned_category WHERE vendor_id = '" . (int)$vendor_id . "'");

		return $query->row['total'];
	}

	public function getVendorAssignedCategories($vendor_id) {
		$query = $this->db->query("SELECT vendor_id, category_id FROM ". DB_PREFIX ."lts_assigned_category WHERE vendor_id='". (int)$vendor_id ."'");

		return $query->row;
	}

	public function getAssignedCategories() {
		$query = $this->db->query("SELECT * , (SELECT store_owner FROM ". DB_PREFIX ."lts_vendor WHERE vendor_id = lac.vendor_id ) AS store_owner FROM ". DB_PREFIX ."lts_assigned_category lac ");

		return $query->rows;
	}

	public function getVendorCategories() {
		$sql = $this->db->query("SELECT *, (SELECT store_owner FROM ". DB_PREFIX ."lts_vendor WHERE vendor_id = lc.vendor_id ) AS store_owner FROM ". DB_PREFIX ."lts_category lc  LEFT JOIN ". DB_PREFIX ."category_description cd ON(lc.category_id = cd.category_id) LEFT JOIN ". DB_PREFIX ."category c ON(lc.category_id = c.category_id) WHERE cd.language_id='". (int)$this->config->get('config_language_id') ."' AND lc.assigned='". (int)0 ."'");

		return $sql->rows;

	}
	
	public function approveStatus($category_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "lts_category SET approved = '" . 1 . "' WHERE category_id = '" . (int)$category_id . "'");
	}

	public function disapproveStatus($category_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "lts_category SET approved = '" . 0 . "' WHERE category_id = '" . (int)$category_id . "'");
	}
}