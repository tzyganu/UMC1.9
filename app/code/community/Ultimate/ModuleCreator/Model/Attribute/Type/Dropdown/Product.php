<?php
/**
 * Ultimate_ModuleCreator extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE_UMC.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @category       Ultimate
 * @package        Ultimate_ModuleCreator
 * @copyright      Copyright (c) 2013
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 * @author         Marius Strajeru <ultimate.module.creator@gmail.com>
 */
/**
 * product attribute dropdown type
 *
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */
class Ultimate_ModuleCreator_Model_Attribute_Type_Dropdown_Product
    extends Ultimate_ModuleCreator_Model_Attribute_Type_Dropdown_Abstract {
    /**
     * entity code for source
     * @var string
     */
    protected $_entityCode = 'catalog_product';
    /**
     * get attribute options for source model
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAttributeOptions(){
        $content = '';
        $padding  = $this->getPadding(2);
        $tab      = $this->getPadding();
        $eol      = $this->getEol();
        $module   = $this->getTypeAttribute()->getAttribute()->getEntity()->getModule()->getLowerModuleName();
        $attrCode = $this->getTypeAttribute()->getAttribute()->getOptionsSourceAttribute();
        $content .= $padding.'$'."source  = Mage::getModel('eav/config')->getAttribute('".$this->getEntityCode()."', '".$attrCode."');".$eol;
        $content .= $padding.'return $source->getSource()->getAllOptions();';
        return $content;
    }

    /**
     * get entity code
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEntityCode(){
        return $this->_entityCode;
    }
}