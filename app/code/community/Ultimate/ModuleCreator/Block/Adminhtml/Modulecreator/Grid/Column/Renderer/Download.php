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
 * download column renderer
 *
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */

class Ultimate_ModuleCreator_Block_Adminhtml_Modulecreator_Grid_Column_Renderer_Download extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * render row
     *
     * @access public
     * @param Varien_Object $row
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function render(Varien_Object $row)
    {
        /** @var string $what */
        $what = $this->getColumn()->getWhat();
        /** @var string $id */
        $id =  $row->getSafeId();
        $packageName = base64_decode(strtr($id, '-_,', '+/='));
        /** @var Ultimate_ModuleCreator_Helper_Data $helper */
        $helper = Mage::helper('modulecreator');
        $path = $helper->getLocalModulesDir();
        switch ($what) {
            case 'config':
                $file = $path.'package'.DS.$packageName . '.xml';
                break;
            case 'list':
                $file = $path.'package'.DS.$packageName . DS. 'files.log';
                break;
            case 'uninstall' :
                $file = $path.'package'.DS.$packageName . DS. 'uninstall.sql';
                break;
            default:
                $file = $path . $packageName . '.tgz';
                break;
        }
        if (file_exists($file) && is_readable($file)) {
            return '<a href="'.
                $this->getUrl('*/*/download', array('type'=>$what, 'id'=>$id)).'">'.$this->_getLabel().
                '</a>';
        }
        return '<span style="color:red;">'.
            Mage::helper('modulecreator')->__('File does not exist or is not readable').
            '</span>';
    }

    /**
     * get the link label
     *
     * @access protected
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _getLabel()
    {
        if ($this->getColumn()->getLabel()) {
            return $this->getColumn()->getLabel();
        }
        return Mage::helper('modulecreator')->__('Download');
    }
}
