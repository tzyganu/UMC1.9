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
 */
/**
 * relations tab block
 *
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */
/**
 * @method array getRelations()
 */
class Ultimate_ModuleCreator_Block_Adminhtml_Modulecreator_Edit_Tab_Relation extends Mage_Adminhtml_Block_Widget_Form implements
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
        $this->setTemplate('ultimate_modulecreator/edit/tab/relation.phtml');
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
        return Mage::helper('modulecreator')->__('Entity Relations');
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
        return Mage::helper('modulecreator')->__('Entity Relations');
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
        return count($this->getRelations()) == 0;
    }
}
