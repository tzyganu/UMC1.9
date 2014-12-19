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
 * help fieldset
 *
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */
/**
 * @method Ultimate_ModuleCreator_Block_Adminhtml_ModuleCreator_Edit_Tab_Help_Fieldset setFieldsets()
 * @method Ultimate_ModuleCreator_Block_Adminhtml_ModuleCreator_Edit_Tab_Help_Fieldset setColumns()
 * @method Ultimate_ModuleCreator_Block_Adminhtml_ModuleCreator_Edit_Tab_Help_Fieldset setDescription()
 */
class Ultimate_ModuleCreator_Block_Adminhtml_ModuleCreator_Edit_Tab_Help_Fieldset extends Mage_Adminhtml_Block_Template
{
    /**
     * default column type
     */
    const DEFAULT_COLUMN_TYPE = 'text';

    /**
     * constructor
     *
     * @access public
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('ultimate_modulecreator/edit/tab/help/fieldset.phtml');
    }

    /**
     * format value
     *
     * @param $field
     * @param $column
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getFormatedValue($field, $column)
    {
        if (!isset($column['type'])) {
            $column['type'] = self::DEFAULT_COLUMN_TYPE;
        }
        if (!isset($column['key'])) {
            return '';
        }
        $key = $column['key'];
        $rawValue = $field->$key;
        switch($column['type']) {
            case 'bool':
                $value = (bool)(string)$rawValue;
                if ($value == 1) {
                    return Mage::helper('modulecreator')->__('Yes');
                }
                return Mage::helper('modulecreator')->__('No');
            break;
            case 'text':
                //intentional fall through
            default:
                return $rawValue;
            break;
        }
    }
}
