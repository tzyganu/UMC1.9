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
 * install source mode
 *
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */
class Ultimate_ModuleCreator_Model_Source_Install
{
    /**
     * get the list of available install actions
     *
     * @access public
     * @param bool $withEmpty
     * @return array
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function toOptionArray($withEmpty = false)
    {
        $options = array();
        if ($withEmpty) {
            $options[] = array(
                'value'=>'',
                'label'=>Mage::helper('modulecreator')->__('Select action')
            );
        }
        $options[] = array(
            'value' => '1',
            'label'=>Mage::helper('modulecreator')->__('Install new extension on the current instance.')
        );
        $options[] = array(
            'value' => '0',
            'label'=>Mage::helper('modulecreator')->__('Create archive. I will install it later')
        );
        return $options;
    }

    /**
     * get options as an array
     *
     * @access public
     * @param bool $withEmpty
     * @return array
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAllOptions($withEmpty = true)
    {
        $options = array();
        foreach ($this->toOptionArray($withEmpty) as $option) {
            $options[$option['value']] = $option['label'];
        }
        return $options;
    }

    /**
     * get options as an array - wrapper
     *
     * @param bool $withEmpty
     * @return array
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function toArray($withEmpty = true)
    {
        return $this->getAllOptions($withEmpty);
    }
}
