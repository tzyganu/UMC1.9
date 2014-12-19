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
class Ultimate_ModuleCreator_Model_Abstract extends Varien_Object
{
    /**
     * entity code
     *
     * @var string
     */
    protected $_entityCode = 'umc_abstract';

    /**
     * end of line characters
     *
     * @var string
     */
    protected $_eol;

    /**
     * var used for indentation
     *
     * @var string
     */
    protected $_padding;

    /**
     * helper
     *
     * @var mixed
     */
    protected $_helper;

    /**
     * to array
     *
     * @access public
     * @param array
     * @return array()
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function toArray(array $arrAttributes = array())
    {
        if (empty($arrAttributes)) {
            $arrAttributes = array_keys($this->_data);
        }
        $arrRes = array();
        foreach ($arrAttributes as $attribute) {
            $arrRes[$attribute] = $this->getDataUsingMethod($attribute);
        }
        return $arrRes;
    }

    /**
     * get the list of attributes that need saving in XML
     *
     * @access public
     * @return array
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getXmlAttributes()
    {
        $dom    = $this->getHelper()->getConfig();
        $code   = $this->_entityCode;
        return array_keys((array)$dom->getNode('xml_attributes/'.$code));
    }

    /**
     * getter for helper member
     *
     * @access public
     * @return Ultimate_ModuleCreator_Helper_Data|mixed
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getHelper()
    {
        if (is_null($this->_helper)) {
            $this->_helper = Mage::helper('modulecreator');
        }
        return $this->_helper;
    }

    /**
     * getter for padding
     *
     * @access public
     * @param int $length
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getPadding($length = 1)
    {
        if (is_null($this->_padding)) {
            $this->_padding = $this->getHelper()->getPadding();
        }
        return str_repeat($this->_padding, $length);
    }

    /**
     * getter for end of line
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEol()
    {
        if (is_null($this->_eol)) {
            $this->_eol = $this->getHelper()->getEol();
        }
        return $this->_eol;
    }
}
