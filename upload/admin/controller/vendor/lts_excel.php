<?php 
class ControllerVendorLtsExcel extends Controller{
	
	public function index(){

		$this->load->language('vendor/lts_excel');
		$this->load->model('vendor/lts_excel');
		$this->document->setTitle($this->language->get('heading_title'));
	    $this->getForm();
	}


	protected function getForm() {
		$url = '';
	
		$data['user_token'] = $this->session->data['user_token'];
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];

			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}
        $data['max_time'] = ini_get("max_execution_time")/100;
		
		$data['memory_limit'] = ini_get("memory_limit");

		$data['export'] = $this->url->link('vendor/lts_excel/export', 'user_token=' . $this->session->data['user_token'], true);	

		$lang=$this->model_vendor_lts_excel->getAllLanguage();

		if(!empty($lang)){
			
			foreach($lang as $langKey => $langValue){
				$data['languages'][] = array(
				'language_id'	=>	$langValue['language_id'],
				'name'			=>	$langValue['name'].''.(($langValue['code'] == $this->config->get('config_language')) ? $this->language->get('text_default') : ''),
				'code'			=>	$langValue['code'],
				'default'		=>	(($langValue['code'] == $this->config->get('config_language')) ? $this->language->get('text_default') : null)
				);
					
			}
		}

		$data['next_product_id'] = 1;
		$data['vendors']=$this->model_vendor_lts_excel->getVendorId();
		$last_product_id = (int)$this->model_vendor_lts_excel->getLastProductId();
		if(isset($last_product_id)) {
			$data['next_product_id'] = $last_product_id + 1;
		}


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('vendor/lts_excel', $data));		
	}
	public function export(){
			
		$this->load->language('vendor/lts_excel');
		$this->load->model('vendor/lts_excel');
		$this->load->model('catalog/product');
		if($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateForm()) {

			// print_r($this->request->post);



			// die;

			// die;
				$cwd = getcwd();
				$dir = 'library/vendor';
				chdir( DIR_SYSTEM.$dir );
				require_once( 'PHPExcel/IOFactory.php' );
				chdir( $cwd );	
				$fileName 	= 'ltsmultivendor';
				$vendor_id = $this->request->post['vendor_id'];
				$language_id=$this->request->post['language'];
				$objPHPexcel = new PHPExcel();

				$objPHPexcel->getProperties()->setCreator("LETSCMS")->setLastModifiedBy("LETSCMS")->setTitle("LETSCMS")->setSubject("LETSCMS")->setDescription("LETSCMS")->setKeywords("LETSCMS")->setCategory("LETSCMS");
		 
				$this->exportExcelData($objPHPexcel,'General','product_description',$vendor_id, $language_id, 0);	
			
				$objPHPexcel->createSheet(); 
				$this->exportExcelData($objPHPexcel,'Data','product',$vendor_id, $language_id, 1);

				$objPHPexcel->createSheet();
				$this->exportLinksData($objPHPexcel,'Links',$vendor_id, $language_id, 2);

				$objPHPexcel->createSheet();
				$this->exportExcelData($objPHPexcel,'Attribute','product_attribute',$vendor_id, $language_id, 3);


				$objPHPexcel->createSheet();
				$this->exportExcelData($objPHPexcel,'Recurring','product_recurring',$vendor_id, $language_id, 4);
				
				$objPHPexcel->createSheet();
				$this->exportExcelData($objPHPexcel,'Discount','product_discount', $vendor_id, $language_id, 5);

				$objPHPexcel->createSheet();
				$this->exportExcelData($objPHPexcel,'Special','product_special',$vendor_id, $language_id, 6);
			
				$objPHPexcel->createSheet();	
				$this->exportExcelData($objPHPexcel,'Rewardpoints','product_reward',$vendor_id, $language_id, 7);

				$objPHPexcel->createSheet();	
				$this->exportExcelData($objPHPexcel,'Image','product_image',$vendor_id, $language_id, 8);
			
				 $objPHPexcel->createSheet();
				$this->exportExcelData($objPHPexcel,'SEO','seo_url',$vendor_id, $language_id, 9);
				
				$objPHPexcel->createSheet();	
			 	$this->exportExcelData($objPHPexcel,'Design','product_to_layout',$vendor_id, $language_id, 10); 
				
			 	$objPHPexcel->createSheet(); 
			 	$this->exportExcelData($objPHPexcel,'ProductOption','product_option',$vendor_id, $language_id, 11);  
			 		
			 	$objPHPexcel->createSheet(); 
		 		$this->exportExcelData($objPHPexcel,'ProductOptionValue','product_option_value',$vendor_id, $language_id, 12);    
		 	
				$objPHPexcel->setActiveSheetIndex(0);
				 $objWriter = PHPExcel_IOFactory::createWriter($objPHPexcel, 'Excel2007');
				 $objWriter->save(DIR_SYSTEM.'/library/vendor/export/'.$fileName.'.xlsx'); 
				 $attachment_location = DIR_SYSTEM."/library/vendor/export/".$fileName.".xlsx";


				if (file_exists($attachment_location)) {
					header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
					header("Cache-Control: public");
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					header("Content-Transfer-Encoding: Binary");
					header("Content-Length:".filesize($attachment_location));
					header("Content-Disposition: attachment; filename=ltsmultivendor.xlsx");
					readfile($attachment_location);
					$this->session->data['success'] = $this->language->get('text_success');
					die();
				} else {
					die("File not found.");
				}
			}
		}
		
		public function exportExcelData($objPHPexcel, $tab_name, $table_name, $vendor_id, $language, $sheetIndex){
	
		$objPHPexcel->setActiveSheetIndex($sheetIndex);
		$column_name=array();
		$export_datas=array();

		if($table_name == 'seo_url'){
			$export_datas = $this->model_vendor_lts_excel->getExportSeoUrlData($table_name, $vendor_id, $language);		
		} else {
			$export_datas = $this->model_vendor_lts_excel->getExportData($table_name, $vendor_id, $language);			
		}

		
		if($table_name=='product') {
		$export_data = $export_datas;
		$column_name = array();
		$export_datas = array();
		if(!empty($export_data)){

			foreach($export_data as $key => $value){

			$tax_class=$this->model_vendor_lts_excel->getTaxClass($value['tax_class_id']);

			$stockstatus=$this->model_vendor_lts_excel->getStockStatus1($value['stock_status_id']);

			$manufacturer_name=$this->model_vendor_lts_excel->getManufacturerName($value['manufacturer_id']);

			$weight_class=$this->model_vendor_lts_excel->getWeightClassName($value['weight_class_id'],$this->request->post['language']);

			$length_class=$this->model_vendor_lts_excel->getlengthClassName($value['length_class_id'],$this->request->post['language']);

			$server=explode('admin/',HTTPS_SERVER);
			
			$export_datas[]=array(
						'product_id'		=>	$value['product_id'],
						'model'				=>	$value['model'],
						'sku'				=>	$value['sku'],
						'upc'				=>	$value['upc'],
						'ean'				=>	$value['ean'],
						'jan'				=>	$value['jan'],
						'isbn'				=>	$value['isbn'],
						'mpn'				=>	$value['mpn'],
						'location'			=>	$value['location'],
						'quantity'			=>	$value['quantity'],
						'stock_status'		=>	$stockstatus,
						'image'				=>	$value['image']? $server[0].'image/'.$value['image']:'',
						'manufacturer'	    =>	$manufacturer_name,
						'shipping'			=>	(int)$value['shipping']?'Yes':'No',
						'price'				=>	$value['price'],
						'points'			=>	$value['points'],
						'tax_class'			=>	$tax_class,
						'date_available'	=>	$value['date_available'],
						'weight'			=>	$value['weight'],
						'weight_class'		=>	$weight_class,
						'length'			=>	$value['length'],
						'width'				=>	$value['width'],
						'height'			=>	$value['height'],
						'length_class'		=>	$length_class,
						'subtract'			=>	(int)$value['subtract']?'Yes':'No',
						'minimum'			=>	$value['minimum'],
						'sort_order'		=>	$value['sort_order'],
						'status'			=>	(int)$value['status'] ? 'Enabled' : 'Disabled',
						);
			}
		}
		$column_name=array('A'=>'product_id','B'=>'model','C'=>'sku','D'=>'upc','E'=>'ean','F'=>'jan','G'=>'isbn','H'=>'mpn','I'=>'location','J'=>'quantity','K'=>'stock_status','L'=>'image','M'=>'manufacturer','N'=>'shipping','O'=>'price','P'=>'points','Q'=>'tax_class','R'=>'date_available','S'=>'weight','T'=>'weight_class','U'=>'length','V'=>'width','W'=>'height','X'=>'length_class','Y'=>'subtract','Z'=>'minimum','AA'=>'sort_order','AB'=>'status',);

		}


		if($table_name=='product_description') {
			$export_data = $export_datas;
			$column_name = array();
			$export_datas = array();

			if(!empty($export_data)) {

				foreach($export_data as $key => $value){

					$export_datas[]=array('product_id'=>$value['product_id'],
							'name'=>htmlspecialchars_decode($value['name']),
							'description'=>htmlspecialchars_decode($value['description']),
							'tag'=>htmlspecialchars_decode($value['tag']),
							'meta_title'=>htmlspecialchars_decode($value['meta_title']),
							'meta_description'=>htmlspecialchars_decode($value['meta_description']),
							'meta_keyword'=>htmlspecialchars_decode($value['meta_keyword']),
							);
				}
			}
			
			$column_name=array('A'=>'product_id','B'=>'name','C'=>'description','D'=>'tag','E'=>'meta_title','F'=>'meta_description','G'=>'meta_keyword');
		}

		if($table_name=='product_attribute') {
			$export_data = $export_datas;
			$column_name = array();
			$export_datas = array();
			if(!empty($export_data)){
				foreach($export_data as $key=>$value){
					$attribute_name=$this->model_vendor_lts_excel->getAttributeName($value['attribute_id']);	
					$export_datas[]=array('product_id'=>$value['product_id'],
						'attribute'=>htmlspecialchars_decode($attribute_name),
						'text'=>htmlspecialchars_decode($value['text'])
					);
				}
			}

			$column_name=array('A'=>'product_id','B'=>'attribute','C'=>'text');
		}

		if($table_name=='product_recurring') {
			$export_data = $export_datas;
			$column_name = array();
			$export_datas = array();
		
			if(!empty($export_data)) {
				foreach($export_data as $key=> $value){
					$customerGroupName=$this->model_vendor_lts_excel->getCustomerGroupName($value['customer_group_id'],$this->request->post['language']);
			
					$recurring=$this->model_vendor_lts_excel->getRecurringName($value['recurring_id'],$this->request->post['language']);
					
					$export_datas[]=array(
						'product_id'					=>	$value['product_id'],
						'recurring'					=>	$recurring,
						'customer_group'				=>	$customerGroupName
					);
				}
			}
		
			$column_name=array('A'=>'product_id','B'=>'recurring','C'=>'customer_group');
		}

		if($table_name=='product_discount') {
			$export_data = $export_datas;
			$column_name = array();
			$export_datas = array();
		
			if(!empty($export_data)){
				foreach($export_data as $key => $value){
					$customerGroupName=$this->model_vendor_lts_excel->getCustomerGroupName($value['customer_group_id'],$this->request->post['language']);
					$export_datas[]=array(
								'product_discount_id'			=>	$value['product_discount_id'],
								'product_id'					=>	$value['product_id'],
								'customer_group'				=>	$customerGroupName,
								'quantity'						=>	$value['quantity'],
								'priority'						=>	$value['priority'],
								'price'							=>	$value['price'],
								'date_start'					=>	$value['date_start'],
								'date_end'						=>	$value['date_end']
							);
				}
			}

			$column_name=array('A'=>'product_discount_id','B'=>'product_id','C'=>'customer_group','D'=>'quantity','E'=>'priority','F'=>'price','G'=>'date_start','H'=>'date_end');
		}

		if($table_name=='product_special') {
			$export_data = $export_datas;
			$column_name = array();
			$export_datas = array();
			if(!empty($export_data)) {
				foreach($export_data as $kv=>$value) {
					$customerGroupName=$this->model_vendor_lts_excel->getCustomerGroupName($value['customer_group_id'],$this->request->post['language']);
					$export_datas[]=array(
							'product_special_id'			=>	$value['product_special_id'],
							'product_id'					=>	$value['product_id'],
							'customer_group'				=>	$customerGroupName,
							'priority'						=>	$value['priority'],
							'price'							=>	$value['price'],
							'date_start'					=>	$value['date_start'],
							'date_end'						=>	$value['date_end']
						);
				}
			}
			
			$column_name=array('A'=>'product_special_id','B'=>'product_id','C'=>'customer_group','D'=>'priority','E'=>'price','F'=>'date_start','G'=>'date_end');
		}

		if($table_name=='product_reward') {
			$export_data = $export_datas;
			$column_name = array();
			$export_datas = array();
			if(!empty($export_data)){
				foreach($export_data as $key => $value){
					$customerGroupName=$this->model_vendor_lts_excel->getCustomerGroupName($value['customer_group_id'],$this->request->post['language']);

					$export_data[] = array(
						'product_id'=>$value['product_id'],
						'customer_group'=>$customerGroupName,
						'points'=>$value['points']
					);
					
				}
			}

			$column_name=array('A'=>'product_id','B'=>'customer_group','C'=>'points');
		}


		if($table_name=='product_image') {

			$export_data = $export_datas;
			$column_name = array();
			$export_datas = array();
			$server=explode('admin/',HTTPS_SERVER);
			if(!empty($export_data)) {
				foreach($export_data as $key => $value) {
					$export_datas[] = array(
							'product_image_id'		=>	$value['product_image_id'],
							'product_id'			=>	$value['product_id'],
							'image'					=>	$value['image'] ? $server[0].'image/'.$value['image']:'',
							'sort_order'			=>	$value['sort_order']
					);
				}
			}

			$column_name=array('A'=>'product_image_id', 'B'=>'product_id', 'C'=>'image', 'D'=>'sort_order');
		}

		
		if($table_name=='seo_url') {
		
			$export_data = $export_datas;
			$column_name = array();
			$export_datas = array();

			if(!empty($export_data)){
				foreach($export_data as $seourl_data) {
					if($seourl_data['store_id']==0){
						$store_name='Default';
					}else {
						$store_name=$this->model_vendor_lts_excel->getStoreById($seourl_data['store_id']);	
					}
				$export_datas[]=array(
					'product_id'=>$seourl_data['product_id'],
					'store'=>$store_name,
					'keyword'=>htmlspecialchars_decode($seourl_data['keyword'])
				);	
					
				}
			}


			$column_name=array('A'=>'product_id','B'=>'store','C'=>'keyword');
		}	


		if($table_name=='product_to_layout') {
		
			$export_data = $export_datas;
			$column_name = array();
			$export_datas = array();

			if(!empty($export_data)){
				foreach($export_data as $layoutData){
				if($layoutData['store_id']==0){
					$storeName='Default';
					
				}else {
				$storeName=$this->model_vendor_lts_excel->getStoreById($layoutData['store_id']);	
				}
				$layout=$this->model_vendor_lts_excel->getLayoutName($layoutData['layout_id']);
				
				$export_datas[]=array(
					'product_id'	=>	$layoutData['product_id'],
					'store'		=>	$storeName,
					'layout'		=>	$layout
					);	
	
				}
			}
			$column_name=array('A'=>'product_id','B'=>'store','C'=>'layout');
	
					
		}

		if($table_name=='product_option') {
			$export_data = $export_datas;
			$column_name = array();
			$export_datas = array();
			if(!empty($export_data)){
				foreach($export_data as $key => $value){
					$type=$this->model_vendor_lts_excel->getOptionType($value['option_id']);	
					$name=$this->model_vendor_lts_excel->getOptionName($value['option_id']);	

				$export_datas[]=array('product_id'=>$value['product_id'],
						'name'=>htmlspecialchars_decode($name),
						'type'=>$type,
						'value'=>htmlspecialchars_decode($value['value']),
						'required'=>(int)$value['required']?'Yes':'No'
					);
					
				}
			}

			$column_name=array('A'=>'product_id','B'=>'name','C'=>'type','D'=>'value','E'=>'required');
		}

		
		

		if($table_name=='product_option_value') {
			$export_data = $export_datas;
			$column_name = array();
			$export_datas = array();
			if(!empty($export_data)){
				foreach($export_data as $key => $value){
					$type=$this->model_vendor_lts_excel->getOptionName($value['option_id']);

					$name=$this->model_vendor_lts_excel->getOptionValueName($value['option_id'],$value['option_value_id'],$this->request->post['language']);	

				$export_datas[]=array(
							'product_id'		=>$value['product_id'],
							'option'			=>htmlspecialchars_decode($type),
							'option_value'	=>htmlspecialchars_decode($name),
							'quantity'			=>$value['quantity'],
							'subtract'			=>($value['subtract'])?'Yes':'No',
							'price'				=>$value['price'],
							'price_prefix'		=>$value['price_prefix'],
							'points'			=>$value['points'],
							'points_prefix'		=>$value['points_prefix'],
							'weight'			=>$value['weight'],
							'weight_prefix'		=>$value['weight_prefix']
							);
			
				}
			}
			$column_name=array('A'=>'product_id','B'=>'option','C'=>'option_value','D'=>'quantity','E'=>'subtract','F'=>'price','G'=>'price_prefix','H'=>'points','I'=>'points_prefix','J'=>'weight','K'=>'weight_prefix');

		}
		
		
		if(!empty($column_name)){	
			foreach($column_name as $key => $tbName) {
			if($tbName=='name' || $tbName=='description' || $tbName=='tag' || $tbName=='meta_title' || $tbName=='meta_description' || $tbName=='meta_keyword' || $tbName=='date_available' || $tbName=='download' || $tbName=='category' || $tbName=='filter' || $tbName=='related' || $tbName=='text' || $tbName=='date_start' || $tbName=='date_end' || $tbName=='value' ) {
				$objPHPexcel->getActiveSheet()->getStyle($key)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_TEXT );
			}
				$objPHPexcel->getActiveSheet()->setCellValue($key.'1',$tbName);	
				$objPHPexcel->getActiveSheet($sheetIndex)->getColumnDimension($key)->setWidth(strlen($tbName)+4);
				$objPHPexcel->getActiveSheet($sheetIndex)->getRowDimension(1)->setRowHeight(25);
				$objPHPexcel->getActiveSheet($sheetIndex)->getStyle($key.'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPexcel->getActiveSheet($sheetIndex)->getStyle($key.'1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPexcel->getActiveSheet($sheetIndex)->getStyle($key.'1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
				$objPHPexcel->getActiveSheet($sheetIndex)->getStyle($key.'1')->getFill()->getStartColor()->setARGB('adc5c5');
				$objPHPexcel->getActiveSheet($sheetIndex)->getStyle($key.'1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('D3D3D3'); 
				  $objPHPexcel->getActiveSheet($sheetIndex)->getStyle($key.'1')->getFont()->setBold(true)->setName('Verdana')->setSize(10)->getColor()->setRGB('FFFFFF');
				}
		}

		$objPHPexcel->getActiveSheet()->freezePaneByColumnAndRow( 1, 2 );

		$i=0;

		if(!empty($export_datas) && $export_datas!=NULL){
			foreach($export_datas as $key => $export_datas){
				$ii = $i+2;	
				if($column_name!=NULL){
					foreach($column_name as $k=>$v){
						if($v != 'viewed') {
							$objPHPexcel->getActiveSheet()->setCellValue($k.''.$ii, $export_datas[$v]);
						}
					}
				}
				$i++;
			} 
		}
	 	$objPHPexcel->getActiveSheet()->setTitle($tab_name);
	}
	
	public function exportLinksData($objPHPexcel,$tab_name,$vendor_id,$language,$sheetIndex){	
		$this->load->model('vendor/lts_excel');

		$objPHPexcel->setActiveSheetIndex($sheetIndex);
		$column_name=array();
		$export_data=array();
		$links=array();
		$vvv=array();
		$tblfield=array();


		$product_to_download = $this->model_vendor_lts_excel->getTableName('product_to_download');


		$product_to_category=$this->model_vendor_lts_excel->getTableName('product_to_category');
		$product_filter=$this->model_vendor_lts_excel->getTableName('product_filter');
		$product_related=$this->model_vendor_lts_excel->getTableName('product_related');
		$product_to_store=$this->model_vendor_lts_excel->getTableName('product_to_store');


		$links = array_merge(array_values($product_to_download), array_values($product_to_category), array_values($product_filter), array_values($product_related), array_values($product_to_store));

		$vvv = array_unique($links);
		$xcx = range('A','Z');
		$combine_arr = array_combine(array_slice($xcx,0,count($vvv),true),$vvv);
		$combine_arr['B']	='download';
		$combine_arr['C']	='category';
		$combine_arr['D']	='filter';
		$combine_arr['E']	='related';
		$combine_arr['F']	='store';

		$export_data_arr = $this->model_vendor_lts_excel->getExportData('product',$vendor_id,$language);

		if(!empty($export_data_arr) && $export_data_arr != NULL) {
			$export_data=array_column($export_data_arr,'product_id');
		}

		$fetchData = array();
		$fetchData1 = array();
		if(!empty($export_data)) {
			foreach($export_data as $value_product_id) {
				if($value_product_id!='' || $value_product_id!=NULL ) {
			
				$fetchData[$value_product_id] = array("product_id"=>$value_product_id);
					
				$download_product = $this->model_vendor_lts_excel->getProductDownloads($value_product_id);

					if(!empty($download_product)) {
						if(!empty($fetchData[$value_product_id])) {
								$downld=array(); 
							$x= explode(",", $download_product['download_id']);
							foreach($x as $download){
								if($this->model_vendor_lts_excel->getProductDownloadsName($download,$this->request->post['language'])){
								$downld[] =$this->model_vendor_lts_excel->getProductDownloadsName($download,$this->request->post['language']);
								}
							}
							if(!empty($downld)){
							$dwn=implode(",",$downld);
							} else { $dwn=''; }

							$fetchData[$value_product_id] = array_merge($fetchData[$value_product_id],array("download"=>$dwn));
						}
					} else {
						$fetchData[$value_product_id] = array_merge($fetchData[$value_product_id],array("download"=>""));
					}
					
				$categories_product = $this->model_vendor_lts_excel->getProductCategories($value_product_id);

				$this->load->model('catalog/category');

				$categories=array();

				$category_data = array();
					if(!empty($categories_product)) {
						if(!empty($fetchData[$value_product_id])) {
							$categories=explode(",",$categories_product['category_id']);
							if(!empty($categories)) {
								foreach($categories as $category_id){
									$category_info = $this->model_catalog_category->getCategory($category_id);

									if ($category_info) {
										$category_data[] = ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name'];
									
									}
								}	
							}
							if(!empty($category_data)) {
								$category_name=implode(",", $category_data);

							} else {
								$category_name='';
							}				
					
							$fetchData[$value_product_id] = array_merge($fetchData[$value_product_id],array("category"=>htmlspecialchars_decode($category_name)));
						}
					} else {
						$fetchData[$value_product_id] = array_merge($fetchData[$value_product_id],array("category"=>''));
						
					}

			
					$filteres_product = $this->model_vendor_lts_excel->getProductFilter($value_product_id);
					$this->load->model('catalog/filter');
					$filteres = array();
					$filter_data = array();
					if(!empty($filteres_product)) {
						if(!empty($fetchData[$value_product_id])) {
								$filteres=explode(",",$filteres_product['filter_id']);
							if(!empty($filteres)){
								foreach($filteres as $filterId){
										$filter_info = $this->model_catalog_filter->getFilter($filterId);

										if ($filter_info) {
											$filter_data[] =$filter_info['group'] . ' &gt; ' . $filter_info['name'];
											
										}	
								}
								if(!empty($filter_data)){
									$filter_name = implode(",",$filter_data);
									
								} else {
									$filter_name = '';
									
								}
							}
							
							$fetchData[$value_product_id] = array_merge($fetchData[$value_product_id],array("filter"=>htmlspecialchars_decode($filter_name)));
						} 
					} else {
							$fetchData[$value_product_id] = array_merge($fetchData[$value_product_id],array("filter"=>''));		
					}
					
			
				$this->load->model('catalog/product');
				$related_porducts = $this->model_vendor_lts_excel->getProductRelated($value_product_id);
				$relatedPro = array();

				if(!empty($related_porducts)) {
					if(!empty($fetchData[$value_product_id])) {
						if(!empty($related_porducts['related_id'])){
							$relatedProduct=explode(",",$related_porducts['related_id']);
							if(!empty($relatedProduct)) {
							foreach($relatedProduct as $relatedP){
									$related_info = $this->model_catalog_product->getProduct($relatedP);

									if ($related_info) {
										$relatedPro[] =$related_info['name'];
										
									}
								
							}
							}

						}
						if(!empty($relatedPro)){
							$relatedProductData=implode(",",$relatedPro);	
						} else {
							$relatedProductData='';	
						}
						
						$fetchData[$value_product_id] = array_merge($fetchData[$value_product_id],array("related"=>htmlspecialchars_decode($relatedProductData)));
					}
				} else {
						$fetchData[$value_product_id] = array_merge($fetchData[$value_product_id],array("related"=>''));
				}
				
				$productstore = $this->model_vendor_lts_excel->getProductToStore($value_product_id);
				
				$storeId = array();
				$productsto = array();
				if(!empty($productstore) and $productstore!=NULL) {
				
					$storeId=explode(",",$productstore['store_id']);

					if(!empty($storeId)) {
					foreach($storeId as $sid){
						if($sid!=0){
							if($this->model_vendor_lts_excel->getStoreById($sid)!=NULL){
								$productsto[] = $this->model_vendor_lts_excel->getStoreById($sid);
							}
						}else {
							$productsto[]='Default';	
						}		
					} 
				}
					$storename=implode(",",$productsto);
				} else {
					$storename='Default';	
				}

				$fetchData[$value_product_id] = array_merge($fetchData[$value_product_id],array("store"=>$storename)); 

			}
		} 
	}
	if(!empty($combine_arr)){
		foreach($combine_arr as $key => $tbName) {
			if($tbName=='name' || $tbName=='description' || $tbName=='tag' || $tbName=='meta_title' || $tbName=='meta_description' || $tbName=='meta_keyword' || $tbName=='date_available' || $tbName=='download' || $tbName=='category' || $tbName=='filter' || $tbName=='related' || $tbName=='text' || $tbName=='date_start' || $tbName=='date_end' || $tbName=='value' ) {
				$objPHPexcel->getActiveSheet()->getStyle($key)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_TEXT );
			}

		$objPHPexcel->getActiveSheet()->setCellValue($key.'1',$tbName);	
		$objPHPexcel->getActiveSheet($sheetIndex)->getColumnDimension($key)->setWidth(strlen($tbName)+4);
		$objPHPexcel->getActiveSheet($sheetIndex)->getRowDimension(1)->setRowHeight(25);
		$objPHPexcel->getActiveSheet($sheetIndex)->getStyle($key.'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPexcel->getActiveSheet($sheetIndex)->getStyle($key.'1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPexcel->getActiveSheet($sheetIndex)->getStyle($key.'1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPexcel->getActiveSheet($sheetIndex)->getStyle($key.'1')->getFill()->getStartColor()->setARGB('adc5c5');
		$objPHPexcel->getActiveSheet($sheetIndex)->getStyle($key.'1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)->getColor()->setRGB('D3D3D3'); 
		$objPHPexcel->getActiveSheet($sheetIndex)->getStyle($key.'1')->getFont()->setBold(true)->setName('Verdana')->setSize(10)->getColor()->setRGB('FFFFFF');
		}
	}
		$objPHPexcel->getActiveSheet()->freezePaneByColumnAndRow( 1, 2 );
		$i=0;
		
		if(!empty($fetchData) && $fetchData !=NULL){
			foreach($fetchData as $key => $export_data){
				$ii = $i+2;	
				if(!empty($combine_arr)){ 
					foreach($combine_arr as $key => $value){
						$objPHPexcel->getActiveSheet()->setCellValue($key.''.$ii, $export_data[$value]);
					}
				}
				$i++;
			} 
		}
	 $objPHPexcel->getActiveSheet()->setTitle($tab_name);	

	}

	protected function validateForm() {
    	if (!$this->user->hasPermission('modify', 'vendor/lts_excel')) {
    	    $this->error['warning'] = $this->language->get('error_permission');
    	}

    	return !$this->error;
    }

	
}

?>