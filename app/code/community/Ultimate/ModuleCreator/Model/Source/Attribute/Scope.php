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
 * allowed attribute scopes
 *
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */
class Ultimate_ModuleCreator_Model_Source_Attribute_Scope
{
    /**
     * options
     *
     * @var null
     */
    protected $_options = null;
    /**
     * get options array
     *
     * @access public
     * @param bool $withEmpty
     * @return array|null
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function toArray($withEmpty = false)
    {
        if (is_null($this->_options)) {
            $this->_options = array();
            $this->_options[] = array(
                'label' => Mage::helper('modulecreator')->__('Store View'),
                'value' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE
            );
            $this->_options[] = array(
                'label' => Mage::helper('modulecreator')->__('Website'),
                'value' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE
            );
            $this->_options[] = array(
                'label' => Mage::helper('modulecreator')->__('Global'),
                'value' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL
            );
        }
        $options = $this->_options;
        if ($withEmpty) {
            $options = array_merge(array(''=>''), $options);
        }
        return $options;
    }
}
