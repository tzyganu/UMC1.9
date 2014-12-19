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
 * entities tab
 *
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */
class Ultimate_ModuleCreator_Block_Adminhtml_ModuleCreator_Edit_Tab_Entities extends Mage_Adminhtml_Block_Widget implements
    Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('ultimate_modulecreator/edit/tab/entities.phtml');
    }

    /**
     * Return Tab label
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getTabLabel()
    {
        return Mage::helper('modulecreator')->__('Entities');
    }

    /**
     * Return Tab title
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getTabTitle()
    {
        return Mage::helper('modulecreator')->__('Entities');
    }

    /**
     * Can show tab in tabs
     *
     * @access public
     * @return boolean
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Tab is hidden
     *
     * @access public
     * @return boolean
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * get the list of entities
     *
     * @access public
     * @return array()
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEntities()
    {
        /** @var null|Ultimate_ModuleCreator_Model_Module $module */
        $module = Mage::registry('current_module');
        if ($module) {
            return $module->getEntities();
        }
        return array();
    }
}
