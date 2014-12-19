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
 * entity model
 *
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */
/**
 * @method Ultimate_ModuleCreator_Model_Entity setIndex()
 * @method Ultimate_ModuleCreator_Model_Entity setEditor()
 * @method Ultimate_ModuleCreator_Model_Entity setHasImage()
 * @method Ultimate_ModuleCreator_Model_Entity setHasFile()
 * @method Ultimate_ModuleCreator_Model_Entity setHasCountry()
 * @method Ultimate_ModuleCreator_Model_Entity setHasMultipleSelect()
 * @method bool getCreateFrontend()
 * @method bool getLinkProduct()
 * @method bool getLinkCategory()
 * @method bool getIsTree()
 * @method string getType()
 * @method string getLabelSingular()
 * @method string getLabelPlural()
 * @method string getProductAttributeCode()
 * @method string getCategoryAttributeCode()
 * @method int getPosition()
 * @method string getUrlRewriteList()
 * @method bool getAllowComment()
 * @method bool getStore()
 * @method bool getShowCategory()
 * @method int getProductAttributeScope()
 * @method int getCategoryAttributeScope()
 * @method bool getProductAttribute();
 * @method bool getCategoryAttribute()
 * @method string getProductAttributeGroup()
 * @method string getCategoryAttributeGroup()
 * @method int getListMenu()
 * @method bool getRest()
 * @method Ultimate_ModuleCreator_Model_Entity setPosition()
 * @method Ultimate_ModuleCreator_Model_Entity setRss()
 * @method bool getEditor()
 * @method bool getApi()
 * @method bool getHasCountry()
 * @method bool getSearch()
 * @method int getIndex()
 */
class Ultimate_ModuleCreator_Model_Entity extends Ultimate_ModuleCreator_Model_Abstract
{
    /**
     * reference to type instance
     *
     * @var null|Ultimate_ModuleCreator_Model_Entity_Type_Abstract
     */
    protected $_typeInstance           = null;

    /**
     * entity code
     *
     * @var string
     */
    protected $_entityCode             = 'umc_entity';

    /**
     * entity attributes
     *
     * @var array
     */
    protected $_attributes             = array();

    /**
     * entity module
     *
     * @var Ultimate_ModuleCreator_Model_Module
     */
    protected $_module                 = null;

    /**
     * attribute that behaves as name
     *
     * @var Ultimate_ModuleCreator_Model_Attribute
     */
    protected $_nameAttribute           = null;

    /**
     * remember if attributes were prepared
     *
     * @var bool
     */
    protected $_preparedAttributes      = null;

    /**
     * related entities
     *
     * @var array()
     */
    protected $_relatedEntities         = array();

    /**
     * placeholders for replacing in source files
     *
     * @var mixed
     */
    protected $_placeholders            = null;

    /**
     * placeholders for sibling entities
     *
     * @var mixed
     */
    protected $_placeholdersAsSibling   = null;

    /**
     * set the entity module
     *
     * @access public
     * @param Ultimate_ModuleCreator_Model_Module $module
     * @return Ultimate_ModuleCreator_Model_Entity
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function setModule(Ultimate_ModuleCreator_Model_Module $module)
    {
        $this->_module = $module;
        return $this;
    }

    /**
     * get the entity module
     *
     * @access public
     * @return Ultimate_ModuleCreator_Model_Module
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getModule()
    {
        return $this->_module;
    }

    /**
     * add new attribute
     *
     * @access public
     * @param Ultimate_ModuleCreator_Model_Attribute $attribute
     * @return Ultimate_ModuleCreator_Model_Entity
     * @throws Ultimate_ModuleCreator_Exception
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function addAttribute(Ultimate_ModuleCreator_Model_Attribute $attribute)
    {
        Mage::dispatchEvent(
            'umc_entity_add_attribute_before',
            array('attribute'=>$attribute, 'entity'=>$this)
        );
        $attribute->setEntity($this);
        if (isset($this->_attributes[$attribute->getCode()])) {
            throw new Ultimate_ModuleCreator_Exception(
                Mage::helper('modulecreator')->__(
                    'An attribute with the code "%s" already exists for entity "%s"',
                    $attribute->getCode(),
                    $this->getNameSingular()
                )
            );
        }
        $this->_preparedAttributes = false;
        $this->_attributes[$attribute->getCode()] = $attribute;
        if ($attribute->getIsName()) {
            if (!$attribute->getIsAllowedAsName()) {
                /** @var Ultimate_ModuleCreator_Helper_Data $helper */
                $helper = Mage::helper('modulecreator');
                $attributeTypes = $helper->getNameAttributeTypes(true);
                throw new Ultimate_ModuleCreator_Exception(
                    Mage::helper('modulecreator')->__(
                        'An attribute that acts as name must have one of the types "%s".',
                        implode(', ', $attributeTypes)
                    )
                );
            }
            $attribute->setUserDefined(false);
            $this->_nameAttribute = $attribute;
        }
        if ($attribute->getEditor()) {
            $this->setEditor(true);
        }
        if ($attribute->getType() == 'image') {
            $this->setHasImage(true);
        }
        if ($attribute->getType() == 'file') {
            $this->setHasFile(true);
        }
        if ($attribute->getType() == 'country') {
            $this->setHasCountry(true);
        }
        if ($attribute->getIsMultipleSelect()) {
            $this->setHasMultipleSelect(true);
        }
        Mage::dispatchEvent(
            'umc_entity_add_attribute_after',
            array('attribute'=>$attribute, 'entity'=>$this)
        );
        return $this;
    }

    /**
     * prepare attributes
     *
     * @access protected
     * @return Ultimate_ModuleCreator_Model_Entity
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _prepareAttributes()
    {
        if ($this->_preparedAttributes) {
            return $this;
        }
        $attributesByPosition = array();
        foreach ($this->_attributes as $attribute) {
            /** @var Ultimate_ModuleCreator_Model_Attribute $attribute */
            $attributesByPosition[$attribute->getPosition()][] = $attribute;
        }
        ksort($attributesByPosition);
        $attributes = array();
        foreach ($attributesByPosition as $attributeList) {
            foreach ($attributeList as $attribute) {
                $attributes[$attribute->getCode()] = $attribute;
            }
        }
        $this->_attributes = $attributes;
        Mage::dispatchEvent('umc_entity_prepare_attributes', array('entity'=>$this));
        $this->_preparedAttributes = true;
        return $this;
    }

    /**
     * ge the entity attributes
     *
     * @access public
     * @return Ultimate_ModuleCreator_Model_Attribute[]
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAttributes()
    {
        if (!$this->_preparedAttributes) {
            $this->_prepareAttributes();
        }
        return $this->_attributes;
    }

    /**
     * entity to xml
     *
     * @access public
     * @param array $arrAttributes
     * @param string $rootName
     * @param bool $addOpenTag
     * @param bool $addCdata
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function toXml(array $arrAttributes = array(), $rootName = 'entity', $addOpenTag=false, $addCdata=false)
    {
        $xml = '';
        if ($addOpenTag) {
            $xml.= '<?xml version="1.0" encoding="UTF-8"?>'.$this->getEol();
        }
        if (!empty($rootName)) {
            $xml.= '<'.$rootName.'>'.$this->getEol();
        }
        $xml .= parent::toXml($this->getXmlAttributes(), '', false, $addCdata);
        $xml .= '<attributes>'.$this->getEol();
        foreach ($this->getAttributes() as $attribute) {
            $xml .= $attribute->toXml(array(), 'attribute', false, $addCdata);
        }
        $xml .= '</attributes>'.$this->getEol();
        if (!empty($rootName)) {
            $xml.= '</'.$rootName.'>'.$this->getEol();
        }
        return $xml;
    }

    /**
     * get magic function code for the name attribute
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getNameAttributeMagicCode()
    {
        $nameAttribute = $this->getNameAttribute();
        if ($nameAttribute) {
            $entityNameMagicCode = $nameAttribute->getMagicMethodCode();
        } else {
            $entityNameMagicCode = 'Name';
        }
        return $entityNameMagicCode;
    }

    /**
     * get the name attribute
     *
     * @access public
     * @return mixed(null|Ultimate_ModuleCreator_Model_Attribute)
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getNameAttribute()
    {
        return $this->_nameAttribute;
    }

    /**
     * get the attribute code for the name attribute
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getNameAttributeCode()
    {
        return $this->_nameAttribute->getCode();
    }

    /**
     * get the attribute label for the name attribute
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getNameAttributeLabel()
    {
        return $this->getNameAttribute()->getLabel();
    }

    /**
     * check if the entity has file attributes
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getHasFile()
    {
        return $this->getTypeInstance()->getHasFile();
    }

    /**
     * check if the entity has image attributes
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getHasImage()
    {
        return $this->getTypeInstance()->getHasImage();
    }
    /**
     * check if the entity has upload attributes
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getHasUpload()
    {
        return $this->getHasFile() || $this->getHasImage();
    }

    /**
     * get the first image attribute code
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getFirstImageField()
    {
        foreach ($this->getAttributes() as $attribute) {
            if ($attribute->getType() == 'image') {
                return $attribute->getCode();
            }
        }
        return '';
    }

    /**
     * get the attribute name for plural
     *
     * @access public
     * @param bool $lower
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getNamePlural($lower = false)
    {
        $plural = $this->getData('name_plural');
        if ($plural == $this->getNameSingular()) {
            if ($plural == "") {
                return "";
            }
            $plural = $this->getNameSingular().'s';
        }
        if ($lower) {
            $plural = strtolower($plural);
        }
        return $plural;
    }

    /**
     * check if frontend list files must be created
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getCreateList()
    {
        return $this->getCreateFrontend() && $this->getData('create_list');
    }

    /**
     * get list template
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getListTemplate()
    {
        if ($this->getCreateList()) {
            return $this->getData('list_template');
        }
        return '';
    }

    /**
     * check if frontend view files must be created
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getCreateView()
    {
        return $this->getCreateFrontend() && $this->getData('create_view');
    }

    /**
     * get list template
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getViewTemplate()
    {
        if ($this->getCreateView()) {
            return $this->getData('view_template');
        }
        return '';
    }

    /**
     * check if widget list files must be created
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getWidget()
    {
        return $this->getCreateFrontend() && $this->getData('widget');
    }

    /**
     * check if SEO attributes should be added
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAddSeo()
    {
        return $this->getCreateView() && $this->getData('add_seo');
    }

    /**
     * check if SEO attributes should be added
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getRss()
    {
        return $this->getCreateFrontend() && $this->getData('rss');
    }

    /**
     * check if url rewrite should be added
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getUrlRewrite()
    {
        return $this->getCreateView() && $this->getData('url_rewrite');
    }

    /**
     * get url prefix
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getUrlPrefix()
    {
        if ($this->getUrlRewrite()) {
            return $this->getData('url_prefix');
        }
        return '';
    }

    /**
     * get url suffix
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getUrlSuffix()
    {
        if ($this->getUrlRewrite()) {
            return $this->getData('url_suffix');
        }
        return '';
    }

    /**
     * check if products are listed in the entity view page
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getShowProducts()
    {
        return $this->getLinkProduct() &&
            $this->getCreateFrontend() &&
            $this->getData('show_products');
    }

    /**
     * check if entity list is shown on product page
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getShowOnProduct()
    {
        return $this->getLinkProduct() && $this->getData('show_on_product');
    }

    /**
     * check if entity list is shown on category page
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getShowOnCategory()
    {
        return $this->getLinkCategory() && $this->getData('show_on_category');
    }

    /**
     * add related entities
     *
     * @access public
     * @param string $type
     * @param Ultimate_ModuleCreator_Model_Entity $entity
     * @return Ultimate_ModuleCreator_Model_Entity
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function addRelatedEntity($type, $entity)
    {
        $this->_relatedEntities[$type][] = $entity;
        return $this;
    }

    /**
     * get the related entities
     *
     * @access public
     * @param mixed $type
     * @return Ultimate_ModuleCreator_Model_Entity[]
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getRelatedEntities($type = null)
    {
        if (is_null($type)) {
            return $this->_relatedEntities;
        }
        if (isset($this->_relatedEntities[$type])) {
            return $this->_relatedEntities[$type];
        }
        return array();
    }

    /**
     * check if entity does not behave as tree
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getNotIsTree()
    {
        return !$this->getIsTree();
    }

    /**
     * check if entity does not have url rewrites
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getNotUrlRewrite()
    {
        return !$this->getUrlRewrite();
    }

    /**
     * check if entity is EAV
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getIsEav()
    {
        return $this->getType() == Ultimate_ModuleCreator_Model_Entity_Type_Abstract::TYPE_EAV;
    }

    /**
     * check if entity is Flat
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getIsFlat()
    {
        return $this->getType() == Ultimate_ModuleCreator_Model_Entity_Type_Abstract::TYPE_FLAT;
    }

    /**
     * get the entity type instance
     *
     * @access public
     * @return Ultimate_ModuleCreator_Model_Entity_Type_Abstract
     * @throws Ultimate_ModuleCreator_Exception
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getTypeInstance()
    {
        if (is_null($this->_typeInstance)) {
            $type = $this->getType();
            /** @var Ultimate_ModuleCreator_Helper_Data $helper */
            $helper = Mage::helper('modulecreator');
            $types  = $helper->getEntityTypes(false);
            if (isset($types[$type])) {
                /** @var Ultimate_ModuleCreator_Model_Entity_Type_Abstract $typeInstance */
                $typeInstance = Mage::getModel((string)$types[$type]->type_model);
                $this->_typeInstance = $typeInstance;
            } else {
                throw new Ultimate_ModuleCreator_Exception(
                    Mage::helper('modulecreator')->__('Entity "%s" type is not valid', $type)
                );
            }
            $this->_typeInstance->setEntity($this);
        }
        return $this->_typeInstance;
    }

    /**
     * check if entity has default config settings
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getHasConfigDefaults()
    {
        return $this->getCreateFrontend();
    }

    /**
     * check if entity is linked to core entities (product, category)
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getLinkCore()
    {
        return $this->getLinkProduct() || $this->getLinkCategory();
    }

    /**
     * get entity placeholders
     *
     * @access public
     * @param null $param
     * @return array|mixed|null|string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getPlaceholders($param = null)
    {
        if (is_null($this->_placeholders)) {
            $this->_placeholders = array();
            $this->_placeholders['{{entity_default_config}}']       = $this->getDefaultConfig();
            $this->_placeholders['{{entity}}']                      = $this->getNameSingular(true);
            $this->_placeholders['{{Entity}}']                      = ucfirst($this->getNameSingular(true));
            $this->_placeholders['{{ENTITY}}']                      = strtoupper($this->getNameSingular());
            $this->_placeholders['{{EntityLabel}}']                 = ucfirst($this->getLabelSingular());
            $this->_placeholders['{{entityLabel}}']                 = strtolower($this->getLabelSingular());
            $this->_placeholders['{{EntitiesLabel}}']               = ucfirst($this->getLabelPlural());
            $this->_placeholders['{{entitiesLabel}}']               = strtolower($this->getLabelPlural());
            $this->_placeholders['{{entityCollectionAttributes}}']  = $this->getCollectionAttributes();
            $this->_placeholders['{{entityAdminJoin}}']             = $this->getAdminJoin();
            $this->_placeholders['{{prepareColumnsHeader}}']        = $this->getPrepareColumnsHeader();
            $this->_placeholders['{{nameAttributeGridEav}}']        = $this->getNameAttributeGridEav();
            $this->_placeholders['{{nameAttributeCode}}']           = $this->getNameAttributeCode();
            $this->_placeholders['{{nameAttributeLabel}}']          = $this->getNameAttributeLabel();
            $this->_placeholders['{{entities}}']                    = $this->getNamePlural(true);
            $this->_placeholders['{{Entities}}']                    = ucfirst($this->getNamePlural(true));
            $this->_placeholders['{{EntityNameMagicCode}}']         = $this->getNameAttributeMagicCode();
            $this->_placeholders['{{attributeDdlSql}}']             = $this->getAttributesDdlSql();
            $this->_placeholders['{{referenceHead}}']               = $this->getReferenceHeadLayout();
            $this->_placeholders['{{EntityViewRelationLayout}}']    = $this->getRelationLayoutXml();
            $this->_placeholders['{{listLayout}}']                  = $this->getListTemplate();
            $this->_placeholders['{{viewLayout}}']                  = $this->getViewTemplate();
            $this->_placeholders['{{entityHtmlLink}}']              = $this->getHtmlLink();
            $this->_placeholders['{{EntityViewAttributes}}']        = $this->getViewAttributesHtml();
            $this->_placeholders['{{EntityViewWidgetAttributes}}']  = $this->getViewWidgetAttributesHtml();
            $this->_placeholders['{{systemAttributes}}']            = $this->getSystemAttributes();
            $this->_placeholders['{{entityApiAdditional}}']         = $this->getApiAdditional();
            $this->_placeholders['{{entityApiFaults}}']             = $this->getApiFaults();
            $this->_placeholders['{{entityAdditionalApiAcl}}']      = $this->getAdditionalApiAcl();
            $this->_placeholders['{{entityWsdlAttributes}}']        = $this->getWsdlAttributes();
            $this->_placeholders['{{entityWsdlAttributesForAdd}}']  = $this->getWsdlAttributes(false, true);
            $this->_placeholders['{{entityWsdlRelationTypes}}']     = $this->getWsdlRelationTypes();
            $this->_placeholders['{{entityWsdlPortTypeRelation}}']  = $this->getWsdlPortTypeRelation();
            $this->_placeholders['{{entityWsdlRelationBinding}}']   = $this->getWsdlRelationBinding();
            $this->_placeholders['{{entityWsiRelationParamTypes}}'] = $this->getWsiRelationParamTypes();
            $this->_placeholders['{{entityWsiRelationMessages}}']   = $this->getWsiRelationMessages();
            $this->_placeholders['{{entityWsiPortTypeRelation}}']   = $this->getWsiPortTypeRelation();
            $this->_placeholders['{{entityWsiRelationBinding}}']    = $this->getWsiRelationBinding();
            $this->_placeholders['{{entityWsiAttributes}}']         = $this->getWsiAttributes();
            $this->_placeholders['{{entityWsiAttributesForAdd}}']   = $this->getWsiAttributes(true);
            $this->_placeholders['{{entityWsiRelationTypes}}']      = $this->getWsiRelationTypes();
            $this->_placeholders['{{entityWsdlMessages}}']          = $this->getWsdlMessages();
            $this->_placeholders['{{fksDdl}}']						= $this->getParentEntitiesFksDdl();
            $this->_placeholders['{{selectedMenuPath}}']            = $this->getSelectedMenuPath();
            $this->_placeholders['{{entityAttributesSetup}}']       = $this->getAttributesSetup();
            $this->_placeholders['{{ResourceModelParent}}']         = $this->getResourceModelParent();
            $this->_placeholders['{{ResourceCollectionParent}}']    = $this->getResourceCollectionModelParent();
            $this->_placeholders['{{RelationsResourceTables}}']     = $this->getResourceRelationsTables();
            $this->_placeholders['{{RelationsResourceTablesDeclare}}'] = $this->getResourceRelationsTablesDeclare();
            $this->_placeholders['{{adminIndexContent}}']           = $this->getAdminIndexLayoutContent();
            $this->_placeholders['{{EntityParentModel}}']           = $this->getEntityParentModel();
            $this->_placeholders['{{entityTableAlias}}']            = $this->getEntityTableAlias();
            $this->_placeholders['{{additionalPrepareCollection}}'] = $this->getAdditionalPrepareCollection();
            $this->_placeholders['{{entityEditLayoutLeft}}']        = $this->getEditLayoutLeft();
            $this->_placeholders['{{entityLayoutAdditional}}']      = $this->getEditLayoutAdditional();
            $this->_placeholders['{{productAttributeCode}}']        = $this->getProductAttributeCode();
            $this->_placeholders['{{categoryAttributeCode}}']       = $this->getCategoryAttributeCode();
            $this->_placeholders['{{entityProductAttributeScope}}'] = $this->getProductAttributeScopeLabel();
            $this->_placeholders['{{entityCategoryAttributeScope}}']= $this->getCategoryAttributeScopeLabel();
            $this->_placeholders['{{productAttributeGroup}}']       = $this->getProductAttributeGroupLabel();
            $this->_placeholders['{{categoryAttributeGroup}}']      = $this->getCategoryAttributeGroupLabel();
            $this->_placeholders['{{beforeSaveParam}}']             = $this->getBeforeSaveParam();
            $this->_placeholders['{{EntityAttributeSetId}}']        = $this->getEntityAttributeSetId();
            $this->_placeholders['{{filterMethod}}']                = $this->getFilterMethod();
            $this->_placeholders['{{multipleSelectConvert}}']       = $this->getMultipleSelectConvert();
            $this->_placeholders['{{toOptionAddition}}']            = $this->getToOptionAddition();
            $this->_placeholders['{{multiselectMethods}}']          = $this->getMultiselectMethods();
            $this->_placeholders['{{nameHtml}}']                    = $this->getNameHtml();
            $this->_placeholders['{{isTree}}']                      = (int)$this->getIsTree();
            $this->_placeholders['{{commentFilterIndexPrefix}}']    = $this->getCommentFilterIndexPrefix();
            $this->_placeholders['{{entityApiAdditionalSettings}}'] = $this->getApiAdditionalSettings();
            $this->_placeholders['{{subEntitiesAcl}}']              = $this->getSubEntitiesAcl();
            $this->_placeholders['{{position}}']                    = $this->getPosition();
            $this->_placeholders['{{entityApiResourcesAlias}}']     = $this->getApiResourcesAlias();
            $this->_placeholders['{{entityApiResourcesAliasV2}}']   = $this->getApiResourcesAliasV2();
            $this->_placeholders['{{defaultApiAttributes}}']        = $this->getDefaultApiAttributes();
            $this->_placeholders['{{filterEntityDates}}']           = $this->getFilterDates();
            $this->_placeholders['{{filterEntityDates3}}']          = $this->getFilterDates(3);
            $this->_placeholders['{{allAttributesToCollection}}']   = $this->getAllAttributesToCollection();
            $this->_placeholders['{{loadStoreId}}']                 = $this->getLoadStoreId();
            $this->_placeholders['{{restCollectionCleanup}}']       = $this->getRestCollectionCleanup();
            $this->_placeholders['{{restCollectionStoreId}}']       = $this->getRestCollectionStoreId();
            $this->_placeholders['{{defaultAttributeValues}}']      = $this->getDefaultAttributeValues();
            $this->_placeholders['{{toOptionArraySelect}}']         = $this->getToOptionArraySelect();
            $this->_placeholders['{{parentStaticParams}}']          = $this->getParentStaticParams();


            $eventObject = new Varien_Object(
                array(
                    'placeholders' => $this->_placeholders
                )
            );
            Mage::dispatchEvent('umc_entity_placeholdrers', array('event_object'=>$eventObject));
            $this->_placeholders = $eventObject->getPlaceholders();
        }
        if (is_null($param)) {
            return $this->_placeholders;
        }
        if (isset($this->_placeholders[$param])) {
            return $this->_placeholders[$param];
        }
        return '';
    }

    /**
     * get placeholders as sibling
     *
     * @access public
     * @param null $param
     * @return array|mixed|null|string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getPlaceholdersAsSibling($param = null)
    {
        if (is_null($this->_placeholdersAsSibling)) {
            $this->_placeholdersAsSibling = array();
            $this->_placeholdersAsSibling['{{sibling_default_config}}']         = $this->getDefaultConfig();
            $this->_placeholdersAsSibling['{{sibling}}']                        = $this->getNameSingular(true);
            $this->_placeholdersAsSibling['{{Sibling}}']                        = ucfirst($this->getNameSingular(true));
            $this->_placeholdersAsSibling['{{SIBLING}}']                        = strtoupper($this->getNameSingular());
            $this->_placeholdersAsSibling['{{SiblingLabel}}']                   = ucfirst($this->getLabelSingular());
            $this->_placeholdersAsSibling['{{siblingLabel}}']                   = strtolower($this->getLabelSingular());
            $this->_placeholdersAsSibling['{{SiblingsLabel}}']                  = ucfirst($this->getLabelPlural());
            $this->_placeholdersAsSibling['{{siblingsLabel}}']                  = $this->getLabelPlural(true);
            $this->_placeholdersAsSibling['{{siblingCollectionAttributes}}']    = $this->getCollectionAttributes();
            $this->_placeholdersAsSibling['{{siblingAdminJoin}}']               = $this->getAdminJoin();
            $this->_placeholdersAsSibling['{{siblingColumnsHeader}}']           = $this->getPrepareColumnsHeader();
            $this->_placeholdersAsSibling['{{siblingNameAttributeGridEav}}']    = $this->getNameAttributeGridEav();
            $this->_placeholdersAsSibling['{{siblingNameAttributeCode}}']       = $this->getNameAttributeCode();
            $this->_placeholdersAsSibling['{{siblingNameAttributeLabel}}']      = $this->getNameAttributeLabel();
            $this->_placeholdersAsSibling['{{siblings}}']                       = strtolower($this->getNamePlural());
            $this->_placeholdersAsSibling['{{Siblings}}']                       = ucfirst($this->getNamePlural(true));
            $this->_placeholdersAsSibling['{{SiblingNameMagicCode}}']           = $this->getNameAttributeMagicCode();
            $this->_placeholdersAsSibling['{{SiblingViewRelationLayout}}']      = $this->getRelationLayoutXml();
            $this->_placeholdersAsSibling['{{siblingListLayout}}']              = $this->getListTemplate();
            $this->_placeholdersAsSibling['{{siblingViewLayout}}']              = $this->getViewTemplate();
            $this->_placeholdersAsSibling['{{SiblingListItem}}']                = $this->getHtmlLink();
            $this->_placeholdersAsSibling['{{siblingNameAttribute}}']           = $this->getNameAttributeCode();
            $this->_placeholdersAsSibling['{{siblingAdditionalPrepareCollection}}']
                = $this->getAdditionalPrepareCollection();
            $this->_placeholdersAsSibling['{{siblingTableAlias}}']              = $this->getEntityTableAlias();
            $this->_placeholdersAsSibling['{{siblingFilterMethod}}']            = $this->getFilterMethod();
            $this->_placeholdersAsSibling['{{siblingAllAttributesToCollection}}']
                = $this->getAllAttributesToCollection();
            $this->_placeholdersAsSibling['{{siblingLoadStoreId}}']              = $this->getLoadStoreId();
            $this->_placeholdersAsSibling['{{siblingToOptionArraySelect}}']     = $this->getToOptionArraySelect();
            $this->_placeholdersAsSibling['{{siblingParentStaticParams}}']      = $this->getParentStaticParams();

            $eventObject = new Varien_Object(
                array(
                    'placeholders' => $this->_placeholdersAsSibling
                )
            );
            Mage::dispatchEvent(
                'umc_entity_placeholdrers_as_sibling',
                array('event_object'=>$eventObject)
            );
            $this->_placeholdersAsSibling = $eventObject->getPlaceholders();
        }
        if (is_null($param)) {
            return $this->_placeholdersAsSibling;
        }
        if (isset($this->_placeholdersAsSibling[$param])) {
            return $this->_placeholdersAsSibling[$param];
        }
        return '';
    }

    /**
     * get default config settings
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getDefaultConfig()
    {
        if (!$this->getHasConfigDefaults()) {
            return '';
        }
        $padding3   = $this->getPadding(3);
        $padding    = $this->getPadding();
        $eol        = $this->getEol();

        $text = '<'.$this->getNameSingular().'>'.$eol;
        if ($this->getCreateFrontend()) {
            $text.= $padding3.$padding.'<breadcrumbs>1</breadcrumbs>'.$eol;
        }
        if ($this->getUrlRewrite() && $this->getUrlPrefix()) {
            $text.= $padding3.$padding.'<url_prefix>'.$this->getUrlPrefix().'</url_prefix>'.$eol;
        }
        if ($this->getUrlRewrite() && $this->getUrlSuffix()) {
            $text.= $padding3.$padding.'<url_suffix>'.$this->getUrlSuffix().'</url_suffix>'.$eol;
        }
        if ($this->getUrlRewrite() && $this->getUrlRewriteList()) {
            $text.= $padding3.$padding.'<url_rewrite_list>'.$this->getUrlRewriteList().'</url_rewrite_list>'.$eol;
        }
        if ($this->getAllowComment()) {
            $text.= $padding3.$padding.'<allow_comment>1</allow_comment>'.$eol;
        }
        if ($this->getRss()) {
            $text.= $padding3.$padding.'<rss>1</rss>'.$eol;
        }
        if ($this->getAddSeo()) {
            $text.= $padding3.$padding.'<meta_title>'.$this->getLabelPlural().'</meta_title>'.$eol;
        }
        if ($this->getIsTree() && $this->getCreateFrontend()) {
            $text.= $padding3.$padding.'<tree>1</tree>'.$eol;
            $text.= $padding3.$padding.'<recursion>0</recursion>'.$eol;
        }
        $text .= $padding3.'</'.$this->getNameSingular().'>'.$eol;
        return $text;
    }

    /**
     * get xml for add product event
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAddBlockToProductEvent()
    {
        $text = '';
        if ($this->getLinkProduct()) {
            $ns     = $this->getNamespace(true);
            $eol    = $this->getEol();
            $text   = $this->getPadding(5).'<'.$ns.'_'.$this->getModule()->getLowerModuleName().'_'.
                $this->getNameSingular(true).'>'.$eol;
            $text  .= $this->getPadding(6).'<type>singleton</type>'.$eol;
            $text  .= $this->getPadding(6).'<class>'.$ns.'_'.$this->getModule()->getLowerModuleName().
                '/adminhtml_observer</class>'.$eol;
            $text  .= $this->getPadding(6).'<method>add'.ucfirst(strtolower($this->getNameSingular())).
                'Block</method>'.$eol;
            $text  .= $this->getPadding(5).'</'.$ns.'_'.$this->getModule()->getLowerModuleName().'_'.
                $this->getNameSingular(true).'>'.$eol;
        }
        return $text;
    }

    /**
     * get xml for add product save after event
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getProductSaveAfterEvent()
    {
        $text = '';
        if ($this->getLinkProduct()) {
            $ns     = strtolower($this->getModule()->getNamespace());
            $eol    = $this->getEol();
            $text   = $this->getPadding(5).'<'.$ns.'_'.$this->getModule()->getLowerModuleName().'_'.
                $this->getNameSingular(true).'>'.$eol;
            $text  .= $this->getPadding(6).'<type>singleton</type>'.$eol;
            $text  .= $this->getPadding(6).'<class>'.$ns.'_'.$this->getModule()->getLowerModuleName().
                '/adminhtml_observer</class>'.$eol;
            $text  .= $this->getPadding(6).'<method>save'.ucfirst(strtolower($this->getNameSingular())).
                'Data</method>'.$eol;
            $text  .= $this->getPadding(5).'</'.$ns.'_'.$this->getModule()->getLowerModuleName().
                '_'.$this->getNameSingular(true).'>'.$eol;
        }
        return $text;
    }

    /**
     * get xml for add can load ext js
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getCanLoadExtJsEvent()
    {
        $text = '';
        if ($this->getLinkProduct() && $this->getIsTree()) {
            $eol    = $this->getEol();
            $ns     = strtolower($this->getModule()->getNamespace());
            $text   = $this->getPadding(5).'<'.$ns.'_'.$this->getModule()->getLowerModuleName().'_'.
                $this->getNameSingular(true).'>'.$eol;
            $text  .= $this->getPadding(6).'<type>singleton</type>'.$eol;
            $text  .= $this->getPadding(6).'<class>'.$ns.'_'.$this->getModule()->getLowerModuleName().
                '/adminhtml_observer</class>'.$eol;
            $text  .= $this->getPadding(6).'<method>setCanLoadExtJs</method>'.$eol;
            $text  .= $this->getPadding(5).'</'.$ns.'_'.$this->getModule()->getLowerModuleName().
                '_'.$this->getNameSingular(true).'>'.$eol;
        }
        return $text;
    }

    /**
     * get xml for admin menu
     *
     * @access public
     * @param $padding
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getMenu($padding)
    {
        $extension  = $this->getModule()->getExtensionName(true);
        $module     = $this->getModule()->getLowerModuleName();
        $title      = ucwords($this->getLabelSingular());
        $entity     = strtolower($this->getNameSingular());
        $action     = $module.'_'.$entity;
        $eol        = $this->getEol();

        $text  = $this->getPadding($padding).'<'.$entity.' translate="title" module="'.$extension.'">'.$eol;
        $text .= $this->getPadding($padding + 1).'<title>'.$title.'</title>'.$eol;
        $text .= $this->getPadding($padding + 1).'<action>adminhtml/'.$action.'</action>'.$eol;
        $text .= $this->getPadding($padding + 1).'<sort_order>'.$this->getPosition().'</sort_order>'.$eol;
        $text .= $this->getPadding($padding).'</'.$entity.'>'.$eol;
        if ($this->getAllowComment()) {
            $text .= $this->getPadding($padding).'<'.$entity.
                '_comments translate="title" module="'.$extension.'">'.$eol;
            $text .= $this->getPadding($padding + 1).'<title>Manage '.$title.' Comments</title>'.$eol;
            $text .= $this->getPadding($padding + 1).'<action>adminhtml/'.$action.'_comment</action>'.$eol;
            $text .= $this->getPadding($padding + 1).'<sort_order>'.($this->getPosition() + 4).'</sort_order>'.$eol;
            $text .= $this->getPadding($padding).'</'.$entity.'_comments>'.$eol;
        }

        $text .= $this->getTypeInstance()->getAdditionalMenu($padding);
        return $text;

    }

    /**
     * get xml for acl
     *
     * @access public
     * @param $padding
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getMenuAcl($padding)
    {
        $extension  = $this->getModule()->getExtensionName(true);
        $title      = ucwords($this->getLabelSingular());
        $entity     = $this->getNameSingular(true);
        $eol        = $this->getEol();

        $text  = $this->getPadding($padding).'<'.$entity.' translate="title" module="'.$extension.'">'.$eol;
        $text .= $this->getPadding($padding + 1).'<title>'.$title.'</title>'.$eol;
        $text .= $this->getPadding($padding + 1).'<sort_order>'.$this->getPosition().'</sort_order>'.$eol;
        $text .= $this->getPadding($padding).'</'.$entity.'>'.$eol;

        if ($this->getAllowComment()) {
            $text .= $this->getPadding($padding).'<'.$entity.
                '_comments translate="title" module="'.$extension.'">'.$eol;
            $text .= $this->getPadding($padding + 1).'<title>Manage '.$title.' Comments</title>'.$eol;
            $text .= $this->getPadding($padding + 1).'<sort_order>'.($this->getPosition() + 5).'</sort_order>'.$eol;
            $text .= $this->getPadding($padding).'</'.$entity.'_comments>'.$eol;
        }
        $text .= $this->getTypeInstance()->getAdditionalMenuAcl($padding);
        return $text;

    }

    /**
     * get attributes that need to be added in the admin grid collection
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getCollectionAttributes()
    {
        return $this->getTypeInstance()->getCollectionAttributes();
    }

    /**
     * get join with admin - for the admin grid
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAdminJoin()
    {
        return $this->getTypeInstance()->getAdminJoin();
    }

    /**
     * code above the prepare columns
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getPrepareColumnsHeader()
    {
        return $this->getTypeInstance()->getPrepareColumnsHeader();
    }

    /**
     * name attribute in grid
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getNameAttributeGridEav()
    {
        return $this->getTypeInstance()->getNameAttributeGridEav();
    }

    /**
     * check if the frontend list block can be created
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getCanCreateListBlock()
    {
        if ($this->getCreateList()) {
            return true;
        }
        if ($this->getShowOnProduct()) {
            return true;
        }
        if ($this->getShowOnCategory()) {
            return true;
        }
        //check for siblings with frontend view
        $related = $this->getRelatedEntities(
            Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_SIBLING
        );
        foreach ($related as $r) {
            if ($r->getCreateView()) {
                return true;
            }
        }
        //check for parents with frontend view
        $related = $this->getRelatedEntities(
            Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_CHILD
        );
        foreach ($related as $r) {
            if ($r->getCreateView()) {
                return true;
            }
        }
        return false;
    }

    /**
     * get ddl text for attributes
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAttributesDdlSql()
    {
        $padding    = $this->getPadding();
        $eol        = $this->getEol();
        $content    = '';
        $content   .= $this->getParentEntitiesFkAttributes($padding, true);
        if ($this->getIsFlat()) {
            foreach ($this->getAttributes() as $attribute) {
                $content .= $padding.$attribute->getDdlSqlColumn().$eol;
            }
        }
        if ($this->getIsFlat()) {
            $simulated = $this->getSimulatedAttributes(null, false);
        } elseif ($this->getIsTree()) {
            $simulated = $this->getSimulatedAttributes('tree', false);
        } else {
            $simulated = array();
        }
        foreach ($simulated as $attr) {
            $content .= $padding.$attr->getDdlSqlColumn().$eol;
        }
        return substr($content, 0, strlen($content) - strlen($eol));
    }

    /**
     * get foreign key columns
     *
     * @access public
     * @param $padding
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getParentEntitiesFkAttributes($padding)
    {
        if ($this->getIsEav()) {
            return '';
        }
        $parents = $this->getRelatedEntities(
            Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_CHILD
        );
        $content = '';
        foreach ($parents as $parent) {
            /** @var Ultimate_ModuleCreator_Model_Attribute $attr */
            $attr = Mage::getModel('modulecreator/attribute');
            $attr->setCode($parent->getPlaceholders('{{entity}}').'_id');
            $attr->setLabel($parent->getPlaceholders('{{EntityLabel}}'). ' ID');
            $attr->setType('int');
            $content .= $padding.$attr->getDdlSqlColumn()."\n";
        }
        return $content;
    }

    /**
     * get simulated attributes
     *
     * @access public
     * @param mixed $type
     * @param bool $ignoreSettings
     * @param array $except
     * @return Ultimate_ModuleCreator_Model_Attribute[]
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getSimulatedAttributes($type = null, $ignoreSettings = false, $except = array())
    {
        /** @var Ultimate_ModuleCreator_Model_Attribute[] $attributes */
        $attributes = array();
        $namespace  = $this->getNamespace(true);
        if (is_null($type)) {
            $types = array('status', 'url_rewrite', 'tree', 'rss', 'seo', 'comment');
            $attributes = array();
            foreach ($types as $type) {
                if (!in_array($type, $except)) {
                    $attributes = array_merge($attributes, $this->getSimulatedAttributes($type, $ignoreSettings));
                }
            }
            return $attributes;
        }
        switch ($type) {
            case 'status':
                /** @var Ultimate_ModuleCreator_Model_Attribute $attr */
                $attr = Mage::getModel('modulecreator/attribute');
                $attr->setCode('status');
                $attr->setLabel('Enabled');
                $attr->setType('yesno');
                $attr->setDefaultValue('1');
                $attributes[] = $attr;
                break;
            case 'url_rewrite':
                if ($this->getUrlRewrite() || $ignoreSettings) {
                    /** @var Ultimate_ModuleCreator_Model_Attribute $attr */
                    $attr = Mage::getModel('modulecreator/attribute');
                    $attr->setCode('url_key');
                    $attr->setLabel('URL key');
                    $attr->setType('text');
                    $module = $this->getModule()->getLowerModuleName();
                    $entity = $this->getNameSingular(true);
                    $attr->setForcedSetupBackend($namespace.'_'.$module.'/'.$entity.'_attribute_backend_urlkey');
                    $attributes[] = $attr;
                }
                break;
            case 'tree' :
                if ($this->getIsTree() || $ignoreSettings) {
                    /** @var Ultimate_ModuleCreator_Model_Attribute $attr */
                    $attr = Mage::getModel('modulecreator/attribute');
                    $attr->setCode('parent_id');
                    $attr->setLabel('Parent id');
                    $attr->setType('int');
                    $attributes[] = $attr;

                    /** @var Ultimate_ModuleCreator_Model_Attribute $attr */
                    $attr = Mage::getModel('modulecreator/attribute');
                    $attr->setCode('path');
                    $attr->setLabel('Path');
                    $attr->setType('text');
                    $attr->setIgnoreApi(true);
                    $attributes[] = $attr;

                    /** @var Ultimate_ModuleCreator_Model_Attribute $attr */
                    $attr = Mage::getModel('modulecreator/attribute');
                    $attr->setCode('position');
                    $attr->setLabel('Position');
                    $attr->setType('int');
                    $attr->setIgnoreApi(true);
                    $attributes[] = $attr;

                    /** @var Ultimate_ModuleCreator_Model_Attribute $attr */
                    $attr = Mage::getModel('modulecreator/attribute');
                    $attr->setCode('level');
                    $attr->setLabel('Level');
                    $attr->setType('int');
                    $attr->setIgnoreApi(true);
                    $attributes[] = $attr;

                    /** @var Ultimate_ModuleCreator_Model_Attribute $attr */
                    $attr = Mage::getModel('modulecreator/attribute');
                    $attr->setCode('children_count');
                    $attr->setLabel('Children count');
                    $attr->setType('int');
                    $attr->setIgnoreApi(true);
                    $attributes[] = $attr;
                }
                break;
            case 'rss':
                if ($this->getRss() || $ignoreSettings) {
                    /** @var Ultimate_ModuleCreator_Model_Attribute $attr */
                    $attr = Mage::getModel('modulecreator/attribute');
                    $attr->setCode('in_rss');
                    $attr->setLabel('In RSS');
                    $attr->setType('yesno');
                    $attr->setDefaultValue('1');
                    $attributes[] = $attr;
                }
                break;
            case 'seo':
                if ($this->getAddSeo() || $ignoreSettings) {
                    /** @var Ultimate_ModuleCreator_Model_Attribute $attr */
                    $attr = Mage::getModel('modulecreator/attribute');
                    $attr->setCode('meta_title');
                    $attr->setLabel('Meta title');
                    $attr->setType('text');
                    $attributes[] = $attr;

                    /** @var Ultimate_ModuleCreator_Model_Attribute $attr */
                    $attr = Mage::getModel('modulecreator/attribute');
                    $attr->setCode('meta_keywords');
                    $attr->setLabel('Meta keywords');
                    $attr->setType('textarea');
                    $attributes[] = $attr;

                    $attr = Mage::getModel('modulecreator/attribute');
                    $attr->setCode('meta_description');
                    $attr->setLabel('Meta description');
                    $attr->setType('textarea');
                    $attributes[] = $attr;
                }
                break;
            case 'comment':
                if ($this->getAllowComment() || $ignoreSettings) {
                    /** @var Ultimate_ModuleCreator_Model_Attribute $attr */
                    $attr = Mage::getModel('modulecreator/attribute');
                    $attr->setCode('allow_comment');
                    $attr->setLabel('Allow Comment');
                    $attr->setType('dropdown');
                    $attr->setOptionsSource('custom');
                    $attr->setOptions(false);
                    $attr->setEntity($this);
                    $attr->setDefaultValue('2');
                    $attr->setForcedDefaultValue('2');
                    $attr->setForcedSource(
                        $namespace.'_'.$this->getModule()->getLowerModuleName().
                        '/adminhtml_source_yesnodefault'
                    );
                    $attributes[] = $attr;
                }
                break;
            default:
                break;
        }
        foreach ($attributes as $attribute) {
            $attribute->setUserDefined(false);
        }
        return $attributes;
    }

    /**
     * get layout xml head reference
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getReferenceHeadLayout()
    {
        $eol        = $this->getEol();
        $content    = $this->getPadding(2);
        if ($this->getIsTree()) {
            $namespace  = $this->getNamespace(true);
            $module     = $this->getModule()->getLowerModuleName();
            $entity     = $this->getNameSingular(true);
            $content   .= '<reference name="head">'.$eol;
            $content   .= $this->getPadding(3).
                '<action method="addItem" ifconfig="'.$namespace.'_'.$module.'/'.$entity.
                '/tree"><type>skin_js</type><js>js/'.$namespace.'_'.$module.'/tree.js</js></action>'.$eol;
            $content   .= $this->getPadding(2).'</reference>'.$eol;
            $content   .= $this->getPadding(2);
        }
        return $content;
    }

    /**
     * get layout xml for relation to product
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getRelationLayoutXml()
    {
        $eol        = $this->getEol();
        $content    = $this->getPadding(2);
        $module     = $this->getModule()->getLowerModuleName();
        $entityName = $this->getNameSingular(true);
        $namespace  = $this->getNamespace(true);
        if ($this->getIsTree()) {
            $content .= $this->getPadding().'<block type="'.$namespace.'_'.$module.'/'.
                $entityName.'_children" name="'.$entityName.'_children" template="'.
                $namespace.'_'.$module.'/'.$entityName.'/children.phtml" />'.$eol.$this->getPadding(2);
        }
        if ($this->getShowProducts()) {
            $content .= $this->getPadding().'<block type="'.
                $namespace.'_'.$module.'/'.$entityName.'_catalog_product_list" name="'.
                $entityName.'.info.products" as="'.$entityName.'_products" template="'.
                $namespace.'_'.$module.'/'.$entityName.'/catalog/product/list.phtml" />'.
                $eol.$this->getPadding(2);
        }
        if ($this->getShowCategory()) {
            $content .= $this->getPadding().'<block type="'.
                $namespace.'_'.$module.'/'.$entityName.'_catalog_category_list" name="'.
                $entityName.'.info.categories" as="'.$entityName.
                '_categories" template="'.$namespace.'_'.$module.'/'.$entityName.
                '/catalog/category/list.phtml" />'.$eol.$this->getPadding(2);
        }
        $children = $this->getRelatedEntities(
            Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_PARENT
        );
        $siblings = $this->getRelatedEntities(
            Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_SIBLING
        );
        /** @var Ultimate_ModuleCreator_Model_Entity[] $relatedEntities */
        $relatedEntities = array_merge($children, $siblings);
        foreach ($relatedEntities as $entity) {
            $content .= $this->getPadding().'<block type="'.$namespace.'_'.$module.'/'.
                $entityName.'_'.strtolower($entity->getNameSingular()).'_list" name="'.$entityName.
                '.'.strtolower($entity->getNameSingular()).'_list" as="'.$entityName.'_'.
                strtolower($this->getNamePlural()).'" template="'.$namespace.'_'.$module.'/'.$entityName.'/'.
                strtolower($entity->getNameSingular()).'/list.phtml" />'.$eol.$this->getPadding(2);
        }
        if ($this->getAllowComment()) {
            $content .= $this->getPadding().'<block type="'.
                $namespace.'_'.$module.'/'.$entityName.'_comment_list" name="'.
                $entityName.'.comments_list" as="'.$entityName.'_comment_list" template="'.$namespace.'_'.$module.'/'.
                $entityName.'/comment/list.phtml">'.$eol.$this->getPadding(2);
            $content .= $this->getPadding(2).
                '<block type="'.$namespace.'_'.$module.'/'.$entityName.
                '_comment_form" name="comment_form" as="comment_form" template="'.
                $namespace.'_'.$module.'/'.$entityName.'/comment/form.phtml" />'.$eol.$this->getPadding(2);
            $content .= $this->getPadding().'</block>'.$eol.$this->getPadding(2);
        }
        return $content;
    }

    /**
     * get html link to entity
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getHtmlLink()
    {
        $eol        = $this->getEol();
        $entity     = $this->getNameSingular(true);
        $entityUc   = ucfirst($this->getNameSingular());
        $nameCode   = $this->getNameAttributeMagicCode();
        $content    = '';
        $padd       = 3;
        if ($this->getCreateView()) {
            $padd = 4;
            $content .= $this->getPadding(3).'<a href="<?php echo $_'.$entity.'->get'.$entityUc.'Url()?>">'.$eol;
        }
        $content .= $this->getPadding($padd).'<?php echo $_'.$entity.'->get'.$nameCode.'();?>'.$eol;
        if ($this->getCreateView()) {
            $content .= $this->getPadding(3).'</a>';
        }
        $content .= '<br />';
        return $content;
    }

    /**
     * get the html for attributes in view page
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getViewAttributesHtml()
    {
        $eol     = $this->getEol();
        $content = '';
        foreach ($this->getAttributes() as $attribute) {
            if ($attribute->getFrontend()) {
                $content .= $this->getPadding().
                    '<div class="'.$this->getNameSingular().'-'.$attribute->getCode().'">'.$eol;
                $content .= $this->getPadding(2).$attribute->getFrontendHtml().$eol;
                $content .= $this->getPadding().'</div>'.$eol;
            }
        }
        return $content;
    }

    /**
     * check if comments should be split by store
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAllowCommentByStore()
    {
        return $this->getTypeInstance()->getAllowCommentByStore();
    }

    /**
     * get view widget attributes
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getViewWidgetAttributesHtml()
    {
        $content = '';
        $padding = $this->getPadding(3);
        $tab     = $this->getPadding();
        $eol     = $this->getEol();
        foreach ($this->getAttributes() as $attribute) {
            if ($attribute->getWidget()) {
                $content .= $padding.'<div class="'.$attribute->getCode().'-widget">'.
                    $eol.$padding.$tab.$attribute->getFrontendHtml().$padding.'</div>'.$eol;
            }
        }
        return $content;
    }

    /**
     * get the system attributes
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getSystemAttributes()
    {
        $position = 10;
        $content = '';
        $tab = $this->getPadding();
        $eol = $this->getEol();
        $padding = str_repeat($tab, 6);
        if ($this->getCreateFrontend()) {
            $content .= $padding.'<breadcrumbs translate="label">'.$eol;
            $content .= $padding.$tab.'<label>Use Breadcrumbs</label>'.$eol;
            $content .= $padding.$tab.'<frontend_type>select</frontend_type>'.$eol;
            $content .= $padding.$tab.'<source_model>adminhtml/system_config_source_yesno</source_model>'.$eol;
            $content .= $padding.$tab.'<sort_order>'.$position.'</sort_order>'.$eol;
            $content .= $padding.$tab.'<show_in_default>1</show_in_default>'.$eol;
            $content .= $padding.$tab.'<show_in_website>1</show_in_website>'.$eol;
            $content .= $padding.$tab.'<show_in_store>1</show_in_store>'.$eol;
            $content .= $padding.'</breadcrumbs>'.$eol;
            $position += 10;
        }
        if ($this->getUrlRewrite()) {
            $content .= $padding.'<url_rewrite_list translate="label comment">'.$eol;
            $content .= $padding.$tab.'<label>URL key for list page</label>'.$eol;
            $content .= $padding.$tab.'<frontend_type>text</frontend_type>'.$eol;
            $content .= $padding.$tab.'<sort_order>'.$position.'</sort_order>'.$eol;
            $content .= $padding.$tab.'<show_in_default>1</show_in_default>'.$eol;
            $content .= $padding.$tab.'<show_in_website>1</show_in_website>'.$eol;
            $content .= $padding.$tab.'<show_in_store>1</show_in_store>'.$eol;
            $content .= $padding.$tab.'<comment>Leave empty to use default URL module/controller/action</comment>'.$eol;
            $content .= $padding.'</url_rewrite_list>'.$eol;
            $position += 10;


            $content .= $padding.'<url_prefix translate="label comment">'.$eol;
            $content .= $padding.$tab.'<label>URL prefix</label>'.$eol;
            $content .= $padding.$tab.'<frontend_type>text</frontend_type>'.$eol;
            $content .= $padding.$tab.'<sort_order>'.$position.'</sort_order>'.$eol;
            $content .= $padding.$tab.'<show_in_default>1</show_in_default>'.$eol;
            $content .= $padding.$tab.'<show_in_website>1</show_in_website>'.$eol;
            $content .= $padding.$tab.'<show_in_store>1</show_in_store>'.$eol;
            $content .= $padding.$tab.'<comment>Leave empty for no prefix</comment>'.$eol;
            $content .= $padding.'</url_prefix>'.$eol;
            $position += 10;

            $content .= $padding.'<url_suffix translate="label comment">'.$eol;
            $content .= $padding.$tab.'<label>Url suffix</label>'.$eol;
            $content .= $padding.$tab.'<frontend_type>text</frontend_type>'.$eol;
            $content .= $padding.$tab.'<sort_order>'.$position.'</sort_order>'.$eol;
            $content .= $padding.$tab.'<show_in_default>1</show_in_default>'.$eol;
            $content .= $padding.$tab.'<show_in_website>1</show_in_website>'.$eol;
            $content .= $padding.$tab.'<show_in_store>1</show_in_store>'.$eol;
            $content .= $padding.$tab.'<comment>What goes after the dot. Leave empty for no suffix.</comment>'.$eol;
            $content .= $padding.'</url_suffix>'.$eol;
            $position += 10;
        }
        if ($this->getAllowComment()) {
            $content .= $padding.'<allow_comment translate="label">'.$eol;
            $content .= $padding.$tab.'<label>Allow comments</label>'.$eol;
            $content .= $padding.$tab.'<frontend_type>select</frontend_type>'.$eol;
            $content .= $padding.$tab.'<source_model>adminhtml/system_config_source_yesno</source_model>'.$eol;
            $content .= $padding.$tab.'<sort_order>'.$position.'</sort_order>'.$eol;
            $content .= $padding.$tab.'<show_in_default>1</show_in_default>'.$eol;
            $content .= $padding.$tab.'<show_in_website>1</show_in_website>'.$eol;
            $content .= $padding.$tab.'<show_in_store>1</show_in_store>'.$eol;
            $content .= $padding.'</allow_comment>'.$eol;
            $position += 10;

            $content .= $padding.'<allow_guest_comment translate="label">'.$eol;
            $content .= $padding.$tab.'<label>Allow guest comments</label>'.$eol;
            $content .= $padding.$tab.'<frontend_type>select</frontend_type>'.$eol;
            $content .= $padding.$tab.'<source_model>adminhtml/system_config_source_yesno</source_model>'.$eol;
            $content .= $padding.$tab.'<sort_order>'.$position.'</sort_order>'.$eol;
            $content .= $padding.$tab.'<show_in_default>1</show_in_default>'.$eol;
            $content .= $padding.$tab.'<show_in_website>1</show_in_website>'.$eol;
            $content .= $padding.$tab.'<show_in_store>1</show_in_store>'.$eol;
            $content .= $padding.$tab.'<depends>'.$eol;
            $content .= $padding.$tab.$tab.'<allow_comment>1</allow_comment>'.$eol;
            $content .= $padding.$tab.'</depends>'.$eol;
            $content .= $padding.'</allow_guest_comment>'.$eol;
            $position += 10;
        }
        if ($this->getRss()) {
            $content .= $padding.'<rss translate="label">'.$eol;
            $content .= $padding.$tab.'<label>Enable rss</label>'.$eol;
            $content .= $padding.$tab.'<frontend_type>select</frontend_type>'.$eol;
            $content .= $padding.$tab.'<source_model>adminhtml/system_config_source_yesno</source_model>'.$eol;
            $content .= $padding.$tab.'<sort_order>'.$position.'</sort_order>'.$eol;
            $content .= $padding.$tab.'<show_in_default>1</show_in_default>'.$eol;
            $content .= $padding.$tab.'<show_in_website>1</show_in_website>'.$eol;
            $content .= $padding.$tab.'<show_in_store>1</show_in_store>'.$eol;
            $content .= $padding.'</rss>'.$eol;
            $position += 10;
        }
        if ($this->getIsTree() && $this->getCreateList()) {
            $content .= $padding.'<tree translate="label">'.$eol;
            $content .= $padding.$tab.'<label>Display as tree</label>'.$eol;
            $content .= $padding.$tab.'<frontend_type>select</frontend_type>'.$eol;
            $content .= $padding.$tab.'<source_model>adminhtml/system_config_source_yesno</source_model>'.$eol;
            $content .= $padding.$tab.'<sort_order>'.$position.'</sort_order>'.$eol;
            $content .= $padding.$tab.'<show_in_default>1</show_in_default>'.$eol;
            $content .= $padding.$tab.'<show_in_website>1</show_in_website>'.$eol;
            $content .= $padding.$tab.'<show_in_store>1</show_in_store>'.$eol;
            $content .= $padding.'</tree>'.$eol;
            $position += 10;
        }
        if ($this->getIsTree() && ($this->getCreateList() || $this->getWidget())) {
            $content .= $padding.'<recursion translate="label">'.$eol;
            $content .= $padding.$tab.'<label>Recursion level</label>'.$eol;
            $content .= $padding.$tab.'<frontend_type>text</frontend_type>'.$eol;
            $content .= $padding.$tab.'<sort_order>'.$position.'</sort_order>'.$eol;
            $content .= $padding.$tab.'<show_in_default>1</show_in_default>'.$eol;
            $content .= $padding.$tab.'<show_in_website>1</show_in_website>'.$eol;
            $content .= $padding.$tab.'<show_in_store>1</show_in_store>'.$eol;
            $content .= $padding.'</recursion>'.$eol;
            $position += 10;
        }

        if ($this->getAddSeo() && $this->getCreateList()) {
            $content .= $padding.'<meta_title translate="label">'.$eol;
            $content .= $padding.$tab.'<label>Meta title for '.
                strtolower($this->getLabelPlural()).' list page</label>'.$eol;
            $content .= $padding.$tab.'<frontend_type>text</frontend_type>'.$eol;
            $content .= $padding.$tab.'<sort_order>'.$position.'</sort_order>'.$eol;
            $content .= $padding.$tab.'<show_in_default>1</show_in_default>'.$eol;
            $content .= $padding.$tab.'<show_in_website>1</show_in_website>'.$eol;
            $content .= $padding.$tab.'<show_in_store>1</show_in_store>'.$eol;
            $content .= $padding.'</meta_title>'.$eol;
            $position += 10;

            $content .= $padding.'<meta_description translate="label">'.$eol;
            $content .= $padding.$tab.'<label>Meta description for '.
                strtolower($this->getLabelPlural()).' list page</label>'.$eol;
            $content .= $padding.$tab.'<frontend_type>textarea</frontend_type>'.$eol;
            $content .= $padding.$tab.'<sort_order>'.$position.'</sort_order>'.$eol;
            $content .= $padding.$tab.'<show_in_default>1</show_in_default>'.$eol;
            $content .= $padding.$tab.'<show_in_website>1</show_in_website>'.$eol;
            $content .= $padding.$tab.'<show_in_store>1</show_in_store>'.$eol;
            $content .= $padding.'</meta_description>'.$eol;
            $position += 10;

            $content .= $padding.'<meta_keywords translate="label">'.$eol;
            $content .= $padding.$tab.'<label>Meta keywords for '.
                strtolower($this->getLabelPlural()).' list page</label>'.$eol;
            $content .= $padding.$tab.'<frontend_type>textarea</frontend_type>'.$eol;
            $content .= $padding.$tab.'<sort_order>'.$position.'</sort_order>'.$eol;
            $content .= $padding.$tab.'<show_in_default>1</show_in_default>'.$eol;
            $content .= $padding.$tab.'<show_in_website>1</show_in_website>'.$eol;
            $content .= $padding.$tab.'<show_in_store>1</show_in_store>'.$eol;
            $content .= $padding.'</meta_keywords>'.$eol;
        }
        return substr($content, 0, strlen($content) - strlen($eol));
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
        $content  = $this->getApiTree();
        $content .= $this->getTypeInstance()->getApiAdditional();
        $content .= $this->getApiRelations();
        return $content;
    }

    /**
     * get additional api xml for tree entities
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getApiTree()
    {
        $content    = '';
        $padding    = $this->getPadding();
        $prefix     = str_repeat($padding, 5);
        $eol        = $this->getEol();
        $extension  = $this->getModule()->getExtensionName(true);
        if ($this->getIsTree()) {
            $module         = $this->getModule()->getLowerModuleName();
            $entity         = $this->getNameSingular(true);
            $entityLabel    = strtolower($this->getLabelSingular());
            $entitiesLabel  = strtolower($this->getLabelPlural());
            $content  = $prefix.'<level translate="title" module="'.$extension.'">'.$eol;
            $content .= $prefix.$padding.'<title>Retrieve one level of '.$entitiesLabel.'</title>'.$eol;
            $content .= $prefix.$padding.'<acl>'.$module.'/'.$entity.'/info</acl>'.$eol;
            $content .= $prefix.'</level>'.$eol;
            $content .= $prefix.'<move translate="title" module="'.$extension.'">'.$eol;
            $content .= $prefix.$padding.'<title>Move '.$entityLabel.' in tree</title>'.$eol;
            $content .= $prefix.$padding.'<acl>'.$module.'/'.$entity.'/move</acl>'.$eol;
            $content .= $prefix.'</move>'.$eol;
        }
        return $content;
    }

    /**
     * get api relations for a section
     *
     * @access public
     * @param $relatedCode
     * @param $relatedLabel
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getApiRelationsSection($relatedCode, $relatedLabel)
    {
        $eol            = $this->getEol();
        $padding        = $this->getPadding();
        $prefix         = $this->getPadding(5);
        $module         = $this->getModule()->getLowerModuleName();
        $entity         = $this->getNameSingular(true);
        $entityLabelUc  = ucfirst($this->getLabelSingular());
        $entityLabel    = strtolower($this->getLabelSingular());
        $extension      = $this->getModule()->getExtensionName(true);

        $string  = '';
        $string .= $prefix. '<assign'.$relatedCode.' translate="title" module="'.$extension.'">'.$eol;
        $string .= $prefix.$padding. '<title>Assign '.$relatedLabel.' to '.$entityLabelUc.'</title>'.$eol;
        $string .= $prefix.$padding. '<acl>'.$module.'/'.$entity.'/update</acl>'.$eol;
        $string .= $prefix. '</assign'.$relatedCode.'>'.$eol;

        $string .= $prefix. '<unassign'.$relatedCode.' translate="title" module="'.$extension.'">'.$eol;
        $string .= $prefix.$padding. '<title>Remove '.$relatedLabel.' from '.$entityLabel.'</title>'.$eol;
        $string .= $prefix.$padding. '<acl>'.$module.'/'.$entity.'/update</acl>'.$eol;
        $string .= $prefix. '</unassign'.$relatedCode.'>'.$eol;
        return $string;
    }

    /**
     * get API xml for entity relations
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getApiRelations()
    {
        $string         = '';
        if ($this->getLinkProduct()) {
            $string .= $this->getApiRelationsSection('Product', 'product');
        }
        if ($this->getLinkCategory()) {
            $string .= $this->getApiRelationsSection('Category', 'category');
        }
        $siblings = $this->getRelatedEntities(Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_SIBLING);
        foreach ($siblings as $sibling) {
            $siblingNameUc   = ucfirst($sibling->getNameSingular());
            $siblingLabel    = strtolower($sibling->getLabelSingular());
            $string         .= $this->getApiRelationsSection($siblingNameUc, $siblingLabel);
        }
        $string .= $this->getPadding(4);
        return $string;
    }

    /**
     * get API faults for a section
     *
     * @access public
     * @param $relatedCode
     * @param $relatedLabel
     * @param $code
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getApiFaultsSection($relatedCode, $relatedLabel, $code)
    {
        $padding        = $this->getPadding();
        $prefix         = str_repeat($padding, 5);
        $eol            = $this->getEol();
        $string         = '';
        $string        .= $prefix.'<'.$relatedCode.'_not_exists>'.$eol;
        $string        .= $prefix.$padding.'<code>'.$code.'</code>'.$eol;
        $string        .= $prefix.$padding.'<message>'.$relatedLabel.' does not exist.</message>'.$eol;
        $string        .= $prefix.'</'.$relatedCode.'_not_exists>'.$eol;

        return $string;
    }

    /**
     * get list of faults for API
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getApiFaults()
    {
        $string         = '';
        $padding        = $this->getPadding();
        $prefix         = str_repeat($padding, 5);
        $eol            = $this->getEol();
        $code           = 105;
        $entity         = $this->getNameSingular(true);
        $entityLabelUc  = ucfirst($this->getLabelSingular());
        if ($this->getIsTree()) {
            $string .= $prefix.'<not_moved>'.$eol;
            $string .= $prefix.$padding.'<code>'.$code.'</code>'.$eol;
            $string .= $prefix.$padding.'<message>'.
                $entityLabelUc.' not moved. Details in error message.</message>'.$eol;
            $string .= $prefix.'</not_moved>'.$eol;
            $code++;
        }
        if ($this->getLinkProduct()) {
            $string .= $this->getApiFaultsSection('product', 'Product', $code);
            $code++;
        }
        if ($this->getLinkCategory()) {
            $string .= $this->getApiFaultsSection('category', 'Category', $code);
            $code++;
        }
        $siblings = $this->getRelatedEntities(
            Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_SIBLING
        );
        foreach ($siblings as $sibling) {
            $siblingName     = $sibling->getNameSingular(true);
            $siblingLabelUc  = ucfirst($sibling->getLabelSingular());

            $string .= $this->getApiFaultsSection($entity.'_'.$siblingName, $siblingLabelUc, $code);
            $code++;
        }
        $string .= $this->getTypeInstance()->getApiFaults();
        $string .= str_repeat($padding, 4);
        return $string;
    }

    /**
     * get additional api acl
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAdditionalApiAcl()
    {
        $content    = '';
        $padding    = $this->getPadding();
        $prefix     = str_repeat($padding, 6);
        $eol        = $this->getEol();
        $extension  = $this->getModule()->getExtensionName(true);
        if ($this->getIsTree()) {
            $content       .= $prefix.'<move translate="title" module="'.$extension.'">'.$eol;
            $content       .= $prefix.$padding.'<title>Move</title>'.$eol;
            $content       .= $prefix.'</move>'.$eol;
        }
        $content .= str_repeat($padding, 5);
        return $content;
    }

    /**
     * get attributes format for wsdl
     *
     * @access public
     * @param bool $wsi
     * @param bool $forAdd
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getWsdlAttributes($wsi = false, $forAdd = false)
    {
        $parents    = $this->getRelatedEntities(
            Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_CHILD
        );
        $tab        = $this->getPadding();
        $padding    = str_repeat($tab, 5);
        $eol        = $this->getEol();
        $content    = '';
        foreach ($parents as $parent) {
            /** @var Ultimate_ModuleCreator_Model_Attribute $attr */
            $attr = Mage::getModel('modulecreator/attribute');
            $attr->setCode($parent->getNameSingular().'_id');
            $attr->setLabel($parent->getLabelSingular());
            $attr->setType('int');
            $content .= $padding.$attr->getWsdlFormat($wsi).$eol;
        }
        foreach ($this->getAttributes() as $attribute) {
            $content .= $padding.$attribute->getWsdlFormat($wsi).$eol;
        }
        $simulated = $this->getSimulatedAttributes(null, false);
        foreach ($simulated as $attr) {
            if (!$forAdd || !$attr->getIgnoreApi()) {
                $content .= $padding.$attr->getWsdlFormat($wsi).$eol;
            }
        }
        $content .= $this->getTypeInstance()->getWsdlAttributes($wsi);
        return $content;
    }

    /**
     * get attributes format for wsi
     *
     * @access public
     * @param bool $forAdd
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getWsiAttributes($forAdd = false)
    {
        return $this->getWsdlAttributes(true, $forAdd);
    }

    /**
     * get wsdl relation type for a section
     *
     * @param $relatedCode
     * @param $relatedId
     * @param bool $wsi
     * @return string
     */
    public function getWsdlRelationTypesSection($relatedCode, $relatedId, $wsi = false)
    {
        $content    = '';
        $tab        = $this->getPadding();
        $padding    = str_repeat($tab, 3);
        $mainTag    = ($wsi) ? 'xsd:complexType':'complexType';
        $subtag     = ($wsi) ? 'xsd:sequence' : 'all';
        $element    = ($wsi) ? 'xsd:element' : 'element';
        $eol        = $this->getEol();
        $module     = $this->getModule()->getLowerModuleName();
        $entity     = $this->getNameSingular(true);
        $entityUc   = ucfirst($this->getNameSingular());

        $content .= $padding.'<'.$mainTag .' name="'.$module.$entityUc.'Assign'.$relatedCode.'Entity">'.$eol;
        $content .= $padding.$tab.'<'.$subtag.'>'.$eol;
        $content .= $padding.$tab.'<'.$element.' name="'.$entity.'Id" type="xsd:string"'.
            ((!$wsi)?' minOccurs="1"':'').' />'.$eol;
        $content .= $padding.$tab.'<'.$element.' name="'.$relatedId.'Id" type="xsd:string"'.
            ((!$wsi)?' minOccurs="1"':'').' />'.$eol;
        $content .= $padding.$tab.'<'.$element.' name="position" type="xsd:string"'.
            ((!$wsi)?' minOccurs="0"':'').' />'.$eol;
        $content .= $padding.$tab.'</'.$subtag.'>'.$eol;
        $content .= $padding.'</'.$mainTag.'>'.$eol;

        $content .= $padding.'<'.$mainTag .' name="'.$module.$entityUc.'Unassign'.$relatedCode.'Entity">'.$eol;
        $content .= $padding.$tab.'<'.$subtag.'>'.$eol;
        $content .= $padding.$tab.'<'.$element.' name="'.
            $entity.'Id" type="xsd:string"'.((!$wsi)?' minOccurs="1"':'').' />'.$eol;
        $content .= $padding.$tab.'<'.$element.' name="'.
            $relatedId.'Id" type="xsd:string"'.((!$wsi)?' minOccurs="1"':'').' />'.$eol;
        $content .= $padding.$tab.'</'.$subtag.'>'.$eol;
        $content .= $padding.'</'.$mainTag.'>'.$eol;

        return $content;
    }

    /**
     * get entity WSDL relation types
     *
     * @access public
     * @param bool $wsi
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getWsdlRelationTypes($wsi = false)
    {
        $content    = '';
        $tab        = $this->getPadding();
        $padding    = str_repeat($tab, 3);
        $mainTag    = ($wsi) ? 'xsd:complexType':'complexType';
        $subtag     = ($wsi) ? 'xsd:sequence' : 'all';
        $element    = ($wsi) ? 'xsd:element' : 'element';
        $eol        = $this->getEol();
        $module     = $this->getModule()->getLowerModuleName();
        $entity     = $this->getNameSingular(true);
        $entityUc   = ucfirst($this->getNameSingular());
        if ($this->getIsTree()) {
            $content .= $padding.'<'.$mainTag .' name="'.$module.$entityUc.'MoveEntity">'.$eol;
            $content .= $padding.$tab.'<'.$subtag.'>'.$eol;
            $content .= $padding.$tab.'<'.$element.
                ' name="'.$entity.'_id" type="xsd:string"'.((!$wsi)?' minOccurs="1"':'').' />'.$eol;
            $content .= $padding.$tab.'<'.$element.
                ' name="parent_id" type="xsd:string"'.((!$wsi)?' minOccurs="1"':'').' />'.$eol;
            $content .= $padding.$tab.'<'.$element.
                ' name="after_id" type="xsd:string"'.((!$wsi)?' minOccurs="0"':'').' />'.$eol;
            $content .= $padding.$tab.'</'.$subtag.'>'.$eol;
            $content .= $padding.'</'.$mainTag.'>'.$eol;
        }
        if ($this->getLinkProduct()) {
            $content .= $this->getWsdlRelationTypesSection('Product', 'product', $wsi);
        }
        if ($this->getLinkCategory()) {
            $content .= $this->getWsdlRelationTypesSection('Category', 'category', $wsi);
        }
        $siblings = $this->getRelatedEntities(
            Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_SIBLING
        );
        foreach ($siblings as $sibling) {
            $siblingName     = $sibling->getNameSingular(true);
            $siblingNameUc   = ucfirst($sibling->getNameSingular());
            $content .= $this->getWsdlRelationTypesSection($siblingNameUc, $siblingName, $wsi);

        }
        $content .= $tab.$tab;
        return $content;
    }

    /**
     * get entity WSI relation types
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getWsiRelationTypes()
    {
        return $this->getWsdlRelationTypes(true);
    }

    /**
     * get wsdl port type relations for a section
     *
     * @access public
     * @param $relatedLabel
     * @param $relatedText
     * @param $wsi
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getWsdlPortTypeRelationSection($relatedLabel, $relatedText, $wsi)
    {
        $content    = '';
        $tab        = $this->getPadding();
        $padding    = $tab.$tab;
        $eol        = $this->getEol();
        $module     = $this->getModule()->getLowerModuleName();
        $entityUc   = ucfirst($this->getNameSingular());
        $label      = strtolower($this->getLabelSingular());
        $tagPrefix  = ($wsi) ? 'wsdl:':'';

        $content .= $padding.'<'.$tagPrefix.'operation name="'.$module.$entityUc.'Assign'.$relatedLabel.'">'.$eol;
        $content .= $padding.$tab.'<'.$tagPrefix.'documentation>Assign '.
            $relatedText.' to '.$label.'</'.$tagPrefix.'documentation>'.$eol;
        $content .= $padding.$tab.'<'.$tagPrefix.'input message="typens:'.
            $module.$entityUc.'Assign'.$relatedLabel.'Request" />'.$eol;
        $content .= $padding.$tab.'<'.$tagPrefix.'output message="typens:'.
            $module.$entityUc.'Assign'.$relatedLabel.'Response" />'.$eol;
        $content .= $padding.'</'.$tagPrefix.'operation>'.$eol;
        $content .= $padding.'<'.$tagPrefix.'operation name="'.$module.$entityUc.'Unassign'.$relatedLabel.'">'.$eol;
        $content .= $padding.$tab.'<'.$tagPrefix.'documentation>Remove '.
            $relatedText.' from '.$label.'</'.$tagPrefix.'documentation>'.$eol;
        $content .= $padding.$tab.'<'.$tagPrefix.'input message="typens:'.
            $module.$entityUc.'Unassign'.$relatedLabel.'Request" />'.$eol;
        $content .= $padding.$tab.'<'.$tagPrefix.'output message="typens:'.
            $module.$entityUc.'Unassign'.$relatedLabel.'Response" />'.$eol;
        $content .= $padding.'</'.$tagPrefix.'operation>'.$eol;

        return $content;
    }

    /**
     * get entity WSDL port type for relations
     *
     * @access public
     * @param bool $wsi
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getWsdlPortTypeRelation($wsi = false)
    {
        $content    = '';
        $tab        = $this->getPadding();
        $padding    = $tab.$tab;
        $eol        = $this->getEol();
        $module     = $this->getModule()->getLowerModuleName();
        $entity     = $this->getNameSingular(true);
        $entityUc   = ucfirst($entity);
        $label      = strtolower($this->getLabelSingular());

        $tagPrefix = ($wsi) ? 'wsdl:':'';

        if ($this->getIsTree()) {
            $content .= $padding.'<'.$tagPrefix.'operation name="'.$module.$entityUc.'Move">'.$eol;
            $content .= $padding.$tab.'<'.$tagPrefix.'documentation>Move '.$label.
                ' in tree</'.$tagPrefix.'documentation>'.$eol;
            $content .= $padding.$tab.'<'.$tagPrefix.'input message="typens:'.$module.$entityUc.'MoveRequest" />'.$eol;
            $content .= $padding.$tab.'<'.$tagPrefix.'output message="typens:'.
                $module.$entityUc.'MoveResponse" />'.$eol;
            $content .= $padding.'</'.$tagPrefix.'operation>'.$eol;

        }

        if ($this->getLinkProduct()) {
            $content .= $this->getWsdlPortTypeRelationSection('Product', 'product', $wsi);
        }

        if ($this->getLinkCategory()) {
            $content .= $this->getWsdlPortTypeRelationSection('Category', 'category', $wsi);
        }
        $siblings = $this->getRelatedEntities(
            Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_SIBLING
        );
        foreach ($siblings as $sibling) {
            $siblingNameUc   = ucfirst($sibling->getNameSingular());
            $siblingLabel    = strtolower($sibling->getLabelSingular());

            $content .= $this->getWsdlPortTypeRelationSection($siblingNameUc, $siblingLabel, $wsi);

        }
        $content .= $tab;
        return $content;
    }

    /**
     * get entity WSI port type for relations
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getWsiPortTypeRelation()
    {
        return $this->getWsdlPortTypeRelation(true);
    }

    /**
     * get wsld relation binding for a section
     *
     * @access public
     * @param $sectionName
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getWsdlRelationBindingSection($sectionName)
    {
        $content     = '';
        $tab        = $this->getPadding();
        $doubleTab  = $tab.$tab;
        $padding    = $doubleTab;
        $eol        = $this->getEol();
        $module     = $this->getModule()->getLowerModuleName();
        $entity     = $this->getNameSingular(true);
        $entityUc   = ucfirst($entity);

        $content .= $padding.'<operation name="'.$module.$entityUc.$sectionName.'">'.$eol;
        $content .= $padding.$tab.'<soap:operation soapAction="urn:{{var wsdl.handler}}Action" />'.$eol;
        $content .= $padding.$tab.'<input>'.$eol;
        $content .= $padding.$doubleTab.
            '<soap:body namespace="urn:{{var wsdl.name}}"'.
            ' use="encoded" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" />'.
            $eol;
        $content .= $padding.$tab.'</input>'.$eol;
        $content .= $padding.$tab.'<output>'.$eol;
        $content .= $padding.$doubleTab.
            '<soap:body namespace="urn:{{var wsdl.name}}"'.
            ' use="encoded" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" />'.
            $eol;
        $content .= $padding.$tab.'</output>'.$eol;
        $content .= $padding.'</operation>'.$eol;
        return $content;
    }

    /**
     * get WSDL relation binding
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getWsdlRelationBinding()
    {
        $content     = '';
        if ($this->getIsTree()) {
            $content .= $this->getWsdlRelationBindingSection('Move');
        }
        if ($this->getLinkProduct()) {
            $content .= $this->getWsdlRelationBindingSection('AssignProduct');
            $content .= $this->getWsdlRelationBindingSection('UnassignProduct');
        }
        if ($this->getLinkCategory()) {
            $content .= $this->getWsdlRelationBindingSection('AssignCategory');
            $content .= $this->getWsdlRelationBindingSection('UnassignCategory');
        }

        $siblings = $this->getRelatedEntities(
            Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_SIBLING
        );
        foreach ($siblings as $sibling) {
            $siblingName     = $sibling->getNameSingular(true);
            $siblingNameUc   = ucfirst($siblingName);
            $content .= $this->getWsdlRelationBindingSection('Assign'.$siblingNameUc);
            $content .= $this->getWsdlRelationBindingSection('Unassign'.$siblingNameUc);
        }
        $content .= $this->getPadding();
        return $content;
    }

    /**
     * get wsld relation binding for a section
     *
     * @access public
     * @param $sectionName
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getWsiRelationBindingSection($sectionName)
    {
        $content     = '';
        $tab        = $this->getPadding();
        $doubleTab  = $tab.$tab;
        $padding    = $doubleTab;
        $eol        = $this->getEol();
        $module     = $this->getModule()->getLowerModuleName();
        $entity     = $this->getNameSingular(true);
        $entityUc   = ucfirst($entity);

        $content .= $padding.'<wsdl:operation name="'.$module.$entityUc.$sectionName.'">'.$eol;
        $content .= $padding.$tab.'<soap:operation soapAction="" />'.$eol;
        $content .= $padding.$tab.'<wsdl:input>'.$eol;
        $content .= $padding.$doubleTab.'<soap:body use="literal" />'.$eol;
        $content .= $padding.$tab.'</wsdl:input>'.$eol;
        $content .= $padding.$tab.'<wsdl:output>'.$eol;
        $content .= $padding.$doubleTab.'<soap:body use="literal" />'.$eol;
        $content .= $padding.$tab.'</wsdl:output>'.$eol;
        $content .= $padding.'</wsdl:operation>'.$eol;
        return $content;
    }

    /**
     * get WSI relation binding
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getWsiRelationBinding()
    {
        $content     = '';
        if ($this->getIsTree()) {
            $content .= $this->getWsiRelationBindingSection('Move');
        }
        if ($this->getLinkProduct()) {
            $content .= $this->getWsiRelationBindingSection('AssignProduct');
            $content .= $this->getWsiRelationBindingSection('UnassignProduct');
        }
        if ($this->getLinkCategory()) {
            $content .= $this->getWsiRelationBindingSection('AssignCategory');
            $content .= $this->getWsiRelationBindingSection('UnassignCategory');
        }

        $siblings = $this->getRelatedEntities(
            Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_SIBLING
        );
        foreach ($siblings as $sibling) {
            $siblingName     = $sibling->getNameSingular(true);
            $siblingNameUc   = ucfirst($siblingName);

            $content .= $this->getWsiRelationBindingSection('Assign'.$siblingNameUc);
            $content .= $this->getWsiRelationBindingSection('Unassign'.$siblingNameUc);
        }
        $content .= $this->getPadding();
        return $content;
    }

    /**
     * get wsi relation param types for a section
     *
     * @access public
     * @param $sectionName
     * @param $sectionParam
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getWsiRelationParamTypesSection($sectionName, $sectionParam)
    {
        $content    = '';
        $tab        = $this->getPadding();
        $padding    = $this->getPadding(3);
        $eol        = $this->getEol();
        $module     = $this->getModule()->getLowerModuleName();
        $entity     = $this->getNameSingular(true);
        $entityUc   = ucfirst($entity);
        $content .= $padding.'<xsd:element name="'.$module.$entityUc.'Assign'.$sectionName.'RequestParam">'.$eol;
        $content .= $padding.$tab.'<xsd:complexType>'.$eol;
        $content .= $padding.str_repeat($tab, 2).'<xsd:sequence>'.$eol;
        $content .= $padding.str_repeat($tab, 3).
            '<xsd:element minOccurs="1" maxOccurs="1" name="sessionId" type="xsd:string" />'.$eol;
        $content .= $padding.str_repeat($tab, 3).
            '<xsd:element minOccurs="1" maxOccurs="1" name="'.$entity.'Id" type="xsd:string" />'.$eol;
        $content .= $padding.str_repeat($tab, 3).
            '<xsd:element minOccurs="1" maxOccurs="1" name="'.$sectionParam.'Id" type="xsd:string" />'.$eol;
        $content .= $padding.str_repeat($tab, 3).
            '<xsd:element minOccurs="0" maxOccurs="1" name="position" type="xsd:string" />'.$eol;
        $content .= $padding.str_repeat($tab, 2).'</xsd:sequence>'.$eol;
        $content .= $padding.$tab.'</xsd:complexType>'.$eol;
        $content .= $padding.'</xsd:element>'.$eol;

        $content .= $padding.'<xsd:element name="'.$module.$entityUc.'Assign'.$sectionName.'ResponseParam">'.$eol;
        $content .= $padding.$tab.'<xsd:complexType>'.$eol;
        $content .= $padding.str_repeat($tab, 2).'<xsd:sequence>'.$eol;
        $content .= $padding.str_repeat($tab, 3).
            '<xsd:element minOccurs="1" maxOccurs="1" name="result" type="xsd:boolean" />'.$eol;
        $content .= $padding.str_repeat($tab, 2).'</xsd:sequence>'.$eol;
        $content .= $padding.$tab.'</xsd:complexType>'.$eol;
        $content .= $padding.'</xsd:element>'.$eol;

        $content .= $padding.'<xsd:element name="'.$module.$entityUc.'Unassign'.$sectionName.'RequestParam">'.$eol;
        $content .= $padding.$tab.'<xsd:complexType>'.$eol;
        $content .= $padding.str_repeat($tab, 2).'<xsd:sequence>'.$eol;
        $content .= $padding.str_repeat($tab, 3).
            '<xsd:element minOccurs="1" maxOccurs="1" name="sessionId" type="xsd:string" />'.$eol;
        $content .= $padding.str_repeat($tab, 3).
            '<xsd:element minOccurs="1" maxOccurs="1" name="'.$entity.'Id" type="xsd:string" />'.$eol;
        $content .= $padding.str_repeat($tab, 3).
            '<xsd:element minOccurs="1" maxOccurs="1" name="'.$sectionParam.'Id" type="xsd:string" />'.$eol;
        $content .= $padding.str_repeat($tab, 2).'</xsd:sequence>'.$eol;
        $content .= $padding.$tab.'</xsd:complexType>'.$eol;
        $content .= $padding.'</xsd:element>'.$eol;

        $content .= $padding.'<xsd:element name="'.$module.$entityUc.'Unassign'.$sectionName.'ResponseParam">'.$eol;
        $content .= $padding.$tab.'<xsd:complexType>'.$eol;
        $content .= $padding.str_repeat($tab, 2).'<xsd:sequence>'.$eol;
        $content .= $padding.str_repeat($tab, 3).
            '<xsd:element minOccurs="1" maxOccurs="1" name="result" type="xsd:boolean" />'.$eol;
        $content .= $padding.str_repeat($tab, 2).'</xsd:sequence>'.$eol;
        $content .= $padding.$tab.'</xsd:complexType>'.$eol;
        $content .= $padding.'</xsd:element>'.$eol;

        return $content;
    }

    /**
     * get entity WSI relation param types
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getWsiRelationParamTypes()
    {
        $content    = '';
        $tab        = $this->getPadding();
        $padding    = str_repeat($tab, 3);
        $eol        = $this->getEol();
        $module     = $this->getModule()->getLowerModuleName();
        $entity     = $this->getNameSingular(true);
        $entityUc   = ucfirst($entity);

        if ($this->getIsTree()) {
            $content .= $padding.'<xsd:element name="'.$module.$entityUc.'MoveRequestParam">'.$eol;
            $content .= $padding.$tab.'<xsd:complexType>'.$eol;
            $content .= $padding.str_repeat($tab, 2).'<xsd:sequence>'.$eol;
            $content .= $padding.str_repeat($tab, 3).
                '<xsd:element minOccurs="1" maxOccurs="1" name="session_id" type="xsd:string" />'.$eol;
            $content .= $padding.str_repeat($tab, 3).
                '<xsd:element minOccurs="1" maxOccurs="1" name="'.$entity.'Id" type="xsd:string" />'.$eol;
            $content .= $padding.str_repeat($tab, 3).
                '<xsd:element minOccurs="1" maxOccurs="1" name="parentId" type="xsd:string" />'.$eol;
            $content .= $padding.str_repeat($tab, 3).
                '<xsd:element minOccurs="0" maxOccurs="1" name="afterId" type="xsd:string" />'.$eol;
            $content .= $padding.str_repeat($tab, 2).'</xsd:sequence>'.$eol;
            $content .= $padding.$tab.'</xsd:complexType>'.$eol;
            $content .= $padding.'</xsd:element>'.$eol;

            $content .= $padding.'<xsd:element name="'.$module.$entityUc.'AssignProductResponseParam">'.$eol;
            $content .= $padding.$tab.'<xsd:complexType>'.$eol;
            $content .= $padding.str_repeat($tab, 2).'<xsd:sequence>'.$eol;
            $content .= $padding.str_repeat($tab, 3).
                '<xsd:element minOccurs="1" maxOccurs="1" name="result" type="xsd:boolean" />'.$eol;
            $content .= $padding.str_repeat($tab, 2).'</xsd:sequence>'.$eol;
            $content .= $padding.$tab.'</xsd:complexType>'.$eol;
            $content .= $padding.'</xsd:element>'.$eol;
        }

        if ($this->getLinkProduct()) {
            $content .= $this->getWsiRelationParamTypesSection('Product', 'product');
        }

        if ($this->getLinkCategory()) {
            $content .= $this->getWsiRelationParamTypesSection('Category', 'category');
        }

        $siblings = $this->getRelatedEntities(
            Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_SIBLING
        );
        foreach ($siblings as $sibling) {
            $siblingName     = $sibling->getNameSingular(true);
            $siblingNameUc   = ucfirst($siblingName);
            $content .= $this->getWsiRelationParamTypesSection($siblingNameUc, $siblingName);
        }
        $content .= $tab.$tab;
        return $content;
    }

    /**
     * get wsi relation messaged for a section
     *
     * @param $sectionName
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getWsiRelationMessagesSection($sectionName)
    {
        $content    = '';
        $padding    = $this->getPadding();
        $tab        = $padding;
        $eol        = $this->getEol();
        $module     = $this->getModule()->getLowerModuleName();
        $entity     = $this->getNameSingular(true);
        $entityUc   = ucfirst($entity);

        $content .= $padding.'<wsdl:message name="'.$module.$entityUc.$sectionName.'Request">'.$eol;
        $content .= $padding.$tab.'<wsdl:part name="parameters" element="typens:'.
            $module.$entityUc.$sectionName.'RequestParam" />'.$eol;
        $content .= $padding.'</wsdl:message>'.$eol;
        $content .= $padding.'<wsdl:message name="'.$module.$entityUc.$sectionName.'Response">'.$eol;
        $content .= $padding.$tab.'<wsdl:part name="parameters" element="typens:'.
            $module.$entityUc.$sectionName.'ResponseParam" />'.$eol;
        $content .= $padding.'</wsdl:message>'.$eol;

        return $content;
    }

    /**
     * get entity WSI relation messages
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getWsiRelationMessages()
    {
        $content     = '';
        if ($this->getIsTree()) {
            $content .= $this->getWsiRelationMessagesSection('Move');
        }

        if ($this->getLinkProduct()) {
            $content .= $this->getWsiRelationMessagesSection('AssignProduct');
            $content .= $this->getWsiRelationMessagesSection('UnassignProduct');
        }

        if ($this->getLinkCategory()) {
            $content .= $this->getWsiRelationMessagesSection('AssignCategory');
            $content .= $this->getWsiRelationMessagesSection('UnassignCategory');
        }
        $siblings = $this->getRelatedEntities(Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_SIBLING);
        foreach ($siblings as $sibling) {
            $siblingName     = $sibling->getNameSingular(true);
            $siblingNameUc   = ucfirst($siblingName);

            $content .= $this->getWsiRelationMessagesSection('Assign'.$siblingNameUc);
            $content .= $this->getWsiRelationMessagesSection('Unassign'.$siblingNameUc);
        }
        return $content;
    }

    /**
     * get wsdl messages for a section
     *
     * @param $sectionName
     * @param $sectionParam
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getWsdlMessagesSection($sectionName, $sectionParam)
    {
        $content    = '';
        $tab        = $this->getPadding();
        $padding    = $tab.$tab;
        $eol        = $this->getEol();
        $module     = $this->getModule()->getLowerModuleName();
        $entity     = $this->getNameSingular(true);
        $entityUc   = ucfirst($entity);

        $content .= $padding.'<message name="'.$module.$entityUc.'Assign'.$sectionName.'Request">'.$eol;
        $content .= $padding.$tab.'<part name="sessionId" type="xsd:string" />'.$eol;
        $content .= $padding.$tab.'<part name="'.$entity.'_id" type="xsd:string" />'.$eol;
        $content .= $padding.$tab.'<part name="'.$sectionParam.'_id" type="xsd:string" />'.$eol;
        $content .= $padding.$tab.'<part name="position" type="xsd:string" />'.$eol;
        $content .= $padding.'</message>'.$eol;
        $content .= $padding.'<message name="'.$module.$entityUc.'Assign'.$sectionName.'Response">'.$eol;
        $content .= $padding.$tab.'<part name="result" type="xsd:boolean" />'.$eol;
        $content .= $padding.'</message>'.$eol;
        $content .= $padding.'<message name="'.$module.$entityUc.'Unassign'.$sectionName.'Request">'.$eol;
        $content .= $padding.$tab.'<part name="session_id" type="xsd:string" />'.$eol;
        $content .= $padding.$tab.'<part name="'.$entity.'_id" type="xsd:string" />'.$eol;
        $content .= $padding.$tab.'<part name="'.$sectionParam.'_id" type="xsd:string" />'.$eol;
        $content .= $padding.'</message>'.$eol;
        $content .= $padding.'<message name="'.$module.$entityUc.'Unassign'.$sectionName.'Response">'.$eol;
        $content .= $padding.$tab.'<part name="result" type="xsd:boolean" />'.$eol;
        $content .= $padding.'</message>'.$eol;

        return $content;
    }

    /**
     * get entity WSDL messages for relations
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getWsdlMessages()
    {
        $content    = '';
        $tab        = $this->getPadding();
        $padding    = $tab.$tab;
        $eol        = $this->getEol();
        $module     = $this->getModule()->getLowerModuleName();
        $entity     = $this->getNameSingular(true);
        $entityUc   = ucfirst($entity);

        if ($this->getIsTree()) {
            $content .= $padding.'<message name="'.$module.$entityUc.'MoveRequest">'.$eol;
            $content .= $padding.$tab.'<part name="session_id" type="xsd:string" />'.$eol;
            $content .= $padding.$tab.'<part name="'.$entity.'_id" type="xsd:string" />'.$eol;
            $content .= $padding.$tab.'<part name="parent_id" type="xsd:string" />'.$eol;
            $content .= $padding.$tab.'<part name="after_id" type="xsd:string" />'.$eol;
            $content .= $padding.'</message>'.$eol;

            $content .= $padding.'<message name="'.$module.$entityUc.'MoveResponse">'.$eol;
            $content .= $padding.$tab.'<part name="id" type="xsd:boolean"/>'.$eol;
            $content .= $padding.'</message>'.$eol;
        }
        if ($this->getLinkProduct()) {
            $content .= $this->getWsdlMessagesSection('Product', 'product');
        }

        if ($this->getLinkCategory()) {
            $content .= $this->getWsdlMessagesSection('Category', 'category');
        }

        $siblings = $this->getRelatedEntities(
            Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_SIBLING
        );
        foreach ($siblings as $sibling) {
            $siblingName     = $sibling->getNameSingular(true);
            $siblingNameUc   = ucfirst($siblingName);

            $content .= $this->getWsdlMessagesSection($siblingNameUc, $siblingName);
        }
        $content .= $tab;
        return $content;
    }
    /**
     * get foreign keys for sql (Ddl)
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getParentEntitiesFksDdl()
    {
        $padding    = $this->getPadding();
        $eol        = $this->getEol();
        $parents    = $this->getRelatedEntities(
            Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_CHILD
        );
        $content    = '';

        $module     = $this->getModule()->getLowerModuleName();
        $namespace  = $this->getNamespace(true);
        foreach ($parents as $parent) {
            $parentName = $parent->getNameSingular(true);
            $content .= $eol.$padding."->addIndex($"."this->getIdxName('".
                $namespace.'_'.$module.'/'.$parentName."', array('".$parentName."_id')), array('".
                $parentName."_id'))";
        }
        return $content;
    }

    /**
     * get selected menu path
     *
     * @access public
     * @param string $suffix
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getSelectedMenuPath($suffix = '')
    {
        $path = $this->getModule()->getMenuParent();
        if (!empty($path)) {
            $path .= '/';
        }
        $path .= $this->getModule()->getExtensionName(true).'/';
        $path .= $this->getNameSingular(true);

        return $path . $suffix;
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
        return $this->getTypeInstance()->getAttributesSetup();
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
        return $this->getTypeInstance()->getResourceModelParent();
    }

    /**
     * get parent class for the entity resource model
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getResourceCollectionModelParent()
    {
        return $this->getTypeInstance()->getResourceCollectionModelParent();
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
        return $this->getTypeInstance()->getResourceRelationsTables();
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
        return $this->getTypeInstance()->getResourceRelationsTablesDeclare();
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
        return $this->getTypeInstance()->getAdminIndexLayoutContent();
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
        return $this->getTypeInstance()->getEntityParentModel();
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
        return $this->getTypeInstance()->getEntityTableAlias();
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
        return $this->getTypeInstance()->getAdditionalPrepareCollection();
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
        return $this->getTypeInstance()->getEditLayoutLeft();
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
        return $this->getTypeInstance()->getEditLayoutAdditional();
    }

    /**
     * get the label for product attribute scope
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getProductAttributeScopeLabel()
    {
        return $this->_getScopeLabel($this->getProductAttributeScope());
    }

    /**
     * get the label for category attribute scope
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getCategoryAttributeScopeLabel()
    {
        return $this->_getScopeLabel($this->getCategoryAttributeScope());
    }

    /**
     * get scope label for install scripts
     *
     * @param $value
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _getScopeLabel($value)
    {
        $values = array(
            Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE =>
                'Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE',
            Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE =>
                'Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE',
            Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL =>
                'Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL'
        );
        if (!isset($values[$value])) {
            $value = Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL;
        }
        return $values[$value];
    }

    /**
     * check if the entity is used as an attribute
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getIsAttribute()
    {
        return $this->getProductAttribute() || $this->getCategoryAttribute();
    }

    /**
     * check if source model can be created
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getCanCreateSourceModel()
    {
        return $this->getIsAttribute() || $this->getIsParent();
    }

    /**
     * check if entity has children
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getIsParent()
    {
        $children = $this->getRelatedEntities(Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_PARENT);
        return count($children) > 0;
    }

    /**
     * get product attribute group
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getProductAttributeGroupLabel()
    {
        if ($this->getProductAttributeGroup()) {
            return "'group'             => '".$this->getProductAttributeGroup()."',".
                $this->getEol().$this->getPadding(2);
        }
        return '';
    }
    /**
     * get category attribute group
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getCategoryAttributeGroupLabel()
    {
        if ($this->getCategoryAttributeGroup()) {
            return "'group'             => '".$this->getCategoryAttributeGroup().
                "',".$this->getEol().$this->getPadding(2);
        }
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
        return $this->getTypeInstance()->getBeforeSaveParam();
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
        return $this->getTypeInstance()->getEntityAttributeSetId();
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
        return $this->getTypeInstance()->getFilterMethod();
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
        return $this->getTypeInstance()->getMultipleSelectConvert();
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
        if ($this->getIsTree()) {
            return true;
        }
        if ($this->getHasFile()) {
            return true;
        }
        if ($this->getCreateFrontend()) {
            return true;
        }
        return $this->getTypeInstance()->getCanCreateEntityHelper();
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
        return $this->getTypeInstance()->getToOptionAddition();
    }

    /**
     * check if entity should be included in the category menu
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getShowInCategoryMenu()
    {
        return $this->getListMenu() ==
            Ultimate_ModuleCreator_Model_Source_Entity_Menu::CATEGORY_MENU;
    }

    /**
     * get multiselect methods
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getMultiselectMethods()
    {
        $content = '';
        $padding = $this->getPadding();
        $tab     = $this->getPadding();
        $eol     = $this->getEol();
        foreach ($this->getAttributes() as $attribute) {
            $magicCode = $attribute->getMagicMethodCode();
            $code      = $attribute->getCode();
            if ($attribute->getTypeInstance() instanceof Ultimate_ModuleCreator_Model_Attribute_Type_Multiselect) {
                $content .= $eol.$padding.'/**'.$eol;
                $content .= $padding.'  * get '.$attribute->getLabel().$eol;
                $content .= $padding.'  *'.$eol;
                $content .= $padding.'  * @access public'.$eol;
                $content .= $padding.'  * @return array'.$eol;
                $content .= $padding.'  * '.$this->getModule()->getQwertyuiop().$eol;
                $content .= $padding.'  */'.$eol;
                $content .= $padding.'public function get'.$magicCode.'()'.$eol;
                $content .= $padding.'{'.$eol;
                $content .= $padding.$tab.'if (!$this->getData(\''.$code.'\')) {'.$eol;
                $content .= $padding.$tab.$tab.'return explode(\',\', $this->getData(\''.$code.'\'));'.$eol;
                $content .= $padding.$tab.'}'.$eol;
                $content .= $padding.$tab.'return $this->getData(\''.$code.'\');'.$eol;
                $content .= $padding.'}';
            }
        }
        return $content;
    }

    /**
     * get html for displaying the name
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getNameHtml()
    {
        $content = '';
        $lower   = $this->getNameSingular(true);
        $ucFirst = ucfirst($lower);
        $name    = $this->getNameAttributeMagicCode();
        if ($this->getCreateView()) {
            $content .= '\'<a href="\'.$'.$lower.'->get'.$ucFirst.'Url().\'">\'.$'.$lower.'->get'.$name.'().\'</a>\'';
        } else {
            $content .= '\'<a href="#">\'.$'.$lower.'->get'.$name.'().\'</a>\'';
        }
        return $content;
    }

    /**
     * check if the entity is not store related
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getNoStore()
    {
        return !$this->getStore();
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
        return $this->getTypeInstance()->getCommentFilterIndexPrefix();
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

        if ($this->getAllowComment()) {
            $padding  = $this->getPadding(3);
            $tab      = $this->getPadding();
            $module   = $this->getModule()->getLowerModuleName();
            $entity   = $this->getNameSingular(true);
            $eol      = $this->getEol();
            $title    = $this->getLabelSingular().' Comments';
            $ns       = $this->getNamespace(true);

            $content .= $eol;
            $content .= $padding.'<'.$module.'_'.$entity.'_comment translate="title" module="'.
                $ns.'_'.$module.'">'.$eol;
            $content .= $padding.$tab.'<title>'.$title.'</title>'.$eol;
            $content .= $padding.$tab.'<model>'.$ns.'_'.$module.'/'.$entity.'_comment_api</model>'.$eol;
            $content .= $padding.$tab.'<acl>'.$module.'/'.$entity.'/comment</acl>'.$eol;
            $content .= $padding.$tab.'<methods>'.$eol;
            $content .= $padding.$tab.$tab.'<list translate="title" module="'.$ns.'_'.$module.'">'.$eol;
            $content .= $padding.$tab.$tab.$tab.'<title>Retrieve '.$title.'</title>'.$eol;
            $content .= $padding.$tab.$tab.$tab.'<method>items</method>'.$eol;
            $content .= $padding.$tab.$tab.$tab.'<acl>'.$module.'/'.$entity.'_comment/list</acl>'.$eol;
            $content .= $padding.$tab.$tab.'</list>'.$eol;
            $content .= $padding.$tab.$tab.'<updateStatus translate="title" module="'.$ns.'_'.$module.'">'.$eol;
            $content .= $padding.$tab.$tab.$tab.'<title>Update '.$this->getLabelSingular().' Status</title>'.$eol;
            $content .= $padding.$tab.$tab.$tab.'<method>updateStatus</method>'.$eol;
            $content .= $padding.$tab.$tab.$tab.'<acl>'.$module.'/'.$entity.'_comment/updateStatus</acl>'.$eol;
            $content .= $padding.$tab.$tab.'</updateStatus>'.$eol;
            $content .= $padding.$tab.'</methods>'.$eol;
            $content .= $padding.$tab.'<faults module="'.$ns.'_'.$module.'">'.$eol;
            $content .= $padding.$tab.$tab.'<not_exists>'.$eol;
            $content .= $padding.$tab.$tab.$tab.'<code>101</code>'.$eol;
            $content .= $padding.$tab.$tab.$tab.'<message>Requested comment not found.</message>'.$eol;
            $content .= $padding.$tab.$tab.'</not_exists>'.$eol;
            $content .= $padding.$tab.'</faults>'.$eol;
            $content .= $padding.'</'.$module.'_'.$entity.'_comment>'.$eol;
        }
        $content .= $this->getTypeInstance()->getApiAdditionalSettings();
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
        if ($this->getAllowComment()) {
            $padding  = $this->getPadding(5);
            $tab      = $this->getPadding();
            $module   = $this->getModule()->getLowerModuleName();
            $entity   = $this->getNameSingular(true);
            $eol      = $this->getEol();
            $title    = $this->getLabelSingular().' Comments';
            $content .= $eol;
            $ns       = $this->getModule()->getNamespace(true);

            $content .= $padding.'<'.$entity.'_comment translate="title" module="'.$ns.'_'.$module.'">'.$eol;
            $content .= $padding.$tab.'<title>'.$title.'</title>'.$eol;
            $content .= $padding.$tab.'<sort_order>'.($this->getPosition() + 3).'</sort_order>'.$eol;
            $content .= $padding.$tab.'<list translate="title" module="'.$ns.'_'.$module.'">'.$eol;
            $content .= $padding.$tab.$tab.'<title>List</title>'.$eol;
            $content .= $padding.$tab.'</list>'.$eol;
            $content .= $padding.$tab.'<updateStatus translate="title" module="'.$ns.'_'.$module.'">'.$eol;
            $content .= $padding.$tab.$tab.'<title>Update Status</title>'.$eol;
            $content .= $padding.$tab.'</updateStatus>'.$eol;
            $content .= $padding.'</'.$entity.'_comment>'.$eol;
        }
        $content .= $this->getTypeInstance()->getSubEntitiesAcl();
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
        if ($this->getAllowComment()) {
            $padding  = $this->getPadding(3);
            $module   = $this->getModule()->getLowerModuleName();
            $entity   = $this->getNameSingular(true);
            $eol      = $this->getEol();
            $content .= $eol;
            $content .= $padding.'<'.$entity.'_comment>'.$module.'_'.$entity.'_comment</'.$entity.'_comment>';
        }
        $content .= $this->getTypeInstance()->getApiResourcesAlias();
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
        if ($this->getAllowComment()) {
            $padding  = $this->getPadding(4);
            $module   = $this->getModule()->getLowerModuleName();
            $entity   = strtolower($this->getNameSingular());
            $eol      = $this->getEol();
            $content .= $eol;
            $content .= $padding.'<'.$entity.'_comment>'.$module.ucfirst($entity).'Comment</'.$entity.'_comment>';
        }
        $content .= $this->getTypeInstance()->getApiResourcesAliasV2();
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
        return $this->getTypeInstance()->getDefaultApiAttributes();
    }

    /**
     * get the module namespace
     *
     * @access public
     * @param bool $lower
     * @return mixed|string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getNamespace($lower = false)
    {
        return $this->getModule()->getNamespace($lower);
    }

    /**
     * get entity name
     *
     * @access public
     * @param bool $lower
     * @return mixed|string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getNameSingular($lower = false)
    {
        $name = $this->getData('name_singular');
        if ($lower) {
            $name = strtolower($name);
        }
        return $name;
    }

    /**
     * get code that filters dates
     *
     * @access public
     * @param int $padding
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getFilterDates($padding = 4)
    {
        $dateAttributes = array();
        foreach ($this->getAttributes() as $attribute) {
            if ($attribute->getTypeInstance() instanceof
                Ultimate_ModuleCreator_Model_Attribute_Type_Timestamp) {
                $dateAttributes[] = $attribute->getCode();
            }
        }
        if (count($dateAttributes) == 0) {
            return '';
        }
        return $this->getEol().$this->getPadding($padding).
            '$data = $this->_filterDates($data, array(\''.implode("' ,'", $dateAttributes).'\'));';
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
        return $this->getTypeInstance()->getAllAttributesToCollection();
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
        return $this->getTypeInstance()->getLoadStoreId();
    }

    /**
     * get rest resource group
     *
     * @param $padding
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getRestResourceGroup($padding)
    {
        $content = '';
        $eol = $this->getEol();
        if ($this->getRest()) {
            $ns       = $this->getNamespace(true);
            $md       = $this->getModule()->getLowerModuleName();
            $entity   = $this->getNameSingular(true);
            $content .= $this->getPadding($padding).'<'.$ns.'_'.$md.'_'.$entity.
                ' translate="title" module="'.$ns.'_'.$md.'">'.$eol;
            $content .= $this->getPadding($padding + 1).'<title>'.$this->getLabelSingular().'</title>'.$eol;
            $content .= $this->getPadding($padding + 1).'<sort_order>'.$this->getPosition().'</sort_order>'.$eol;
            $content .= $this->getPadding($padding).'</'.$ns.'_'.$md.'_'.$entity.'>'.$eol;
        }
        return $content;
    }

    /**
     * get rest resource
     *
     * @param $padding
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getRestResource($padding)
    {
        $content = '';
        $eol = $this->getEol();
        if ($this->getRest()) {
            $ns       = $this->getNamespace(true);
            $md       = $this->getModule()->getLowerModuleName();
            $entity   = $this->getNameSingular(true);
            $content .= $this->getPadding($padding).'<'.$ns.'_'.$md.'_'.$entity.
                ' translate="title" module="'.$ns.'_'.$md.'">'.$eol;
            $content .= $this->getPadding($padding + 1).'<group>'.$ns.'_'.$md.'_'.$entity.'</group>'.$eol;
            $content .= $this->getPadding($padding + 1).'<model>'.$ns.'_'.$md.'/api2_'.$entity.'</model>'.$eol;
            $content .= $this->getPadding($padding + 1).'<working_model>'.$ns.'_'.$md.'/'.$entity.
                '</working_model>'.$eol;
            $content .= $this->getPadding($padding + 1).'<title>'.$this->getLabelSingular().'</title>'.$eol;
            $content .= $this->getPadding($padding + 1).'<sort_order>'.$this->getPosition().'</sort_order>'.$eol;
            $content .= $this->getPadding($padding + 1).'<privileges>'.$eol;
            $content .= $this->getPadding($padding + 2).'<admin>'.$eol;
            $content .= $this->getPadding($padding + 3).'<create>1</create>'.$eol;
            $content .= $this->getPadding($padding + 3).'<retrieve>1</retrieve>'.$eol;
            $content .= $this->getPadding($padding + 3).'<update>1</update>'.$eol;
            $content .= $this->getPadding($padding + 3).'<delete>1</delete>'.$eol;
            $content .= $this->getPadding($padding + 2).'</admin>'.$eol;
            $content .= $this->getPadding($padding + 2).'<customer>'.$eol;
            $content .= $this->getPadding($padding + 3).'<retrieve>1</retrieve>'.$eol;
            $content .= $this->getPadding($padding + 2).'</customer>'.$eol;
            $content .= $this->getPadding($padding + 2).'<guest>'.$eol;
            $content .= $this->getPadding($padding + 3).'<retrieve>1</retrieve>'.$eol;
            $content .= $this->getPadding($padding + 2).'</guest>'.$eol;
            $content .= $this->getPadding($padding + 1).'</privileges>'.$eol;
            $content .= $this->getPadding($padding + 1).'<attributes translate="'.
                $this->getRestAttributes(true, true).'" module="'.$ns.'_'.$md.'">'.$eol;
            foreach ($this->getRestAttributes(false, false) as $code=>$label) {
                $content .= $this->getPadding($padding + 2).'<'.$code.'>'.$label.'</'.$code.'>'.$eol;
            }
            $content .= $this->getPadding($padding + 1).'</attributes>'.$eol;
            $content .= $this->getPadding($padding + 1).'<routes>'.$eol;
            $content .= $this->getPadding($padding + 2).'<route_entity>'.$eol;
            $content .= $this->getPadding($padding + 3).'<route>/'.$md.'_'.$this->getNamePlural(true).
                '/:id</route>'.$eol;
            $content .= $this->getPadding($padding + 3).'<action_type>entity</action_type>'.$eol;
            $content .= $this->getPadding($padding + 2).'</route_entity>'.$eol;
            if ($this->getIsEav() || $this->getStore()) {
                $content .= $this->getPadding($padding + 2).'<route_entity_with_store>'.$eol;
                $content .= $this->getPadding($padding + 3).'<route>/'.$md.'_'.$this->getNamePlural(true).
                    '/:id/store/:store</route>'.$eol;
                $content .= $this->getPadding($padding + 3).'<action_type>entity</action_type>'.$eol;
                $content .= $this->getPadding($padding + 2).'</route_entity_with_store>'.$eol;
            }
            $content .= $this->getPadding($padding + 2).'<route_collection>'.$eol;
            $content .= $this->getPadding($padding + 3).'<route>/'.$md.'_'.$this->getNamePlural(true).'</route>'.$eol;
            $content .= $this->getPadding($padding + 3).'<action_type>collection</action_type>'.$eol;
            $content .= $this->getPadding($padding + 2).'</route_collection>'.$eol;
            if ($this->getIsEav() || $this->getStore()) {
                $content .= $this->getPadding($padding + 2).'<route_collection_with_store>'.$eol;
                $content .= $this->getPadding($padding + 3).'<route>/'.$md.'_'.$this->getNamePlural(true).
                    '/store/:store</route>'.$eol;
                $content .= $this->getPadding($padding + 3).'<action_type>collection</action_type>'.$eol;
                $content .= $this->getPadding($padding + 2).'</route_collection_with_store>'.$eol;
            }
            $content .= $this->getPadding($padding + 1).'</routes>'.$eol;
            $content .= $this->getPadding($padding + 1).'<versions>1</versions>'.$eol;
            $content .= $this->getPadding($padding).'</'.$ns.'_'.$md.'_'.$entity.'>'.$eol;
        }
        return $content;
    }

    /**
     * get rest attributes
     *
     * @param bool $codeOnly
     * @param bool $asString
     * @return array|string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getRestAttributes($codeOnly = false, $asString = false)
    {
        $attributes = array(
            'entity_id' => 'Id',
        );
        foreach ($this->getAttributes() as $attribute) {
            $attributes[$attribute->getCode()] = $attribute->getLabel();
        }
        foreach ($this->getSimulatedAttributes() as $attribute) {
            $attributes[$attribute->getCode()] = $attribute->getLabel();
        }
        if ($codeOnly) {
            if ($asString) {
                return implode(' ', array_keys($attributes));
            }
            return array_keys($attributes);
        }
        return $attributes;
    }

    /**
     * @access public
     *
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getRestCollectionCleanup()
    {
        return $this->getTypeInstance()->getRestCollectionCleanup();
    }

    /**
     * @access public
     *
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getRestCollectionStoreId()
    {
        return $this->getTypeInstance()->getRestCollectionStoreId();
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
        return $this->getTypeInstance()->getDefaultAttributeValues();
    }

    /**
     * check if entity has parent entities
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getHasParentRelation()
    {
        return count($this->getRelatedEntities(Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_CHILD)) > 0;
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
        return $this->getTypeInstance()->getToOptionArraySelect();
    }

    /**
     * get parent grid column static params
     *
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getParentStaticParams()
    {
        if ($this->getNotIsTree()) {
            return '';
        }
        $eol      = $this->getEol();
        $padding  = $this->getPadding(4);
        $tab      = $this->getPadding();
        $content  = $eol;
        $content .= $padding."'static' => array(".$eol;
        $content .= $padding.$tab."'clear' => 1".$eol;
        $content .= $padding."),";
        return $content;
    }
}
