<?php
class ModelExtensionModuleLtsVendor extends Model {
	public function install() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "lts_vendor` (
			 `vendor_id` bigint(11) NOT NULL,
			 `image_folder` VARCHAR(64) NOT NULL,
			 `about` text NOT NULL,
			 `store` varchar(64) NOT NULL,
			 `address1` varchar(255) NOT NULL,
			 `address2` varchar(255) NOT NULL,
			 `city` varchar(64) NOT NULL,
			 `postcode` varchar(13) NOT NULL,
			 `country_id` bigint(16) NOT NULL DEFAULT 0,
			 `zone_id` bigint(16) NOT NULL DEFAULT 0,
			 `image` varchar(255) NOT NULL,
			 `salt` varchar(255) NOT NULL,
			 `status` varchar(60) NOT NULL DEFAULT '0', 
			 PRIMARY KEY (`vendor_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "lts_vendor_product` (
			 `product_id` int(11) NOT NULL,
			 `vendor_id` varchar(255) NOT NULL,
			 `status` int(11) NOT NULL,
			 PRIMARY KEY (`product_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "lts_vendor_manufacturer` (
			 `manufacturer_id` int(11) NOT NULL,
			 `vendor_id` int(11) NOT NULL,
			 PRIMARY KEY (`manufacturer_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1");

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."lts_vendor_order_product` (
			 `order_product_id` int(11) NOT NULL,
			 `order_id` int(11) NOT NULL,
			 `product_id` int(11) NOT NULL,
			 `vendor_id` int(11) NOT NULL,
			 `firstname` varchar(150) NOT NULL,
			 `lastname` varchar(150) NOT NULL,
			 `order_status_id` int(11) NOT NULL,
			 `name` varchar(255) NOT NULL,
			 `model` varchar(64) NOT NULL,
			 `quantity` int(4) NOT NULL,
			 `price` decimal(15,4) NOT NULL DEFAULT 0.0000,
			 `total` decimal(15,4) NOT NULL DEFAULT 0.0000,
			 `tax` decimal(15,4) NOT NULL DEFAULT 0.0000,
			 `reward` int(8) NOT NULL,
			 PRIMARY KEY (`order_product_id`),
			 KEY `order_id` (`order_id`)
			) ENGINE=MyISAM AUTO_INCREMENT=103 DEFAULT CHARSET=utf8");

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."lts_vendor_mail` (
		 `mail_id` int(10) NOT NULL AUTO_INCREMENT,
		 `too_id` int(2) NOT NULL,
		 `subject` varchar(250) NOT NULL,
		 `message` text NOT NULL,
		 `status` int(1) NOT NULL,
		 PRIMARY KEY (`mail_id`)
		) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1");	
        
        
		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."lts_vendor_too` (
			 `too_id` int(11) NOT NULL AUTO_INCREMENT,
			 `name` varchar(250) NOT NULL,
			 PRIMARY KEY (`too_id`)
		) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1");

		$this->db->query("INSERT INTO `". DB_PREFIX ."lts_vendor_too` (`too_id`, `name`) VALUES(2,  'All Vendor'),(3, 'Approval Vendor'),
			(4,  'Non Approval')");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "lts_vendor_attribute_group` (
		 	`attribute_group_id` int(11) NOT NULL,
		 	`vendor_id` int(11) NOT NULL,
		 	PRIMARY KEY (`attribute_group_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "lts_vendor_attribute` (
			`attribute_id` int(11) NOT NULL,
			`attribute_group_id` int(11) NOT NULL,
			`vendor_id` int(11) NOT NULL,
			PRIMARY KEY (`attribute_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1");
        
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "lts_vendor_option` (
			 `option_id` int(11) NOT NULL,
			 `vendor_id` int(11) NOT NULL,
			 PRIMARY KEY (`option_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1");
			
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "lts_vendor_filter_group` (
			 `filter_group_id` int(11) NOT NULL,
			 `vendor_id` int(11) DEFAULT NULL,
			 PRIMARY KEY (`filter_group_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1");

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."lts_vendor_commission` (
			`commission_id` int(11) NOT NULL AUTO_INCREMENT,
			 `category_id` int(11) NOT NULL,
			 `commission_type` char(1) NOT NULL,
			 `commission` int(11) NOT NULL,
			 PRIMARY KEY (`commission_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1");

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."lts_vendor_review` (
			 `review_id` int(11) NOT NULL AUTO_INCREMENT,
			 `product_id` int(11) NOT NULL,
			 `vendor_id` int(11) NOT NULL,
			 `firstname` varchar(150) NOT NULL,
			 `lastname` varchar(150) NOT NULL,
			 PRIMARY KEY (`review_id`),
			 KEY `product_id` (`product_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1");

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."lts_vendor_download` (
		 `download_id` int(11) NOT NULL,
		 `vendor_id` int(11) NOT NULL,
		 PRIMARY KEY (`download_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1");

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."lts_vendor_category` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
			 `category_id` int(11) NOT NULL,
			 `vendor_id` int(11) NOT NULL,
			 `assigned` int(11) NOT NULL DEFAULT 0,
			 PRIMARY KEY (`id`),
			 KEY `category_id` (`category_id`,`vendor_id`)
		) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=latin1");

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."lts_vendor_store` (
			 `vendor_id` int(11) NOT NULL,
			 `description` varchar(255) NOT NULL,
			 `meta_title` varchar(65) NOT NULL,
			 `meta_description` varchar(255) NOT NULL,
			 `meta_keyword` varchar(255) NOT NULL,
			 `owner_name` varchar(35) NOT NULL,
			 `store_name` varchar(30) NOT NULL,
			 `address` varchar(64) NOT NULL,
			 `email` varchar(35) NOT NULL,
			 `telephone` varchar(32) NOT NULL,
			 `fax` varchar(32) NOT NULL,
			 `country_id` int(11) NOT NULL,
			 `zone_id` int(11) NOT NULL,
			 `city` varchar(32) NOT NULL,
			 `logo` text NOT NULL,
			 `banner` text NOT NULL,
			 `facebook` text NOT NULL,
			 `instagram` text NOT NULL,
			 `youtube` text NOT NULL,
			 `twitter` text NOT NULL,
			 `pinterest` text NOT NULL,
			 UNIQUE KEY `vendor_id` (`vendor_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."lts_vendor_product_to_category` (
				 `product_id` int(12) NOT NULL,
				 `category_id` int(12) NOT NULL,
				 `vendor_id` int(12) NOT NULL,
				 PRIMARY KEY (`product_id`,`category_id`),
				 KEY `category_id` (`category_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1
				");

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."lts_vendor_payment` (
			 `vendor_id` int(13) NOT NULL,
			 `paypal` varchar(50) NOT NULL,
			 `account_holder` varchar(100) NOT NULL,
			 `bankname` varchar(100) NOT NULL,
			 `accountno` varchar(20) NOT NULL,
			 `ifsc` varchar(15) NOT NULL,
			 PRIMARY KEY (`vendor_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1");

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."lts_vendor_commission_report` (
			 `id` int(11) NOT NULL AUTO_INCREMENT,
			  `vendor_id` int(12) NOT NULL,
			  `product_id` int(11) NOT NULL,
			  `name` varchar(255) NOT NULL,
			  `model` varchar(100) NOT NULL,
			  `quantity` varchar(4) NOT NULL,
			  `price` float NOT NULL,
			  `commission` float NOT NULL,
			  `date_added` datetime NOT NULL,
			  PRIMARY KEY (`id`),
			  KEY `vendor_id` (`vendor_id`),
			  KEY `product_id` (`product_id`)
			) ENGINE = InnoDB AUTO_INCREMENT = 9 DEFAULT CHARSET = latin1");

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."lts_vendor_coupon` (
			 `coupon_id` int(11) NOT NULL,
			 `vendor_id` int(11) NOT NULL,
			 `code` varchar(20) NOT NULL,
			 PRIMARY KEY (`coupon_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1");

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."lts_vendor_assigned_category` (
			 `id` int(11) NOT NULL AUTO_INCREMENT,
			 `vendor_id` int(11) NOT NULL,
			 `category_id` text NOT NULL,
			 PRIMARY KEY (`id`),
			 KEY `categoy_id` (`category_id`(3072)),
			 KEY `vendor_id` (`vendor_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1");

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."lts_vendor_attribute_mapping` (
			 `attribute_mapping_id` int(11) NOT NULL AUTO_INCREMENT,
			 `attribute` varchar(256) NOT NULL,
			 `category_id` int(11) NOT NULL,
			 PRIMARY KEY (`attribute_mapping_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1");

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."lts_vendor_option_mapping` (
			 `option_mapping_id` int(11) NOT NULL AUTO_INCREMENT,
			 `category_id` int(11) NOT NULL,
			 `option` varchar(260) NOT NULL,
			 PRIMARY KEY (`option_mapping_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1");

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."lts_vendor_pincode_status` (
			 `pincode_status_id` int(11) NOT NULL AUTO_INCREMENT,
			 `pincode_id` int(11) NOT NULL,
			 `pincode` int(11) NOT NULL,
			 `status` int(11) NOT NULL,
			 PRIMARY KEY (`pincode_status_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4");


		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."lts_vendor_pincode` (
			 `pincode_id` int(11) NOT NULL AUTO_INCREMENT,
			 `name` varchar(255) DEFAULT NULL,
			 `country_id` int(11) DEFAULT NULL,
			 `zone_id` int(11) DEFAULT NULL,
			 `status` int(11) NOT NULL,
			 PRIMARY KEY (`pincode_id`)
		) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4");

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."lts_vendor_product_to_pincode` (
		 `product_id` int(11) NOT NULL,
		 `pincode_id` int(11) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."lts_vendor_store` (
			 `vendor_id` int(11) NOT NULL,
			 `description` varchar(255) NOT NULL,
			 `meta_title` varchar(65) NOT NULL,
			 `meta_description` varchar(255) NOT NULL,
			 `meta_keyword` varchar(255) NOT NULL,
			 `owner_name` varchar(35) NOT NULL,
			 `store_name` varchar(30) NOT NULL,
			 `address` varchar(64) NOT NULL,
			 `email` varchar(35) NOT NULL,
			 `telephone` varchar(32) NOT NULL,
			 `fax` varchar(32) NOT NULL,
			 `country_id` int(11) NOT NULL,
			 `zone_id` int(11) NOT NULL,
			 `city` varchar(32) NOT NULL,
			 `logo` text NOT NULL,
			 `banner` text NOT NULL,
			 `facebook` text NOT NULL,
			 `instagram` text NOT NULL,
			 `youtube` text NOT NULL,
			 `twitter` text NOT NULL,
			 `pinterest` text NOT NULL,
			 UNIQUE KEY `vendor_id` (`vendor_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."lts_vendor_chat` (
				 `id` int(11) NOT NULL AUTO_INCREMENT,
				 `message` text NOT NULL,
				 `vendor_id` int(11) NOT NULL,
				 `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
				 PRIMARY KEY (`id`)
				) ENGINE=InnoDB AUTO_INCREMENT=194 DEFAULT CHARSET=latin1");

		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."lts_vendor_subscription` (
			 `subscription_id` int(11) NOT NULL AUTO_INCREMENT,
			 `no_of_product` int(11) NOT NULL,
			 `join_fee` decimal(10,0) NOT NULL,
			 `subscription_fee` decimal(10,0) NOT NULL,
			 `validity` int(11) NOT NULL,
			 `status` int(11) NOT NULL,
			 `default_plan` int(11) NOT NULL,
			 `date_added` datetime NOT NULL,
			 `date_modified` datetime NOT NULL,
			 PRIMARY KEY (`subscription_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

		$this->db->query("CREATE TABLE  IF NOT EXISTS `". DB_PREFIX ."lts_vendor_subscription_description` (
			 `subscription_id` int(11) NOT NULL,
			 `language_id` int(11) NOT NULL,
			 `name` varchar(255) NOT NULL,
			 `description` text NOT NULL,
			 PRIMARY KEY (`subscription_id`,`language_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

		$this->db->query("INSERT INTO " . DB_PREFIX . "lts_vendor_subscription SET subscription_id = '". (int)5 ."', no_of_product = '" . (int)10 . "', join_fee = '" . (float)120 . "', subscription_fee = '" . (float)500 . "', validity = '" . (int)180 . "', status = '" . (int)1 . "', default_plan = '". (int)1 ."', date_added = NOW(), date_modified = NOW()");

		$this->db->query("INSERT INTO " . DB_PREFIX . "lts_vendor_subscription_description SET subscription_id = '" . (int)5 . "', language_id = '" . (int)1 . "', name = '" . $this->db->escape('Plan A') . "', description = '" . $this->db->escape('This is simple description') . "'");
		
		$this->db->query("CREATE TABLE  IF NOT EXISTS `". DB_PREFIX ."lts_vendor_plan` (
			 `plan_id` int(11) NOT NULL AUTO_INCREMENT,
			 `vendor_id` int(11) NOT NULL,
			 `subscription_id` int(11) NOT NULL,
			 `name` varchar(255) NOT NULL,
			 `no_of_product` int(11) NOT NULL,
			 `join_fee` int(11) NOT NULL,
			 `subscription_fee` int(11) NOT NULL,
			 `validity` int(11) NOT NULL,
			 `date_added` datetime NOT NULL DEFAULT current_timestamp(),
			 `date_expire` datetime NOT NULL,
			 PRIMARY KEY (`plan_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");


		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."lts_vendor_shipping` (
			 `shipping_id` int(11) NOT NULL AUTO_INCREMENT,
			 `vendor_id` int(11) NOT NULL,
			 `country_id` int(11) NOT NULL,
			 `zone_id` int(11) NOT NULL,
			 `zip_from` int(11) NOT NULL,
			 `zip_to` int(11) NOT NULL,
			 `weight_from` int(11) NOT NULL,
			 `weight_to` int(11) NOT NULL,
			 PRIMARY KEY (`shipping_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
	}

	public function uninstall() {
		
		$this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."lts_vendor`");
		$this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."lts_vendor_product`");
		$this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."lts_vendor_manufacturer`");
		$this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."lts_vendor_order_product`");
		$this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."lts_vendor_mail`");
		$this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."lts_vendor_too`");
		$this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."lts_vendor_attribute_group`");
		$this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."lts_vendor_attribute`");
		$this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."lts_vendor_option`");
		$this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."lts_vendor_filter_group`");
		$this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."lts_vendor_commission`");
		$this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."lts_vendor_review`");
		$this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."lts_vendor_download`");
		$this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."lts_vendor_category`");
		$this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."lts_vendor_store`");
		$this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."lts_vendor_product_to_category`");
		$this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."lts_vendor_payment`");
		$this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."lts_vendor_commission_report`");
		$this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."lts_vendor_coupon`");
		$this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."lts_vendor_assigned_category`");
		$this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."lts_vendor_attribute_mapping`");
		$this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."lts_vendor_option_mapping`");
		$this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."lts_vendor_pincode`");
		$this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."lts_vendor_pincode_status`");
		$this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."lts_vendor_product_to_pincode`");
		$this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."lts_vendor_store`");
		$this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."lts_vendor_chat`");
		$this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."lts_vendor_subscription`");
		$this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."lts_vendor_subscription_description`");
		$this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."lts_vendor_plan`");
		$this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."lts_vendor_shipping`");
	}

}