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
 * add entity form
 *
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */
/**
 * @method Ultimate_ModuleCreator_Block_Adminhtml_Modulecreator_Edit_Tab_Entities_Entity setEntity()
 * @method Varien_Object getEntity()
 * @method Ultimate_ModuleCreator_Block_Adminhtml_Modulecreator_Edit_Tab_Entities_Entity setIncrement()
 * @method int getIncrement()
 */
class Ultimate_ModuleCreator_Block_Adminhtml_Modulecreator_Edit_Tab_Entities_Entity extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare entity form
     *
     * @access protected
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _prepareForm()
    {
        /** @var Ultimate_ModuleCreator_Helper_Data $helper */
        $helper = Mage::helper('modulecreator');
        $form = $helper->getXmlForm('entity');
        $form->setHtmlIdPrefix('entity_'.$this->getIncrement().'_');
        $form->addFieldNameSuffix('entity['.$this->getIncrement().']');
        $this->setForm($form);
        $form->addValues($this->getEntity()->getData());
        return parent::_prepareForm();
    }

    /**
     * set an entity with default values
     *
     * @access public
     * @return Ultimate_ModuleCreator_Block_Adminhtml_Modulecreator_Edit_Tab_Entities_Entity
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function setDefaultEntity()
    {
        /** @var Ultimate_ModuleCreator_Model_Entity  $entity */
        $entity    = Mage::getModel('modulecreator/entity');
        $settings  = Mage::getStoreConfig(Ultimate_ModuleCreator_Helper_Data::XML_ENTITY_CONFIG_PATH);
        $entity->addData($settings);
        $this->setEntity($entity);
        return $this;
    }
}
