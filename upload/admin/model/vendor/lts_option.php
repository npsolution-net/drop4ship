<?php
class ModelVendorLtsOption extends Model {
	public function addOptionMapping($data) {

		if($data['options']) {
			$options = implode(',', $data['options']);
		}

		$this->db->query("DELETE FROM ". DB_PREFIX ."lts_option_mapping WHERE category_id='". (int)$data['category_id'] ."'");
		
		$this->db->query("INSERT INTO ". DB_PREFIX ."lts_option_mapping SET category_id='". (int)$data['category_id'] ."', option='". $this->db->escape($options) ."'");

	}
    
	public function editOptionMapping($option_mapping_id, $data) {
					
		if($data['options']) {
			$options = implode(',', $data['options']);
		}
		$this->db->query("DELETE FROM ". DB_PREFIX ."lts_option_mapping WHERE category_id='". (int)$data['category_id'] ."'");

		$this->db->query("UPDATE ". DB_PREFIX ."lts_option_mapping SET option='". $options ."' WHERE option_mapping_id='". (int)$option_mapping_id ."'");
	}

	public function deleteOptionMapping($option_mapping_id) {
		$this->db->query("DELETE FROM ". DB_PREFIX ."lts_option_mapping WHERE option_mapping_id='". (int)$option_mapping_id ."'");
	}

	public function getOptionMapping() {
		$sql = "SELECT * FROM ". DB_PREFIX ."lts_option_mapping";

		$query = $this->db->query($sql);


		return $query->rows;
	}

	public function getOptionMappingById($option_mapping_id) {
		$sql = "SELECT * FROM ". DB_PREFIX ."lts_option_mapping WHERE option_mapping_id = '". (int)$option_mapping_id ."' ";

		$query = $this->db->query($sql);

		return $query->row;
	}
	public function getOptionMappingByCategoryId($category_id) {
		$sql = "SELECT * FROM ". DB_PREFIX ."lts_option_mapping WHERE category_id = '". (int)$category_id ."' ";

		$query = $this->db->query($sql);

		return $query->row;
	}


	public function getTotalOptionMappingById($category_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "lts_option_mapping WHERE category_id = '" . (int)$category_id . "'");

		return $query->row['total'];
	}
}
