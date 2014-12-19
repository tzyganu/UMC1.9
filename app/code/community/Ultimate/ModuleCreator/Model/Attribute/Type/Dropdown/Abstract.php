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
 * abstract attribute dropdown type
 *
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */
/**
 * @method string getAttributeOptions
 * @method string getSetupType()
 * @method string getTypeDdl()
 * @method string getSizeDdl()
 */
class Ultimate_ModuleCreator_Model_Attribute_Type_Dropdown_Abstract extends Ultimate_ModuleCreator_Model_Abstract
{
    /**
     * type attribute
     *
     * @var Ultimate_ModuleCreator_Model_Attribute_Type_Decimal
     */
    protected $_typeAttribute;

    /**
     * check if source needs to be generated
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getGenerateSource()
    {
        return true;
    }

    /**
     * type attribute setter
     *
     * @access public
     * @param Ultimate_ModuleCreator_Model_Attribute_Type_Dropdown $attributeType
     * @return $this
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function setTypeAttribute(Ultimate_ModuleCreator_Model_Attribute_Type_Dropdown $attributeType)
    {
        $this->_typeAttribute = $attributeType;
        return $this;
    }

    /**
     * type attribute getter
     *
     * @access public
     * @return Ultimate_ModuleCreator_Model_Attribute_Type_Dropdown
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getTypeAttribute()
    {
        return $this->_typeAttribute;
    }

    /**
     * get additional setup values
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAdditionalSetup()
    {
        return '';
    }

    /**
     * get attribute default value
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getDefaultValueProcessed()
    {
        return '';
    }
}
