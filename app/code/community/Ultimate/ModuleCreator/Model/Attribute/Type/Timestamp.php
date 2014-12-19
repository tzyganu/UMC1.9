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
 * timestamp attribute type
 *
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */
class Ultimate_ModuleCreator_Model_Attribute_Type_Timestamp extends Ultimate_ModuleCreator_Model_Attribute_Type_Abstract
{
    /**
     * type code
     *
     * @var string
     */
    protected $_type        = 'timestamp';

    /**
     * sql column ddl type
     *
     * @var string
     */
    protected $_typeDdl     = 'TYPE_DATETIME';

    /**
     * eav setup type
     *
     * @var string
     */
    protected $_setupType   = 'datetime';

    /**
     * eav setup input
     *
     * @var string
     */
    protected $_setupInput 	= 'date';

    /**
     * setup backend
     *
     * @var string
     */
    protected $_setupBackend = 'eav/entity_attribute_backend_datetime';

    /**
     * get admin column options
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAdminColumnOptions()
    {
        $options = $this->getEol();
        $options .= $this->getPadding(4);
        $options .= "'type'=> 'date',".$this->getEol();
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
        return 'date';
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
        return '<?php echo Mage::helper(\''.
            $namespace.'_'.$module.'\')->__("'.
            $this->getAttribute()->getLabel().
            '");?>: <?php echo Mage::helper(\'core\')->formatDate($_'.
            $entityName.'->get'.$this->getAttribute()->getMagicMethodCode().'(), \'full\');?>'.$this->getEol();
    }

    /**
     * get options for admin form
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getFormOptions()
    {
        $options = parent::getFormOptions();
        $padding = $this->getPadding(3);
        $eol     = $this->getEol();
        $options .= $padding.'\'image\' => $this->getSkinUrl(\'images/grid-cal.gif\'),'.$eol;;
        $options .= $padding.
            '\'format\'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),'.$eol;
        return $options;
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
        $attribute  = $this->getAttribute();
        $module     = $this->getModule()->getLowerModuleName();
        $namespace  = $this->getNamespace(true);
        return $this->getPadding(3).'$'.
            'description .= \'<div>\'.Mage::helper(\''.
            $namespace.'_'.$module.'\')->__(\''.
            $attribute->getLabel().'\').\': \'.Mage::helper(\'core\')->formatDate($item->get'.
            $this->getAttribute()->getMagicMethodCode().'(), \'full\').\'</div>\';'.$this->getEol();
    }
}
