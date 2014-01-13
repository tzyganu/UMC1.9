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
 * multiselect attribute type
 *
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */
class Ultimate_ModuleCreator_Model_Attribute_Type_Multiselect
    extends Ultimate_ModuleCreator_Model_Attribute_Type_Dropdown {
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
     * get text for rss
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    //TODO: implement this
    public function getRssText(){
        return '';
    }
}