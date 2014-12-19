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
 * modules grid.
 * 
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */
/**
 * @method Ultimate_ModuleCreator_Block_Adminhtml_Modulecreator_Grid setUseAjax()
 */
class Ultimate_ModuleCreator_Block_Adminhtml_Modulecreator_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * no filters
     * @var bool
     */
    protected $_filterVisibility = false;

    /**
     * Initialize Grid block
     *
     * @access public
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function __construct()
    {
        parent::__construct();
        $this->_defaultLimit = 200;
        $this->setId('ModuleCreator_grid');
        $this->setUseAjax(true);
    }

    /**
     * Creates extension collection if it has not been created yet
     *
     * @access public
     * @return Ultimate_ModuleCreator_Model_Module_Collection
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getCollection()
    {
        if (!$this->_collection) {
            $this->_collection = Mage::getModel('modulecreator/module_collection');
        }
        return $this->_collection;
    }

    /**
     * Prepare Collection for Grid
     *
     * @access protected
     * @return Ultimate_ModuleCreator_Block_Adminhtml_ModuleCreator_Grid
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _prepareCollection()
    {
        $this->setCollection($this->getCollection());
        return parent::_prepareCollection();
    }

    /**
     * Prepare grid columns
     *
     * @access protected
     * @return Ultimate_ModuleCreator_Block_Adminhtml_ModuleCreator_Grid
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'filename_id',
            array(
                'header' => Mage::helper('modulecreator')->__('Module'),
                'index'  => 'filename_id',
                'filter' => false,
            )
        );
        $actionColumnRenderer = 'modulecreator/adminhtml_modulecreator_grid_column_renderer_download';
        $this->addColumn(
            'action_edit',
            array(
                'header'=>  Mage::helper('modulecreator')->__('Edit'),
                'width' => '100',
                'type'  => 'action',
                'getter'=> 'getSafeId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('modulecreator')->__('Edit'),
                        'url'   => array('base'=> '*/*/edit'),
                        'field' => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addColumn(
            'action_config',
            array(
                'header'    =>  Mage::helper('modulecreator')->__('Download Config File'),
                'label'     => Mage::helper('modulecreator')->__('Download Config File'),
                'width'     => '150',
                'renderer'  => $actionColumnRenderer,
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
                'what'      => 'config'
            )
        );
        $this->addColumn(
            'action',
            array(
                'header'    =>  Mage::helper('modulecreator')->__('Download Module'),
                'label'     => Mage::helper('modulecreator')->__('Download Module'),
                'width'     => '100',
                'renderer'  => $actionColumnRenderer,
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addColumn(
            'action_list',
            array(
                'header'    =>  Mage::helper('modulecreator')->__('Download List of Files'),
                'label'     => Mage::helper('modulecreator')->__('Download List of Files'),
                'width'     => '100',
                'renderer'  => $actionColumnRenderer,
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
                'what'      => 'list'
            )
        );
        $this->addColumn(
            'action_uninstall',
            array(
                'header'    =>  Mage::helper('modulecreator')->__('Download Uninstall DB Script'),
                'label'     => Mage::helper('modulecreator')->__('Download Uninstall DB Script'),
                'width'     => '200',
                'renderer'  => $actionColumnRenderer,
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
                'what'      => 'uninstall'
            )
        );
        return parent::_prepareColumns();
    }

    /**
     * Self URL getter
     *
     * @access public
     * @param array() $params
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getCurrentUrl($params = array())
    {
        if (!isset($params['_current'])) {
            $params['_current'] = true;
        }
        return $this->getUrl('*/*/grid', $params);
    }

    /**
     * Row URL getter
     *
     * @access public
     * @param Ultimate_ModuleCreator_Model_Module $row
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getRowUrl($row)
    {
        return $this->getUrl(
            '*/*/edit',
            array(
                'id' => strtr(
                    base64_encode(
                        $row->getFilenameId()
                    ),
                    '+/=',
                    '-_,'
                )
            )
        );
    }
}
