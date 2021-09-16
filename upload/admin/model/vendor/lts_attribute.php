<?php
class ModelVendorLtsAttribute extends Model {
	public function addAttributeMapping($data) {
		if($data['attributes']) {
			$attributes = implode(',', $data['attributes']);
		}
		
		$this->db->query("INSERT INTO ". DB_PREFIX ."lts_attribute_mapping SET category_id='". (int)$data['category_id'] ."', attribute='". $this->db->escape($attributes) ."'");
	}

	public function editAttributeMapping($attribute_mapping_id, $data) {
		if($data['attributes']) {
			$attributes = implode(',', $data['attributes']);
		}

		$this->db->query("DELETE FROM ". DB_PREFIX ."lts_attribute_mapping WHERE category_id='". (int)$data['category_id'] ."'");

		$this->db->query("UPDATE ". DB_PREFIX ."lts_attribute_mapping SET category_id = '". (int)$data['category_id'] ."', attribute='". $attributes ."' WHERE attribute_mapping_id='". (int)$attribute_mapping_id ."'");
	}

	public function getAttributeMapping() {
		$sql = "SELECT * FROM ". DB_PREFIX ."lts_attribute_mapping";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function deleteAttributeMapping($attribute_mapping_id) {
		$this->db->query("DELETE FROM ". DB_PREFIX ."lts_attribute_mapping WHERE attribute_mapping_id='". (int)$attribute_mapping_id ."'");
	}

	public function getAttributeMappingById($attribute_mapping_id) {
		$sql = "SELECT * FROM ". DB_PREFIX ."lts_attribute_mapping WHERE attribute_mapping_id = '". (int)$attribute_mapping_id ."'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getTotalAttributeMappingById($attribute_mapping_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "lts_attribute_mapping WHERE attribute_mapping_id = '" . (int)$attribute_mapping_id . "'");

		return $query->row['total'];
	}

	public function getAttibuteMappingByCategoryId($category_id) {
		$sql = "SELECT * FROM ". DB_PREFIX ."lts_attribute_mapping WHERE category_id = '". (int)$category_id ."' ";

		$query = $this->db->query($sql);

		return $query->row;
	}
}