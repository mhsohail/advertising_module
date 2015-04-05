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

class Cws_Advertizement_Model_Advertizement extends Mage_Core_Model_Abstract
{
	protected function _construct() {
        parent::_construct();
        $this->_init('advertizement/advertizement');
    }

}
