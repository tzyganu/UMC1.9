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
 * file attribute type
 *
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */
class Ultimate_ModuleCreator_Model_Attribute_Type_File
    extends Ultimate_ModuleCreator_Model_Attribute_Type_Abstract {
    /**
     * type code
     * @var string
     */
    protected $_type       = 'file';
    /**
     * eav setup input
     * @var string
     */
    protected $_setupInput = 'file';
    /**
     * don't show in admin grid
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAdminGrid() {
        return false;
    }
    /**
     * check if attribute is required
     * @access public
     * @return mixed
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getRequired(){
        return false;
    }
    /**
     * get the type for the form
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getFormType() {
        return 'file';
    }
    /**
     * get text for rss
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getRssText(){
        $content    = '';
        $entityName = strtolower($this->getAttribute()->getEntity()->getNameSingular());
        $ucEntity   = ucfirst($entityName);
        $eol        = $this->getEol();
        $module     = $this->getAttribute()->getEntity()->getModule()->getLowerModuleName();
        $content   .= $this->getPadding(3).'if ($item->get'.$this->getAttribute()->getMagicMethodCode().'()) {'.$eol;
        $content   .= $this->getPadding(4).'$description .= \'<div>\';'.$eol;
        $content   .= $this->getPadding(4).'$description .= Mage::helper(\''.$module.'\')->__(\''.$this->getAttribute()->getLabel().'\');'.$eol;
        $content   .= $this->getPadding(4).'$description .= \'    <a href="\'.Mage::helper(\''.$module.'/'.$entityName.'\')->getFileBaseUrl().$item->get'.$this->getAttribute()->getMagicMethodCode().'().\'">\';'.$eol;
        $content   .= $this->getPadding(4).'$description .= \'        <span>\'. basename($item->get'.$this->getAttribute()->getMagicMethodCode().'()).\'</span>\';'.$eol;
        $content   .= $this->getPadding(4).'$description .= \'    </a>\';'.$eol;
        $content   .= $this->getPadding(4).'$description .= \'</div>\';'.$eol;
        $content   .= $this->getPadding(3).'}'.$eol;

        return $content;
    }

    /**
     * get the html for frontend
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getFrontendHtml() {
        $content    = '';
        $eol        = $this->getEol();
        $entityName = strtolower($this->getAttribute()->getEntity()->getNameSingular());
        $ucEntity   = ucfirst($entityName);
        $module     = strtolower($this->getAttribute()->getEntity()->getModule()->getModuleName());
        $content   .= $this->getPadding().'<?php if ($_'.$entityName.'->get'.$this->getAttribute()->getMagicMethodCode().'()) :?>'.$eol;
        $content   .= $this->getPadding(2).'<a href="<?php echo Mage::helper(\''.$module.'/'.$entityName.'\')->getFileBaseUrl().$_'.$entityName.'->get'.$this->getAttribute()->getMagicMethodCode().'();?>">'.$eol;
        $content   .= $this->getPadding(3).'<span><?php echo basename($_'.$entityName.'->get'.$this->getAttribute()->getMagicMethodCode().'())?></span>'.$eol;
        $content   .= $this->getPadding(2).'</a>'.$eol;
        $content   .= $this->getPadding().'<?php endif;?>'.$eol;
        return $content;
    }
    /**
     * get the setup backend type
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getSetupBackend(){
        $attribute = $this->getAttribute();
        $entity = $attribute->getEntity();
        $module = $entity->getModule();
        return $module->getLowerModuleName().'/'.strtolower($entity->getNameSingular()).'_attribute_backend_file';
    }

}
