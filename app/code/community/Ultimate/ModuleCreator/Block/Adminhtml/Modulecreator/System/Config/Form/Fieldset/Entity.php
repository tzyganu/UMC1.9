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
 * entity system->config fieldset renderer
 *
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */
class Ultimate_ModuleCreator_Block_Adminhtml_Modulecreator_System_Config_Form_Fieldset_Entity extends Ultimate_ModuleCreator_Block_Adminhtml_Modulecreator_System_Config_Form_Fieldset_Abstract
{
    /**
     * get the form name from umc.xml
     *
     * @access public
     * @return mixed|string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getFormName()
    {
        return 'entity';
    }
}
