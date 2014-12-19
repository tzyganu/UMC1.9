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
 * abstract system->config fieldset renderer
 *
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */
abstract class Ultimate_ModuleCreator_Block_Adminhtml_Modulecreator_System_Config_Form_Fieldset_Abstract extends Mage_Adminhtml_Block_System_Config_Form_Fieldset
{
    /**
     * @var Varien_Object
     */
    protected $_dummyElement;

    /**
     * @var Mage_Adminhtml_Block_System_Config_Form_Field
     */
    protected $_fieldRenderer;

    /**
     * @var array
     */
    protected $_values;

    /**
     * get the form name
     * @return mixed
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public abstract function getFormName();

    /**
     * render the config section
     *
     * @access public
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $html = $this->_getHeaderHtml($element);
        /** @var Ultimate_ModuleCreator_Helper_Data $helper */
        $helper = Mage::helper('modulecreator');
        $config = $helper->getConfig();
        $formName = $this->getFormName();
        if (!$config->getNode('forms/'.$formName)) {
            return '';
        }
        $fieldsets = $config->getNode('forms/'.$formName.'/fieldsets');
        $index = 0;
        foreach ((array)$fieldsets as $key => $set) {
            $positions = array();
            foreach ((array)$set->fields as $id=>$field) {
                if (!$field->system) {
                    continue;
                }
                $positions[(int)$field->position][$id] = $field;
            }
            ksort($positions);
            $sorted = array();
            foreach ($positions as $fields) {
                $sorted = array_merge($sorted, $fields);
            }
            foreach($sorted as $id => $field) {
                $html.= $this->_getFieldHtml($element, $id, $field);
            }
        }
        $html .= $this->_getFooterHtml($element);
        return $html;
    }

    /**
     * get field renderer.
     *
     * @access protected
     * @return Mage_Adminhtml_Block_System_Config_Form_Field
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _getFieldRenderer()
    {
        if (empty($this->_fieldRenderer)) {
            $this->_fieldRenderer = Mage::getBlockSingleton('adminhtml/system_config_form_field');
        }
        return $this->_fieldRenderer;
    }

    /**
     * get HTML for the field
     *
     * @access protected
     * @param Varien_Data_Form_Element_Fieldset $fieldset
     * @param string $key
     * @param $field
     * @return mixed
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _getFieldHtml($fieldset, $key, $field)
    {
        $formName = $this->getFormName();
        $path = 'modulecreator/'.$formName.'/' . $key;
        $data = Mage::getStoreConfig($path, 0);
        $settings = array(
            'name'          => 'groups['.$formName.'][fields]['.$key.'][value]',
            'label'         => (string)$field->label,
            'value'         => $data,
            'inherit'       => false,
            'can_use_default_value' => false,
            'can_use_website_value' => false,
        );
        if (in_array((string)$field->type, array('select', 'multiselect'))) {
            $settings['values'] = Mage::getModel((string)$field->source)->toArray(((string)$field->type == 'select'));
        }
        if ($field->tooltip) {
            $settings['tooltip'] = (string)$field->tooltip;
        }
        $field = $fieldset->addField(
            $formName.$key,
            (string)$field->type, $settings
        );
        $field->setRenderer($this->_getFieldRenderer());
        return $field->toHtml();
    }
}
