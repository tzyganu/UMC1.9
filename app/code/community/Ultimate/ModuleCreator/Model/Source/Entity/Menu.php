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
 * available menus
 *
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */
class Ultimate_ModuleCreator_Model_Source_Entity_Menu
{
    const NO_MENU       = 0;
    const TOP_LINKS     = 1;
    const CATEGORY_MENU = 2;
    const FOOTER_LINKS  = 3;

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
            $this->_options[self::NO_MENU]       = Mage::helper('modulecreator')->__('Do not include in any menu');
            $this->_options[self::TOP_LINKS]     = Mage::helper('modulecreator')
                ->__('Include in top links. (near My account, Checkout, ...)');
            $this->_options[self::CATEGORY_MENU] = Mage::helper('modulecreator')->__('Include in category menu');
            $this->_options[self::FOOTER_LINKS]  = Mage::helper('modulecreator')->__('Include in footer links');
        }
        $options = $this->_options;
        if ($withEmpty) {
            $options = array_merge(array(''=>''), $options);
        }
        return $options;
    }
}
