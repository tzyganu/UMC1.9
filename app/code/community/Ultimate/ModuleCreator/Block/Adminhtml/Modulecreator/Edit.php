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
 * module edit block
 *
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */
class Ultimate_ModuleCreator_Block_Adminhtml_Modulecreator_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * config path to expanded/collapsed fieldsets
     */
    const XML_FIELDSET_COLLAPSED    = 'modulecreator/general/collapsed';

    /**
     * construct
     *
     * @access public
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'adminhtml_modulecreator';
        $this->_controller = 'modulecreator';
        $this->setTemplate('ultimate_modulecreator/edit.phtml');
    }

    /**
     * get current module
     *
     * @access public
     * @return mixed
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getModule()
    {
        return Mage::registry('current_module');
    }

    /**
     * get header text
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getHeaderText()
    {
        $module = $this->getModule();
        if ($module) {
            return Mage::helper('modulecreator')->__('Edit module "%s"', $module->getExtensionName());
        }
        return Mage::helper('modulecreator')->__('Create Module');
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return Ultimate_ModuleCreator_Block_Adminhtml_ModuleCreator_Edit
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _prepareLayout()
    {
        /** @var Mage_Adminhtml_Block_Widget_Button $backButton */
        $backButton = $this->getLayout()
            ->createBlock('adminhtml/widget_button')
            ->setData(
                array(
                    'label' => Mage::helper('modulecreator')->__('Back'),
                    'onclick'   => 'setLocation(\''.$this->getUrl('*/*/').'\')',
                    'class' => 'back'
                )
            );
        $this->setChild('back_button', $backButton);

        /** @var Mage_Adminhtml_Block_Widget_Button $resetButton */
        $resetButton = $this->getLayout()
            ->createBlock('adminhtml/widget_button')
            ->setData(
                array(
                    'label' => Mage::helper('modulecreator')->__('Reset'),
                    'onclick'   => 'setLocation(\''.$this->getUrl('*/*/*', array('_current'=>true)).'\')'
                )
            );
        $this->setChild('reset_button', $resetButton);

        /** @var Mage_Adminhtml_Block_Widget_Button $saveButton */
        $saveButton = $this->getLayout()
            ->createBlock('adminhtml/widget_button')
            ->setData(
                array(
                    'label' => Mage::helper('modulecreator')->__('Save'),
                    'onclick'   => 'moduleForm.submit()',
                    'class' => 'save'
                )
            );
        $this->setChild('save_button', $saveButton);

        /** @var Mage_Adminhtml_Block_Widget_Button $saveEditButton */
        $saveEditButton = $this->getLayout()
            ->createBlock('adminhtml/widget_button')
            ->setData(
                array(
                    'label' => Mage::helper('modulecreator')->__('Save and Continue Edit'),
                    'onclick'   => 'saveAndContinueEdit(\''.$this->getSaveAndContinueUrl().'\')',
                    'class' => 'save'
                )
            );
        $this->setChild('save_and_edit_button', $saveEditButton);

        /** @var Mage_Adminhtml_Block_Widget_Button $addEntityButton */
        $addEntityButton = $this->getLayout()
            ->createBlock('adminhtml/widget_button')
            ->setData(
                array(
                    'label' => Mage::helper('modulecreator')->__('Add entity'),
                    'class' => 'add add-entity'
                )
            );
        $this->setChild('add-entity', $addEntityButton);

        /** @var Ultimate_ModuleCreator_Block_Adminhtml_Modulecreator_Edit_Tab_Entities_Entity $block */
        $block = Mage::app()->getLayout()
            ->createBlock('modulecreator/adminhtml_modulecreator_edit_tab_entities_entity');
        $block->setTemplate('ultimate_modulecreator/edit/tab/entities/entity.phtml');
        $block->setDefaultEntity();
        $block->setIncrement('{{entity_id}}');
        $this->setChild('entity-template', $block);

        /** @var Ultimate_ModuleCreator_Block_Adminhtml_Modulecreator_Edit_Tab_Entities_Entity_Attribute $block */
        $block = Mage::app()->getLayout()
            ->createBlock('modulecreator/adminhtml_modulecreator_edit_tab_entities_entity_attribute');
        $block->setTemplate('ultimate_modulecreator/edit/tab/entities/entity/attribute.phtml');
        $block->setDefaultAttributeInstance();
        $block->setIncrement('{{attribute_id}}');
        $block->setEntityId('{{entity_id}}');
        $this->setChild('attribute-template', $block);

        $this->setChild(
            'menu-selector',
            Mage::app()->getLayout()->createBlock('modulecreator/adminhtml_modulecreator_menu')
        );
        return $this;
    }

    /**
     * get the back button html
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getBackButtonHtml()
    {
        return $this->getChildHtml('back_button');
    }

    /**
     * get the cancel button html
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getCancelButtonHtml()
    {
        return $this->getChildHtml('reset_button');
    }

    /**
     * get the save button html
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getSaveButtonHtml()
    {
        return $this->getChildHtml('save_button');
    }

    /**
     * get the save and continue edit button html
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getSaveAndEditButtonHtml()
    {
        return $this->getChildHtml('save_and_edit_button');
    }

    /**
     * get html for "add entity" button
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAddEntityButtonHtml()
    {
        return $this->getChildHtml('add-entity');
    }

    /**
     * get the save and continue edit url
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getSaveAndContinueUrl()
    {
        return $this->getUrl(
            '*/*/save',
            array(
                '_current'   => true,
                'back'   => 'edit',
                'tab'=> '{{tab_id}}',
                'active_tab' => null
            )
        );
    }

    /**
     * get the validation url
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getValidationUrl()
    {
        return $this->getUrl('*/*/validate', array('_current'=>true));
    }

    /**
     * check if edit mode is read only
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function isReadonly()
    {
        $module = $this->getModule();
        if (!$module) {
            return false;
        }
        if (Mage::registry('module_read_only')) {
            return true;
        }
        if ($module = Mage::registry('current_module')) {
            $installedModules = array_keys((array)Mage::getConfig()->getNode('modules')->children());
            foreach ($installedModules as $installed) {
                if ($installed == $module->getExtensionName()) {
                    Mage::register('module_read_only', true);
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * get select for relations
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getRelationSelectTemplate()
    {
        /** @var Ultimate_ModuleCreator_Helper_Data $helper */
        $helper = Mage::helper('modulecreator');
        $types  = $helper->getRelationTypes();
        $template = '<select class="relation-select" name="relation[#{e1}][#{e2}]" id="relation_#{e1}_#{e2}">';
        foreach ($types as $type=>$values) {
            $template .= '<option value="'.$type.'">'.(string)$values->label.'</option>';
        }
        $template .= '</select>';
        return $template;
    }

    /**
     * check if fieldsets should be collapsed
     *
     * @access public
     * @return int
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getShowFieldsetsCollapsed()
    {
        return (int)Mage::getStoreConfigFlag(self::XML_FIELDSET_COLLAPSED);
    }
}
