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
class Ultimate_ModuleCreator_Model_Config extends Varien_Simplexml_Config
{
    /**
     * cache key
     */
    const CACHE_ID = 'umc_config';

    /**
     * get DOM of the config
     *
     * @access public
     * @return null|Varien_Simplexml_Element
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getDom()
    {
        if (is_null($this->_xml)) {
            $this->_xml = Mage::getConfig()->loadModulesConfiguration('umc.xml')
                ->applyExtends();
        }
        return $this->_xml;
    }

    /**
     * get default translation module
     *
     * @return string
     * @access protected
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _getDefaultTranslateModule()
    {
        return 'Ultimate_ModuleCreator';
    }

    /**
     * translate node
     *
     * @access protected
     * @param Varien_Simplexml_Element $node
     * @return Ultimate_ModuleCreator_Model_Config
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _translateNode(&$node)
    {
        if ($node->getAttribute('translate')) {
            $fields = explode(' ', $node->getAttribute('translate'));
            $module = ($node->getAttribute('module'))
                ? (string)$node->getAttribute('module')
                : $this->_getDefaultTranslateModule();
            foreach ($fields as $field) {
                if ($node->$field) {
                    $node->$field = Mage::helper($module)->__((string)$node->$field);
                }
            }
        }
        if ($node->hasChildren()) {
            foreach ($node->children() as $child) {
                $this->_translateNode($child);
            }
        }
        return $this;
    }
}
