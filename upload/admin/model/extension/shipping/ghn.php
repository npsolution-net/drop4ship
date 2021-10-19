<?php
class ModelExtensionShippingGhn extends Model {
	public function install() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "zone_province` (
			`province_id` int(11) NOT NULL,
			`name` varchar(255) NOT NULL,  
			`code` varchar(255) NOT NULL,
			`country_id` int(11) NOT NULL,
			PRIMARY KEY (`province_id`),
			UNIQUE (`code`,`country_id`),
			FOREIGN KEY (`country_id`) REFERENCES `" . DB_PREFIX . "country`(`country_id`)
		   	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "zone_district` (
			`district_id` int(11) NOT NULL,
		   	`name` varchar(255) NOT NULL,  
			`code` varchar(255) NOT NULL,
			`province_id` int(11) NOT NULL,
			PRIMARY KEY (`district_id`),
			UNIQUE (`code`,`province_id`),
			FOREIGN KEY (`province_id`) REFERENCES `" . DB_PREFIX . "zone_province`(`province_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "zone_ward` (
			`ward_id` int(11) NOT NULL,
			`name` varchar(255) NOT NULL,  
			`district_id` int(11) NOT NULL,
			UNIQUE (`ward_id`,`name`,`district_id`),
			FOREIGN KEY (`district_id`) REFERENCES `" . DB_PREFIX . "district`(`district_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");	

		$this->updateProvinces();
	}
	public function uninstall() {
		// $this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."zone_province`");
		// $this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."zone_district`");
		// $this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."zone_ward`");
	}

	public function updateProvinces(){
		// $this->addProvinces(230);
		// $this->addDistricts();
		// $this->addWards();
	}

	public function addProvinces($country_id){
		$api = $this->config->get("ghn_shipping_api");

		$params = array(

		);

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $api['develop']['url'] . "/master-data/province");
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array( 
			"Content-Type: application/json",
			"Token: ". $api['develop']['token'],
		));		
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));

		$provinces = json_decode(curl_exec($curl));
		curl_close($curl);	

		if($provinces && $provinces->code == 200){		
			$data = array();		
			foreach($provinces->data as $province){
				$data[] = "(" . (int)$province->ProvinceID . ",'" . $province->ProvinceName . "', '" . $province->Code ."', " . (int)$country_id . ")";				
			}

			$this->db->query("INSERT INTO `". DB_PREFIX ."zone_province` VALUES " . implode(",", $data));
		}		
	}

	public function addDistricts(){
		$provinces = $this->db->query("SELECT province_id FROM " . DB_PREFIX . "zone_province");

		if(count($provinces->rows) > 0){
			$data = array();
			foreach($provinces->rows as $province){
				$districts = $this->getDistrictsByProvince($province['province_id']);	

				if($districts){
					foreach($districts as $district){
						$data[] = "(" . (int)$district->DistrictID . ",'" . $district->DistrictName . "', '" . $district->Code ."', " . (int)$province['province_id'] . ")";				
					}
				}
			}
			$this->db->query("INSERT INTO `". DB_PREFIX ."zone_district` VALUES " . implode(",", $data));		
		}		
	}

	private function getDistrictsByProvince($province_id){
		$api = $this->config->get("ghn_shipping_api");

		$params = array(
			"province_id" => (int)$province_id
		);

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $api['develop']['url'] . "/master-data/district");
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array( 
			"Content-Type: application/json",
			"Token: ". $api['develop']['token'],
		));		
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));

		$districts = json_decode(curl_exec($curl));
		curl_close($curl);

		if($districts && $districts->code == 200)
			return $districts->data;

		return null;
	}

	public function addWards(){
		$districts = $this->db->query("SELECT district_id FROM " . DB_PREFIX . "zone_district");
		$count = 0;

		if(count($districts->rows) > 0){
			foreach($districts->rows as $district){
				$wards = $this->getWardsByDistrict($district['district_id']);	

				if($wards){
					$data = array();

					foreach($wards as $ward){						
						$item = "(" . (int)$ward->WardCode . ', "' . $ward->WardName . '", ' . (int)$district['district_id'] . ")";				
						if($count >300){
							var_dump($item);
						}
						$data[] = $item;
						$count += 1;
					}
					try{
						$this->db->query("INSERT INTO `". DB_PREFIX ."zone_ward` VALUES " . implode(",", $data));		
					} catch(Exception $e){
						echo 'Caught exception: ',  $e->getMessage(), "\n";
					}
				}
			}
		}		
	}

	private function getWardsByDistrict($district_id){
		$api = $this->config->get("ghn_shipping_api");

		$params = array(
			"district_id" => (int)$district_id
		);

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $api['develop']['url'] . "/master-data/ward");
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array( 
			"Content-Type: application/json",
			"Token: ". $api['develop']['token'],
		));		
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));

		$wards = json_decode(curl_exec($curl));
		curl_close($curl);
				
		if($wards && $wards->code == 200)
			return $wards->data;

		return null;
	}
}