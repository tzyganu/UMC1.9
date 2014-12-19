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
 * settings tab block
 *
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */
class Ultimate_ModuleCreator_Block_Adminhtml_Modulecreator_Edit_Tab_Settings extends Mage_Adminhtml_Block_Widget_Form implements
    Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * Return Tab label
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getTabLabel()
    {
        return Mage::helper('modulecreator')->__('General Settings');
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
        return Mage::helper('modulecreator')->__('General Settings');
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
     * prepare the form
     *
     * @access public
     * @return Ultimate_ModuleCreator_Block_Adminhtml_Modulecreator_Edit_Tab_Settings
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _prepareForm()
    {
        /** @var Ultimate_ModuleCreator_Helper_Data $helper */
        $helper = Mage::helper('modulecreator');
        $form = $helper->getXmlForm('settings');
        $form->setHtmlIdPrefix('settings_');
        $module = Mage::registry('current_module');
        $values = array();
        if ($module) {
            $values = $module->getData();
        } else {
            $values = Mage::getStoreConfig(Ultimate_ModuleCreator_Helper_Data::XML_SETTINGS_CONFIG_PATH);
        }
        $this->setForm($form);
        $form->addFieldNameSuffix('settings');
        $form->addValues($values);
        return parent::_prepareForm();
    }
}
