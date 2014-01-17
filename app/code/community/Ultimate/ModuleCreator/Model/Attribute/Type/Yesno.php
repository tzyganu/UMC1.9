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
 * yes/no attribute type
 *
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */
class Ultimate_ModuleCreator_Model_Attribute_Type_Yesno
    extends Ultimate_ModuleCreator_Model_Attribute_Type_Abstract {
    /**
     * sql colum ddl type
     * @var string
     */
    protected $_typeDdl     = 'TYPE_SMALLINT';
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
     */
    protected $_setupSource	= 'eav/entity_attribute_source_boolean';

    /**
     * get admin column options
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAdminColumnOptions() {
        $eol        = $this->getEol();
        $options    = $eol;
        $options   .= $this->getPadding(3)."'type'    => 'options',".$eol;
        $options   .= $this->getPadding(3)."'options'    => array(".$eol;
        $options   .= $this->getPadding(4)."'1' => Mage::helper('".strtolower($this->getAttribute()->getEntity()->getModule()->getLowerModuleName())."')->__('Yes'),".$eol;
        $options   .= $this->getPadding(4)."'0' => Mage::helper('".strtolower($this->getAttribute()->getEntity()->getModule()->getLowerModuleName())."')->__('No'),".$eol;
        $options   .= $this->getPadding(3).")".$eol;
        return $options;
    }
    /**
     * get the type for the form
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getFormType() {
        return 'select';
    }
    /**
     * get text for rss
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getRssText() {
        $entityName = strtolower($this->getAttribute()->getEntity()->getNameSingular());
        $ucEntity   = ucfirst($entityName);
        $module     = strtolower($this->getAttribute()->getEntity()->getModule()->getModuleName());
        return $this->getPadding(3).'$description .= Mage::helper(\''.$module.'\')->__("'.$this->getAttribute()->getLabel().'").\':\'.($item->get'.$this->getAttribute()->getMagicMethodCode().'() == 1) ? Mage::helper(\''.$module.'\')->__(\'Yes\') : Mage::helper(\''.$module.'\')->__(\'No\');';
    }
    /**
     * get html for frontend
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getFrontendHtml() {
        $entityName = strtolower($this->getAttribute()->getEntity()->getNameSingular());
        $ucEntity   = ucfirst($entityName);
        $module     = strtolower($this->getAttribute()->getEntity()->getModule()->getModuleName());
        return '<?php echo Mage::helper(\''.$module.'\')->__("'.$this->getAttribute()->getLabel().'");?>:<?php echo ($_'.$entityName.'->get'.$this->getAttribute()->getMagicMethodCode().'() == 1)?Mage::helper(\''.$module.'\')->__(\'Yes\'):Mage::helper(\''.$module.'\')->__(\'No\') ?>'.$this->getEol();
    }
    /**
     * check if attribute is yes/no
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getIsYesNo(){
        return true;
    }
    /**
     * get the options for form input
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getFormOptions(){
        $options = parent::getFormOptions();
        $padding = $this->getPadding(3);
        $tab     = $this->getPadding();
        $eol     = $this->getEol();
        $module = $this->getAttribute()->getEntity()->getModule()->getLowerModuleName();
        $options .= $padding."'values'=> array(".$eol;
        $options .= $padding.$tab.'array('.$eol;
        $options .= $padding.$tab.$tab."'value' => 1,".$eol;
        $options .= $padding.$tab.$tab."'label' => Mage::helper('".$module."')->__('Yes'),".$eol;
        $options .= $padding.$tab."),".$eol;
        $options .= $padding.$tab.'array('.$eol;
        $options .= $padding.$tab.$tab."'value' => 0,".$eol;
        $options .= $padding.$tab.$tab."'label' => Mage::helper('".$module."')->__('No'),".$eol;
        $options .= $padding.$tab."),".$eol;
        $options .= $padding."),".$eol;
        return $options;
    }
}
