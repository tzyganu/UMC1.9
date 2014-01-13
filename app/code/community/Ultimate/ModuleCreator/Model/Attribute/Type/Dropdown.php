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
 * @copyright      Copyright (c) 2013
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
        $module = $this->getAttribute()->getEntity()->getModule()->getLowerModuleName();
        $entity = strtolower($this->getAttribute()->getEntity()->getNameSingular());
        return $module.'/'.$entity.'_attribute_source_'.$this->getAttribute()->getCodeForFileName();
    }
    /**
     * get admin column options
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAdminColumnOptions() {
        $options  = $this->getEol();
        $module   = $this->getAttribute()->getEntity()->getModule()->getLowerModuleName();
        $entity   = strtolower($this->getAttribute()->getEntity()->getNameSingular());
        $attr     = $this->getAttribute()->getCode();
        $options .= $this->getPadding(3)."'type'  => 'options',".$this->getEol();
        $options .= $this->getPadding(3)."'options' => Mage::helper('".$module."')->convertOptions(Mage::getModel('eav/config')->getAttribute('".$module.'_'.$entity."', '".$attr."')->getSource()->getAllOptions(false))".$this->getEol();
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
    //TODO: implement this
    public function getRssText(){
        $entityName = strtolower($this->getAttribute()->getEntity()->getNameSingular());
        $ucEntity = ucfirst($entityName);
        $module = strtolower($this->getAttribute()->getEntity()->getModule()->getModuleName());
        $content = '';
        //$content = '$options = '
        return $this->getPadding(3).'$description .= Mage::helper(\''.$module.'\')->__("'.$this->getAttribute()->getLabel().'").\':\'.($item->get'.$this->getAttribute()->getMagicMethodCode().'() == 1) ? Mage::helper(\''.$module.'\')->__(\'Yes\') : Mage::helper(\''.$module.'\')->__(\'No\');';
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
     * TODO: add non eav value
     */
    public function getFrontendHtml() {
        $entityName = strtolower($this->getAttribute()->getEntity()->getNameSingular());
        $ucEntity = ucfirst($entityName);
        $module = $this->getAttribute()->getEntity()->getModule()->getLowerModuleName();
        if ($this->getAttribute()->getEntity()->getIsEav()){
            return '<?php echo Mage::helper(\''.$module.'\')->__("'.$this->getAttribute()->getLabel().'");?>:<?php echo $_'.strtolower($this->getAttribute()->getEntity()->getNameSingular()).'->getAttributeText(\''.$this->getAttribute()->getCode().'\');?>'.$this->getEol();
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
}