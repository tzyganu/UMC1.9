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
 * country attribute type
 *
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */
class Ultimate_ModuleCreator_Model_Attribute_Type_Country
    extends Ultimate_ModuleCreator_Model_Attribute_Type_Abstract {
    /**
     * type code
     * @var string
     */
    protected $_type        = 'country';
    /**
     * sql column ddl size
     * @var string
     */
    protected $_sizeDdl     = '2';
    /**
     * eav setup input
     */
    protected $_setupInput 	= 'select';


    /**
     * get admin column options
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAdminColumnOptions() {
        $options  = $this->getEol();
        $options .= $this->getPadding(3);
        $options .= "'type'=> 'country',".$this->getEol();
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
        $entityName = $this->getEntity()->getNameSingular(true);
        $ucEntity   = ucfirst($entityName);
        $module     = $this->getModule()->getLowerModuleName();
        $namespace  = $this->getNamespace(true);
        return $this->getPadding(3).'$description .= \'<div>\'.Mage::helper(\''.$namespace.'_'.$module.'\')->__("'.$this->getAttribute()->getLabel().'").\':\'.(($item->get'.$this->getAttribute()->getMagicMethodCode().'()) ? Mage::getModel(\'directory/country\')->load($item->get'.$this->getAttribute()->getMagicMethodCode().'())->getName():Mage::helper(\''.$namespace.'_'.$module.'\')->__(\'None\')).\'</div>\';'.$this->getEol();
    }
    /**
     * get html for frontend
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getFrontendHtml() {
        $entityName = $this->getEntity()->getNameSingular(true);
        $ucEntity   = ucfirst($entityName);
        $module     = $this->getModule()->getLowerModuleName();
        $namespace  = $this->getNamespace(true);
        if ($this->getEntity()->getIsEav()){
            return '<?php echo Mage::helper(\''.$namespace.'_'.$module.'\')->__("'.$this->getAttribute()->getLabel().'");?>:<?php echo $_'.$this->getEntity()->getNameSingular(true).'->getAttributeText(\''.$this->getAttribute()->getCode().'\');?>'.$this->getEol();
        }
        return '<?php echo Mage::helper(\''.$namespace.'_'.$module.'\')->__("'.$this->getAttribute()->getLabel().'");?>:<?php echo ($_'.$entityName.'->get'.$this->getAttribute()->getMagicMethodCode().'()) ? Mage::getModel(\'directory/country\')->load($_'.$entityName.'->get'.$this->getAttribute()->getMagicMethodCode().'())->getName():Mage::helper(\''.$namespace.'_'.$module.'\')->__(\'None\') ?>'.$this->getEol();
    }
    /**
     * get source for setup
     * @access public
     * @return string|void
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getSetupSource() {
        $module     = $this->getModule()->getLowerModuleName();
        $namespace  = $this->getNamespace(true);
        return $namespace.'_'.$module.'/attribute_source_country';
    }
    /**
     * get admin from options
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getFormOptions(){
        $options = parent::getFormOptions();
        $module = $this->getModule()->getLowerModuleName();
        $options .= $this->getPadding(3)."'values'=> Mage::getResourceModel('directory/country_collection')->toOptionArray(),".$this->getEol();
        return $options;
    }
    /**
     * get values for mass action
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getMassActionValues() {
        return 'Mage::getResourceModel(\'directory/country_collection\')->toOptionArray()'.$this->getEol();
    }
}
