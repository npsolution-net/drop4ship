<?php 
class ModelVendorLtsExcel extends Model{
	
	public function getLastProductId(){ 
		$query=$this->db->query("SELECT product_id FROM " . DB_PREFIX . "product ORDER BY `product_id` DESC LIMIT 0,1");
		if($query->num_rows){	
			return $query->row['product_id'];
		} else {
			return NULL;	
		}
	} 

	public function getAllLanguage() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language");
		return $query->rows;
	}

	
	public function getVendorId() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "lts_vendor ORDER BY vendor_id ASC");
		return $query->rows;		
	}



	
	public function getExportSeoUrlData($table_name, $vendor_id, $language){

		if($vendor_id!='') {

			$query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "lts_product WHERE vendor_id= ".(int)$vendor_id."");

			$vendor_product_data = array_column($query->rows,"product_id");
		
			$query1 = $this->db->query("SELECT * FROM " . DB_PREFIX .$table_name." WHERE language_id=".(int)$language."");

			$data = array();

			foreach($query1->rows as $key => $value){
				$p = explode('product_id=', $value['query']);
					if(isset($p[1]) && $p[1] != '') {
						if(in_array($p[1], $vendor_product_data)){
							$data[$p[1]]=array('product_id'		=>	$p[1],
										'store_id'		=>	$value['store_id'],
										'keyword'		=>	$value['keyword']
								);	
						}
					}
			}
		} else {		

			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "".$table_name." WHERE language_id=".(int)$language."")->rows;

			$data=array();
			
			foreach($query as $key => $value){
				$p=explode('product_id=', $value['query']);
				if(isset($p[1]) && $p[1] != '') {
					$data[$p[1]] = array('product_id'		=>	$p[1],
									'store_id'		=>	$value['store_id'],
									'keyword'		=>	$value['keyword']
							);	
				}
			}
		}
		return $data;
	}

	public function getExportData($table_name, $vendor_id, $language){

			$andoperator='';

			$cmdWhere='';

			$lang = '';	


			if($table_name == 'seo_url'){

				$orderBy = 'seo_url_id';	
			}else {
				$orderBy='product_id';
			}

			if($vendor_id!=''){
				$filter_vendor_id="product_id IN (SELECT product_id FROM " . DB_PREFIX . "lts_vendor WHERE vendor_id= ".(int)$vendor_id." ) ";					
			} else {
				$filter_vendor_id='';			
			}
				if($table_name=='product_description' || $table_name=='product_attribute' || $table_name=='seo_url') {
					$lang =' language_id=' . $language; 
						if($vendor_id!='') {
							$andoperator=' AND ';	
						} else {
							$andoperator='';
						}
					if(isset($vendor_id) or ($lang != '')) {
						$cmdWhere=' WHERE ';	
					}
				} else {
					if($vendor_id!='') {
						$cmdWhere=' WHERE ';	
					}			
			}

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "".$table_name . $cmdWhere . $filter_vendor_id . $andoperator . $lang ." ORDER BY ".$orderBy." ASC ");
		

				
			if ($query->num_rows > 0) {
				return $query->rows;
			} else {
				return null;	
			}
		} 
		
	public function getProductDownloads($product_id) {
		$product_download_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
		$downloid = "";
		foreach ($query->rows as $result) {
			if($downloid == '') {
			$product_download_data['download_id'] = $result['download_id'];
			$downloid = $result['download_id'];
			} else {
			$product_download_data['download_id'] = $downloid.','.$result['download_id'];
			$downloid = $downloid.','.$result['download_id'];			
			}
		}

		return $product_download_data;
	}
	
	public function getProductCategories($product_id) {
		$product_category_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		$catid = "";
		foreach ($query->rows as $result) {
			if($catid == '') {
			$product_category_data['category_id'] = $result['category_id'];
			$catid = $result['category_id'];
			} else {
			$product_category_data['category_id'] = $catid.','.$result['category_id'];
			$catid = $catid.','.$result['category_id'];
			}
		}
		return $product_category_data;
	}
	
	public function getProductFilter($product_id) {
		$product_filter_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
		$filterid = "";
		foreach ($query->rows as $result) {
			if($filterid == '') {
			$product_filter_data['filter_id'] = $result['filter_id'];
			$filterid = $result['filter_id'];
			} else {
			$product_filter_data['filter_id'] = $filterid.','.$result['filter_id'];
			$filterid = $filterid.','.$result['filter_id'];
			}
		}
		return $product_filter_data;
	}
	
	
	public function getProductRelated($product_id) {
		$product_related_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
		$productRelatedid = "";
		foreach ($query->rows as $result) {
			if($productRelatedid == '') {
			$product_related_data['related_id'] = $result['related_id'];
			$productRelatedid = $result['related_id'];
			} else {
			$product_related_data['related_id'] = $productRelatedid.','.$result['related_id'];
			$productRelatedid = $productRelatedid.','.$result['related_id'];
			}
		}
		return $product_related_data;
	}
	
	public function getProductToStore($product_id) {
		$product_store_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");
		$productStoreId = "";
		foreach ($query->rows as $result) {
			if($productStoreId == '') {
			$product_store_data['store_id'] = $result['store_id'];
			$productStoreId = $result['store_id'];
			} else {
			$product_store_data['store_id'] = $productStoreId.','.$result['store_id'];
			$productStoreId = $productStoreId.','.$result['store_id'];
			}
		}
		return $product_store_data;
	}


		public function getTableName($table_name) {
			
			$query = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . $this->db->escape($table_name));
			if ($query->num_rows) {
				$table=array();
				$table_name=array();
				foreach($query->rows as $key =>$value) {
					if($value['Field'] != 'viewed') {
						$table[]=$value['Field'];	
					}
				}
				$excelCell=array('AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ');
				$Cell=array_slice(array_merge(range('A','Z'), $excelCell),0,count($table),true);	
				return array_combine($Cell, $table);
			} else {
				return null;	
			}
		}

		public function getOptionType($id){
				$query = $this->db->query("SELECT type AS option_type FROM " . DB_PREFIX . "option WHERE option_id=". (int)$id);

			if ($query->num_rows > 0) {
			return $query->row['option_type'];
		} else {
			return null;	
		}
			
		}
		
		public function getOptionName($id){
			$query = $this->db->query("SELECT name AS option_type FROM " . DB_PREFIX . "option_description WHERE option_id=". (int)$id);

			if ($query->num_rows > 0) {
				return $query->row['option_type'];
			} else {
				return null;	
			}
		}
		
		public function getAttributeName($id){
			$query = $this->db->query("SELECT name AS attribute_name FROM " . DB_PREFIX . "attribute_description WHERE attribute_id=". (int)$id);

				if ($query->num_rows > 0) {
				return $query->row['attribute_name'];
			} else {
				return null;	
			}
				
		}
		
		public function getAttributeId($name, $language_id){

			$query = $this->db->query("SELECT attribute_id AS attribute FROM " . DB_PREFIX . "attribute_description WHERE name= '". trim($name)."' AND language_id= ".(int)$language_id);

			if ($query->num_rows > 0) {
				return $query->row['attribute'];
			} else {
				return null;	
			}
			
		}
		public function getOptionValueName($optId, $option_value_id, $language_id){

			$query = $this->db->query("SELECT name AS optvaluename FROM " . DB_PREFIX . "option_value_description WHERE option_id=". (int)$optId.' AND option_value_id = '.(int)$option_value_id.' AND language_id = '.(int)$language_id);

			if ($query->num_rows > 0) {
				return $query->row['optvaluename'];
			} else {
				return null;	
			}	
		}
		
		public function getTaxClass($tax_class_id){
			$query = $this->db->query("SELECT title AS taxtitle FROM " . DB_PREFIX . "tax_class WHERE tax_class_id=". $tax_class_id);

			if ($query->num_rows > 0) {
				return $query->row['taxtitle'];
			} else {
				return null;	
			}	
		}
		
		public function getStockStatus1($stock_status_id){

			$query = $this->db->query("SELECT name AS stockstatus FROM " . DB_PREFIX . "stock_status WHERE stock_status_id=". (int)$stock_status_id);

			if ($query->num_rows > 0) {
				return $query->row['stockstatus'];
			} else {
				return null;	
			}	
		}
		
		
		public function getManufacturerName($manufacturer_id){

			$query = $this->db->query("SELECT name AS manufacturerName FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id=". (int)$manufacturer_id);

			if ($query->num_rows > 0) {
				return $query->row['manufacturerName'];
			} else {
				return null;	
			}	
		}
		
		public function getWeightClassName($weight_class_id, $language_id){

			$query = $this->db->query("SELECT title AS titleName FROM " . DB_PREFIX . "weight_class_description WHERE weight_class_id=". (int)$weight_class_id." AND language_id= ".(int)$language_id);

			if ($query->num_rows > 0) {
				return $query->row['titleName'];
			} else {
				return null;	
			}	
		}		
		public function getlengthClassName($length_class_id, $language_id){

			$query = $this->db->query("SELECT title AS titleName FROM " . DB_PREFIX . "length_class_description WHERE length_class_id=". (int)$length_class_id." AND language_id= ".(int)$language_id);

			if ($query->num_rows > 0) {
				return $query->row['titleName'];
			} else {
				return null;	
			}	
		}		
		public function getCustomerGroupName($customer_group_id, $language_id){
				
			$query = $this->db->query("SELECT name AS titleName FROM " . DB_PREFIX . "customer_group_description WHERE customer_group_id=". (int)$customer_group_id." AND language_id= ".(int)$language_id);

			if ($query->num_rows > 0) {
				return $query->row['titleName'];
			} else {
				return null;	
			}	
		}
		
		
		public function getProductDownloadsName($download_id, $language_id){

				$query = $this->db->query("SELECT name AS titleName FROM " . DB_PREFIX . "download_description WHERE download_id=". (int)$download_id." AND language_id= ".(int)$language_id);

			if ($query->num_rows > 0) {
				return $query->row['titleName'];
			} else {
				return null;	
			}	
		}
		
		
		public function getStoreById($store_d){

				$query = $this->db->query("SELECT name AS titleName FROM " . DB_PREFIX . "store WHERE store_id=". (int)$store_d);

			if ($query->num_rows > 0) {
				return $query->row['titleName'];
			} else {
				return null;	
			}	
		}
				
		public function getLayoutName($layout_id){

			$query = $this->db->query("SELECT name AS titleName FROM " . DB_PREFIX . "layout WHERE layout_id=". (int)$layout_id);

			if ($query->num_rows > 0) {
				return $query->row['titleName'];
			} else {
				return null;	
			}	
		}		
		
		public function getRecurringName($recurring_id , $language_id){

			$query = $this->db->query("SELECT name AS titleName FROM " . DB_PREFIX . "recurring_description WHERE recurring_id=". (int)$recurring_id." AND language_id= ".(int)$language_id);

			if ($query->num_rows > 0) {
				return $query->row['titleName'];
			} else {
				return null;	
			}	
		}
		
		public function getRecurringId($recurring , $language_id){

			$query = $this->db->query("SELECT recurring_id FROM " . DB_PREFIX . "recurring_description WHERE name='". $this->db->escape($recurring)."' AND language_id= ".(int)$language_id);

			if ($query->num_rows > 0) {
				return $query->row['recurring_id'];
			} else {
				return '';	
			}	
		}		
		
		public function getCustomerGroupId($customer_group_name , $language_id){

			$query = $this->db->query("SELECT customer_group_id FROM " . DB_PREFIX . "customer_group_description WHERE name='". $this->db->escape($customer_group_name)."' AND language_id= ".(int)$language_id);

			if ($query->num_rows > 0) {
				return $query->row['customer_group_id'];
			} else {
				return '';	
			}	
		}
		public function getStoreId($storeName){

			$query = $this->db->query("SELECT store_id FROM " . DB_PREFIX . "store WHERE name='". trim($storeName)."'");
			if ($query->num_rows > 0) {
				return $query->row['store_id'];
			}	

			return '';
		}		
		
		public function getCategoryId($name){

				$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category_description WHERE name='". $this->db->escape($name)."'");

			if ($query->num_rows > 0) {
				return $query->row['category_id'];
			} else {
				return '';	
			}	
		}
				
		public function getDownloadId($name, $language_id){

				$query = $this->db->query("SELECT download_id FROM " . DB_PREFIX . "download_description WHERE name='". $this->db->escape($name)."' AND language_id=".(int)$language_id);

			if ($query->num_rows > 0) {
				return $query->row['download_id'];
			} else {
				return '';	
			}	
		}		
		
	
		
		public function getFilterId($name, $language_id, $gpId){

				$query = $this->db->query("SELECT filter_id FROM " . DB_PREFIX . "filter_description WHERE name='". trim($this->db->escape($name))."' AND language_id=".(int)$language_id." AND filter_group_id=".$this->db->escape($gpId));

			if ($query->num_rows > 0) {
				return $query->row['filter_id'];
			} else {
				return '';	
			}	
		}			
		
		public function getFilterGroupId($name, $language_id){
				$query = $this->db->query("SELECT filter_group_id FROM " . DB_PREFIX . "filter_group_description WHERE name='".trim($this->db->escape($name))."' AND language_id=".(int)$language_id);

			if ($query->num_rows > 0) {
				return $query->row['filter_group_id'];
			} else {
				return '';	
			}	
		}	

		public function getRelatedId($name, $language_id){

				$query = $this->db->query("SELECT filter_id FROM " . DB_PREFIX . "filter_description WHERE name='". $this->db->escape($name)."' AND language_id=".(int)$language_id);

			if ($query->num_rows > 0) {
				return $query->row['filter_id'];
			} else {
				return '';	
			}	
		}	

		public function getProductIdByName($name, $language_id){

				$query = $this->db->query("SELECT  	product_id FROM " . DB_PREFIX . "product_description WHERE name='". $this->db->escape($name)."' AND language_id=".(int)$language_id);

			if ($query->num_rows > 0) {
				return $query->row['product_id'];
			} else {
				return '';	
			}	
		}
		
		
		public function getStock_status_id($stock_status){

			$query = $this->db->query("SELECT stock_status_id  FROM " . DB_PREFIX . "stock_status WHERE  name='". $this->db->escape($stock_status)."'");
			 
			if ($query->num_rows > 0) {
				return $query->row['stock_status_id'];
			} else {
				return null;	
			}	
		}
		
		public function getManufacturerId($manufacturer){

			$query = $this->db->query("SELECT manufacturer_id  FROM " . DB_PREFIX . "manufacturer WHERE  name='". $this->db->escape($manufacturer)."'");
			 
			
			if ($query->num_rows > 0) {
				return $query->row['manufacturer_id'];
			} else {
				return null;	
			}	
		}
		public function getTax_class_id($tex_class){

			$query = $this->db->query("SELECT tax_class_id  FROM " . DB_PREFIX . "tax_class WHERE  title='". $this->db->escape($tex_class)."'");
			 
			if ($query->num_rows > 0) {
				return $query->row['tax_class_id'];
			} else {
				return null;	
			}	
		}
		public function getWeightClassId($weight_class){

			$query = $this->db->query("SELECT weight_class_id  FROM " . DB_PREFIX . "weight_class_description WHERE  title='". $this->db->escape($weight_class)."'");
			 
			 if ($query->num_rows > 0) {
				return $query->row['weight_class_id'];
			} else {
				return null;	
			}	
		}
		public function getLengthClassId($length_class){

			$query = $this->db->query("SELECT length_class_id  FROM " . DB_PREFIX . "length_class_description WHERE  title='".$this->db->escape($length_class)."'");
			 
			if ($query->num_rows > 0) {
				return $query->row['length_class_id'];
			} else {
				return null;	
			}	
		}	
		

		public function getFilter($filter_id) {
			$query = $this->db->query("SELECT *, (SELECT name FROM " . DB_PREFIX . "filter_group_description fgd WHERE f.filter_group_id = fgd.filter_group_id AND fgd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS `group` FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_description fd ON (f.filter_id = fd.filter_id) WHERE f.filter_id = '" . (int)$filter_id . "' AND fd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

			return $query->row;
		}


		public function getProduct($product_id) {
			$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

			return $query->row;
		}

		public function getCategory($category_id) {
			$query = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id AND cp.category_id != cp.path_id) WHERE cp.category_id = c.category_id AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.category_id) AS path FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (c.category_id = cd2.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
			return $query->row;
		}

		public function getInProductId($product_id){

			$query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE product_id='". (int)$product_id."'");

			if ($query->num_rows > 0) {
				return $product_id;
			} else {
				return NULL;	
			}	
		}	
		
}

if (! function_exists('array_column')) {
    function array_column(array $input, $column_key, $index_Key = null) {
        $array = array();
        foreach ($input as $value) {
            if ( !array_key_exists($column_key, $value)) {
                trigger_error("Key \"$column_key\" does not exist in array");
                return false;
            }
            if (is_null($index_Key)) {
                $array[] = $value[$column_key];
            }
            else {
                if ( !array_key_exists($index_Key, $value)) {
                    trigger_error("Key \"$index_Key\" does not exist in array");
                    return false;
                }
                if ( ! is_scalar($value[$index_Key])) {
                    trigger_error("Key \"$index_Key\" does not contain scalar value");
                    return false;
                }
                $array[$value[$index_Key]] = $value[$column_key];
            }
        }
        return $array;
    }
}
?>