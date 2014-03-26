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
 * dropdown attribute type
 *
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */
class Ultimate_ModuleCreator_Model_Attribute_Type_Dropdown
    extends Ultimate_ModuleCreator_Model_Attribute_Type_Abstract {
    /**
     * type code
     * @var string
     */
    protected $_type        = 'dropdown';
    /**
     * dropdown subtype
     * @var Ultimate_ModuleCreator_Model_Abstract
     */
    protected $_subTypeInstance;
    /**
     * sql colum ddl type
     * @var string
     */
    protected $_typeDdl     = 'TYPE_INTEGER';
    /**
     * sql colum ddl size
     * @var string
     */
    protected $_sizeDdl     = 'null';
    /**
     * eav setup type
     */
    protected $_setupType   = 'int';
    /**
     * eav setup input
     */
    protected $_setupInput 	= 'select';
    /**
     * eav setup source
     * @var string
     */
    protected $_setupSource = 'eav/entity_attribute_source_table';

    /**
     * get source for setup
     * @access public
     * @return string|void
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getSetupSource() {
        if (!$this->getGenerateSource()){
            return parent::getSetupSource();
        }
        $module     = $this->getModule()->getLowerModuleName();
        $namespace  = $this->getNamespace(true);
        $entity     = $this->getEntity()->getNameSingular(true);
        return $namespace.'_'.$module.'/'.$entity.'_attribute_source_'.$this->getAttribute()->getCodeForFileName();
    }
    /**
     * get admin column options
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAdminColumnOptions() {
        $options    = $this->getEol();
        $module     = $this->getModule()->getLowerModuleName();
        $namespace  = $this->getNamespace(true);
        $entity     = strtolower($this->getAttribute()->getEntity()->getNameSingular());
        $attr       = $this->getAttribute()->getCode();
        $options   .= $this->getPadding(3)."'type'  => 'options',".$this->getEol();
        if ($this->getAttribute()->getEntity()->getIsEav()) {
            $options .= $this->getPadding(3)."'options' => Mage::helper('".$namespace.'_'.$module."')->convertOptions(Mage::getModel('eav/config')->getAttribute('".$namespace.'_'.$module.'_'.$entity."', '".$attr."')->getSource()->getAllOptions(false))".$this->getEol();
        }
        else {
            $options .= $this->getPadding(3)."'options' => Mage::helper('".$namespace.'_'.$module."')->convertOptions(Mage::getModel('".$namespace.'_'.$module.'/'.$entity."_attribute_source_".$this->getAttribute()->getCodeForFileName(false)."')->getAllOptions(false))".$this->getEol();
        }
        return $options;
    }
    /**
     * get the type for the form
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getFormType(){
        return 'select';
    }
    /**
     * get text for rss
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getRssText(){
        $entityName     = $this->getEntity()->getNameSingular(true);
        $ucEntity       = ucfirst($entityName);
        $module         = $this->getModule()->getLowerModuleName();
        $namespace      = $this->getNamespace(true);
        $content        = '';
        if ($this->getAttribute()->getEntity()->getIsEav()){
            return $this->getPadding(3).'$description .= \'<div>\'.Mage::helper(\''.$namespace.'_'.$module.'\')->__("'.$this->getAttribute()->getLabel().'").\': \'.$item->getAttributeText(\''.$this->getAttribute()->getCode().'\').\'</div>\';'.$this->getEol();
        }
        else {
            $attributeFile = $this->getAttribute()->getCodeForFileName(false);
            $code = $this->getAttribute()->getMagicMethodCode();
            return $this->getPadding(3).'$description .= \'<div>\'.Mage::helper(\''.$namespace.'_'.$module.'\')->__("'.$this->getAttribute()->getLabel().'").\': \'.Mage::getSingleton(\''.$namespace.'_'.$module.'/'.$entityName.'_attribute_source_'.$attributeFile.'\')->getOptionText($item->get'.$code.'()).\'</div>\';'.$this->getEol();
        }
    }
    /**
     * check if source needs to be generated
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getGenerateSource(){
        return $this->getSubTypeInstance()->getGenerateSource();
    }
    /**
     * get subtype instance
     * @access public
     * @return Ultimate_ModuleCreator_Model_Attribute_Dropdown_Abstract
     * @throws Ultimate_ModuleCreator_Exception
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getSubTypeInstance(){
        if (!$this->_subTypeInstance){
            $type = $this->getAttribute()->getOptionsSource();
            try{
                $types = Mage::helper('modulecreator')->getDropdownSubtypes(false);
                $instanceModel = (string)$types->$type->type_model;
                $this->_subTypeInstance = Mage::getModel($instanceModel);
                $this->_subTypeInstance->setTypeAttribute($this);
            }
            catch (Exception $e){
                throw new Ultimate_ModuleCreator_Exception("Invalid dropdown subtype: ". $type);
            }
        }
        return $this->_subTypeInstance;
    }
    /**
     * get additional setup values
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAdditionalSetup(){
        return $this->getSubTypeInstance()->getAdditionalSetup();
    }
    /**
     * get html for frontend
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getFrontendHtml() {
        $entityName     = $this->getEntity()->getNameSingular(true);
        $ucEntity       = ucfirst($entityName);
        $module         = $this->getModule()->getLowerModuleName();
        $namespace      = $this->getNamespace(true);
        if ($this->getAttribute()->getEntity()->getIsEav()){
            return '<?php echo Mage::helper(\''.$namespace.'_'.$module.'\')->__("'.$this->getAttribute()->getLabel().'");?>:<?php echo $_'.$entityName.'->getAttributeText(\''.$this->getAttribute()->getCode().'\');?>'.$this->getEol();
        }
        else {
            $attributeFile = $this->getAttribute()->getCodeForFileName(false);
            $code = $this->getAttribute()->getMagicMethodCode();
            return '<?php echo Mage::helper(\''.$namespace.'_'.$module.'\')->__("'.$this->getAttribute()->getLabel().'");?>:<?php echo Mage::getSingleton(\''.$namespace.'_'.$module.'/'.$entityName.'_attribute_source_'.$attributeFile.'\')->getOptionText($_'.$entityName.'->get'.$code.'())'.';?>'.$this->getEol();
        }
    }
    /**
     * get attribute options for source model
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAttributeOptions(){
        return $this->getSubTypeInstance()->getAttributeOptions();
    }
    /**
     * get the options for form input
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getFormOptions(){
        $options    = parent::getFormOptions();
        $padding    = $this->getPadding(3);
        $tab        = $this->getPadding();
        $eol        = $this->getEol();
        $module     = $this->getModule()->getLowerModuleName();
        $entity     = $this->getEntity()->getNameSingular(true);
        $namespace  = $this->getNamespace(true);
        $flag       = $this->getOptionsFlag();
        $options   .= $padding."'values'=> Mage::getModel('".$namespace.'_'.$module.'/'.$entity."_attribute_source_".$this->getAttribute()->getCodeForFileName(false)."')->getAllOptions(".$this->getOptionsFlag()."),".$this->getEol();
        return $options;
    }

    /**
     * check if options should be returned with empty
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getOptionsFlag() {
        return 'true';
    }
    /**
     * get the setup type of the dropdown
     * @access public
     * @return string|void
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getSetupType(){
        $setupType = $this->getSubTypeInstance()->getSetupType();
        if (empty($setupType)) {
            return parent::getSetupType();
        }
        return $setupType;
    }
    /**
     * get the setup type of the dropdown
     * @access public
     * @return string|void
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getTypeDdl(){
        $setupType = $this->getSubTypeInstance()->getTypeDdl();
        if (empty($setupType)) {
            return parent::getTypeDdl();
        }
        return $setupType;
    }
    /**
     * get column ddl size
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getSizeDdl() {
        $size = $this->getSubTypeInstance()->getSizeDdl();
        if (empty($size)) {
            return parent::getSizeDdl();
        }
        return $size;
    }
    /**
     * get values for mass action
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getMassActionValues() {
        $module     = $this->getModule()->getLowerModuleName();
        $entity     = $this->getEntity()->getNameSingular(true);
        $namespace  = $this->getNamespace(true);
        if ($this->getEntity()->getIsEav()) {
            return "Mage::getModel('eav/config')->getAttribute('".$namespace.'_'.$module."_".$entity."', '".$this->getAttribute()->getCode()."')->getSource()->getAllOptions(".$this->getOptionsFlag()."),".$this->getEol();
        }
        else {
            return "Mage::getModel('".$namespace.'_'.$module.'/'.$entity."_attribute_source_".$this->getAttribute()->getCodeForFileName(false)."')->getAllOptions(".$this->getOptionsFlag()."),".$this->getEol();
        }
    }

}