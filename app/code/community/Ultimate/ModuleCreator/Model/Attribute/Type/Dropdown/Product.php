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
 * @copyright      Copyright (c) 2014
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
class Ultimate_ModuleCreator_Model_Attribute_Type_Dropdown_Product extends Ultimate_ModuleCreator_Model_Attribute_Type_Dropdown_Abstract
{
    /**
     * entity code for source
     *
     * @var string
     */
    protected $_entityCode      = 'catalog_product';
    /**
     * entity attribute
     *
     * @var string
     */
    protected $_entityAttribute = null;

    /**
     * get attribute options for source model
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAttributeOptions()
    {
        $content = '';
        $padding  = $this->getPadding(2);
        $eol      = $this->getEol();
        $attrCode = $this->getTypeAttribute()->getAttribute()->getOptionsSourceAttribute();
        $content .= $padding.'$'."source  = Mage::getModel('eav/config')->getAttribute('";
        $content .= $this->getEntityCode()."', '".$attrCode."');".$eol;
        $content .= $padding.'return $source->getSource()->getAllOptions($withEmpty, $defaultValues);';
        return $content;
    }

    /**
     * get entity code
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEntityCode()
    {
        return $this->_entityCode;
    }

    /**
     * get attribute setup type
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getSetupType()
    {
        $attrCode = $this->getTypeAttribute()->getAttribute()->getOptionsSourceAttribute();
        /** @var Mage_Eav_Model_Config $config */
        $config = Mage::getModel('eav/config');
        $productAttribute = $config->getAttribute($this->getEntityCode(), $attrCode);
        if ($productAttribute->getId()) {
            $type = $productAttribute->getBackendType();
            if ($type == 'static') {
                return false;
            }
            return $type;
        }
        return false;
    }

    /**
     * get attribute setup type
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getTypeDdl()
    {
        $entityAttribute = $this->_getEntityAttribute();
        switch($entityAttribute->getBackendType()) {
            case 'int':
                return 'TYPE_INTEGER';
            break;
            case 'varchar':
                return 'TYPE_TEXT';
            break;
            case 'text':
                return 'TYPE_TEXT';
            case 'datetime':
                return 'TYPE_DATETIME';
            break;
            case 'decimal':
                return 'TYPE_DECIMAL';
            break;
            default:
                return false;
            break;
        }
    }

    /**
     * get attribute setup type
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getTypeSize()
    {
        $entityAttribute = $this->_getEntityAttribute();
        switch($entityAttribute->getBackendType()) {
            case 'int':
                return 'null';
                break;
            case 'varchar':
                return '255';
                break;
            case 'text':
                return "'64k'";
                break;
            case 'datetime':
                return "255";
                break;
            case 'decimal':
                return "'12,4'";
                break;
            default:
                return false;
                break;
        }
    }

    /**
     * get the source model attribute
     *
     * @access public
     * @return null|Mage_Eav_Model_Entity_Attribute_Abstract
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _getEntityAttribute()
    {
        if (is_null($this->_entityAttribute)) {
            $attrCode = $this->getTypeAttribute()->getAttribute()->getOptionsSourceAttribute();
            /** @var Mage_Eav_Model_Config $config */
            $config = Mage::getModel('eav/config');
            $productAttribute = $config->getAttribute($this->getEntityCode(), $attrCode);
            $this->_entityAttribute = $productAttribute;
        }
        return $this->_entityAttribute;
    }
}
