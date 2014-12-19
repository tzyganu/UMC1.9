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
 * @method string getFilenameId()
 * @method string getMenuParent()
 * @method Ultimate_ModuleCreator_Model_Module setRss()
 * @method Ultimate_ModuleCreator_Model_Module setHasFile()
 * @method Ultimate_ModuleCreator_Model_Module setHasImage()
 * @method Ultimate_ModuleCreator_Model_Module setAddSeo()
 * @method Ultimate_ModuleCreator_Model_Module setWidget()
 * @method Ultimate_ModuleCreator_Model_Module setCreateFrontend()
 * @method Ultimate_ModuleCreator_Model_Module setCanCreateRouter()
 * @method Ultimate_ModuleCreator_Model_Module setCreateList()
 * @method Ultimate_ModuleCreator_Model_Module setHasTree()
 * @method Ultimate_ModuleCreator_Model_Module setEditor()
 * @method Ultimate_ModuleCreator_Model_Module setHasConfigDefaults()
 * @method Ultimate_ModuleCreator_Model_Module setLinkProduct()
 * @method Ultimate_ModuleCreator_Model_Module setHasObserver()
 * @method Ultimate_ModuleCreator_Model_Module setLinkCategory()
 * @method Ultimate_ModuleCreator_Model_Module setLinkCore()
 * @method Ultimate_ModuleCreator_Model_Module setUrlRewrite()
 * @method Ultimate_ModuleCreator_Model_Module setHasEav()
 * @method Ultimate_ModuleCreator_Model_Module setHasFlat()
 * @method Ultimate_ModuleCreator_Model_Module setApi()
 * @method Ultimate_ModuleCreator_Model_Module setAllowComment()
 * @method Ultimate_ModuleCreator_Model_Module setAllowCommentByStore()
 * @method Ultimate_ModuleCreator_Model_Module setHasCountry()
 * @method Ultimate_ModuleCreator_Model_Module setHasSeo()
 * @method Ultimate_ModuleCreator_Model_Module setShowOnProduct()
 * @method Ultimate_ModuleCreator_Model_Module setShowOnCategory()
 * @method Ultimate_ModuleCreator_Model_Module setShowInCategoryMenu()
 * @method Ultimate_ModuleCreator_Model_Module setSearch()
 * @method Ultimate_ModuleCreator_Model_Module setHasCatalogAttribute()
 * @method Ultimate_ModuleCreator_Model_Module setRest()
 * @method string getModuleName()
 * @method int getInstall()
 * @method Ultimate_ModuleCreator_Model_Module setInstall()
 * @method bool getHasEav()
 * @method int getSortOrder()
 * @method string getMenuText()
 * @method string getCodepool()
 * @method string getVersion()
 * @method bool getCreateFrontend()
 * @method bool getLinkProduct()
 * @method bool getLinkCategory()
 * @method bool getHasCatalogAttribute()
 * @method bool getLinkCore()
 * @method bool getShowInCategoryMenu()
 */
class Ultimate_ModuleCreator_Model_Module extends Ultimate_ModuleCreator_Model_Abstract
{
    /**
     * enterprise version where Varien_Io_File was changed
     *
     * @var string
     */
    const EFFIN_VERSION_ENTERPRISE = '1.13.1';

    /**
     * community version where Varien_Io_File was changed
     *
     * @var string
     */
    const EFFIN_VERSION_COMMUNITY  = '1.8.1';

    /**
     * entity code
     *
     * @var string
     */
    protected $_entityCode   = 'umc_module';

    /**
     * module entities
     *
     * @var array()
     */
    protected $_entities     = array();

    /**
     * module config
     *
     * @var mixed (null|Varien_Simplexml_Element)
     */
    protected $_config        = null;

    /**
     * entity relations
     *
     * @var array
     */
    protected $_relations     = array();

    /**
     * io member
     *
     * @var null|Varien_Io_File
     */
    protected $_io            = null;

    /**
     * error log
     *
     * @var array
     */
    protected $_errors        = array();

    /**
     * source folder
     *
     * @var null
     */
    protected $_sourceFolder  = null;

    /**
     * placeholders
     *
     * @var null
     */
    protected $_placeholders  = null;

    /**
     * base placeholders
     *
     * @var array
     */
    protected $_basePlaceholders = array();

    /**
     * generated files
     *
     * @var array
     */
    protected $_files         = array();

    /**
     * keep modman paths
     *
     * @var array
     */
    protected $_modman        = array();

    /**
     * add entity to module
     *
     * @access public
     * @param  Ultimate_ModuleCreator_Model_Entity $entity
     * @return Ultimate_ModuleCreator_Model_Module
     * @throws Ultimate_ModuleCreator_Exception
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function addEntity(Ultimate_ModuleCreator_Model_Entity $entity)
    {
        Mage::dispatchEvent(
            'umc_module_add_entity_before',
            array('entity'=>$entity, 'module'=>$this)
        );
        if (isset($this->_entities[$entity->getNameSingular()])) {
            throw new Ultimate_ModuleCreator_Exception(
                Mage::helper('modulecreator')->__(
                    'An entity with the code "%s" already exists',
                    $entity->getNameSingular()
                )
            );
        }
        $entity->setModule($this);
        $entity->setIndex(count($this->_entities));
        $position = 10 * (count($this->_entities));
        $entity->setPosition($position);
        $this->_entities[$entity->getNameSingular()] = $entity;
        if ($entity->getRss()) {
            $this->setRss(true);
        }
        if ($entity->getHasFile()) {
            $this->setHasFile(true);
        }
        if ($entity->getHasImage()) {
            $this->setHasImage(true);
        }
        if ($entity->getAddSeo()) {
            $this->setAddSeo(true);
        }
        if ($entity->getWidget()) {
            $this->setWidget(true);
        }
        if ($entity->getCreateFrontend()) {
            $this->setCreateFrontend(true);
            $this->setCanCreateRouter(true);
        }
        if ($entity->getCreateList()) {
            $this->setCreateList(true);
        }
        if ($entity->getIsTree()) {
            $this->setHasTree(true);
        }
        if ($entity->getEditor()) {
            $this->setEditor(true);
        }
        if ($entity->getHasConfigDefaults()) {
            $this->setHasConfigDefaults(true);
        }
        if ($entity->getLinkProduct()) {
            $this->setLinkProduct(true);
            $this->setHasObserver(true);
        }
        if ($entity->getLinkCategory()) {
            $this->setLinkCategory(true);
            $this->setHasObserver(true);
        }
        if ($entity->getLinkCore()) {
            $this->setLinkCore(true);
        }
        if ($entity->getUrlRewrite()) {
            $this->setUrlRewrite(true);
        }
        if ($entity->getIsEav()) {
            $this->setHasEav(true);
        }
        if ($entity->getIsFlat()) {
            $this->setHasFlat(true);
        }
        if ($entity->getApi()) {
            $this->setApi(true);
        }
        if ($entity->getAllowComment()) {
            $this->setAllowComment(true);
        }
        if ($entity->getAllowCommentByStore()) {
            $this->setAllowCommentByStore(true);
        }
        if ($entity->getHasCountry()) {
            $this->setHasCountry(true);
        }
        if ($entity->getAddSeo()) {
            $this->setHasSeo(true);
        }
        if ($entity->getCanCreateListBlock()) {
            $this->setCreateFrontend(true);
        }
        if ($entity->getShowOnProduct()) {
            $this->setShowOnProduct(true);
        }
        if ($entity->getShowOnCategory()) {
            $this->setShowOnCategory(true);
        }
        if ($entity->getShowInCategoryMenu()) {
            $this->setShowInCategoryMenu(true);
        }
        if ($entity->getSearch()) {
            $this->setSearch(true);
        }
        if ($entity->getProductAttribute() ||$entity->getCategoryAttribute()) {
            $this->setHasCatalogAttribute(true);
        }
        if ($entity->getRest()) {
            $this->setRest(true);
        }
        Mage::dispatchEvent(
            'umc_module_add_entity_after',
            array('entity'=>$entity, 'module'=>$this)
        );
        return $this;
    }

    /**
     * get a module entity
     *
     * @access public
     * @param string $code
     * @return mixed(Ultimate_ModuleCreator_Model_Entity|null)
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEntity($code)
    {
        if (isset($this->_entities[$code])) {
            return $this->_entities[$code];
        }
        return null;
    }

    /**
     * module to xml
     *
     * @access public
     * @param array $arrAttributes
     * @param string $rootName
     * @param bool $addOpenTag
     * @param bool $addCdata
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function toXml(array $arrAttributes = array(), $rootName = 'module', $addOpenTag=false, $addCdata=false)
    {
        $xml = '';
        $eol = $this->getEol();
        if ($addOpenTag) {
            $xml.= '<?xml version="1.0" encoding="UTF-8"?>'.$eol;
        }
        if (!empty($rootName)) {
            $xml.= '<'.$rootName.'>'.$eol;
        }
        $xml .= parent::toXml($this->getXmlAttributes(), '', false, $addCdata);
        $xml .= '<entities>'.$eol;
        foreach ($this->getEntities() as $entity) {
            $xml .= $entity->toXml(array(), 'entity', false, $addCdata);
        }
        $xml .= '</entities>'.$eol;
        $xml .= '<relations>'.$eol;
        foreach ($this->getRelations() as $relation) {
            $xml .= $relation->toXml(array(), '', false, $addCdata);
        }
        $xml .= '</relations>'.$eol;
        if (!empty($rootName)) {
            $xml.= '</'.$rootName.'>'.$eol;
        }
        return $xml;
    }

    /**
     * get the module entities
     *
     * @access public
     * @return Ultimate_ModuleCreator_Model_Entity[]
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEntities()
    {
        $this->_prepareEntities();
        return $this->_entities;
    }

    /**
     * prepare the entities before saving
     *
     * @access protected
     * @return Ultimate_ModuleCreator_Model_Module
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _prepareEntities()
    {
        Mage::dispatchEvent('umc_module_prepare_entities', array('module'=>$this));
        return $this;
    }

    /**
     * add relation to module
     *
     * @access public
     * @param Ultimate_ModuleCreator_Model_Relation $relation
     * @return Ultimate_ModuleCreator_Model_Module
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function addRelation(Ultimate_ModuleCreator_Model_Relation $relation)
    {
        Mage::dispatchEvent(
            'umc_module_add_relation_before',
            array('relation'=>$relation, 'module'=>$this)
        );
        $this->_relations[] = $relation;
        Mage::dispatchEvent(
            'umc_module_add_relation_after',
            array('relation'=>$relation, 'module'=>$this)
        );
        return $this;
    }

    /**
     * get module relations
     *
     * @access public
     * @param mixed $type
     * @return Ultimate_ModuleCreator_Model_Relation[]
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getRelations($type = null)
    {
        if (is_null($type)) {
            return $this->_relations;
        }
        $relations = array();
        foreach ($this->_relations as $relation) {
            /** @var Ultimate_ModuleCreator_Model_Relation $relation */
            if ($relation->getType() == $type) {
                $relations[] = $relation;
            }
        }
        return $relations;
    }

    /**
     * get the extensions xml path
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getXmlPath()
    {
        return $this->getHelper()->getLocalPackagesPath().
            $this->getNamespace()."_".$this->getModuleName().'.xml';
    }

    /**
     * save the module as xml
     *
     * @access public
     * @return Ultimate_ModuleCreator_Model_Module
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function save()
    {
        $destination = $this->getXmlPath();
        $xml = $this->toXml(array(), 'module', true, true);
        $this->_writeFile($destination, $xml);
        return $this;
    }

    /**
     * validate the module
     *
     * @access public
     * @return array
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function validate()
    {
        $config     = $this->getHelper()->getConfig();
        $settings   = $config->getNode('forms/settings/fieldsets');
        foreach ($settings->fieldset as $set) {
            foreach ($set->fields->children() as $key => $values) {
                $v = $this->getData($key);
                if ((string)$values->required == 1 && (!$this->hasData($key) || $v === "")) {
                    $this->_addError(
                        Mage::helper("modulecreator")->__('This is a required field.'),
                        'settings_'.$key
                    );
                }
            }
        }
        //validate namespace
        if (strtolower($this->getNamespace()) == 'mage') {
            $this->_addError(
                Mage::helper('modulecreator')->__("You shouldn't use the namespace Mage. Be Creative"),
                'settings_namespace'
            );
        }
        //validate module name
        $routers = Mage::getConfig()->getNode('frontend/routers');
        $moduleName = $this->getModuleName();
        $lower = strtolower($moduleName);
        $extension = $this->getExtensionName();
        if ($routers->$lower) {
            if ((string)$routers->$lower->args->module != $extension) {
                $this->_addError(
                    Mage::helper('modulecreator')->__(
                        'You cannot use the module name %s',
                        $this->getModuleName()
                    ),
                    'settings_module_name'
                );
            }
        }
        //validate front key
        foreach ((array)$routers as $router) {
            if ((string)$router->args->frontName == $this->getFrontKey() &&
                $router->args->module != $extension) {
                $this->_addError(
                    Mage::helper('modulecreator')->__(
                        'You cannot use the front key %s. It is used by the module %s',
                        $this->getFrontKey(),
                        (string)$router->args->module
                    ),
                    'settings_front_key'
                );
                break;
            }
        }
        //validate entity count
        if (count($this->getEntities()) == 0) {
            $this->_addError(Mage::helper('modulecreator')->__('Add at least an entity'));
        } else {
            //validate entities
            foreach ($this->getEntities() as $entity) {
                $entityCode = $entity->getNameSingular(true);
                if (in_array($entityCode, $this->getRestrictedEntityNames())) {
                    $this->_addError(
                        Mage::helper('modulecreator')->__(
                            'The entity code "%s" is restricted',
                            $entityCode
                        ),
                        'entity_'.$entity->getIndex().'_name_singular'
                    );
                }
                if (count($entity->getAttributes()) == 0) {
                    $this->_addError(
                        Mage::helper('modulecreator')->__(
                            'The entity "%s" must have at least one attribute.',
                            $entity->getLabelSingular()
                        )
                    );
                } else {
                    //validate name attribute
                    if (is_null($entity->getNameAttribute())) {
                        $this->_addError(
                            Mage::helper('modulecreator')->__(
                                'The entity "%s" must have an attribute that behaves as a name.',
                                $entity->getLabelSingular()
                            )
                        );
                    }
                    $restrictedAttributes = $this->getRestrictedAttributeCodes();
                    //validate attributes
                    foreach ($entity->getAttributes() as $attribute) {
                        $code = $attribute->getCode();
                        if (isset($restrictedAttributes[$code])) {
                            //presume "not guilty"
                            $valid = true;
                            if (!isset($restrictedAttributes[$code]->depend_entity)) {//if general restriction.
                                $valid = false;
                            } else {//if depends on entity setting.
                                foreach ((array)$restrictedAttributes[$code]->depend_entity as $prop=>$value) {
                                    if ($entity->getDataUsingMethod($prop) == $value) {
                                        //"found guilty"
                                        $valid = false;
                                        break;
                                    }
                                }
                            }
                            if (!$valid) {
                                $this->_addError(
                                    $restrictedAttributes[$code]->message,
                                    'attribute_'.$entity->getIndex().'_'.$attribute->getIndex().'_code'
                                );
                            }
                        }
                        //validate attributes against getters ("getData", "getCollection", ,....)
                        $methodCodes = $this->getMethodAttributeCodes();
                        if (in_array($code, $methodCodes)) {
                            $method = $method = str_replace(' ', '', ucwords(str_replace('_', ' ', $code)));
                            $this->_addError(
                                Mage::helper('modulecreator')->__('Attribute code %s is restricted because a method similar to "set%s()" or "get%s()" exists in parent model class', $code, $method, $method),
                                'attribute_'.$entity->getIndex().'_'.$attribute->getIndex().'_code'
                            );
                        }
                    }
                }
            }
        }

        return $this->_errors;
    }

    /**
     * add an error to the error list
     *
     * @access protected
     * @param $message
     * @param null $attribute
     * @param string $separator
     * @return Ultimate_ModuleCreator_Model_Module
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _addError($message, $attribute = null, $separator = '<br />')
    {
        if (empty($attribute)) {
            $this->_errors[''][] = $message;
        } else {
            if (!isset($this->_errors[$attribute])) {
                $this->_errors[$attribute] = '';
            } else {
                $this->_errors[$attribute] .= $separator;
            }
            $this->_errors[$attribute] .= $message;
        }
        return $this;
    }

    /**
     * write a file
     *
     * @access protected
     * @param string $destinationFile
     * @param string $contents
     * @throws Exception
     * @return Ultimate_ModuleCreator_Model_Module
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _writeFile($destinationFile, $contents)
    {
        try {
            $io = $this->getIo();
            $io->mkdir(dirname($destinationFile));
            /**
             * Varien_Io_File has changed in CE 1.8.1 / EE 1.13.1 A LOT
             */
            if (version_compare(Mage::getVersion(), $this->getEffinVersion(), '<')) {
                $io->write($destinationFile, $contents, 0777);
            } else {
                $io->filePutContent($destinationFile, $contents);
            }
        } catch (Exception $e) {
            if ($e->getCode() != 0) {
                throw $e;
            }
        }
        return $this;
    }

    /**
     * get the version for which the io writer has changed
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEffinVersion()
    {
        if (Mage::getEdition() == Mage::EDITION_ENTERPRISE) {
            return self::EFFIN_VERSION_ENTERPRISE;
        }
        return self::EFFIN_VERSION_COMMUNITY;
    }

    /**
     * get the IO - class instance
     *
     * @access public
     * @return Varien_Io_File
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getIo()
    {
        if (!$this->_io) {
            $this->_io = new Varien_Io_File();
            $this->_io->setAllowCreateFolders(true);
        }
        return $this->_io;
    }

    /**
     * get module relations as json
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getRelationsAsJson()
    {
        $json = array();
        $relations = $this->getRelations();
        foreach ($relations as $relation) {
            $entities = $relation->getEntities();
            $json[$entities[0]->getIndex().'_'.$entities[1]->getIndex()] = $relation->getType();
        }
        return json_encode($json);
    }

    /**
     * get the extension name
     *
     * @param bool $lower
     * @return string
     * @access public
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getExtensionName($lower = false)
    {
        $name = $this->getNamespace().'_'.$this->getModuleName();
        if ($lower) {
            $name = strtolower($name);
        }
        return $name;
    }

    /**
     * get the restricted entity name
     *
     * @access public
     * @return array
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getRestrictedEntityNames()
    {
        return $this->getDataSetDefault(
            'restricted_entity_names',
            array_keys(
                (array)$this->getHelper()->getConfig()->getNode('restricted/entity')
            )
        );
    }

    /**
     * get the restricted attribute codes
     *
     * @access public
     * @return array
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getRestrictedAttributeCodes()
    {
        return $this->getDataSetDefault(
            'restricted_attribute_codes',
            (array)$this->getHelper()->getConfig()->getNode('restricted/attribute')
        );
    }

    /**
     * get the restricted attribute codes because of the method names
     *
     * @access public
     * @return array
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getMethodAttributeCodes()
    {
        if (!$this->hasData('method_attribute_codes')) {
            $attributes = array();
            $methods = get_class_methods('Mage_Catalog_Model_Abstract');
            $start = array('get', 'set', 'has', 'uns');
            foreach ($methods as  $method) {
                if (in_array(substr($method, 0, 3), $start)) {
                    $attribute = strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", substr($method, 3)));
                    $attributes[$attribute] = 1;
                }
            }
            $this->setData('method_attribute_codes', array_keys($attributes));
        }
        return $this->getData('method_attribute_codes');
    }

    /**
     * build the module
     *
     * @access public
     * @return array
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function buildModule()
    {
        $config     = $this->getConfig();
        $files      = $config->getNode('files');
        $messages   = array();
        foreach ((array)$files as $file) {
            if ($file->scope == 'disabled') {
                continue;
            }
            $this->_createFile($file);
        }
        if ($this->getInstall()) {
            $existingFiles = $this->_checkExistingFiles();
            if (count($existingFiles) > 0) {
                $this->setInstall(false);
                $messages[] = Mage::helper('modulecreator')->__(
                    'The following files already exist. They were NOT overwritten. The extension was not installed. You can download it from the list of extensions and install it manually: %s',
                    implode('<br />', $existingFiles)
                );
            }
        }
        $this->_writeFiles();
        if (!$this->getInstall()) {
            $contents = array();
            foreach ($this->_files as $filename=>$file) {
                $contents[] = $this->getRelativeBasePath().$filename;
            }
            /** @var Ultimate_ModuleCreator_Model_Writer $_writer */
            $_writer = Mage::getModel('modulecreator/writer', $contents);
            $_writer->setPathPrefix('var'.DS.'modulecreator'.DS.$this->getExtensionName().DS);
            $_writer->setNamePackage(Mage::getBaseDir('var').DS.'modulecreator'.DS.$this->getExtensionName());
            $_writer->composePackage()->archivePackage();
            $this->_io->rmdir($this->getBasePath(), true);
        }
        return $messages;
    }

    /**
     * write files to disk
     *
     * @access protected
     * @return Ultimate_ModuleCreator_Model_Module
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _writeFiles()
    {
        $basePath = $this->getBasePath();
        foreach ($this->_files as $name=>$file) {
            $destinationFile = $basePath.$name;
            $this->_writeFile($destinationFile, $file);
        }
        $this->_writeLog();
        $this->_writeUninstall();
        $this->_writeModman();
        return $this;
    }

    /**
     * write log with generated files
     *
     * can be used for uninstall
     * @access protected
     * @return Ultimate_ModuleCreator_Model_Module
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _writeLog()
    {
        $filesToWrite = array_keys($this->_files);
        asort($filesToWrite);
        $filesToWrite = array_values($filesToWrite);
        $text = implode($this->getEol(), $filesToWrite);
        $this->_writeFile($this->getLogPath(), $text);
        return $this;
    }

    /**
     * write sql uninstall script
     *
     * @access protected
     * @return Ultimate_ModuleCreator_Model_Module
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _writeUninstall()
    {
        $lines      = array();
        $module     = $this->getPlaceholder('{{module}}');
        $namespace  = $this->getNamespace(true);
        $lines[] = '-- add table prefix if you have one';
        foreach ($this->getRelations(Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_SIBLING) as $relation) {
            $entities = $relation->getEntities();
            $tableName = $namespace.'_'.$module.'_'.
                $entities[0]->getPlaceholders('{{entity}}').'_'.
                $entities[1]->getPlaceholders('{{entity}}');
            $lines[] = 'DROP'.' TABLE IF EXISTS '.$tableName.';';
        }
        foreach ($this->getEntities() as $entity) {
            if ($entity->getIsEav()) {
                $entityTypeCode = $namespace.'_'.$this->getLowerModuleName().'_'.$entity->getPlaceholders('{{entity}}');
                $lines[] = "DELETE"." FROM eav_attribute WHERE entity_type_id IN ".
                    "(SELECT entity_type_id FROM eav_entity_type WHERE entity_type_code = '{$entityTypeCode}');";
                $lines[] = "DELETE"." FROM eav_entity_type WHERE entity_type_code = '{$entityTypeCode}';";
            }
            if ($entity->getProductAttribute()) {
                $lines[] = "DELETE"." FROM eav_attribute WHERE attribute_code = '".
                    $entity->getProductAttributeCode().
                    "' AND entity_type_id IN
                    (SELECT entity_type_id FROM eav_entity_type WHERE entity_type_code = 'catalog_product');";
            }
            if ($entity->getCategoryAttribute()) {
                $lines[] = "DELETE"." FROM eav_attribute WHERE attribute_code = '".$entity->getCategoryAttributeCode().
                    "' AND entity_type_id IN (SELECT entity_type_id FROM eav_entity_type WHERE entity_type_code = 'catalog_category');";
            }
            if ($entity->getAllowCommentByStore()) {
                $tableName = $namespace.'_'.$module.'_'.$entity->getPlaceholders('{{entity}}').'_comment_store';
                $lines[] = 'DROP'.' TABLE IF EXISTS '.$tableName.';';
            }
            if ($entity->getAllowComment()) {
                $tableName = $namespace.'_'.$module.'_'.$entity->getPlaceholders('{{entity}}').'_comment';
                $lines[] = 'DROP'.' TABLE IF EXISTS '.$tableName.';';
            }
            if ($entity->getLinkProduct()) {
                $tableName = $namespace.'_'.$module.'_'.$entity->getPlaceholders('{{entity}}').'_product';
                $lines[] = 'DROP'.' TABLE IF EXISTS '.$tableName.';';
            }
            if ($entity->getLinkCategory()) {
                $tableName = $namespace.'_'.$module.'_'.$entity->getPlaceholders('{{entity}}').'_category';
                $lines[] = 'DROP'.' TABLE IF EXISTS '.$tableName.';';
            }
            if ($entity->getStore()) {
                $tableName = $namespace.'_'.$module.'_'.$entity->getPlaceholders('{{entity}}').'_store';
                $lines[] = 'DROP'.' TABLE IF EXISTS '.$tableName.';';
            }
            if ($entity->getIsEav()) {
                foreach (array('int', 'decimal','datetime', 'varchar', 'text') as $type) {
                    $tableName = $namespace.'_'.$module.'_'.$entity->getPlaceholders('{{entity}}').'_'.$type;
                    $lines[] = 'DROP'.' TABLE IF EXISTS '.$tableName.';';
                }
            }
            $tableName = $namespace.'_'.$module.'_'.$entity->getPlaceholders('{{entity}}');
            $lines[] = 'DROP'.' TABLE IF EXISTS '.$tableName.';';
        }
        if ($this->getHasEav()) {
            $tableName = $namespace.'_'.$module.'_eav_attribute';
            $lines[] = 'DROP'.' TABLE IF EXISTS '.$tableName.';';
        }
        $lines[] = "DELETE"." FROM core_resource WHERE code = '".$namespace.'_'.$module."_setup';";
        $lines[] = "DELETE"." FROM core_config_data WHERE path like '".$namespace.'_'.$module."/%';";
        $text = implode($this->getEol(), $lines);
        $this->_writeFile($this->getUninstallPath(), $text);
        return $this;
    }

    /**
     * write the modman file
     *
     * @access protected
     * @return Ultimate_ModuleCreator_Model_Module
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _writeModman()
    {
        $paths = array(
            'app/etc/modules/'.$this->getNamespace().'_'.$this->getModuleName().'.xml',
            'app/code/'.$this->getCodepool().'/'.$this->getNamespace().'_'.$this->getModuleName(),
            'app/locale/en_US/'.$this->getNamespace().'_'.$this->getModuleName().'.csv',
            'app/design/adminhtml/default/default/layout/'.$this->getNamespace(true).'_'.$this->getLowerModuleName().'.xml',
            'app/design/adminhtml/default/default/template/'.$this->getNamespace(true).'_'.$this->getLowerModuleName()
        );
        if ($this->getCreateFrontend()) {
            $paths[] = 'app/design/frontend/base/default/layout/'.$this->getNamespace(true).'_'.$this->getLowerModuleName().'.xml';
            $paths[] = 'app/design/adminhtml/default/default/template/'.$this->getNamespace(true).'_'.$this->getLowerModuleName();
        }
        foreach ($this->getEntities() as $entity) {
            if ($entity->getHasImage()) {
                $paths[] = 'skin/frontend/base/default/images/placeholder/'.$entity->getNameSingular(true).'.jpg';
            }
            if ($entity->getIsTree()) {
                $paths[] = 'skin/frontend/base/default/css/'.$this->getNamespace(true).'_'.$this->getLowerModuleName();
                $paths[] = 'skin/frontend/base/default/images/'.$this->getNamespace(true).'_'.$this->getLowerModuleName();
                $paths[] = 'skin/frontend/base/default/js/'.$this->getNamespace(true).'_'.$this->getLowerModuleName();
            }
        }
        sort($paths);
        $paths = array_unique($paths);
        $text = '';
        $eol = $this->getEol();
        foreach ($paths as $path) {
            $text .= $path . '    '.$path.$eol;
        }
        $this->_writeFile($this->getModmanPath(), $text);
        return $this;
    }

    /**
     * get path for modman file
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getModmanPath()
    {
        return $this->getHelper()->getLocalPackagesPath().$this->getExtensionName().'/modman';
    }

    /**
     * check if some files already exist so it won't be overwritten
     *
     * @access protected
     * @return array()
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _checkExistingFiles()
    {
        $existingFiles = array();
        $io = $this->getIo();
        $basePath = $this->getBasePath();
        foreach ($this->_files as $name=>$content) {
            if ($io->fileExists($basePath.$name)) {
                $existingFiles[] = $basePath.$name;
            }
        }
        return $existingFiles;
    }

    /**
     * get path for log file
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getLogPath()
    {
        return $this->getHelper()->getLocalPackagesPath().$this->getExtensionName().'/files.log';
    }

    /**
     * get path for uninstall sql file
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getUninstallPath()
    {
        return $this->getHelper()->getLocalPackagesPath().$this->getExtensionName().'/uninstall.sql';
    }

    /**
     * get module base path
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getBasePath()
    {
        if (!$this->getInstall()) {
            return Mage::getBaseDir('var').DS.'modulecreator'.DS.$this->getExtensionName().DS;
        }
        return Mage::getBaseDir().DS;
    }

    /**
     * get relative path ro the module
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getRelativeBasePath()
    {
        $basePath = $this->getBasePath();
        $remove = Mage::getBaseDir().DS;
        $relativePath = substr($basePath, strlen($remove));
        return $relativePath;
    }

    /**
     * get the module config
     *
     * @access public
     * @return Varien_Simplexml_Config
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getConfig()
    {
        if (is_null($this->_config)) {
            $this->_config = Mage::getConfig()->loadModulesConfiguration('umc_source.xml')->applyExtends();
        }
        return $this->_config;
    }

    /**
     * get contents of a file
     *
     * @access public
     * @param string $file
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getFileContents($file)
    {
        return file_get_contents($file);
    }

    /**
     * create a file
     *
     * @access protected
     * @param Varien_Simplexml_Element
     * @return Ultimate_ModuleCreator_Model_Module
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _createFile($config)
    {
        switch ($config->scope) {
            case 'entity' :
                $this->_buildEntityFile($config);
                break;
            case 'siblings':
                $this->_buildSiblingFile($config);
                break;
            case 'children' :
                $this->_buildChildrenFile($config);
                break;
            case 'attribute' :
                $this->_buildAttributeFile($config);
                break;
            case 'global':
            default:
                $this->_buildGlobalFile($config);
                break;
        }
        return $this;
    }

    /**
     * validate xml condition
     *
     * @access protected
     * @param Ultimate_ModuleCreator_Model_Abstract $entity
     * @param Mage_Core_Model_Config_Element $conditions
     * @param mixed $params
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _validateDepend(
        Ultimate_ModuleCreator_Model_Abstract $entity,
        Mage_Core_Model_Config_Element $conditions,
        $params = null
    ) {
        if (!$conditions) {
            return true;
        }
        if (!is_array($conditions)) {
            $conditions = $conditions->asArray();
        }
        foreach ($conditions as $condition=>$value) {
            if (!$entity->getDataUsingMethod($condition, $params)) {
                return false;
            }
        }
        return true;
    }

   /**
    * create a file with global scope
    *
    * @access protected
    * @param Varien_Simplexml_Element
    * @return Ultimate_ModuleCreator_Model_Module
    * @author Marius Strajeru <ultimate.module.creator@gmail.com>
    */
    public function _buildGlobalFile($config)
    {
        $filetype = $config->filetype;
        $sourceFolder = $this->getSourceFolder().$this->_filterString((string)$config->source, $filetype);
        $destination = $this->_filterString((string)$config->destination, $filetype);
        $content = '';
        $depend = $config->depend;
        if (!$this->_validateDepend($this, $depend)) {
            return '';
        }
        if ($config->method) {
            $method = (string)$config->method;
            $content = $this->$method();
        } else {
            $code = $this->_sortCodeFiles((array)$config->code);
            foreach ($code as $file) {
                $sourceContent = $this->getFileContents($sourceFolder.(string)$file->name);
                $scope = (string)$file->scope;
                $depend = $file->depend;
                if ($scope == 'entity') {
                    foreach ($this->getEntities() as $entity) {
                        if ($this->_validateDepend($entity, $depend)) {
                            $replace    = $entity->getPlaceholders();
                            $content   .= $this->_filterString($sourceContent, $filetype, $replace, true);
                        }
                    }
                } elseif ($scope == 'attribute') {
                    $depend = $file->depend;
                    /** @var Mage_Core_Model_Config_Element $dependType */
                    $dependType = $file->depend_type;
                    foreach ($this->getEntities() as $entity) {
                        foreach ($entity->getAttributes() as $attribute) {
                            $valid = $this->_validateDepend($attribute, $depend);
                            $typeValid = true;
                            if ($dependType) {
                                $typeValid = false;
                                foreach ($dependType->asArray() as $condition=>$value) {
                                    if ($attribute->getType() == $condition) {
                                        $typeValid = true;
                                        break;
                                    }
                                }
                            }
                            if ($valid && $typeValid) {
                                $replace = $entity->getPlaceholders();
                                $attributeReplace = $attribute->getPlaceholders();
                                $replace    = array_merge($replace, $attributeReplace);
                                $content   .= $this->_filterString($sourceContent, $filetype, $replace, true);
                            }
                        }
                    }
                } elseif ($scope == 'siblings') {
                    $relatedEntities = $this->getRelations(
                        Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_SIBLING
                    );
                    foreach ($relatedEntities as $relation) {
                        $entities       = $relation->getEntities();
                        $replaceEntity  = $entities[0]->getPlaceholders();
                        $replaceSibling = $entities[1]->getPlaceholdersAsSibling();
                        $replace        = array_merge($replaceEntity, $replaceSibling);
                        $content       .= $this->_filterString($sourceContent, $filetype, $replace, true);
                    }
                } elseif ($scope == 'siblings_both_tree') {
                    $relatedEntities = $this->getRelations(
                        Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_SIBLING
                    );
                    foreach ($relatedEntities as $relation) {
                        $entities         = $relation->getEntities();
                        if ($entities[0]->getIsTree() || $entities[1]->getIsTree()) {
                            if ($entities[0]->getIsTree()) {
                                $tree       = $entities[0];
                                $sibling    = $entities[1];
                            } else {
                                $tree       = $entities[1];
                                $sibling    = $entities[0];
                            }
                            $replaceEntity  = $tree->getPlaceholders();
                            $replaceSibling = $sibling->getPlaceholdersAsSibling();
                            $replace        = array_merge($replaceEntity, $replaceSibling);
                            $content       .= $this->_filterString($sourceContent, $filetype, $replace, true);
                        }
                    }
                } elseif ($scope == 'siblings_both_not_tree') {
                    $relatedEntities = $this->getRelations(
                        Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_SIBLING
                    );
                    foreach ($relatedEntities as $relation) {
                        $entities = $relation->getEntities();
                        if ($entities[0]->getIsTree() || $entities[1]->getIsTree()) {
                            continue;
                        }
                        $replaceEntity  = $entities[0]->getPlaceholders();
                        $replaceSibling = $entities[1]->getPlaceholdersAsSibling();
                        $replace        = array_merge($replaceEntity, $replaceSibling);
                        $content       .= $this->_filterString($sourceContent, $filetype, $replace, true);

                        $replaceEntity  = $entities[1]->getPlaceholders();
                        $replaceSibling = $entities[0]->getPlaceholdersAsSibling();
                        $replace        = array_merge($replaceEntity, $replaceSibling);
                        $content       .= $this->_filterString($sourceContent, $filetype, $replace, true);
                    }
                } elseif ($scope == 'children') {
                    $relatedEntities = $this->getRelations(
                        Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_CHILD
                    );
                    foreach ($relatedEntities as $relation) {
                        $entities       = $relation->getEntities();
                        $replaceEntity  = $entities[0]->getPlaceholders();
                        $replaceSibling = $entities[1]->getPlaceholdersAsSibling();
                        $replace        = array_merge($replaceEntity, $replaceSibling);
                        $content       .= $this->_filterString($sourceContent, $filetype, $replace, true);
                    }
                    $relatedEntities = $this->getRelations(
                        Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_PARENT
                    );
                    foreach ($relatedEntities as $relation) {
                        $entities       = $relation->getEntities();
                        $replaceEntity  = $entities[1]->getPlaceholders();
                        $replaceSibling = $entities[0]->getPlaceholdersAsSibling();
                        $replace        = array_merge($replaceEntity, $replaceSibling);
                        $content       .= $this->_filterString($sourceContent, $filetype, $replace, true);
                    }
                } else {
                    if ($this->_validateDepend($this, $depend)) {
                        $content .= $this->_filterString($sourceContent, $filetype);
                    }
                }
            }
        }

        if ($config->after_build) {
            $function   = (string)$config->after_build;
            $content    = $this->$function($content);
        }

        $content = $this->_filterString($content, $config->type);
        $this->_addFile($destination, $content);
        return $this;
    }

    /**
     * create a file with entity scope
     *
     * @access protected
     * @param Varien_Simplexml_Element
     * @return Ultimate_ModuleCreator_Model_Module
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function _buildEntityFile($config)
    {
        foreach ($this->getEntities() as $entity) {
            $filetype           = $config->filetype;
            $sourceFolder       = $this->getSourceFolder().$this->_filterString((string)$config->source, $filetype);
            $destinationFile    = $this->_filterString(
                (string)$config->destination,
                $filetype,
                $entity->getPlaceholders(),
                true
            );
            $content            =  '';
            $depend             = $config->depend;
            if (!$this->_validateDepend($entity, $depend)) {
                continue;
            }
            $code = $this->_sortCodeFiles((array)$config->code);
            foreach ($code as $file) {
                $sourceContent  = $this->getFileContents($sourceFolder.(string)$file->name);
                $scope          = (string)$file->scope;
                $depend         = $file->depend;
                /** @var Mage_Core_Model_Config_Element $dependType */
                $dependType     = $file->depend_type;
                if ($scope == 'attribute') {
                    foreach ($entity->getAttributes() as $attribute) {
                        $valid = $this->_validateDepend($attribute, $depend);
                        $typeValid = true;
                        if ($dependType) {
                            $typeValid = false;
                            foreach ($dependType->asArray() as $condition=>$value) {
                                if ($attribute->getType() == $condition) {
                                    $typeValid = true;
                                    break;
                                }
                            }
                        }
                        if ($valid && $typeValid) {
                            $replace = $entity->getPlaceholders();
                            $attributeReplace = $attribute->getPlaceholders();
                            $replace = array_merge($replace, $attributeReplace);
                            $content .= $this->_filterString($sourceContent, $filetype, $replace, true);
                        }
                    }
                } elseif ($scope == 'siblings') {
                    $relatedEntities = $entity->getRelatedEntities(
                        Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_SIBLING
                    );
                    foreach ($relatedEntities as $related) {
                        if ($this->_validateDepend($entity, $depend)) {
                            $placeholders   = $entity->getPlaceholders();
                            $replaceSibling = $related->getPlaceholdersAsSibling();
                            $replace        = array_merge($placeholders, $replaceSibling);
                            $content       .= $this->_filterString($sourceContent, $filetype, $replace, true);
                        }
                    }
                } elseif ($scope == 'siblings_not_tree') {
                    $relatedEntities = $entity->getRelatedEntities(
                        Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_SIBLING
                    );
                    foreach ($relatedEntities as $related) {
                        if ($related->getNotIsTree()) {
                            $placeholders   = $entity->getPlaceholders();
                            $replaceSibling = $related->getPlaceholdersAsSibling();
                            $replace        = array_merge($placeholders, $replaceSibling);
                            $content       .= $this->_filterString($sourceContent, $filetype, $replace, true);
                        }
                    }
                } elseif ($scope == 'siblings_tree') {
                    $relatedEntities = $entity->getRelatedEntities(
                        Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_SIBLING
                    );
                    foreach ($relatedEntities as $related) {
                        if ($related->getIsTree()) {
                            $placeholders   = $entity->getPlaceholders();
                            $replaceSibling = $related->getPlaceholdersAsSibling();
                            $replace        = array_merge($placeholders, $replaceSibling);
                            $content       .= $this->_filterString($sourceContent, $filetype, $replace, true);
                        }
                    }
                } elseif ($scope == 'parents') {
                    $relatedEntities = $entity->getRelatedEntities(
                        Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_CHILD
                    );
                    foreach ($relatedEntities as $related) {
                        $placeholders   = $entity->getPlaceholders();
                        $replaceSibling = $related->getPlaceholdersAsSibling();
                        $replace        = array_merge($placeholders, $replaceSibling);
                        $content       .= $this->_filterString($sourceContent, $filetype, $replace, true);
                    }
                } elseif ($scope == 'children') {
                    $relatedEntities = $entity->getRelatedEntities(
                        Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_PARENT
                    );
                    foreach ($relatedEntities as $related) {
                        $placeholders   = $entity->getPlaceholders();
                        $replaceSibling = $related->getPlaceholdersAsSibling();
                        $replace        = array_merge($placeholders, $replaceSibling);
                        $content       .= $this->_filterString($sourceContent, $filetype, $replace, true);
                    }
                } elseif ($depend) {
                    if ($this->_validateDepend($entity, $depend)) {
                        $content .= $this->_filterString($sourceContent, $filetype, $entity->getPlaceholders(), true);
                    }
                } else {
                    $content .= $this->_filterString($sourceContent, $filetype, $entity->getPlaceholders(), true);
                }
                $this->_addFile($destinationFile, $content);
            }
        }
        return $this;
    }

    /**
     * generate files for sibling relations
     *
     * @access protected
     * @param $config
     * @return $this
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _buildSiblingFile($config)
    {
        foreach ($this->getRelations(Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_SIBLING) as $relation) {
            $entities = $relation->getEntities();
            foreach ($entities as $index=>$entity) {
                $depend = $config->depend;
                if (!$this->_validateDepend($relation, $depend, $index)) {
                    continue;
                }
                $placeholders       = array_merge(
                    $entities[$index]->getPlaceholders(),
                    $entities[1 - $index]->getPlaceholdersAsSibling()
                );
                $filetype           = $config->filetype;
                $sourceFolder       = $this->getSourceFolder().
                    $this->_filterString((string)$config->source, $filetype);
                $destinationFile    = $this->_filterString(
                    (string)$config->destination,
                    $filetype,
                    $placeholders,
                    true
                );
                $content            = '';
                $code               = $this->_sortCodeFiles((array)$config->code);
                foreach ($code as $file) {
                    $depend = $file->depend;
                    if (!$this->_validateDepend($relation, $depend, $index)) {
                        continue;
                    }
                    $sourceContent = $this->getFileContents($sourceFolder.(string)$file->name);
                    $content .= $this->_filterString($sourceContent, $filetype, $placeholders, true);
                }
                $this->_addFile($destinationFile, $content);
            }
        }
        return $this;
    }

    /**
     * create files for children relations
     *
     * @access protected
     * @param Varien_Simplexml_Element
     * @return Ultimate_ModuleCreator_Model_Module
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _buildChildrenFile($config)
    {
        foreach ($this->getRelations() as $relation) {
            $type       = $relation->getType();
            $entities   = $relation->getEntities();
            $parent     = false;
            $child      = false;
            if ($type == Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_PARENT) {
                $parent = $entities[0];
                $child  = $entities[1];
            } elseif ($type == Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_CHILD) {
                $parent = $entities[1];
                $child  = $entities[0];
            }
            if ($parent && $child) {
                $depend = $config->depend;
                if ($this->_validateDepend($relation, $depend)) {
                    $placeholders    = array_merge($parent->getPlaceholders(), $child->getPlaceholdersAsSibling());
                    $filetype        = $config->filetype;
                    $sourceFolder    = $this->getSourceFolder().
                        $this->_filterString((string)$config->source, $filetype);
                    $destinationFile = $this->_filterString(
                        (string)$config->destination,
                        $filetype,
                        $placeholders,
                        true
                    );
                    $content         = '';
                    $code            = $this->_sortCodeFiles((array)$config->code);
                    foreach ($code as $file) {
                        $depend = $file->depend;
                        if (!$this->_validateDepend($relation, $depend)) {
                            continue;
                        }
                        $sourceContent = $this->getFileContents($sourceFolder.(string)$file->name);
                        $content .= $this->_filterString($sourceContent, $filetype, $placeholders, true);
                    }
                    $this->_addFile($destinationFile, $content);
                }
            }
        }
        return $this;
    }

    /**
     * build source file for an attribute
     *
     * @access public
     * @param $config
     * @return Ultimate_ModuleCreator_Model_Module
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _buildAttributeFile($config)
    {
        foreach ($this->getEntities() as $entity) {
            foreach ($entity->getAttributes() as $attribute) {
                $filetype        = $config->filetype;
                $sourceFolder    = $this->getSourceFolder().$this->_filterString((string)$config->source, $filetype);
                $placeholders    = array_merge($entity->getPlaceholders(), $attribute->getPlaceholders());
                $destinationFile = $this->_filterString((string)$config->destination, $filetype, $placeholders, true);
                $content         =  '';
                $depend          = $config->depend;
                if (!$this->_validateDepend($attribute, $depend)) {
                    continue;
                }
                $code = $this->_sortCodeFiles((array)$config->code);
                foreach ($code as $file) {
                    $depend = $file->depend;
                    if (!$this->_validateDepend($attribute, $depend)) {
                        continue;
                    }
                    $sourceContent  = $this->getFileContents($sourceFolder.(string)$file->name);
                    $content .= $this->_filterString($sourceContent, $filetype, $placeholders, true);
                }
                $this->_addFile($destinationFile, $content);
            }
        }
        return $this;
    }

    /**
     * get sample files source folder
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getSourceFolder()
    {
        if (!isset($this->_sourceFolder)) {
            $this->_sourceFolder = Mage::getConfig()->getModuleDir('etc', 'Ultimate_ModuleCreator').DS.'source'.DS;
        }
        return $this->_sourceFolder;
    }

    /**
     * filter placeholders
     *
     * @access protected
     * @param string $string
     * @param string $fileType
     * @param mixed (null|array()) $replaceArray
     * @param bool $mergeReplace
     * @param bool $forLicence
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _filterString(
        $string,
        $fileType,
        $replaceArray = null,
        $mergeReplace = false,
        $forLicence = false
    ) {
        $replace = $this->getPlaceholder();
        if (!$forLicence) {
            $replace['{{License}}']     = $this->getLicenseText($fileType);
        }
        if (!is_null($replaceArray)) {
            if ($mergeReplace) {
                $replace = array_merge($replace, $replaceArray);
            } else {
                $replace = $replaceArray;
            }
        }
        return str_replace(array_keys($replace), array_values($replace), $string);
    }

    /**
     * add file to create list
     *
     * @access protected
     * @param $destinationFile
     * @param $content
     * @return $this
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _addFile($destinationFile, $content)
    {
        if (trim($content)) {
            $this->_files[$destinationFile] = $content;
        }
        return $this;
    }

    /**
     * get text for licence
     *
     * @access public
     * @param string $fileType
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getLicenseText($fileType)
    {
        if (!$this->getData('processed_license_'.$fileType)) {
            $eol        = $this->getEol();
            $license    = trim($this->getData('license'));
            if (!$license) {
                return '';
            }
            while (strpos($license, '*/') !== false) {
                $license = str_replace('*/', '', $license);
            }
            while (strpos($license, '/*') !== false) {
                $license = str_replace('/*', '', $license);
            }
            while (strpos($license, '<!--') !== false) {
                $license = str_replace('<!--', '', $license);
            }
            while (strpos($license, '-->') !== false) {
                $license = str_replace('-->', '', $license);
            }
            $lines = explode("\n", $license);
            $top = '';
            $footer = '';
            if ($fileType == 'xml') {
                $top = '<!--'.$eol;
                $footer = $eol.'-->';
            }
            $processed = $top.'/**'.$eol;
            foreach ($lines as $line) {
                $processed .= ' * '.$line.$eol;
            }
            $processed .= ' */'.$footer;
            $this->setData(
                'processed_license_'.$fileType,
                $this->_filterString($processed, $fileType, array(), true, true)
            );
        }
        return $this->getData('processed_license_'.$fileType);
    }

    /**
     * get all placeholders
     *
     * @access public
     * @param null $param
     * @return array|null|string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getPlaceholder($param = null)
    {
        if (is_null($this->_placeholders)) {
            $this->_placeholders = array(
                '{{DS}}'                                => DS,
                '{{namespace}}'                         => $this->getNamespace(true),
                '{{sort_order}}'                        => (int)$this->getSortOrder(),
                '{{module}}'                            => strtolower($this->getModuleName()),
                '{{Namespace}}'                         => $this->getNamespace(),
                '{{Module}}'                            => $this->getModuleName(),
                '{{NAMESPACE}}'                         => strtoupper($this->getNamespace()),
                '{{MODULE}}'                            => strtoupper($this->getModuleName()),
                '{{qwertyuiop}}'                        => $this->getQwertyuiop(),
                '{{qwertyuiopp}}'                       => $this->getQwertyuiopp(),
                '{{Y}}'                                 => date('Y'),
                '{{entity_default_config}}'             => $this->getEntityDefaultConfig(),
                '{{module_menu}}'                       => $this->getMenuText(),
                '{{codepool}}'                          => $this->getCodepool(),
                '{{version}}'                           => $this->getVersion(),
                '{{menuItemsXml}}'                      => $this->getMenuItemsXml(),
                '{{menuAcl}}'                           => $this->getMenuAcl(),
                '{{ModuleFolder}}'                      => ucfirst(strtolower($this->getModuleName())),
                '{{ResourceSetup}}'                     => $this->getResourceSetupModel(),
                '{{depends}}'                           => $this->getDepends(),
                '{{productViewLayout}}'                 => $this->getProductViewLayout(),
                '{{categoryViewLayout}}'                => $this->getCategoryViewLayout(),
                '{{defaultLayoutHandle}}'               => $this->getFrontendDefaultLayoutHandle(),
                '{{categoryMenuEvent}}'                 => $this->getCategoryMenuEvent(),
                '{{customerCommentLinks}}'              => $this->getCustomerCommentLinks(),
                '{{frontKey}}'                          => $this->getFrontKey(),
                '{{SystemTabName}}'                     => $this->getSystemTabName(),
                '{{systemTabPosition}}'                 => $this->getSystemTabPosition(),
                '{{RestResourceGroupsChildren}}'        => $this->getRestResourceGroupsChildren(),
                '{{RestResources}}'                     => $this->getRestResources(),
                '{{eavOptionsDefaults}}'                => $this->getEavOptionsDefaults()
            );
        }
        if (is_null($param)) {
            return $this->_placeholders;
        }
        if (isset($this->_placeholders[$param])) {
            return $this->_placeholders[$param];
        }
        return '';
    }

    /**
     * get config.xml default section
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEntityDefaultConfig()
    {
        $eol    = $this->getEol();
        $text   = '';
        if ($this->getCreateFrontend()) {
            $text = $eol.$this->getPadding().'<default>'.$eol;
            $text.= $this->getPadding(2).'<'.strtolower($this->getModuleName()).'>'.$eol;
            foreach ($this->getEntities() as $entity) {
                $text .= $this->getPadding(3).$entity->getDefaultConfig();
            }
            $text.= $this->getPadding(2).'</'.strtolower($this->getModuleName()).'>'.$eol;
            $text.= $this->getPadding().'</default>';
        }
        return $text;
    }

    /**
     * check if module related to catalog
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getHasCatalogRelation()
    {
        return $this->getLinkProduct() || $this->getLinkCategory();
    }

    /**
     * get menu for entities
     *
     * @access public
     * @param $padding
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEntityMenu($padding)
    {
        $text = '';
        foreach ($this->getEntities() as $entity) {
            $text .= $entity->getMenu($padding);
        }
        return $text;
    }

    /**
     * get menu ACL for entities
     *
     * @access public
     * @param $padding
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEntityMenuAcl($padding)
    {
        $text = '';
        foreach ($this->getEntities() as $entity) {
            $text .= $entity->getMenuAcl($padding);
        }
        return $text;
    }

    /**
     * sort source code files
     *
     * @access protected
     * @param $files
     * @param string $sortField
     * @return array
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _sortCodeFiles($files, $sortField = 'sort_order')
    {
        $sorted = array();
        foreach ($files as $values) {
            $sorted[(int)$values->$sortField][] = $values;
        }
        ksort($sorted);
        $return = array();
        foreach ($sorted as $values) {
            foreach ($values as $file) {
                $return[] = $file;
            }
        }
        return $return;
    }

    /**
     * get module name in lower case
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getLowerModuleName()
    {
        return strtolower($this->getModuleName());
    }

    /**
     * get menu items xml
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getMenuItemsXml()
    {
        $xml        = '';
        $parts      = array();
        $padding    = 2;
        $namespace  = $this->getNamespace(true);
        $eol        = $this->getEol();
        if ($this->getMenuParent()) {
            $parts = explode('/', $this->getMenuParent());
        }
        foreach ($parts as $part) {
            $xml .= $this->getPadding($padding++).'<'.$part.'>'.$eol;
            $xml .= $this->getPadding($padding++).'<children>'.$eol;
        }
        $xml .= $this->getPadding($padding++).'<'.$namespace.'_'.$this->getLowerModuleName().
            ' translate="title" module="'.$namespace.'_'.$this->getLowerModuleName().'">'.$eol;
        $xml .= $this->getPadding($padding).'<title>'.$this->getMenuText().'</title>'.$eol;
        $xml .= $this->getPadding($padding).'<sort_order>'.$this->getSortOrder().'</sort_order>'.$eol;
        $xml .= $this->getPadding($padding++).'<children>'.$eol;
        $xml .= $this->getEntityMenu($padding);
        $xml .= $this->getPadding(--$padding).'</children>'.$eol;
        $xml .= $this->getPadding(--$padding).'</'.$namespace.'_'.$this->getLowerModuleName().'>';

        $parts = array_reverse($parts);
        foreach ($parts as $part) {
            $xml .= $this->getPadding(--$padding).'</children>'.$eol;
            $xml .= $this->getPadding(--$padding).'</'.$part.'>'.$eol;
        }
        return $xml;
    }

    /**
     * get menu ACL
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getMenuAcl()
    {
        $xml        = '';
        $parts      = array();
        $padding    = 5;
        $eol        = $this->getEol();
        $namespace  = $this->getNamespace(true);
        if ($this->getMenuParent()) {
            $parts = explode('/', $this->getMenuParent());
        }
        foreach ($parts as $part) {
            $xml .= $this->getPadding($padding++).'<'.$part.'>'.$eol;
            $xml .= $this->getPadding($padding++).'<children>'.$eol;
        }
        $xml .= $this->getPadding($padding++).'<'.$namespace.'_'.
            $this->getLowerModuleName().' translate="title" module="'.
            $namespace.'_'.$this->getLowerModuleName().'">'.$eol;
        $xml .= $this->getPadding($padding).'<title>'.$this->getMenuText().'</title>'.$eol;
        $xml .= $this->getPadding($padding++).'<children>'.$eol;
        $xml .= $this->getEntityMenuAcl($padding);
        $xml .= $this->getPadding(--$padding).'</children>'.$eol;
        $xml .= $this->getPadding(--$padding).'</'.$namespace.'_'.$this->getLowerModuleName().'>';

        $parts = array_reverse($parts);
        foreach ($parts as $part) {
            $xml .= $this->getPadding(--$padding).'</children>'.$eol;
            $xml .= $this->getPadding(--$padding).'</'.$part.'>'.$eol;
        }
        return $xml;
    }

    /**
     * get resource setup base class
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getResourceSetupModel()
    {
        if ($this->getHasCatalogRelation() || $this->getHasEav() || $this->getHasCatalogAttribute()) {
            return 'Mage_Catalog_Model_Resource_Setup';
        }
        return 'Mage_Core_Model_Resource_Setup';
    }

    /**
     * sort the translation file
     *
     * @access protected
     * @param string $content
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _sortTranslationFile($content)
    {
        $lines = explode($this->getEol(), $content);
        $distinct = array();
        foreach ($lines as $line) {
            if (trim($line)) {
                $distinct[$line] = 1;
            }
        }
        //remove blank line
        if (isset($distinct['"",""'])) {
            unset($distinct['"",""']);
        }
        ksort($distinct);
        $content = implode($this->getEol(), array_keys($distinct));
        return $content;
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
        return $this->getHelper()->getQwertyuiop();
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
        return $this->getHelper()->getQwertyuiopp();
    }

    /**
     * check module dependency
     *
     * @access public
     * @return array
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getDepends()
    {
        if (!$this->hasData('_depends')) {
            $dependency = array('<Mage_Core />'=>1);
            if ($this->getLinkCore() || $this->getHasEav()) {
                $dependency['<Mage_Catalog />'] = 1;
            }
            $eol = $this->getEol();
            $padding = $this->getPadding(4);
            $depends = '';
            foreach ($dependency as $key=>$value) {
                $depends = $padding.$key.$eol;
            }
            $this->setData('_depends', $depends);
        }
        return $this->getData('_depends');
    }

    /**
     * get layout for product view page
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getProductViewLayout()
    {
        $content = '';
        $padding = $this->getPadding(3);
        $eol     = $this->getEol();
        $tab     = $this->getPadding();
        $ns      = $this->getNamespace(true);
        $module  = $this->getLowerModuleName();
        foreach ($this->getEntities() as $entity) {
            $name  = strtolower($entity->getNameSingular());
            $names = strtolower($entity->getNamePlural());
            $label = $entity->getLabelPlural();
            if ($entity->getShowOnProduct()) {
                $content .= $padding.
                    '<block type="'.$ns.'_'.$module.'/catalog_product_list_'.
                    $name.'" name="product.info.'.$names.'" as="product_'.$names.
                    '" template="'.$ns.'_'.$module.'/catalog/product/list/'.$name.'.phtml">'.$eol;
                $content .= $padding.$tab.
                    '<action method="addToParentGroup"><group>detailed_info</group></action>'.$eol;
                $content .= $padding.$tab.
                    '<action method="setTitle" translate="value" module="'.
                    $ns.'_'.$module.'"><value>'.$label.'</value></action>'.$eol;
                $content .= $padding.'</block>'.$eol;
            }
        }
        return $content;
    }
    /**
     * get layout for category view page
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getCategoryViewLayout()
    {
        $content = '';
        $padding = $this->getPadding(3);
        $eol     = $this->getEol();
        $ns      = $this->getNamespace(true);
        $module  = $this->getLowerModuleName();
        foreach ($this->getEntities() as $entity) {
            $name  = $entity->getNameSingular(true);
            $names = $entity->getNamePlural(true);
            if ($entity->getShowOnCategory()) {
                $content .= $padding.'<block type="'.
                    $ns.'_'.$module.'/catalog_category_list_'.
                    $name.'" name="category.info.'.$names.
                    '" as="category_'.$names.'" template="'.$ns.'_'.$module.
                    '/catalog/category/list/'.$name.'.phtml" after="-" />'.$eol;
            }
        }
        return $content;
    }

    /**
     * get default layout handle
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getFrontendDefaultLayoutHandle()
    {
        $padding    = $this->getPadding(1);
        $tab        = $this->getPadding();
        $eol        = $this->getEol();
        /** @var Ultimate_ModuleCreator_Model_Entity[] $top */
        $top        = array();
        /** @var Ultimate_ModuleCreator_Model_Entity[] $footer */
        $footer     = array();
        $content    = $eol.$padding;
        $namespace  = $this->getNamespace(true);
        $tree       = false;
        if ($this->getCreateFrontend()) {
            foreach ($this->getEntities() as $entity) {
                if ($entity->getCreateList()) {
                    if ($entity->getListMenu() == Ultimate_ModuleCreator_Model_Source_Entity_Menu::TOP_LINKS) {
                        $top[] = $entity;
                    } elseif (
                        $entity->getListMenu() == Ultimate_ModuleCreator_Model_Source_Entity_Menu::FOOTER_LINKS
                    ) {
                        $footer[] = $entity;
                    }
                    if ($entity->getIsTree()) {
                        $tree = true;
                    }
                }
            }
        }
        if (count($top) > 0 || count($footer) > 0 || $tree) {
            $content .= '<default>'.$eol;
            if ($tree) {
                $content .= $padding.'<reference name="head">'.$eol;
                $content .= $padding.$tab.'<action method="addCss"><js>css/'.
                    $this->getNamespace(true).'_'.$this->getLowerModuleName().
                    '/tree.css</js></action>'.$eol;
                $content .= $padding.'</reference>'.$eol;
            }
            if (count($top) > 0) {
                $content .= $padding.$tab.'<reference name="top.links">'.$eol;
                $position = 120;
                foreach ($top as $entity) {
                    $content .= $padding.$tab.$tab.'<action method="addLink" translate="label title" module="'.
                        $namespace.'_'.$this->getLowerModuleName().'">'.$eol;
                    $content .= $padding.$tab.$tab.$tab.'<label>'.$entity->getLabelPlural().'</label>'.$eol;
                    $content .= $padding.$tab.$tab.$tab.'<url helper="'.$namespace.'_'.
                        $this->getLowerModuleName().'/'.strtolower($entity->getNameSingular()).'/get'.
                        ucfirst(strtolower($entity->getNamePlural())).'Url" />'.$eol;
                    $content .= $padding.$tab.$tab.$tab.'<title>'.$entity->getLabelPlural().'</title>'.$eol;
                    $content .= $padding.$tab.$tab.$tab.'<prepare />'.$eol;
                    $content .= $padding.$tab.$tab.$tab.'<urlParams/>'.$eol;
                    $content .= $padding.$tab.$tab.$tab.'<position>'.$position.'</position>'.$eol;
                    $content .= $padding.$tab.$tab.'</action>'.$eol;
                    $position += 10;
                }
                $content .= $padding.$tab.'</reference>'.$eol;
            }
            if (count($footer) > 0) {
                $content .= $padding.$tab.'<reference name="footer_links">'.$eol;
                $position = 120;
                foreach ($footer as $entity) {
                    $content .= $padding.$tab.$tab.'<action method="addLink" translate="label title" module="'.
                        $namespace.'_'.$this->getLowerModuleName().'">'.$eol;
                    $content .= $padding.$tab.$tab.$tab.'<label>'.$entity->getLabelPlural().'</label>'.$eol;
                    $content .= $padding.$tab.$tab.$tab.
                        '<url helper="'.$namespace.'_'.$this->getLowerModuleName().'/'.
                        strtolower($entity->getNameSingular()).'/get'.
                        ucfirst(strtolower($entity->getNamePlural())).'Url" />'.$eol;
                    $content .= $padding.$tab.$tab.$tab.'<title>'.$entity->getLabelPlural().'</title>'.$eol;
                    $content .= $padding.$tab.$tab.$tab.'<prepare />'.$eol;
                    $content .= $padding.$tab.$tab.$tab.'<urlParams/>'.$eol;
                    $content .= $padding.$tab.$tab.$tab.'<position>'.$position.'</position>'.$eol;
                    $content .= $padding.$tab.$tab.'</action>'.$eol;
                    $position += 10;
                }
                $content .= $padding.$tab.'</reference>'.$eol;
            }
            $content .= $padding.'</default>';
        }
        return $content;
    }

    /**
     * get xml for category menu event
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getCategoryMenuEvent()
    {
        if ($this->getShowInCategoryMenu()) {
            $namespace  = $this->getNamespace(true);

            $eol      = $this->getEol();
            $padding  = $this->getPadding(2);
            $tab      = $this->getPadding();
            $content  = $eol;
            $content .= $padding.'<events>'.$eol;
            $content .= $padding.$tab.'<page_block_html_topmenu_gethtml_before>'.$eol;
            $content .= $padding.$tab.$tab.'<observers>'.$eol;
            $content .= $padding.$tab.$tab.$tab.'<'.$namespace.'_'.$this->getLowerModuleName().'>'.$eol;
            $content .= $padding.$tab.$tab.$tab.$tab.
                '<class>'.$namespace.'_'.$this->getLowerModuleName().'/observer</class>'.$eol;
            $content .= $padding.$tab.$tab.$tab.$tab.'<method>addItemsToTopmenuItems</method>'.$eol;
            $content .= $padding.$tab.$tab.$tab.'</'.$namespace.'_'.$this->getLowerModuleName().'>'.$eol;
            $content .= $padding.$tab.$tab.'</observers>'.$eol;
            $content .= $padding.$tab.'</page_block_html_topmenu_gethtml_before>'.$eol;
            $content .= $padding.'</events>'.$eol;
            return $content;
        }
        return '';
    }

    /**
     * get customer comment links
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getCustomerCommentLinks()
    {
        $namespace  = $this->getNamespace(true);
        $eol        = $this->getEol();
        $padding    = $this->getPadding(3);
        $content    = $eol;
        $module     = $this->getLowerModuleName();
        foreach ($this->getEntities() as $entity) {
            if ($entity->getAllowComment()) {
                $entityName = $entity->getNameSingular(true);
                $label      = $entity->getLabelPlural();
                $content   .= $padding . '<action method="addLink" translate="label" module="'.
                    $namespace.'_'.$module.'"><name>'.$entityName.'_comments</name><path>'.
                    $namespace.'_'.$module.'/'.$entityName.'_customer_comment</path><label>'.
                    $label.' Comments</label></action>'.$eol;
            }
        }
        return $content;
    }

    /**
     * get the module namespace
     *
     * @access public
     * @param bool $lower
     * @return mixed|string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getNamespace($lower = false)
    {
        $namespace = $this->getData('namespace');
        if ($lower) {
            $namespace = strtolower($namespace);
        }
        return $namespace;
    }

    /**
     * get front key
     *
     * @access public
     * @return mixed
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getFrontKey()
    {
        if (!$this->getCreateFrontend()) {
            return $this->getData('front_key');
        }
        if (!$this->getData('front_key')) {
            $frontKey = $this->getNamespace(true).'_'.$this->getLowerModuleName();
            $this->setData('front_key', $frontKey);
        }
        return $this->getData('front_key');
    }

    /**
     * system configuration tab name
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getSystemTabName()
    {
        if (!$this->getData('system_tab')) {
            $this->setData('system_tab', $this->getNamespace());
        }
        return $this->getData('system_tab');
    }

    /**
     * system configuration tab position
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getSystemTabPosition()
    {
        return (int)$this->getData('system_tab_position');
    }

    /**
     * get xml for api2.xml resource groups children
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getRestResourceGroupsChildren()
    {
        $content = '';
        foreach ($this->getEntities() as $entity) {
            $content .= $entity->getRestResourceGroup(5);
        }
        return $content;
    }

    /**
     * get xml for api2.xml resources
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getRestResources()
    {
        $content = '';
        foreach ($this->getEntities() as $entity) {
            $content .= $entity->getRestResource(3);
        }
        return $content;
    }

    /**
     * get eav default values
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEavOptionsDefaults()
    {
        $content = '';
        foreach ($this->getEntities() as $entity) {
            if ($entity->getIsEav()) {
                foreach ($entity->getAttributes() as $attribute) {
                    $content .= $attribute->getDefaultValueSetup();
                }
            }
        }
        return $content;
    }

    /**
     * check if there are sibling relations
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getHasRelationColumnRenderer()
    {
        if (count($this->getRelations(Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_SIBLING)) > 0) {
            return true;
        }
        foreach ($this->getEntities() as $_entity) {
            if ($_entity->getLinkCore()) {
                return true;
            }
        }
        return false;
    }

    /**
     * check if there are parent-child relations
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getHasParentRelation()
    {
        $parentRelations = $this->getRelations(Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_PARENT);
        if (count($parentRelations)) {
            return true;
        }
        $childRelations = $this->getRelations(Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_CHILD);
        if (count($childRelations)) {
            return true;
        }
        return false;
    }
}
