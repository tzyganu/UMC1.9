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
 * @method Ultimate_ModuleCreator_Model_Attribute setIndex()
 * @method string getForcedDefaultValue()
 * @method string getDefaultValue()
 * @method string getOptionsSourceAttribute()
 * @method string getLabel()
 * @method string getNote()
 * @method int getPosition()
 * @method string getCode()
 * @method bool getIsName()
 * @method Ultimate_ModuleCreator_Model_Attribute setUserDefined
 * @method Ultimate_ModuleCreator_Model_Attribute setEditor
 * @method string getType()
 * @method Ultimate_ModuleCreator_Model_Attribute setCode()
 * @method Ultimate_ModuleCreator_Model_Attribute setType()
 * @method Ultimate_ModuleCreator_Model_Attribute setLabel()
 * @method string getOptionsSource()
 * @method Ultimate_ModuleCreator_Model_Attribute setOptionsSource()
 * @method Ultimate_ModuleCreator_Model_Attribute setForcedSource()
 * @method Ultimate_ModuleCreator_Model_Attribute setScope()
 * @method Ultimate_ModuleCreator_Model_Attribute setUseFilterIndex()
 * @method Ultimate_ModuleCreator_Model_Attribute setPosition()
 * @method Ultimate_ModuleCreator_Model_Attribute setForcedSetupType()
 * @method Ultimate_ModuleCreator_Model_Attribute setForcedVisible()
 * @method string getPreElementText()
 * @method bool getUseFilterIndex()
 * @method bool hasForcedSetupType()
 * @method string getForcedSetupType()
 * @method string getForcedSetupBackend()
 * @method string getForcedSource()
 * @method int getScope()
 * @method bool hasForcedVisible()
 * @method string getForcedVisible()
 * @method Ultimate_ModuleCreator_Model_Attribute setDefaultValue()
 * @method Ultimate_ModuleCreator_Model_Attribute setForcedSetupBackend()
 * @method Ultimate_ModuleCreator_Model_Attribute setIgnoreApi()
 * @method Ultimate_ModuleCreator_Model_Attribute setOptions()
 * @method Ultimate_ModuleCreator_Model_Attribute setForcedDefaultValue()
 * @method bool getWidget()
 * @method bool getFrontend()
 * @method bool getIgnoreApi()
 * @method int getIndex()
 *
 */
class Ultimate_ModuleCreator_Model_Attribute extends Ultimate_ModuleCreator_Model_Abstract
{
    /**
     * custom option separator
     */
    const OPTION_SEPARATOR      = "\n";

    /**
     * entity object
     *
     * @var mixed(null|Ultimate_ModuleCreator_Model_Entity)
     */
    protected $_entity          = null;

    /**
     * attribute type instance
     *
     * @var mixed(null|Ultimate_ModuleCreator_Model_Attribute_Type_Abstract)
     */
    protected $_typeInstance    = null;

    /**
     * placeholders for replacing in source
     *
     * @var mixed
     */
    protected $_placeholders    = null;

    /**
     * set the model entity
     *
     * @access public
     * @param  Ultimate_ModuleCreator_Model_Entity $entity
     * @return Ultimate_ModuleCreator_Model_Attribute
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function setEntity(Ultimate_ModuleCreator_Model_Entity $entity)
    {
        $this->_entity = $entity;
        return $this;
    }

    /**
     * get the attribute entity
     *
     * @access public
     * @return Ultimate_ModuleCreator_Model_Entity
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEntity()
    {
        return $this->_entity;
    }

    /**
     * get the magic function code for attribute
     *
     * @access public
     * @param bool $ucFirst
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getMagicMethodCode($ucFirst = true)
    {
        $code = $this->getCode();
        $code = $this->_camelize($code);
        if ($ucFirst) {
            return $code;
        }
        //lcfirst only works for php 5.3+
        $code{0} = strtolower($code{0});
        return $code;
    }

    /**
     * get attribute the type instance
     *
     * @access public
     * @return Ultimate_ModuleCreator_Model_Attribute_Type_Abstract
     * @throws Ultimate_ModuleCreator_Exception
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getTypeInstance()
    {
        if (!$this->_typeInstance) {
            $type = $this->getType();
            try {
                $types = $this->getHelper()->getAttributeTypes(false);
                $instanceModel = $types->$type->type_model;
                /** @var Ultimate_ModuleCreator_Model_Attribute_Type_Abstract $typeInstance */
                $typeInstance = Mage::getModel($instanceModel);
                $this->_typeInstance = $typeInstance;
                $this->_typeInstance->setAttribute($this);
            } catch (Exception $e){
                throw new Ultimate_ModuleCreator_Exception("Invalid attribute type: ". $type);
            }
        }
        return $this->_typeInstance;
    }

    /**
     * check if an attribute is in the admin grid
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAdminGrid()
    {
        if ($this->getIsName()) {
            return true;
        }
        return $this->getTypeInstance()->getAdminGrid();
    }

    /**
     * check if an attribute can use an editor
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEditor()
    {
        return $this->getTypeInstance()->getEditor();
    }

    /**
     * check if attribute is required
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getRequired()
    {
        if ($this->getIsName()) {
            return true;
        }
        return $this->getTypeInstance()->getRequired();
    }

    /**
     * check if attribute can behave as name
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getIsAllowedAsName()
    {
        return $this->getTypeInstance()->getIsAllowedAsName();
    }

    /**
     * check if the attribute acts as name
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getNotIsName()
    {
        return !$this->getIsName();
    }

    /**
     * get attribute placeholders
     *
     * @access public
     * @param null $key
     * @return mixed|null|string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getPlaceholders($key = null)
    {
        if (is_null($this->_placeholders)) {
            $placeholders['{{attributeLabel}}']             = $this->getLabel();
            $placeholders['{{AttributeMagicCode}}']         = $this->getMagicMethodCode();
            $placeholders['{{attributeMagicCode}}']         = $this->getMagicMethodCode(false);
            $placeholders['{{attributeCode}}']              = $this->getCode();
            $placeholders['{{attributeColumnOptions}}']     = $this->getAdminColumnOptions();
            $placeholders['{{attributeFormType}}']          = $this->getFormType();
            $placeholders['{{attributeFormOptions}}']       = $this->getFormOptions();
            $placeholders['{{attributePreElementText}}']    = $this->getPreElementText();
            $placeholders['{{attributeRssText}}']           = $this->getRssText();
            $placeholders['{{attributeNote}}']              = $this->getNote();
            $placeholders['{{AttributeCodeForFile}}']       = $this->getCodeForFileName(true);
            $placeholders['{{attributeCodeForFile}}']       = $this->getCodeForFileName(false);
            $placeholders['{{attributeOptions}}']           = $this->getAttributeOptions();
            $placeholders['{{massActionValues}}']           = $this->getMassActionValues();

            $eventObject = new Varien_Object(
                array(
                    'placeholders' => $placeholders
                )
            );
            Mage::dispatchEvent('umc_attribute_placeholdrers', array('event_object'=>$eventObject));
            $placeholders = $eventObject->getPlaceholders();
            $this->_placeholders = $placeholders;
        }
        if (is_null($key)) {
            return $this->_placeholders;
        }
        if (isset($this->_placeholders[$key])) {
            return $this->_placeholders[$key];
        }
        return '';
    }

    /**
     * get additional admin grid column options
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAdminColumnOptions()
    {
        $options = $this->getTypeInstance()->getAdminColumnOptions();
        if ($this->getUseFilterIndex()) {
            $options .= $this->getPadding(3).
                "'filter_index' => '".$this->getEntity()->getEntityTableAlias().
                ".".$this->getCode()."'".$this->getEol();
        }
        return $options;
    }

    /**
     * get options for attribute
     *
     * @access public
     * @param bool $asArray
     * @return array|mixed
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getOptions($asArray = false)
    {
        if (!$asArray) {
            return $this->getData('options');
        }
        return explode(self::OPTION_SEPARATOR, $this->getData('options'));
    }

    /**
     * get form type
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getFormType()
    {
        return $this->getTypeInstance()->getFormType();
    }

    /**
     * get text for rss
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getRssText()
    {
        return $this->getTypeInstance()->getRssText();
    }

    /**
     * get the sql column
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getDdlSqlColumn()
    {
        $eol = $this->getEol();
        $padding = $this->getPadding(2);
        $tab = $this->getPadding();
        $ddl = '';
        $ddl .= "->addColumn(".$eol;
        $ddl .= $padding."'{$this->getCode()}',".$eol;
        $ddl .= $padding."Varien_Db_Ddl_Table::".$this->getTypeDdl().", ".$this->getSizeDdl().",".$eol;
        $ddl .= $padding."array(";
        $newLine = false;
        if ($this->getRequired()) {
            $ddl .= $eol.$padding.$tab."'nullable'  => false,";
            $newLine  = true;
        }
        //TODO: move this inside the type class
        if ($this->getType() == 'int') {
            $ddl .= $eol.$padding.$tab."'unsigned'  => true,";
            $newLine = true;
        }
        if ($newLine) {
            $ddl .= $eol. $padding;
        }
        $ddl .= "),".$eol;
        $ddl .= $padding."'".$this->getLabel()."'".$eol.$tab.")";
        return $ddl;
    }

    /**
     * get column ddl type
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getTypeDdl()
    {
        return $this->getTypeInstance()->getTypeDdl();
    }

    /**
     * get column ddl size
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getSizeDdl()
    {
        return $this->getTypeInstance()->getSizeDdl();
    }

    /**
     * get the frontend html
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getFrontendHtml()
    {
        return $this->getTypeInstance()->getFrontendHtml();
    }

    /**
     * get wsdl format for attribute
     *
     * @access public
     * @param bool $wsi
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getWsdlFormat($wsi = false)
    {
        if ($wsi) {
            return '<xsd:element name="'.$this->getCode().'" type="xsd:string" />';
        }
        return '<element name="'.$this->getCode().'" type="xsd:string" minOccurs="'.(int)$this->getRequired().'" />';
    }

    /**
     * get setup content for attribute
     *
     * @access public
     * @return mixed
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getSetupContent()
    {
        $content    = '';
        $padding5   = $this->getPadding(5);
        $padding6   = $this->getPadding(6);
        $eol        = $this->getEol();
        $coreHelper = Mage::helper('core');
        $content .= $padding5."'".$this->getCode()."' => array(".$eol;
        $content .= $padding6."'group'          => 'General',".$eol;
        $content .= $padding6."'type'           => '".$this->getSetupType()."',".$eol;
        $content .= $padding6."'backend'        => '".$this->getSetupBackend()."',".$eol;
        $content .= $padding6."'frontend'       => '',".$eol;
        $content .= $padding6."'label'          => '".$coreHelper->jsQuoteEscape($this->getLabel())."',".$eol;
        $content .= $padding6."'input'          => '".$this->getSetupInput()."',".$eol;
        $content .= $padding6."'source'         => '".$this->getSetupSource()."',".$eol;
        $content .= $padding6."'global'         => ".$this->getSetupIsGlobal().",".$eol;
        $content .= $padding6."'required'       => '".$this->getRequired()."',".$eol;
        $content .= $padding6."'user_defined'   => ".$this->getIsUserDefined().",".$eol;
        $content .= $padding6."'default'        => '".$coreHelper->jsQuoteEscape($this->getDefaultValueProcessed()).
            "',".$eol;
        $content .= $padding6."'unique'         => false,".$eol;
        $content .= $padding6."'position'       => '".(int)$this->getPosition()."',".$eol;
        $content .= $padding6."'note'           => '".$coreHelper->jsQuoteEscape($this->getNote())."',".$eol;
        $content .= $padding6."'visible'        => '".(int)$this->getVisible()."',".$eol;
        $content .= $padding6."'wysiwyg_enabled'=> '".(int)$this->getEditor()."',".$eol;
        $content .= $this->getAdditionalSetup();
        $content .= $padding5 .'),'.$eol;

        return $content;
    }

    /**
     * get setup type
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getSetupType()
    {
        if ($this->hasForcedSetupType()) {
            return $this->getForcedSetupType();
        }
        return $this->getTypeInstance()->getSetupType();
    }

    /**
     * get setup backend
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getSetupBackend()
    {
        if ($this->getForcedSetupBackend()) {
            return $this->getForcedSetupBackend();
        }
        return $this->getTypeInstance()->getSetupBackend();
    }

    /**
     * get setup input
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getSetupInput()
    {
        return $this->getTypeInstance()->getSetupInput();
    }

    /**
     * get setup source
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getSetupSource()
    {
        if ($this->getForcedSource()) {
            return $this->getForcedSource();
        }
        return $this->getTypeInstance()->getSetupSource();
    }

    /**
     * check id an attribute is global
     *
     * @access public
     * @return int
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getSetupIsGlobal()
    {
        switch ($this->getScope()) {
            case Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE:
                return 'Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE';
                break;
            case Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL:
                return 'Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL';
                break;
            case Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE:
                return 'Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE';
                break;
            default :
                return '';
                break;
        }
    }

    /**
     * get attribute code for file name
     *
     * @access public
     * @param bool $uppercase
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getCodeForFileName($uppercase = false)
    {
        $code = str_replace('_', '', $this->getCode());
        if ($uppercase) {
            $code = ucfirst($code);
        }
        return $code;
    }

    /**
     * check if attribute is visible
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getVisible()
    {
        if (($this->hasForcedVisible())) {
            return $this->getForcedVisible();
        }
        return $this->getTypeInstance()->getVisible();
    }

    /**
     * check if source needs to be generated
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getGenerateSource()
    {
        return $this->getTypeInstance()->getGenerateSource();
    }

    /**
     * get additional setup values
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAdditionalSetup()
    {
        return $this->getTypeInstance()->getAdditionalSetup();
    }

    /**
     * check if attribute is yes/no
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getIsYesNo()
    {
        return $this->getTypeInstance()->getIsYesNo();
    }

    /**
     * get attribute options for source model
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAttributeOptions()
    {
        return $this->getTypeInstance()->getAttributeOptions();
    }

    /**
     * check if attribute is user defined
     *
     * @access public
     * @param bool $asText
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getIsUserDefined($asText = true)
    {
        if (!$this->hasData('user_defined')) {
            $this->setData('user_defined', true);
        }
        if (!$asText) {
            return $this->getData('user_defined');
        }
        if ($this->getData('user_defined')) {
            return 'true';
        }
        return 'false';
    }

    /**
     * get admin from options
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getFormOptions()
    {
        return $this->getTypeInstance()->getFormOptions();
    }

    /**
     * check if attribute is multiple select
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getIsMultipleSelect()
    {
        return $this->getTypeInstance()->getIsMultipleSelect();
    }

    /**
     * check if entity is eav
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEntityEav()
    {
        return $this->getEntity()->getIsEav();
    }

    /**
     * check if entity is not eav
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getNotEntityEav()
    {
        return !$this->getEntityEav();
    }

    /**
     * check if attribute can be in mass update
     *
     * @access public
     * @return mixed
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getMassUpdate()
    {
        return $this->getTypeInstance()->getMassUpdate();
    }

    /**
     * get values for mass action
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getMassActionValues()
    {
        return $this->getTypeInstance()->getMassActionValues();
    }

    /**
     * get module
     *
     * @access public
     * @return Ultimate_ModuleCreator_Model_Module
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getModule()
    {
        return $this->getEntity()->getModule();
    }

    /**
     * get namespace
     *
     * @access public
     * @param bool $lower
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getNamespace($lower = false)
    {
        return $this->getModule()->getNamespace($lower);
    }

    /**
     * get attribute default value
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getDefaultValueProcessed()
    {
        return $this->getTypeInstance()->getDefaultValueProcessed();
    }

    /**
     * get attribute default value setup content
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getDefaultValueSetup()
    {
        return $this->getTypeInstance()->getDefaultValueSetup();
    }
}
