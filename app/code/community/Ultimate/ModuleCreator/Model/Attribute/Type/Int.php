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
 * int attribute type
 *
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */
class Ultimate_ModuleCreator_Model_Attribute_Type_Int extends Ultimate_ModuleCreator_Model_Attribute_Type_Abstract
{
    /**
     * type code
     *
     * @var string
     */
    protected $_type        = 'int';

    /**
     * sql column ddl type
     *
     * @var string
     */
    protected $_typeDdl     = 'TYPE_INTEGER';

    /**
     * sql column ddl size
     *
     * @var string
     */
    protected $_sizeDdl     = 'null';

    /**
     * eav setup type
     *
     * @var string
     */
    protected $_setupType   = 'int';

    /**
     * get admin column options
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAdminColumnOptions()
    {
        $options = $this->getEol();
        $options .= $this->getPadding(4);
        $options .= "'type'=> 'number',".$this->getEol();
        return $options;
    }
}
