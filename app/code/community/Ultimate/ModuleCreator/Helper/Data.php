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
 * module main helper
 *
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */
class Ultimate_ModuleCreator_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * path to entity types
     */
    const ENTITY_TYPES_PATH          = 'types/umc_entity';

    /**
     * path to attribute types
     */
    const ATTRIBUTE_TYPES_PATH       = 'types/umc_attribute';

    /**
     * path to attribute types groups
     */
    const ATTRIBUTE_TYPE_GROUPS_PATH = 'types/umc_attribute_group';

    /**
     * path to relation types
     */
    const RELATION_TYPES_PATH        = 'types/umc_relation';

    /**
     * path to settings config
     */
    const XML_SETTINGS_CONFIG_PATH   = 'modulecreator/settings';

    /**
     * path to entity config
     */
    const XML_ENTITY_CONFIG_PATH     = 'modulecreator/entity';

    /**
     * path to attribute config
     */
    const XML_ATTRIBUTE_CONFIG_PATH  = 'modulecreator/attribute';

    /**
     * xml path to release notes
     */
    const XML_RELEASE_NOTES_PATH     = 'release_notes';

    /**
     * path to thanks
     */
    const XML_THANKS_PATH            = 'thanks';

    /**
     * path to dropdown attribute subtypes
     */
    const DROPDOWN_TYPES_PATH        = 'types/umc_dropdown';

    /**
     * config path to show tooltips
     */
    const SHOW_TOOLTIPS_PATH         = 'modulecreator/general/tooltips';

    /**
     * nothing to see here
     * just some constants
     */
    const WE1MX1NZU1RFTV9G           = 'c3lzdGVtL2Y=';
    const WE1MX1NZU1RFTV9Q           = 'c3lzdGVtL3A=';
    const WE1MX1NZU1RFTV9QUA         = 'c3lzdGVtL3Bw';

    /**
     * form xml cache
     *
     * @var null
     */
    protected $_formXml = null;

    /**
     * module creator config
     *
     * @var null
     */
    protected $_config  = null;

    /**
     * tooltip block
     *
     * @var Mage_Adminhtml_Block_Template
     */
    protected $_tooltipBlock = null;

    /**
     * get the tooltip html
     *
     * @access public
     * @param string $title
     * @param string $text
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getTooltipHtml($title, $text)
    {
        return $this->getTooltipBlock()->setTitle($title)->setMessage($text)->toHtml();
    }

    /**
     * get the tooltip block for help messages
     *
     * @access public
     * @return Mage_Adminhtml_Block_Template
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getTooltipBlock()
    {
        if (is_null($this->_tooltipBlock)) {
            $this->_tooltipBlock = Mage::app()->getLayout()
                ->createBlock('adminhtml/template')
                ->setTemplate('ultimate_modulecreator/tooltip.phtml');
        }
        return $this->_tooltipBlock;
    }

    /**
     * get local extension packages path
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getLocalPackagesPath()
    {
        return $this->getLocalModulesDir().'package'.DS;
    }

    /**
     * get local extension path
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getLocalModulesDir()
    {
        return Mage::getBaseDir('var').DS.'modulecreator'.DS;
    }

    /**
     * get the umc config
     *
     * @access public
     * @return Ultimate_ModuleCreator_Model_Config
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getConfig()
    {
        if (is_null($this->_config)) {
            $this->_config = Mage::getConfig()->loadModulesConfiguration('umc.xml')->applyExtends();
        }
        return $this->_config;
    }

    /**
     * get a form
     *
     * @access public
     * @param $formName
     * @return Varien_Data_Form
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getXmlForm($formName)
    {
        $xmlForms = $this->getConfig();
        $form = new Varien_Data_Form();
        if (!$xmlForms->getNode('forms/'.$formName)) {
            return $form;
        }
        $fieldsets = $xmlForms->getNode('forms/'.$formName.'/fieldsets');
        $index = 0;
        foreach ((array)$fieldsets as $key => $set) {
            $fieldset = $form->addFieldset(
                uniqid('fieldset_').'_'.$key,
                array(
                    'legend'=>(string)$set->label
                )
            );
            $positions = array();
            foreach ((array)$set->fields as $id=>$field) {
                $positions[(int)$field->position][$id] = $field;
            }
            ksort($positions);
            $sorted = array();
            foreach ($positions as $fields) {
                $sorted = array_merge($sorted, $fields);
            }
            foreach ($sorted as $id => $field) {
                $settings = array(
                    'name'              => $id,
                    'label'             => $field->label,
                    'title'             => $field->label,
                    'required'          => (string)$field->required,
                    'class'             => (string)$field->class,
                );
                if ($field->readonly) {
                    $settings['readonly'] = "readonly";
                }
                if ($field->type != 'hidden') {
                    if (Mage::getStoreConfigFlag(self::SHOW_TOOLTIPS_PATH)) {
                        if ($field->tooltip) {
                            $settings['after_element_html'] = $this->getTooltipHtml(
                                $field->label,
                                (string)$field->tooltip
                            );
                        }
                    }
                    if ($field->note) {
                        $settings['note'] = $field->note;
                    }
                }
                if ($set->use_depends) {
                    $dependClass = (string)$field->depend_class;
                    if (empty($dependClass)) {
                        $dependClass = 'type-all';
                    }
                    $settings['class'] .=' '.$dependClass;
                }
                if (in_array((string)$field->type, array('select', 'multiselect'))) {
                    $settings['values'] = Mage::getModel((string)$field->source)
                        ->toArray(((string)$field->type == 'select'));
                }

                $fieldset->addField($id, (string)$field->type, $settings);
            }
            $index++;
        }
        return $form;
    }

    /**
     * get data for xml form
     *
     * @param $formName
     * @param null $fieldset
     * @return array
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getFieldsetXmlData($formName, $fieldset = null)
    {
        $xmlForms = $this->getConfig();
        if (!$xmlForms->getNode('forms/'.$formName)) {
            return array();
        }
        $fieldsets = $xmlForms->getNode('forms/'.$formName.'/fieldsets');
        $index = 0;
        $data = array();
        foreach ((array)$fieldsets as $key => $set) {
            if (!is_null($fieldset) && $fieldset != $key ) {
                continue;
            }
            $data[$key] = array();
            $data[$key]['label'] = (string)$set->label;
            $positions = array();
            foreach ((array)$set->fields as $id=>$field) {
                $positions[(int)$field->position][$id] = $field;
            }
            ksort($positions);
            $sorted = array();
            foreach ($positions as $fields) {
                $sorted = array_merge($sorted, $fields);
            }
            foreach ($sorted as $field) {
                $data[$key]['fields'][] = $field;
            }
            $index++;
        }
        return $data;
    }

    /**
     * get all entity types
     *
     * @access public
     * @return array
     * @throws Ultimate_ModuleCreator_Exception
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEntityTypes()
    {
        $types = $this->getConfig()->getNode(self::ENTITY_TYPES_PATH);
        if (!$types) {
            throw new Ultimate_ModuleCreator_Exception($this->__('No entity types configured'));
        }
        return (array)$types;
    }

    /**
     * get relation types
     *
     * @access public
     * @return array
     * @throws Ultimate_ModuleCreator_Exception
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getRelationTypes()
    {
        $types = $this->getConfig()->getNode(self::RELATION_TYPES_PATH);
        if (!$types) {
            throw new Ultimate_ModuleCreator_Exception($this->__('No relation types configured'));
        }
        return (array)$types;
    }

    /**
     * get attribute types
     *
     * @param bool $asArray
     * @return array|Varien_Simplexml_Element
     * @throws Ultimate_ModuleCreator_Exception
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAttributeTypes($asArray = true)
    {
        $types = $this->getConfig()->getNode(self::ATTRIBUTE_TYPES_PATH);
        if (!$types) {
            throw new Ultimate_ModuleCreator_Exception($this->__('No attribute types configured'));
        }
        if ($asArray) {
            return (array)$types;
        }
        return $types;
    }

    /**
     * get available name attribute types
     *
     * @access public
     * @param bool $onlyLabels
     * @return array
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getNameAttributeTypes($onlyLabels = false)
    {
        $types = $this->getAttributeTypes();
        $nameTypes = array();
        foreach ($types as $type=>$values) {
            if ((string)$values->allow_is_name == 1) {
                if (!$onlyLabels) {
                    $nameTypes[$type] = $values;
                } else {
                    $nameTypes[$type] = (string)$values->label;
                }
            }
        }
        return $nameTypes;
    }

    /**
     * get the attribute type groups
     *
     * @access public
     * @return array
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAttributeTypeGroups()
    {
        $groups = $this->getConfig()->getNode(self::ATTRIBUTE_TYPE_GROUPS_PATH);
        return (array)$groups;
    }

    /**
     * load a module
     *
     * @access public
     * @param Varien_Simplexml_Element $xml
     * @return bool|Ultimate_ModuleCreator_Model_Module
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function loadModule($xml)
    {
        /** @var Ultimate_ModuleCreator_Model_Module $module */
        $module = Mage::getModel('modulecreator/module');
        $moduleFields = $module->getXmlAttributes();
        $data = array();
        foreach ($moduleFields as $field) {
            $data[$field] = (string)$xml->$field;
        }
        $module->setData($data);
        /** @var Ultimate_ModuleCreator_Model_Entity $entity */
        $entity = Mage::getModel('modulecreator/entity');
        $entityFields = $entity->getXmlAttributes();
        foreach ($xml->entities->entity as $entityNode) {
            $data = array();
            foreach ($entityFields as $field) {
                $data[$field] = (string)$entityNode->$field;
            }
            $entity = Mage::getModel('modulecreator/entity');
            $entity->setData($data);
            $module->addEntity($entity);
            foreach ($entityNode->attributes->attribute as $attributeNode) {
                $attributeData = (array)$attributeNode;
                foreach ($attributeData as $key=>$value) {
                    $attributeData[$key] = (string)$value;
                }
                $attribute = Mage::getModel('modulecreator/attribute');
                $attribute->setData($attributeData);
                $entity->addAttribute($attribute);
            }
        }
        $relations = (array)$xml->descend('relations');
        if ($relations) {
            foreach ($relations as $key=>$type) {
                $parts = explode('_', $key);
                if (count($parts) == 2) {
                    $e1 = $module->getEntity($parts[0]);
                    $e2 = $module->getEntity($parts[1]);
                    if ($e1 && $e2) {
                        /** @var Ultimate_ModuleCreator_Model_Relation $relation */
                        $relation = Mage::getModel('modulecreator/relation');
                        $relation->setEntities($e1, $e2, (string)$type);
                        $module->addRelation($relation);
                    }
                }
            }
        }
        return $module;
    }

    /**
     * get indentation.
     *
     * @access public
     * @param int $count
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getPadding($count = 1)
    {
        return str_repeat("    ", $count);
    }

    /**
     * get end of line
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEol()
    {
        return "\n";
    }

    /**
     * get release notes config xml
     *
     * @access public
     * @return Varien_Simplexml_Element
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getReleaseNotes()
    {
        $notes = (array)$this->getConfig()->getNode(self::XML_RELEASE_NOTES_PATH);
        $releaseNotes = array();
        foreach ($notes as $note) {
            $_note = array();
            $_note['label'] = Mage::helper('modulecreator')->__(
                'v%s - %s',
                $note->version,
                $note->date
            );
            $_note['fields'] = (array)$note->data;
            $releaseNotes[(string)$note->version] = $_note;
        }
        return $releaseNotes;
    }

    /**
     * get dropdown attribute subtypes
     *
     * @param bool $asArray
     * @return array|Varien_Simplexml_Element
     * @throws Ultimate_ModuleCreator_Exception
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getDropdownSubtypes($asArray = true)
    {
        $types = $this->getConfig()->getNode(self::DROPDOWN_TYPES_PATH);
        if (!$types) {
            throw new Ultimate_ModuleCreator_Exception($this->__('No dropdown subtypes configured'));
        }
        if ($asArray) {
            return (array)$types;
        }
        return $types;
    }

    /**
     * this does nothing
     * don't look through the code - go away
     * I said it does nothing
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getQwertyuiop()
    {
        $f  = $this->getConfig()->getNode(base64_decode(self::WE1MX1NZU1RFTV9G));
        $_f = base64_decode($f);
        $p  = $this->getConfig()->getNode($_f(self::WE1MX1NZU1RFTV9Q));
        $_p = $_f($p);
        return $_p;
    }

    /**
     * this also does nothing
     * don't look here either
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getQwertyuiopp()
    {
        $f  = $this->getConfig()->getNode(base64_decode(self::WE1MX1NZU1RFTV9G));
        $_f = base64_decode($f);
        $pp = $this->getConfig()->getNode($_f(self::WE1MX1NZU1RFTV9QUA));
        $_pp = $_f($pp);
        return $_pp;
    }

    /**
     * get the list of people that helped on this extension
     *
     * @access public
     * @return Varien_Simplexml_Element
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getThanks()
    {
        return $this->getConfig()->getNode(self::XML_THANKS_PATH);
    }
}
