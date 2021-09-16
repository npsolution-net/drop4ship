<?php
class ModelVendorLtsCommissionReport extends Model {
	public function addCommissionReport($order_id,$product) {
		$query = $this->db->query("SELECT product_id, vendor_id FROM ". DB_PREFIX ."lts_vendor_product WHERE product_id='". (int)$product['product_id'] ."'");
		if($query->row) {
			$sql1 = $this->db->query("SELECT category_id FROM ". DB_PREFIX ."lts_vendor_product_to_category WHERE product_id ='". (int)$product['product_id'] ."'");

			if(!empty($sql1->row['category_id'])) {
				
				$sql2 = $this->db->query("SELECT * FROM ". DB_PREFIX ."lts_vendor_commission WHERE category_id='". (int)$sql1->row['category_id'] ."'");

				if($sql2->row) {
					
					if($sql2->row['commission_type'] == 'p') {
						$commission = $this->db->escape($product['quantity']) * $product['price'] * $sql2->row['commission'] / 100;
					} else {
						$commission = $this->db->escape($product['quantity']) * $sql2->row['commission'];    
					}

					$this->db->query("INSERT INTO ". DB_PREFIX ."lts_vendor_commission_report SET vendor_id='". (int)$query->row['vendor_id'] ."', product_id='". (int)$product['product_id'] ."', name='". $this->db->escape($product['name']) ."', model='". $this->db->escape($product['model'])  ."', quantity='". (int)$product['quantity'] ."', price='". (float)$product['price'] ."', commission_type= '". $sql2->row['commission_type'] ."', commission='". (float)$commission ."', status = '0',  date_added= NOW()");
				}
			}
		}
	}

}          