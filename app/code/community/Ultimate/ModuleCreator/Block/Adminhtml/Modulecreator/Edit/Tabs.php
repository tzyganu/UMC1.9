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
 * add/edit tabs
 *
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */
/**
 * @method Ultimate_ModuleCreator_Block_Adminhtml_Modulecreator_Edit_Tabs setTitle
 */
class Ultimate_ModuleCreator_Block_Adminhtml_Modulecreator_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * construct
     *
     * @access public
     * @return void
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function _construct()
    {
        parent::_construct();
        $this->setId('modulecreator_info_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('modulecreator')->__('Module information'));
    }
}
