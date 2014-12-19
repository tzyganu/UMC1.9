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
class Ultimate_ModuleCreator_Model_Attribute_Type_Country extends Ultimate_ModuleCreator_Model_Attribute_Type_Abstract
{
    /**
     * type code
     *
     * @var string
     */
    protected $_type        = 'country';

    /**
     * sql column ddl size
     *
     * @var string
     */
    protected $_sizeDdl     = '2';

    /**
     * eav setup input
     *
     * @var string
     */
    protected $_setupInput 	= 'select';

    /**
     * get admin column options
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAdminColumnOptions()
    {
        $options  = $this->getEol();
        $options .= $this->getPadding(4);
        $options .= "'type'=> 'country',".$this->getEol();
        return $options;
    }

    /**
     * get the type for the form
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getFormType()
    {
        return 'select';
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
        $eol        = $this->getEol();
        $module     = $this->getModule()->getLowerModuleName();
        $namespace  = $this->getNamespace(true);
        $text       = $this->getPadding(3);
        $text      .= '$description .= \'<div>\''.$eol.$this->getPadding(4).'.Mage::helper(\'';
        $text      .= $namespace.'_'.$module;
        $text      .= '\')->__("';
        $text      .= $this->getAttribute()->getLabel();
        $text      .= '").\':'.$eol.$this->getPadding(4).'\'.(($item->get';
        $text      .= $this->getAttribute()->getMagicMethodCode();
        $text      .= '()) '.$eol.$this->getPadding(4).'? Mage::getModel(\'directory/country\')->load($item->get';
        $text      .= $this->getAttribute()->getMagicMethodCode();
        $text      .= '())->getName():'.$eol.$this->getPadding(4).'Mage::helper(\'';
        $text      .= $namespace.'_'.$module;
        $text      .= '\')->__(\'None\')).'.$eol.$this->getPadding(4).'\'</div>\';'.$this->getEol();
        return $text;
    }

    /**
     * get html for frontend
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getFrontendHtml()
    {
        $entityName = $this->getEntity()->getNameSingular(true);
        $module     = $this->getModule()->getLowerModuleName();
        $namespace  = $this->getNamespace(true);
        if ($this->getEntity()->getIsEav()) {
            $html  = '<?php echo Mage::helper(\'';
            $html .= $namespace.'_'.$module;
            $html .= '\')->__("';
            $html .= $this->getAttribute()->getLabel();
            $html .= '");?>:<?php echo $_';
            $html .= $this->getEntity()->getNameSingular(true);
            $html .= '->getAttributeText(\'';
            $html .= $this->getAttribute()->getCode().'\');?>'.$this->getEol();
            return $html;
        }
        $html  = '<?php echo Mage::helper(\'';
        $html .= $namespace.'_'.$module;
        $html .= '\')->__("';
        $html .= $this->getAttribute()->getLabel();
        $html .= '");?>:<?php echo ($_';
        $html .= $entityName.'->get'.$this->getAttribute()->getMagicMethodCode();
        $html .= '()) ? Mage::getModel(\'directory/country\')->load($_';
        $html .= $entityName.'->get'.$this->getAttribute()->getMagicMethodCode();
        $html .= '())->getName():Mage::helper(\'';
        $html .= $namespace.'_'.$module;
        $html .= '\')->__(\'None\') ?>'.$this->getEol();
        return $html;
    }

    /**
     * get source for setup
     *
     * @access public
     * @return string|void
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getSetupSource()
    {
        $module     = $this->getModule()->getLowerModuleName();
        $namespace  = $this->getNamespace(true);
        return $namespace.'_'.$module.'/attribute_source_country';
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
        $options  = parent::getFormOptions();
        $options .= $this->getPadding(3);
        $options .="'values'=> Mage::getResourceModel('directory/country_collection')->toOptionArray(),";
        $options .= $this->getEol();
        return $options;
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
        return 'Mage::getResourceModel(\'directory/country_collection\')->toOptionArray()'.$this->getEol();
    }
}
