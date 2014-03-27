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
 * multiselect attribute type
 *
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */
class Ultimate_ModuleCreator_Model_Attribute_Type_Multiselect
    extends Ultimate_ModuleCreator_Model_Attribute_Type_Dropdown {
    /**
     * type code
     * @var string
     */
    protected $_type        = 'multiselect';
    /**
     * sql column ddl type
     * @var string
     */
    protected $_typeDdl     = 'TYPE_TEXT';
    /**
     * sql column ddl size
     * @var string
     */
    protected $_sizeDdl     = "'64k'";
    /**
     * eav setup input
     */
    protected $_setupInput 	= 'multiselect';
    /**
     * eav setup type
     */
    protected $_setupType   = 'text';
    /**
     * backend setup type
     * @var string
     */
    protected $_setupBackend = 'eav/entity_attribute_backend_array';
    /**
     * don't show in admin grid
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAdminGrid(){
        return false;
    }
    /**
     * get the type for the form
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getFormType(){
        return 'multiselect';
    }
    /**
     * check if attribute is multiple select
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getIsMultipleSelect(){
        return true;
    }
    /**
     * get the setup type of the dropdown
     * @access public
     * @return string|void
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getSetupType(){
        return $this->_setupType;
    }
    /**
     * get attribute setup type
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getTypeDdl(){
        return $this->_typeDdl;
    }
    /**
     * check if options should be returned with empty
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getOptionsFlag() {
        return 'false';
    }
    /**
     * get values for mass action
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getMassActionValues() {
        return '';
    }
}
