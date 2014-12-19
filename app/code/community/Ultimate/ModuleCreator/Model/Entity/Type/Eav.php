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
class Ultimate_ModuleCreator_Model_Entity_Type_Eav extends Ultimate_ModuleCreator_Model_Entity_Type_Abstract
{
    /**
     * parent entities FK
     *
     * @var null
     */
    protected $_parentAttributes = null;

    /**
     * get collection attributes
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getCollectionAttributes()
    {
        $result = '';
        $eol = $this->getEol();
        $padding = $this->getPadding(3);
        foreach ($this->_getParentAttributes() as $attribute) {
            $result .= $eol;
            $result .= $padding;
            $result .= '->addAttributeToSelect(\''.$attribute->getCode().'\')';
        }
        foreach ($this->getEntity()->getAttributes() as $attribute) {
            if ($attribute->getAdminGrid() && $attribute->getCode() != $this->getEntity()->getNameAttributeCode()) {
                $result .= $eol;
                $result .= $padding;
                $result .= '->addAttributeToSelect(\''.$attribute->getCode().'\')';
            }
        }
        foreach ($this->getEntity()->getSimulatedAttributes('status') as $attribute) {
            $result .= $eol;
            $result .= $padding;
            $result .= '->addAttributeToSelect(\''.$attribute->getCode().'\')';
        }
        if ($this->getEntity()->getUrlRewrite()) {
            foreach ($this->getEntity()->getSimulatedAttributes('url_rewrite') as $attribute) {
                $result .= $eol;
                $result .= $padding;
                $result .= '->addAttributeToSelect(\''.$attribute->getCode().'\')';
            }
        }
        return $result;
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
        $eol     = $this->getEol();
        $result  = $eol;
        $result .= $this->getPadding(2).'$adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;'.$eol;
        $result .= $this->getPadding(2).'$store = $this->_getStore();'.$eol;
        $result .= $this->getPadding(2).'$collection->joinAttribute('.$eol.$this->getPadding(3).'\''.
            $this->getEntity()->getNameAttributeCode().'\', '.$eol.$this->getPadding(3).'\''.
            strtolower($this->getEntity()->getModule()->getNamespace()).'_'.
            $this->getEntity()->getModule()->getLowerModuleName().'_'.
            $this->getEntity()->getNameSingular().'/'.
            $this->getEntity()->getNameAttributeCode().'\', '.
            $eol.$this->getPadding(3).'\'entity_id\', '
            .$eol.$this->getPadding(3).'null, '.
            $eol.$this->getPadding(3).'\'inner\', '.
            $eol.$this->getPadding(3).'$adminStore'
            .$eol.$this->getPadding(2).');'.$eol;
        $result .= $this->getPadding(2).'if ($store->getId()) {'.$eol;
        $result .= $this->getPadding(3).    '$collection->joinAttribute(\''.
            $eol.$this->getPadding(4).$this->getModule()->getNamespace(true).'_'.
            $this->getModule()->getLowerModuleName().'_'.
            $this->getEntity()->getNameSingular().'_'.
            $this->getEntity()->getNameAttributeCode().'\', '.
            $eol.$this->getPadding(4).'\''.$this->getModule()->getNamespace(true).
            '_'.$this->getModule()->getLowerModuleName().
            '_'.$this->getEntity()->getNameSingular().
            '/'.$this->getEntity()->getNameAttributeCode().'\', '.
            $eol.$this->getPadding(4).'\'entity_id\', '.
            $eol.$this->getPadding(4).'null, '.
            $eol.$this->getPadding(4).'\'inner\', '.
            $eol.$this->getPadding(4).'$store->getId()'.
            $eol.$this->getPadding(3).');'.$eol;
        $result .= $this->getPadding(2).'}'.$eol;
        return $result;
    }

    /**
     * prepare columns header
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getPrepareColumnsHeader()
    {
        return '$store = $this->_getStore();'.$this->getEol().$this->getPadding(2);
    }

    /**
     * get name attribute for grid
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getNameAttributeGridEav()
    {
        $eol     = $this->getEol();
        $result  = $eol;
        $result .= $this->getPadding(2).'if ($this->_getStore()->getId()) {'.$eol;
        $result .= $this->getPadding(3).    '$this->addColumn('.
            $eol.$this->getPadding(4).'\''.$this->getNamespace(true).'_'.
            $this->getLowerModuleName().'_'.$this->getEntity()->getNameSingular().'_'.
            $this->getEntity()->getNameAttributeCode().'\', '.
            $eol.$this->getPadding(4).'array('.$eol;
        $result .= $this->getPadding(5). '\'header\'    => Mage::helper(\''.
            $this->getNamespace(true).'_'.$this->getLowerModuleName().'\')->__(\''.
            $this->getEntity()->getNameAttributeLabel().' in %s\', $this->_getStore()->getName()),'.$eol;
        $result .= $this->getPadding(5).'\'align\'     => \'left\','.$eol;
        $result .= $this->getPadding(5).'\'index\'     => \''.
            $this->getNamespace(true).'_'.$this->getLowerModuleName().'_'.
            $this->getEntity()->getNameSingular().'_'.$this->getEntity()->getNameAttributeCode().'\','.$eol;
        $result .= $this->getPadding(4).')'.
            $eol.$this->getPadding(3).');'.$eol;
        $result .= $this->getPadding(2).'}'.$eol;
        return $result;
    }

    /**
     * eav always has image
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getHasImage()
    {
        return true;
    }

    /**
     * eav always has files
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getHasFile()
    {
        return true;
    }

    /**
     * eav always has submenu
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getHasSubmenu()
    {
        return true;
    }

    /**
     * get additional menu
     *
     * @access public
     * @param $padding
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAdditionalMenu($padding)
    {
        $extension   = $this->getModule()->getExtensionName(true);
        $module      = $this->getLowerModuleName();
        $entity      = $this->getEntity()->getNameSingular(true);
        $entityTitle = $this->getEntity()->getLabelSingular();
        $action      = $module.'_'.$entity;
        $eol         = $this->getEol();

        $text  = $this->getPadding($padding).'<'.$entity.'_attributes translate="title" module="'.$extension.'">'.$eol;
        $text .= $this->getPadding($padding + 1).'<title>Manage '.$entityTitle.' Attributes</title>'.$eol;
        $text .= $this->getPadding($padding + 1).'<action>adminhtml/'.$action.'_attribute</action>'.$eol;
        $text .= $this->getPadding($padding + 1).
            '<sort_order>'.($this->getEntity()->getPosition() + 7).'</sort_order>'.$eol;
        $text .= $this->getPadding($padding).'</'.$entity.'_attributes>'.$eol;
        return $text;
    }

    /**
     * get additional menu acl
     *
     * @access public
     * @param $padding
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAdditionalMenuAcl($padding)
    {
        $extension   = $this->getModule()->getExtensionName(true);
        $entity      = $this->getEntity()->getNameSingular(true);
        $entityTitle = $this->getEntity()->getLabelSingular();
        $eol         = $this->getEol();

        $text  = $this->getPadding($padding).'<'.$entity.'_attributes translate="title" module="'.$extension.'">'.$eol;
        $text .= $this->getPadding($padding + 1).'<title>Manage '.$entityTitle.' attributes</title>'.$eol;
        $text .= $this->getPadding($padding + 1).'<sort_order>'.
            ($this->getEntity()->getPosition() + 7).'</sort_order>'.$eol;
        $text .= $this->getPadding($padding).'</'.$entity.'_attributes>'.$eol;
        return $text;
    }

    /**
     * allow comments by store
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAllowCommentByStore()
    {
        return $this->getEntity()->getAllowComment();
    }

    /**
     * get parent attributes
     *
     * @access protected
     * @return Ultimate_ModuleCreator_Model_Attribute[]
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _getParentAttributes()
    {
        if (is_null($this->_parentAttributes)) {
            $parents = $this->getEntity()->getRelatedEntities(
                Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_CHILD
            );
            $this->_parentAttributes = array();
            foreach ($parents as $parent) {
                $module = $parent->getModule()->getLowerModuleName();
                $namespace = $parent->getModule()->getNamespace(true);
                $name   = $parent->getNameSingular();
                /** @var Ultimate_ModuleCreator_Model_Attribute $attr */
                $attr   = Mage::getModel('modulecreator/attribute');
                $attr->setCode($name.'_id');
                $attr->setLabel($parent->getLabelSingular());
                $attr->setType('dropdown');
                $attr->setOptionsSource('custom');
                $attr->setForcedSource($namespace.'_'.$module.'/'.$name.'_source');
                $attr->setScope(Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL);
                $attr->setEntity($this->getEntity());
                $attr->setUseFilterIndex(true);
                $this->_parentAttributes[] = $attr;
            }
        }
        return $this->_parentAttributes;
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
        $content = '';
        $position = 0;
        //all parent attributes
        /** @var Ultimate_ModuleCreator_Model_Attribute $attribute */
        foreach ($this->_getParentAttributes() as $attribute) {
            $content .= $attribute->getSetupContent();
        }
        foreach ($this->getEntity()->getAttributes() as $attribute) {
            $content .= $attribute->getSetupContent();
            $position = $attribute->getPosition();
        }
        $position += 10;
        foreach ($this->getEntity()->getSimulatedAttributes(null, false, array('tree')) as $attribute) {
            $attribute->setPosition($position);
            $content .= $attribute->getSetupContent();
            $position += 10;
        }
        foreach ($this->getEntity()->getSimulatedAttributes('tree', false) as $attribute) {
            $attribute->setForcedSetupType('static');
            $attribute->setForcedVisible(0);
            $content .= $attribute->getSetupContent();
            $position += 10;
        }
        return $content;
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
        return 'Mage_Catalog_Model_Resource_Abstract';
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
        return 'Mage_Catalog_Model_Resource_Collection_Abstract';
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
        $padding    = $this->getPadding(2);
        $content    = '';
        $eol        = $this->getEol();
        $entity     = $this->getEntity()->getNameSingular(true);
        $module     = $this->getLowerModuleName();
        $namespace  = $this->getNamespace(true);
        if ($this->getEntity()->getLinkProduct()) {
            $content .= $padding.'$'.'this->_'.
                $entity.'ProductTable = $'."this->getTable('".
                $namespace.'_'.$module."/".$entity."_product');".$eol;
        }
        if ($this->getEntity()->getLinkCategory()) {
            $content .= $padding.'$'.'this->_'.
                $entity.'CategoryTable = $'."this->getTable('".
                $namespace.'_'.$module."/".$entity."_category');".$eol;
        }
        $related = $this->getEntity()->getRelatedEntities(Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_SIBLING);
        foreach ($related as $_entity) {
            $_entityUc      = ucfirst($_entity->getNameSingular());
            $_entityLower   = $_entity->getNameSingular(true);
            $content .= $padding.'$'.'this->_'.
                $entity.$_entityUc.'Table = $'."this->getTable('".
                $namespace.'_'.$module."/".$entity."_".$_entityLower."');".$eol;
        }
        return $content;
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
        $padding = $this->getPadding();
        $content = '';
        $eol     = $this->getEol();
        $entity  = $this->getEntity()->getNameSingular(true);
        if ($this->getEntity()->getLinkProduct()) {
            $content .= $padding.'protected $'.'_'.$entity.'ProductTable = null;'.$eol;
        }
        if ($this->getEntity()->getLinkCategory()) {
            $content .= $padding.'protected $'.'_'.$entity.'CategoryTable = null;'.$eol;
        }
        $related = $this->getEntity()->getRelatedEntities(Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_SIBLING);
        foreach ($related as $_entity) {
            $_entityUc      = ucfirst($_entity->getNameSingular());
            $content       .= $padding.'protected $'.'_'.$entity.$_entityUc.'Table = null;'.$eol;
        }
        return $content;
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
        $eol        = $this->getEol();
        $content    = $this->getPadding(3).
            '<block type="'.$namespace.'_'.$module.
            '/adminhtml_'.$entity.'" name="'.$entity.'">'.$eol;
        $content   .= $this->getPadding(4).
            '<block type="adminhtml/store_switcher" name="store_switcher" as="store_switcher">'.$eol;
        $content   .= $this->getPadding(5).'<action method="setUseConfirm"><params>0</params></action>'.$eol;
        $content   .= $this->getPadding(4).'</block>'.$eol;
        $content   .= $this->getPadding(3).'</block>'.$eol;
        return $content;
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
        return 'Mage_Catalog_Model_Abstract';
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
        return 'e';
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
        return "->addAttributeToSelect('".$this->getEntity()->getNameAttributeCode()."')";
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
        return '<block type="adminhtml/store_switcher" name="store_switcher" before="-"></block>'.
            $this->getEol().$this->getPadding(3);
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
        $content  = '';
        $eol      = $this->getEol();
        $content .= $eol.$this->getPadding(2).'<reference name="head">'.$eol;
        $content .= $this->getPadding(3).'<action method="setCanLoadTinyMce"><load>1</load></action>'.$eol;
        $content .= $this->getPadding(2).'</reference>'.$eol;
        $content .= $this->getPadding(2).'<reference name="js">'.$eol;
        $content .= $this->getPadding(3).
            '<block type="core/template" name="catalog.wysiwyg.js" template="catalog/wysiwyg/js.phtml"/>'.
            $eol;
        $content .= $this->getPadding(2).'</reference>';
        return $content;
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
        return 'Varien_Object';
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
        $namespace  = $this->getNamespace(true);
        $module     = $this->getLowerModuleName();
        $entity     = $this->getEntity()->getNameSingular(true);
        return $this->getEol().$this->getPadding().
            "->setAttributeSetId(Mage::getModel('".$namespace.'_'.$module.'/'.$entity.
            "')->getDefaultAttributeSetId())";
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
        return 'addAttributeToFilter';
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
        return $this->getEol();
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
        return true;
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
        $attribute = $this->getEntity()->getNameAttributeCode();
        return '$this->addAttributeToSelect(\''.$attribute.'\');'.$this->getEol().$this->getPadding(2);
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
        return '';
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
        $eol      = $this->getEol();
        $padding  = $this->getPadding(5);
        $tab      = $this->getPadding();
        $content  = '';
        $content .= $padding.'<currentStore>'.$eol;
        $content .= $padding.$tab.'<title>Set/Get current store view</title>'.$eol;
        $content .= $padding.'</currentStore>'.$eol;
        $content .= $padding.'<listOfAdditionalAttributes translate="title" module="'.
            $this->getNamespace(true).'_'.$this->getLowerModuleName().'">'.$eol;
        $content .= $padding.$tab.'<title>Get list of non-default attributes</title>'.$eol;
        $content .= $padding.$tab.'<method>getAdditionalAttributes</method>'.$eol;
        $content .= $padding.'</listOfAdditionalAttributes>'.$eol;
        return $content;
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
        $eol      = $this->getEol();
        $padding  = $this->getPadding(5);
        $tab      = $this->getPadding();
        $content  = '';
        $content .= $padding.'<store_not_exists>'.$eol;
        $content .= $padding.$tab.'<code>100</code>'.$eol;
        $content .= $padding.$tab.'<message>Requested store view not found.</message>'.$eol;
        $content .= $padding.'</store_not_exists>'.$eol;
        return $content;
    }

    /**
     * additional API subentities.
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getApiAdditionalSettings()
    {
        $content = '';
        $padding  = $this->getPadding(3);
        $tab      = $this->getPadding();
        $module   = $this->getLowerModuleName();
        $entity   = $this->getEntity()->getNameSingular(true);
        $eol      = $this->getEol();
        $content .= $eol;
        $extension = $this->getModule()->getExtensionName(true);
        $content .= $padding.'<'.$module.'_'.$entity.'_attribute translate="title" module="'.$extension.'">'.$eol;
        $content .= $padding.$tab.'<title>Product attributes API</title>'.$eol;
        $content .= $padding.$tab.'<model>'.$extension.'/'.$entity.'_attribute_api</model>'.$eol;
        $content .= $padding.$tab.'<acl>'.$module.'/'.$entity.'</acl>'.$eol;
        $content .= $padding.$tab.'<methods>'.$eol;
        $content .= $padding.$tab.$tab.'<currentStore translate="title" module="'.$extension.'">'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<title>Set/Get current store view</title>'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<acl>'.$module.'/'.$entity.'_attribute/write</acl>'.$eol;
        $content .= $padding.$tab.$tab.'</currentStore>'.$eol;
        $content .= $padding.$tab.$tab.'<list translate="title" module="'.$extension.'">'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<title>Retrieve attribute list</title>'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<method>items</method>'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<acl>'.$module.'/'.$entity.'_attribute/read</acl>'.$eol;
        $content .= $padding.$tab.$tab.'</list>'.$eol;
        $content .= $padding.$tab.$tab.'<options translate="title" module="'.$extension.'">'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<title>Retrieve attribute options</title>'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<acl>'.$module.'/'.$entity.'_attribute/read</acl>'.$eol;
        $content .= $padding.$tab.$tab.'</options>'.$eol;
        $content .= $padding.$tab.$tab.'<types translate="title" module="'.$extension.'">'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<title>Get list of possible attribute types</title>'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<acl>'.$module.'/'.$entity.'_attribute/types</acl>'.$eol;
        $content .= $padding.$tab.$tab.'</types>'.$eol;
        $content .= $padding.$tab.$tab.'<create translate="title" module="'.$extension.'">'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<title>Create new attribute</title>'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<acl>'.$module.'/'.$entity.'_attribute/create</acl>'.$eol;
        $content .= $padding.$tab.$tab.'</create>'.$eol;
        $content .= $padding.$tab.$tab.'<update translate="title" module="'.$extension.'">'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<title>Update attribute</title>'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<acl>'.$module.'/'.$entity.'_attribute/update</acl>'.$eol;
        $content .= $padding.$tab.$tab.'</update>'.$eol;
        $content .= $padding.$tab.$tab.'<remove translate="title" module="'.$extension.'">'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<title>Delete attribute</title>'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<acl>'.$module.'/'.$entity.'_attribute/remove</acl>'.$eol;
        $content .= $padding.$tab.$tab.'</remove>'.$eol;
        $content .= $padding.$tab.$tab.'<info translate="title" module="'.$extension.'">'.$eol;
        $content .= $padding.$tab.$tab.$tab.
            '<title>Get full information about attribute with list of options</title>'.
            $eol;
        $content .= $padding.$tab.$tab.$tab.'<acl>'.$module.'/'.$entity.'_attribute/info</acl>'.$eol;
        $content .= $padding.$tab.$tab.'</info>'.$eol;
        $content .= $padding.$tab.$tab.'<addOption translate="title" module="'.$extension.'">'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<title>Add option</title>'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<acl>'.$module.'/'.$entity.'_attribute/option/add</acl>'.$eol;
        $content .= $padding.$tab.$tab.'</addOption>'.$eol;
        $content .= $padding.$tab.$tab.'<removeOption translate="title" module="'.$extension.'">'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<title>Remove option</title>'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<acl>'.$module.'/'.$entity.'_attribute/option/remove</acl>'.$eol;
        $content .= $padding.$tab.$tab.'</removeOption>'.$eol;
        $content .= $padding.$tab.'</methods>'.$eol;
        $content .= $padding.$tab.'<faults module="'.$extension.'">'.$eol;
        $content .= $padding.$tab.$tab.'<store_not_exists>'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<code>100</code>'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<message>Requested store view not found.</message>'.$eol;
        $content .= $padding.$tab.$tab.'</store_not_exists>'.$eol;
        $content .= $padding.$tab.$tab.'<not_exists>'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<code>101</code>'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<message>Requested attribute not found.</message>'.$eol;
        $content .= $padding.$tab.$tab.'</not_exists>'.$eol;
        $content .= $padding.$tab.$tab.'<invalid_parameters>'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<code>102</code>'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<message>Invalid request parameters.</message>'.$eol;
        $content .= $padding.$tab.$tab.'</invalid_parameters>'.$eol;
        $content .= $padding.$tab.$tab.'<invalid_code>'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<code>103</code>'.$eol;
        $content .= $padding.$tab.$tab.$tab.
            '<message>Attribute code is invalid. Please use only letters (a-z), '.
            'numbers (0-9) or underscore(_) in this field, first character should be a letter.</message>'.
            $eol;
        $content .= $padding.$tab.$tab.'</invalid_code>'.$eol;
        $content .= $padding.$tab.$tab.'<invalid_frontend_input>'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<code>104</code>'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<message>Incorrect attribute type.</message>'.$eol;
        $content .= $padding.$tab.$tab.'</invalid_frontend_input>'.$eol;
        $content .= $padding.$tab.$tab.'<unable_to_save>'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<code>105</code>'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<message>Unable to save attribute.</message>'.$eol;
        $content .= $padding.$tab.$tab.'</unable_to_save>'.$eol;
        $content .= $padding.$tab.$tab.'<can_not_delete>'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<code>106</code>'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<message>This attribute cannot be deleted.</message>'.$eol;
        $content .= $padding.$tab.$tab.'</can_not_delete>'.$eol;
        $content .= $padding.$tab.$tab.'<can_not_edit>'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<code>107</code>'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<message>This attribute cannot be edited.</message>'.$eol;
        $content .= $padding.$tab.$tab.'</can_not_edit>'.$eol;
        $content .= $padding.$tab.$tab.'<unable_to_add_option>'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<code>108</code>'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<message>Unable to add option.</message>'.$eol;
        $content .= $padding.$tab.$tab.'</unable_to_add_option>'.$eol;
        $content .= $padding.$tab.$tab.'<unable_to_remove_option>'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<code>109</code>'.$eol;
        $content .= $padding.$tab.$tab.$tab.'<message>Unable to remove option.</message>'.$eol;
        $content .= $padding.$tab.$tab.'</unable_to_remove_option>'.$eol;
        $content .= $padding.$tab.'</faults>'.$eol;
        $content .= $padding.'</'.$module.'_'.$entity.'_attribute>'.$eol;
        return $content;
    }

    /**
     * get subentities acl
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getSubEntitiesAcl()
    {
        $content = '';
        $padding  = $this->getPadding(5);
        $tab      = $this->getPadding();
        $entity   = $this->getEntity()->getNameSingular(true);
        $eol      = $this->getEol();
        $title    = $this->getEntity()->getLabelSingular().' Attributes';
        $extension = $this->getModule()->getExtensionName(true);
        $content .= $eol;

        $content .= $padding.'<'.$entity.'_attribute translate="title" module="'.$extension.'">'.$eol;
        $content .= $padding.$tab.'<title>'.$title.'</title>'.$eol;
        $content .= $padding.$tab.'<sort_order>'.($this->getEntity()->getPosition() + 6).'</sort_order>'.$eol;
        $content .= $padding.$tab.'<currentStore translate="title" module="'.$extension.'">'.$eol;
        $content .= $padding.$tab.$tab.'<title>Set/Get current store view</title>'.$eol;
        $content .= $padding.$tab.'</currentStore>'.$eol;
        $content .= $padding.$tab.'<list translate="title" module="'.$extension.'">'.$eol;
        $content .= $padding.$tab.$tab.'<title>Retrieve attribute list</title>'.$eol;
        $content .= $padding.$tab.'</list>'.$eol;
        $content .= $padding.$tab.'<options translate="title" module="'.$extension.'">'.$eol;
        $content .= $padding.$tab.$tab.'<title>Retrieve attribute options</title>'.$eol;
        $content .= $padding.$tab.'</options>'.$eol;
        $content .= $padding.$tab.'<types translate="title" module="'.$extension.'">'.$eol;
        $content .= $padding.$tab.$tab.'<title>Get list of possible attribute types</title>'.$eol;
        $content .= $padding.$tab.'</types>'.$eol;
        $content .= $padding.$tab.'<create translate="title" module="'.$extension.'">'.$eol;
        $content .= $padding.$tab.$tab.'<title>Create new attribute</title>'.$eol;
        $content .= $padding.$tab.'</create>'.$eol;
        $content .= $padding.$tab.'<update translate="title" module="'.$extension.'">'.$eol;
        $content .= $padding.$tab.$tab.'<title>Update attribute</title>'.$eol;
        $content .= $padding.$tab.'</update>'.$eol;
        $content .= $padding.$tab.'<remove translate="title" module="'.$extension.'">'.$eol;
        $content .= $padding.$tab.$tab.'<title>Remove attribute</title>'.$eol;
        $content .= $padding.$tab.'</remove>'.$eol;
        $content .= $padding.$tab.'<info translate="title" module="'.$extension.'">'.$eol;
        $content .= $padding.$tab.$tab.'<title>Get full information about attribute with list of options</title>'.$eol;
        $content .= $padding.$tab.'</info>'.$eol;
        $content .= $padding.$tab.'<addOption translate="title" module="'.$extension.'">'.$eol;
        $content .= $padding.$tab.$tab.'<title>Add option</title>'.$eol;
        $content .= $padding.$tab.'</addOption>'.$eol;
        $content .= $padding.$tab.'<removeOption translate="title" module="'.$extension.'">'.$eol;
        $content .= $padding.$tab.$tab.'<title>Remove option</title>'.$eol;
        $content .= $padding.$tab.'</removeOption>'.$eol;
        $content .= $padding.'</'.$entity.'_attribute>'.$eol;
        return $content;
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
        $content = '';
        $padding  = $this->getPadding(3);
        $module   = $this->getLowerModuleName();
        $entity   = $this->getEntity()->getNameSingular(true);
        $eol      = $this->getEol();
        $content .= $eol;
        $content .= $padding.'<'.$entity.'_attribute>'.$module.'_'.$entity.'_attribute</'.$entity.'_attribute>';
        return $content;
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
        $content = '';
        $padding  = $this->getPadding(4);
        $module   = $this->getLowerModuleName();
        $entity   = $this->getEntity()->getNameSingular(true);
        $eol      = $this->getEol();
        $content .= $eol;
        $content .= $padding.'<'.$entity.'_attribute>'.$module.ucfirst($entity).'Attribute</'.$entity.'_attribute>';
        return $content;
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
        $tab        = $this->getPadding();
        $padding    = str_repeat($tab, 5);
        $eol        = $this->getEol();
        $content    = '';
        $module     = $this->getLowerModuleName();
        $entity     = ucfirst($this->getEntity()->getNameSingular(true));
        if (!$wsi) {
            $content   .= $padding.
                '<element name="additional_attributes" type="typens:'.$module.$entity.
                'AdditionalAttributesEntity" minOccurs="0"/>'.$eol;
        } else {
            $content   .= $padding.
                '<xsd:element name="additional_attributes" type="typens:associativeArray" minOccurs="0" />'.
                $eol;
        }
        return $content;
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
        $padding  = $this->getPadding();
        $tab      = $padding;
        $eol      = $this->getEol();
        $entity   = $this->getEntity();
        $content  = $padding.'protected $_defaultAttributeList = array('.$eol;
        $parents  = $entity->getRelatedEntities(Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_CHILD);
        foreach ($parents as $parent) {
            $content .= $padding.$tab."'".$parent->getNameSingular(true).'_id'."', ".$eol;
        }
        foreach ($entity->getAttributes() as $attribute) {
            $content .= $padding.$tab."'".$attribute->getCode()."'".', '.$eol;
        }
        $simulated = $entity->getSimulatedAttributes(null, false);
        foreach ($simulated as $attr) {
            $content .= $padding.$tab."'".$attr->getCode()."'".', '.$eol;
        }
        $content .= $padding.$tab."'created_at', ".$eol;
        $content .= $padding.$tab."'updated_at', ".$eol;
        $content .= $padding.');'.$eol;
        return $content;
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
        return '->addAttributeToSelect(\'*\')';
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
        return '->setStoreId(Mage::app()->getStore()->getId())';
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
        return '';
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
        return $this->getEol().$this->getPadding(2).'$collection->setStoreId($this->_getStore()->getId());';
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
        return '';
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
        return '->addAttributeToSelect(\''.$this->getEntity()->getNameAttributeCode().'\')';
    }
}
