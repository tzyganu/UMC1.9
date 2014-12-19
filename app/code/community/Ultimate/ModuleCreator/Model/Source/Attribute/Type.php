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
 * allowed attribute types
 *
 * @category    Ultimate
 * @package        Ultimate_ModuleCreator
 * @author         Marius Strajeru <ultimate.module.creator@gmail.com>
 */
class Ultimate_ModuleCreator_Model_Source_Attribute_Type
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
            /** @var Ultimate_ModuleCreator_Helper_Data $helper */
            $helper = Mage::helper('modulecreator');
            $types  = $helper->getAttributeTypes();
            $groups = $helper->getAttributeTypeGroups();
            foreach ($groups as $key=>$group) {
                $this->_options[$key] = array('label'=>(string)$group->label, 'value'=>array());
            }
            foreach ($types as $type=>$values) {
                $group = (string)$values->group;
                if (!empty($group) && isset($this->_options[$group])) {
                    $this->_options[$group]['value'][] = array('label'=>(string)$values->label, 'value'=>$type);
                } else {
                    $this->_options[] = array('label'=>(string)$values->label, 'value'=>$type);
                }
            }
            $this->_options = array_values($this->_options);
        }
        $options = $this->_options;
        if ($withEmpty) {
            array_unshift($options, array('label'=>'', 'value'=>''));
        }
        return $options;
    }
}
