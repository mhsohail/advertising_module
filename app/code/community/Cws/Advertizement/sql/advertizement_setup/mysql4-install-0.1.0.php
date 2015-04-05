<?php
/**
 * Cws Advertizement Plugin
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * It is available on the World Wide Web at:
 * http://opensource.org/licenses/osl-3.0.php
 * If you are unable to access it on the World Wide Web, please send an email
 * To: javed.alam@cwsinfotech.com.  We will send you a copy of the source file.
 *
 * @category   Advertizement Plugin
 * @package    Cws_Advertizement
 * @copyright  Copyright (c) 2014 Cws Technology Pvt. Ltd., India
 *             http://www.cwstechnology.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Javed Alam <javed.alam@cwsinfotech.com>
 */

$installer = $this;
$installer->startSetup();
$installer->run("    
-- DROP TABLE IF EXISTS {$this->getTable('advertizement/advertizement')};
CREATE TABLE {$this->getTable('advertizement/advertizement')} (
	`advertizement_id` int(11) NOT NULL AUTO_INCREMENT,
	`merchant_id` int(11) NOT NULL,
	`store_id` int(11) NOT NULL,
	`categories` varchar(255) NOT NULL,
	`addvertizement_banner` varchar(255) NOT NULL,
	`addvertizement_link` varchar(255) NOT NULL,
	`active_status` enum('0','1') NOT NULL,
	`createddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`modifieddate` datetime NOT NULL,
	`ip_address` varchar(255) NOT NULL,
	`page_location` varchar(255) NOT NULL,
	PRIMARY KEY (`advertizement_id`)
)
ENGINE = InnoDB;"
);

$installer->endSetup();
