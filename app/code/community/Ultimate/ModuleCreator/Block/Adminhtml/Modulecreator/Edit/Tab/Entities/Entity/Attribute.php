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
 * add attribute form
 *
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */
/**
 * @method Varien_Object getAttributeInstance()
 * @method Ultimate_ModuleCreator_Block_Adminhtml_Modulecreator_Edit_Tab_Entities_Entity_Attribute setAttributeInstance()
 * @method string getEntityId()
 * @method Ultimate_ModuleCreator_Block_Adminhtml_Modulecreator_Edit_Tab_Entities_Entity_Attribute setIncrement()
 * @method int getIncrement()
 * @method Ultimate_ModuleCreator_Block_Adminhtml_Modulecreator_Edit_Tab_Entities_Entity_Attribute setEntityId()
 */
class Ultimate_ModuleCreator_Block_Adminhtml_Modulecreator_Edit_Tab_Entities_Entity_Attribute extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare attribute form
     *
     * @access protected
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _prepareForm()
    {
        /** @var Ultimate_ModuleCreator_Helper_Data $helper */
        $helper = Mage::helper('modulecreator');
        $form = $helper->getXmlForm('attribute', false);
        $form->setHtmlIdPrefix('attribute_'.$this->getEntityId().'_'.$this->getIncrement().'_');
        $form->addFieldNameSuffix('entity['.$this->getEntityId().'][attributes]['.$this->getIncrement().']');
        $this->setForm($form);
        $form->addValues($this->getAttributeInstance()->getData());
        return parent::_prepareForm();
    }

    /**
     * set an entity with default values
     *
     * @access public
     * @return Ultimate_ModuleCreator_Block_Adminhtml_Modulecreator_Edit_Tab_Entities_Entity
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function setDefaultAttributeInstance()
    {
        /** @var Ultimate_ModuleCreator_Model_Attribute $attribute */
        $attribute = Mage::getModel('modulecreator/attribute');
        $settings  = Mage::getStoreConfig(Ultimate_ModuleCreator_Helper_Data::XML_ATTRIBUTE_CONFIG_PATH);
        $attribute->addData($settings);
        $this->setAttributeInstance($attribute);
        return $this;
    }
}
