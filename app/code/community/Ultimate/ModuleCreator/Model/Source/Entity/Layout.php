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
 * allowed entity layouts
 *
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */
class Ultimate_ModuleCreator_Model_Source_Entity_Layout
{
    /**
     * options
     *
     * @var mixed
     */
    protected $_options = null;
    /**
     * get options array
     *
     * @access public
     * @param bool $withEmpty
     * @return array|null
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function toArray($withEmpty = false)
    {
        if (is_null($this->_options)) {
            /** @var Mage_Page_Model_Source_Layout $layoutModel */
            $layoutModel = Mage::getSingleton('page/source_layout');
            $_options    = $layoutModel->toOptionArray();
            foreach ($_options as $option) {
                $this->_options[$option['value']] = $option['label'];
            }
        }
        $options = $this->_options;
        if ($withEmpty) {
            $options = array_merge(array(''=>''), $options);
        }
        return $options;
    }
}
