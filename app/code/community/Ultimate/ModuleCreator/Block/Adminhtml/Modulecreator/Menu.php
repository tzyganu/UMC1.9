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
 * select menu block.
 *
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */
class Ultimate_ModuleCreator_Block_Adminhtml_Modulecreator_Menu extends Mage_Adminhtml_Block_Page_Menu
{
    /**
     * don't cache
     *
     * @access public
     * @return int|null
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getCacheLifetime()
    {
        return null;
    }

    /**
     * draw the menu
     *
     * @access public
     * @param $menu
     * @param string $parentId
     * @param int $level
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function renderUmcMenu($menu, $parentId = '', $level = 0)
    {
        $html = '<ul ' . (!$level ? 'id="umc-nav"' : '') . '>';
        $html .= $this->_renderSelector($parentId, 0);
        $previousSortOrder = 0;
        foreach ($menu as $key=> $item) {
            $html .= '<li>';
            $html .= '<span class="delete toggler collapsed"></span>';
            $html .= '<a href="#" onclick="return false">' . $this->escapeHtml($item['label']) . '</a>';
            if ($parentId) {
                $nextParentId = $parentId.'/'.$key;
            } else {
                $nextParentId = $key;
            }
            if (!empty($item['children'])) {
                $html .= $this->renderUmcMenu($item['children'], $nextParentId, $level + 1);
            } else {
                $html .= '<ul>'.$this->_renderSelector($nextParentId, 10).'</ul>';
            }
            $html .= '</li>';
            $html .= $this->_renderSelector($parentId, (int)(($item['sort_order'] + $previousSortOrder)/2));
            $previousSortOrder = $item['sort_order'];
        }
        $html .= '</ul>';

        return $html;
    }

    /**
     * render the selection link
     *
     * @access protected
     * @param $parentId
     * @param $sortOrder
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _renderSelector($parentId, $sortOrder)
    {
        $html  = '<li class="umc-menu-selector">';
        $html .= '<a class="insert-menu" menu-data=\'{"parent":"'.$parentId.'", "sort_order": "'.$sortOrder.'"}\'';
        $html .= 'title="'.Mage::helper('modulecreator')->__('Insert here').'">';
        $html .= Mage::helper('modulecreator')->__('Insert here');
        $html .= '</a>';
        $html .= '</li>';
        return $html;
    }

    /**
     * render menu
     *
     * @access protected
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _toHtml()
    {
        return $this->renderUmcMenu($this->getMenuArray());
    }
}
