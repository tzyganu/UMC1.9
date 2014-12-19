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
 * main admin controller
 *
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */
class Ultimate_ModuleCreator_Adminhtml_ModulecreatorController extends Mage_Adminhtml_Controller_Action
{
    /**
     * default action
     *
     * @access public
     * @return void
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function indexAction()
    {
        $this->_title(Mage::helper('modulecreator')->__('Ultimate module creator'));
        $this->_getSession()->addNotice(
            Mage::helper('modulecreator')->__(
                'To delete a module from this list go to "<strong>%s</strong>" and remove the files "<strong>%s</strong>" and "<strong>%s</strong>" and folder "<strong>%s</strong>" if they exist. Replace <strong>Namespace_Module</strong> with the appropriate value for each module. There is no delete link in here for security reasons.',
                Mage::getBaseDir('var').DS.'modulecreator',
                'Namespace_Module.tgz',
                'package/Namespace_Module.xml',
                'package/Namespace_Module/'
            )
        );
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * grid action
     *
     * @access public
     * @return void
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * new action
     *
     * @access public
     * @return void
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * edit action
     *
     * @access public
     * @return void
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function editAction()
    {
        $module = $this->_initModule();
        $this->_title(Mage::helper('modulecreator')->__('Ultimate module creator'));
        if ($module) {
            $extensionName = $module->getNamespace().'_'.$module->getModuleName();
            $this->_getSession()->addNotice(
                Mage::helper('modulecreator')->__(
                    'You are editing the module: %s',
                    $extensionName
                )
            );
            $this->_title($extensionName);
        } else {
            $this->_title(Mage::helper('modulecreator')->__('Add module'));
        }
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * init module
     *
     * @access protected
     * @return mixed
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _initModule()
    {
        $packageName = base64_decode(strtr($this->getRequest()->getParam('id'), '-_,', '+/='));
        if ($packageName) {
            try {
                /** @var Ultimate_ModuleCreator_Helper_Data $helper */
                $helper      = Mage::helper('modulecreator');
                $path        = $helper->getLocalPackagesPath();
                $packageName = basename($packageName);
                $xmlFile = $path . $packageName . '.xml';
                if (file_exists($xmlFile) && is_readable($xmlFile)) {
                    $xml = simplexml_load_file($xmlFile, 'Varien_Simplexml_Element');
                    $module = $helper->loadModule($xml);
                    Mage::register('current_module', $module);
                    return $module;
                }
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage());
                $this->_redirect('*/*/index');
            }
        }
        return false;
    }

    /**
     * init a module from an array
     *
     * @access public
     * @param array $data
     * @return Ultimate_ModuleCreator_Model_Module
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _initModuleFromData($data)
    {
        $entitiesByIndex = array();
        /** @var Ultimate_ModuleCreator_Model_Module $module */
        $module = Mage::getModel('modulecreator/module');
        if (isset($data['settings'])) {
            $module->addData($data['settings']);
        }
        if (isset($data['entity'])) {
            $entities = $data['entity'];
            if (is_array($entities)) {
                foreach ($entities as $key=>$entityData) {
                    /** @var Ultimate_ModuleCreator_Model_Entity $entity */
                    $entity = Mage::getModel('modulecreator/entity');
                    $entity->addData($entityData);
                    $entity->setIndex($key);
                    if (isset($entityData['attributes']) && is_array($entityData['attributes'])) {
                        if (isset($entityData['attributes']['is_name'])) {
                            $isName = $entityData['attributes']['is_name'];
                            unset($entityData['attributes']['is_name']);
                            if (isset($entityData['attributes'][$isName])) {
                                $entityData['attributes'][$isName]['is_name'] = 1;
                            }
                        }
                        foreach ($entityData['attributes'] as $aKey=>$attributeData) {
                            /** @var Ultimate_ModuleCreator_Model_Attribute $attribute */
                            $attribute = Mage::getModel('modulecreator/attribute');
                            $attribute->addData($attributeData);
                            $attribute->setIndex($aKey);
                            $entity->addAttribute($attribute);
                        }
                    }
                    $module->addEntity($entity);
                    $entitiesByIndex[$key] = $entity;
                }
            }
            if (isset($data['relation'])) {
                foreach($data['relation'] as $index => $values) {
                    foreach ($values as $jndex=>$type) {
                        if (isset($entitiesByIndex[$index]) && isset($entitiesByIndex[$jndex])) {
                            /** @var Ultimate_ModuleCreator_Model_Relation $relation */
                            $relation = Mage::getModel('modulecreator/relation');
                            $relation->setEntities($entitiesByIndex[$index], $entitiesByIndex[$jndex], $type);
                            $module->addRelation($relation);
                        }
                    }
                }
            }
        }
        return $module;
    }

    /**
     * validate module before saving
     *
     * @access public
     * @return void
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function validateAction()
    {
        try{
            $response = new Varien_Object();
            $module = $this->_initModuleFromData($this->getRequest()->getPost());
            $errors = $module->validate();
            if (count($errors) == 0) {
                $messages = $module->buildModule();
                $module->save();
                $response->setError(false);
            } else {
                if (isset($errors[''])) {
                    $response->setMessage(implode('<br />', $errors['']));
                    unset($errors['']);
                }
                $response->setError(true);
                $response->setAttributes($errors);
            }
        } catch (Exception $e){
            $response->setError(true);
            $response->setMessage($e->getMessage());
        }
        $this->getResponse()->setBody($response->toJson());
    }

    /**
     * save module - actually only redirects the page
     * the save was done in validateAction(). there is no need to process the request twice.
     *
     * @access public
     * @return void
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function saveAction()
    {
        $this->_getSession()->addSuccess(
            Mage::helper('modulecreator')->__('Your extension has been created!')
        );
        $module = $this->_initModuleFromData($this->getRequest()->getPost());
        $redirectBack = $this->getRequest()->getParam('back', false);
        if ($redirectBack) {
            $this->_redirect(
                '*/*/edit',
                array(
                    'id'    => strtr(base64_encode($module->getExtensionName()), '+/=', '-_,'),
                    '_current'    => true
                )
            );
        } else {
            $this->_redirect('*/*/');
        }
    }

    /**
     * download module action
     *
     * @access public
     * @return void
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function downloadAction()
    {
        $what = $this->getRequest()->getParam('type');
        $packageName = base64_decode(strtr($this->getRequest()->getParam('id'), '-_,', '+/='));
        /** @var Ultimate_ModuleCreator_Helper_Data $helper */
        $helper = Mage::helper('modulecreator');
        $path   = $helper->getLocalModulesDir();
        $namePrefix = '';
        switch ($what) {
            case 'config' :
                $file = $path.'package'.DS.$packageName . '.xml';
                break;
            case 'list':
                $file = $path.'package'.DS.$packageName . DS. 'files.log';
                $namePrefix = $packageName.'_';
                break;
            case 'uninstall' :
                $file = $path.'package'.DS.$packageName . DS. 'uninstall.sql';
                $namePrefix = $packageName.'_';
                break;
            default:
                $file = $path . $packageName . '.tgz';
                break;
        }
        if (file_exists($file) && is_readable($file)) {
            $content = file_get_contents($file);
            $this->_prepareDownloadResponse($namePrefix.basename($file), $content);
        } else {
            $this->_getSession()->addError(
                Mage::helper('modulecreator')->__(
                    'Your extension archive was not created or is not readable'
                )
            );
            $this->_redirect('*/*');
        }
    }
}
