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
 */ 
/**
 * modules grid.
 * 
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */ 
class Ultimate_ModuleCreator_Block_Adminhtml_Modulecreator_Grid
    extends Mage_Adminhtml_Block_Widget_Grid {
    /**
     * Initialize Grid block
     * @access public
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function __construct() {
        parent::__construct();
        $this->_defaultLimit = 200;
        $this->setId('ModuleCreator_grid');
        $this->setUseAjax(true);
    }

    /**
     * Creates extension collection if it has not been created yet
     * @access public
     * @return Ultimate_ModuleCreator_Model_Module_Collection
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getCollection() {
        if (!$this->_collection) {
            $this->_collection = Mage::getModel('modulecreator/module_collection');
        }
        return $this->_collection;
    }
    /**
     * Prepare  Collection for Grid
     * @access protected
     * @return Ultimate_ModuleCreator_Block_Adminhtml_ModuleCreator_Grid
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _prepareCollection() {
        $this->setCollection($this->getCollection());
        return parent::_prepareCollection();
    }

    /**
     * Prepare grid columns
     * @access protected
     * @return Ultimate_ModuleCreator_Block_Adminhtml_ModuleCreator_Grid
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _prepareColumns() {
        $this->addColumn('filename_id', array(
            'header' => Mage::helper('modulecreator')->__('Module'),
            'index'  => 'filename_id',
        ));
        $this->addColumn('action',
            array(
                'header'=>  Mage::helper('modulecreator')->__('Download'),
                'width' => '100',
                'type'  => 'action',
                'getter'=> 'getSafeId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('modulecreator')->__('Download'),
                        'url'   => array('base'=> '*/*/download'),
                        'field' => 'id'
                    )
                ),
                'filter'=> false,
                'is_system'    => true,
                'sortable'  => false,
        ));

        return parent::_prepareColumns();
    }

    /**
     * Self URL getter
     * @access public
     * @param array() $params
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getCurrentUrl($params = array()) {
        if (!isset($params['_current'])) {
            $params['_current'] = true;
        }
        return $this->getUrl('*/*/grid', $params);
    }
    /**
     * Row URL getter
     * @access public
     * @param Ultimate_ModuleCreator_Model_Module
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => strtr(base64_encode($row->getFilenameId()), '+/=', '-_,')));
    }
}
