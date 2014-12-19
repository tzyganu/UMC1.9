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
 */ 
/**
 * main admin block - grid container.
 * 
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */ 
class Ultimate_ModuleCreator_Block_Adminhtml_Modulecreator extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * constructor
     *
     * @access public
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function __construct()
    {
        $this->_controller = 'adminhtml_modulecreator';
        $this->_blockGroup = 'modulecreator';
        $this->_headerText = Mage::helper('modulecreator')->__('Manage modules');
        parent::__construct();
        $this->_updateButton('add', 'label', Mage::helper('modulecreator')->__('Create new module'));
    }
}
