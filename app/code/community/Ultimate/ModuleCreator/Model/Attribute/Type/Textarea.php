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
 * textarea attribute type
 *
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */
class Ultimate_ModuleCreator_Model_Attribute_Type_Textarea extends Ultimate_ModuleCreator_Model_Attribute_Type_Abstract
{
    /**
     * type code
     *
     * @var string
     */
    protected $_type        = 'textarea';

    /**
     * sql column ddl type
     *
     * @var string
     */
    protected $_typeDdl     = 'TYPE_TEXT';

    /**
     * sql column ddl size
     *
     * @var string
     */
    protected $_sizeDdl     = "'64k'";

    /**
     * eav setup type
     *
     * @var string
     */
    protected $_setupType   = 'text';

    /**
     * eav setup input
     *
     * @var string
     */
    protected $_setupInput 	= 'textarea';

    /**
     * don't show in admin grid
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAdminGrid()
    {
        return false;
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
        if ($this->getAttribute()->getEditor()) {
            return 'editor';
        }
        return 'textarea';
    }

    /**
     * can use editor
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEditor()
    {
        return $this->getAttribute()->getData('editor');
    }

    /**
     * get the options for form input
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getFormOptions()
    {
        $options = '';
        if ($this->getEditor() && !$this->getEntity()->getIsTree()) {
            $options = $this->getPadding(3)."'config' => "."$"."wysiwygConfig,".$this->getEol();
        }
        $options .= parent::getFormOptions();
        return $options;
    }
}
