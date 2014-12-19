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
 * abstract entity type
 *
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */
abstract class Ultimate_ModuleCreator_Model_Entity_Type_Abstract extends Ultimate_ModuleCreator_Model_Abstract
{
    /**
     * constant for eav type
     */
    const TYPE_EAV = 'eav';

    /**
     * constant for flat type
     */
    const TYPE_FLAT = 'flat';

    /**
     * current entity
     *
     * @var  Ultimate_ModuleCreator_Model_Entity
     */
    protected $_entity;

    /**
     * set the entity
     *
     * @access public
     * @param Ultimate_ModuleCreator_Model_Entity $entity
     * @return Ultimate_ModuleCreator_Model_Entity_Type_Abstract
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function setEntity(Ultimate_ModuleCreator_Model_Entity $entity)
    {
        $this->_entity = $entity;
        return $this;
    }

    /**
     * get the entity object
     *
     * @access public
     * @return Ultimate_ModuleCreator_Model_Entity|null
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEntity()
    {
        return $this->_entity;
    }

    /**
     * get the module object
     *
     * @access public
     * @return Ultimate_ModuleCreator_Model_Module|null
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getModule()
    {
        return $this->getEntity()->getModule();
    }

    /**
     * get the namespace
     *
     * @access public
     * @param bool $lower
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getNamespace($lower = false)
    {
        return $this->getModule()->getNamespace($lower);
    }

    /**
     * get lower module name
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getLowerModuleName()
    {
        return $this->getModule()->getLowerModuleName();
    }

    /**
     * get collection attributes
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getCollectionAttributes()
    {
        return '';
    }

    /**
     * get admin join
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAdminJoin()
    {
        return '';
    }

    /**
     * prepare columns text
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getPrepareColumnsHeader()
    {
        return '';
    }

    /**
     * get name attribute grid eav
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getNameAttributeGridEav()
    {
        return '';
    }

    /**
     * check if entity has images
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getHasImage()
    {
        return $this->getEntity()->getData('has_image');
    }

    /**
     * check if entity has files
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getHasFile()
    {
        return $this->getEntity()->getData('has_file');
    }

    /**
     * check if submenu should exist
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getHasSubmenu()
    {
        return false;
    }

    /**
     * get Additional submenu
     *
     * @access public
     * @param $padding
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAdditionalMenu($padding)
    {
        return '';
    }

    /**
     * get Additional menu acl
     *
     * @access public
     * @param $padding
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAdditionalMenuAcl($padding)
    {
        return '';
    }

    /**
     * check if comments are allowed by store
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAllowCommentByStore()
    {
        return $this->getEntity()->getAllowComment() && $this->getEntity()->getStore();
    }

    /**
     * get attributes content for setup
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAttributesSetup()
    {
        return '';
    }

    /**
     * get parent class for the entity resource model
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getResourceModelParent()
    {
        return 'Mage_Core_Model_Resource_Db_Abstract';
    }

    /**
     * get parent class for the entity resource collection model
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getResourceCollectionModelParent()
    {
        return 'Mage_Core_Model_Resource_Db_Collection_Abstract';
    }

    /**
     * get related entities relations table
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getResourceRelationsTables()
    {
        return '';
    }

    /**
     * get related entities relations table declaration
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getResourceRelationsTablesDeclare()
    {
        return '';
    }

    /**
     * get admin layout content for index page
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAdminIndexLayoutContent()
    {
        $entity     = $this->getEntity()->getNameSingular(true);
        $module     = $this->getLowerModuleName();
        $namespace  = $this->getNamespace(true);
        return $this->getPadding(3).'<block type="'.
            $namespace.'_'.$module.'/adminhtml_'.$entity.'" name="'.$entity.'" />'.$this->getEol();
    }

    /**
     * get the parent model class
     *
     * @access public
     * @return mixed
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEntityParentModel()
    {
        return 'Mage_Core_Model_Abstract';
    }

    /**
     * get entity table alias
     *
     * @access public
     * @return mixed
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEntityTableAlias()
    {
        return 'main_table';
    }

    /**
     * get additional prepare collection
     *
     * @access public
     * @return mixed
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAdditionalPrepareCollection()
    {
        return '';
    }

    /**
     * additional layout block for left section
     *
     * @access public
     * @return mixed
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEditLayoutLeft()
    {
        return '';
    }

    /**
     * additional layout block edit
     *
     * @access public
     * @return mixed
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEditLayoutAdditional()
    {
        return '';
    }

    /**
     * get param name for before save
     *
     * @access public
     * @return mixed
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getBeforeSaveParam()
    {
        return 'Mage_Core_Model_Abstract';
    }

    /**
     * entity attribute set string
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEntityAttributeSetId()
    {
        return '';
    }

    /**
     * filter method name
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getFilterMethod()
    {
        return 'addFieldToFilter';
    }

    /**
     * convert multiple select fields to strings
     *
     * @access public
     * @return mixed
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getMultipleSelectConvert()
    {
        $padding = $this->getPadding(2);
        $tab     = $this->getPadding();
        $eol     = $this->getEol();
        $content = '';
        foreach ($this->getEntity()->getAttributes() as $attribute) {
            if ($attribute->getIsMultipleSelect()) {
                $ucCode = $attribute->getMagicMethodCode();
                $lcCode = $attribute->getCodeForFileName(false);
                $content .= '$'.$attribute->getCodeForFileName(false).' = $object->get'.$ucCode.'();'.$eol;
                $content .= $padding.'if (is_array($'.$lcCode.')) {'.$eol;
                $content .= $padding.$tab.'$object->set'.$ucCode."(implode(',', $".$lcCode.'));'.$eol;
                $content .= $padding.'}'.$eol.$padding;
            }
        }
        return $content;
    }

    /**
     * check if the entity helper can be created
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getCanCreateEntityHelper()
    {
        return false;
    }

    /**
     * get additional code for toOptionArray()
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getToOptionAddition()
    {
        return '';
    }

    /**
     * get comment name field filter index
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getCommentFilterIndexPrefix()
    {
        return $this->getEntityTableAlias().'.';
    }

    /**
     * get additional api xml
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getApiAdditional()
    {
        return '';
    }

    /**
     * get additional api faults
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getApiFaults()
    {
        return '';
    }

    /**
     * additional API sub-entities.
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getApiAdditionalSettings()
    {
        return '';
    }

    /**
     * get sub-entities acl
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getSubEntitiesAcl()
    {
        return '';
    }

    /**
     * get api aliases
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getApiResourcesAlias()
    {
        return '';
    }

    /**
     * get api V2 aliases
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getApiResourcesAliasV2()
    {
        return '';
    }

    /**
     * get attributes format for wsdl
     *
     * @access public
     * @param bool $wsi
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getWsdlAttributes($wsi = false)
    {
        return '';
    }

    /**
     * get default api attributes
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getDefaultApiAttributes()
    {
        return '';
    }

    /**
     * get add all attributes to collection
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAllAttributesToCollection()
    {
        return '';
    }

    /**
     * get load store id statement
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getLoadStoreId()
    {
        return '';
    }

    /**
     * get rest collection cleanup
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getRestCollectionCleanup()
    {
        return $this->getEol().$this->getPadding(2).'$'.
            $this->getEntity()->getNamePlural(true).
            'Array = $'.$this->getEntity()->getNamePlural(true).'Array[\'items\'];'.$this->getEol();
    }

    /**
     * get rest collection store id
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getRestCollectionStoreId()
    {
        return '';
    }

    /**
     * get default attribute values
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getDefaultAttributeValues()
    {
        $content = '';
        foreach ($this->getEntity()->getAttributes() as $attribute) {
            $defaultValue = $attribute->getDefaultValueProcessed();
            if (!empty($defaultValue)) {
                $content .= $this->getPadding(2).
                    '$values[\''.$attribute->getCode().'\'] = \''.$defaultValue.'\';'.$this->getEol();
            }
        }
        if ($content) {
            $content .= $this->getEol();
        }
        return $content;
    }

    /**
     * get additional to option array select
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getToOptionArraySelect()
    {
        return '';
    }
}
