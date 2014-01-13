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
 * @copyright      Copyright (c) 2013
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 * @author         Marius Strajeru <ultimate.module.creator@gmail.com>
 */
class Ultimate_ModuleCreator_Model_Entity_Type_Eav extends Ultimate_ModuleCreator_Model_Entity_Type_Abstract{
    /**
     * get collection attributes
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getCollectionAttributes(){
        $result = '';
        $eol = $this->getEol();
        $padding = $this->getPadding(3);
        foreach ($this->getEntity()->getAttributes() as $attribute){
            if ($attribute->getAdminGrid() && $attribute->getCode() != $this->getEntity()->getNameAttributeCode()){
                $result .= $eol;
                $result .= $padding;
                $result .= '->addAttributeToSelect(\''.$attribute->getCode().'\')';
            }
        }
        foreach ($this->getEntity()->getSimulatedAttributes('status') as $attribute){
            $result .= $eol;
            $result .= $padding;
            $result .= '->addAttributeToSelect(\''.$attribute->getCode().'\')';
        }
        if ($this->getEntity()->getUrlRewrite()){
            foreach ($this->getEntity()->getSimulatedAttributes('url_rewrite') as $attribute){
                $result .= $eol;
                $result .= $padding;
                $result .= '->addAttributeToSelect(\''.$attribute->getCode().'\')';
            }
        }
        return $result;
    }
    /**
     * get admin join
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAdminJoin(){
        $eol     = $this->getEol();
        $result  = $eol;
        $result .= $this->getPadding(2).'$adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;'.$eol;
        $result .= $this->getPadding(2).'$store = $this->_getStore();'.$eol;
        $result .= $this->getPadding(2).'$collection->joinAttribute(\''.$this->getEntity()->getNameAttributeCode().'\', \''.$this->getEntity()->getModule()->getLowerModuleName().'_'.$this->getEntity()->getNameSingular().'/'.$this->getEntity()->getNameAttributeCode().'\', \'entity_id\', null, \'inner\', $adminStore);'.$eol;
        $result .= $this->getPadding(2).'if ($store->getId()) {'.$eol;
        $result .= $this->getPadding(3).    '$collection->joinAttribute(\''.$this->getEntity()->getModule()->getLowerModuleName().'_'.$this->getEntity()->getNameSingular().'_'.$this->getEntity()->getNameAttributeCode().'\', \''.$this->getEntity()->getModule()->getLowerModuleName().'_'.$this->getEntity()->getNameSingular().'/'.$this->getEntity()->getNameAttributeCode().'\', \'entity_id\', null, \'inner\', $store->getId());'.$eol;
        $result .= $this->getPadding(2).'}'.$eol;
        return $result;
    }

    /**
     * prepare columns header
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getPrepareColumnsHeader(){
        return '$store = $this->_getStore();'.$this->getEol().$this->getPadding(2);
    }

    /**
     * get name attribute for grid
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getNameAttributeGridEav(){
        $eol     = $this->getEol();
        $result  = $eol;
        $result .= $this->getPadding(2).'if ($this->_getStore()->getId()){'.$eol;
        $result .= $this->getPadding(3).    '$this->addColumn(\''.$this->getEntity()->getModule()->getLowerModuleName().'_'.$this->getEntity()->getNameSingular().'_'.$this->getEntity()->getNameAttributeCode().'\', array('.$eol;
        $result .= $this->getPadding(4).        '\'header\'    => Mage::helper(\''.$this->getEntity()->getModule()->getLowerModuleName().'\')->__(\''.$this->getEntity()->getNameAttributeLabel().' in %s\', $this->_getStore()->getName()),'.$eol;
        $result .= $this->getPadding(4).        '\'align\'     => \'left\','.$eol;
        $result .= $this->getPadding(4).        '\'index\'     => \''.$this->getEntity()->getModule()->getLowerModuleName().'_'.$this->getEntity()->getNameSingular().'_'.$this->getEntity()->getNameAttributeCode().'\','.$eol;
        $result .= $this->getPadding(3).    '));'.$eol;
        $result .= $this->getPadding(2).'}'.$eol;
        return $result;
    }

    /**
     * eav always has image
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getHasImage(){
        return true;
    }
    /**
     * eav always has files
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getHasFile(){
        return true;
    }
    /**
     * eav always has submenu
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getHasSubmenu(){
        return true;
    }
    /**
     * get additional menu
     * @access public
     * @param $padding
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAdditionalMenu($padding) {
        $extension   = $this->getEntity()->getModule()->getExtensionName();
        $module      = $this->getEntity()->getModule()->getLowerModuleName();
        $entity      = strtolower($this->getEntity()->getNameSingular());
        $entityTitle = $this->getEntity()->getLabelSingular();
        $action      = $module.'_'.$entity;
        $eol         = $this->getEol();

        $text  = $this->getPadding($padding).'<'.$extension.'_'.$entity.'_attributes translate="title" module="'.$module.'">'.$eol;
        $text .= $this->getPadding($padding + 1).'<title>Manage '.$entityTitle.' Attributes</title>'.$eol;
        $text .= $this->getPadding($padding + 1).'<action>adminhtml/'.$action.'_attribute</action>'.$eol;
        $text .= $this->getPadding($padding + 1).'<sort_order>'.($this->getEntity()->getPosition() + 7).'</sort_order>'.$eol;
        $text .= $this->getPadding($padding).'</'.$extension.'_'.$entity.'_attributes>'.$eol;
        return $text;
    }

    /**
     * get additional menu acl
     * @access public
     * @param $padding
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAdditionalMenuAcl($padding) {
        $extension   = $this->getEntity()->getModule()->getExtensionName();
        $module      = strtolower($this->getEntity()->getModule()->getModuleName());
        $entity      = strtolower($this->getEntity()->getNameSingular());
        $entityTitle = $this->getEntity()->getLabelSingular();
        $action      = $module.'_'.$entity;
        $eol         = $this->getEol();

        $text  = $this->getPadding($padding).'<'.$extension.'_attributes translate="title" module="'.$module.'">'.$eol;
        $text .= $this->getPadding($padding + 1).'<title>Manage '.$entityTitle.' attributes</title>'.$eol;
        $text .= $this->getPadding($padding + 1).'<sort_order>'.($this->getEntity()->getPosition() + 7).'</sort_order>'.$eol;
        $text .= $this->getPadding($padding).'</'.$extension.'_attributes>'.$eol;
        return $text;
    }
    /**
     * allow comments by store
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAllowCommentByStore() {
        return $this->getEntity()->getAllowComment();
    }
    /**
     * get attributes content for setup
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAttributesSetup() {
        $content = '';
        $position = 0;
        foreach ($this->getEntity()->getAttributes() as $attribute){
            $content .= $attribute->getSetupContent();
            $position = $attribute->getPosition();
        }
        $position += 10;
        foreach ($this->getEntity()->getSimulatedAttributes(null, false, array('tree')) as $attribute) {
            $attribute->setPosition($position);
            $content .= $attribute->getSetupContent();
            $position += 10;
        }
        foreach ($this->getEntity()->getSimulatedAttributes('tree', false) as $attribute){
            $attribute->setForcedSetupType('static');
            $attribute->setForcedVisible(0);
            $content .= $attribute->getSetupContent();
            $position += 10;
        }
        return $content;
    }
    /**
     * get parent class for the entity resource model
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getResourceModelParent(){
        return 'Mage_Catalog_Model_Resource_Abstract';
    }
    /**
     * get parent class for the entity resource collection model
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getResourceCollectionModelParent(){
        return 'Mage_Catalog_Model_Resource_Collection_Abstract';
    }
    /**
     * get related entities relations table
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getResourceRelationsTables() {
        $padding = $this->getPadding(2);
        $content = '';
        $eol     = $this->getEol();
        $entity  = strtolower($this->getEntity()->getNameSingular());
        $module  = $this->getEntity()->getModule()->getLowerModuleName();
        if ($this->getEntity()->getLinkProduct()){
            $content .= $padding.'$'.'this->_'.$entity.'ProductTable = $'."this->getTable('".$module."/".$entity."_product');".$eol;
        }
        if ($this->getEntity()->getLinkCategory()){
            $content .= $padding.'$'.'this->_'.$entity.'CategoryTable = $'."this->getTable('".$module."/".$entity."_category');".$eol;
        }
        $related = $this->getEntity()->getRelatedEntities(Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_SIBLING);
        foreach ($related as $_entity){
            $_entityUc      = ucfirst($_entity->getNameSingular());
            $_entityLower   = strtolower($_entity->getNameSingular());
            $content .= $padding.'$'.'this->_'.$entity.$_entityUc.'Table = $'."this->getTable('".$module."/".$entity."_".$_entityLower."');".$eol;
        }
        return $content;
    }
    /**
     * get related entities relations table declaration
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getResourceRelationsTablesDeclare() {
        $padding = $this->getPadding();
        $content = '';
        $eol     = $this->getEol();
        $entity  = strtolower($this->getEntity()->getNameSingular());
        $module  = $this->getEntity()->getModule()->getLowerModuleName();
        if ($this->getEntity()->getLinkProduct()) {
            $content .= $padding.'protected $'.'_'.$entity.'ProductTable = null;'.$eol;
        }
        if ($this->getEntity()->getLinkCategory()) {
            $content .= $padding.'protected $'.'_'.$entity.'CategoryTable = null;'.$eol;
        }
        $related = $this->getEntity()->getRelatedEntities(Ultimate_ModuleCreator_Model_Relation::RELATION_TYPE_SIBLING);
        foreach ($related as $_entity) {
            $_entityUc      = ucfirst($_entity->getNameSingular());
            $_entityLower   = strtolower($_entity->getNameSingular());
            $content .= $padding.'protected $'.'_'.$entity.$_entityUc.'Table = null;'.$eol;
        }
        return $content;
    }
    /**
     * get admin layout content for index page
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAdminIndexLayoutContent() {
        $entity   = strtolower($this->getEntity()->getNameSingular());
        $module   = $this->getEntity()->getModule()->getLowerModuleName();
        $eol      = $this->getEol();
        $content  = $this->getPadding(3).'<block type="'.$module.'/adminhtml_'.$entity.'" name="'.$entity.'">'.$eol;
        $content .= $this->getPadding(4).'<block type="adminhtml/store_switcher" name="store_switcher" as="store_switcher">'.$eol;
        $content .= $this->getPadding(5).'<action method="setUseConfirm"><params>0</params></action>'.$eol;
        $content .= $this->getPadding(4).'</block>'.$eol;
        $content .= $this->getPadding(3).'</block>'.$eol;
        return $content;
    }
    /**
     * get the parent model class
     * @access public
     * @return mixed
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEntityParentModel() {
        return 'Mage_Catalog_Model_Abstract';
    }
    /**
     * get entity table alias
     * @access public
     * @return mixed
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEntityTableAlias() {
        return 'e';
    }
    /**
     * get additional prepare collection
     * @access public
     * @return mixed
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAdditionalPrepareCollection(){
        return "->addAttributeToSelect('".$this->getEntity()->getNameAttributeCode()."')";
    }
    /**
     * additional layout block for left section
     * @access public
     * @return mixed
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEditLayoutLeft() {
        return '<block type="adminhtml/store_switcher" name="store_switcher" before="-"></block>'.$this->getEol().$this->getPadding(3);
    }
    /**
     * additional layout block edit
     * @access public
     * @return mixed
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEditLayoutAdditional(){
        $content  = '';
        $eol      = $this->getEol();
        $content .= $eol.$this->getPadding(2).'<reference name="head">'.$eol;
        $content .= $this->getPadding(3).'<action method="setCanLoadTinyMce"><load>1</load></action>'.$eol;
        $content .= $this->getPadding(2).'</reference>'.$eol;
        $content .= $this->getPadding(2).'<reference name="js">'.$eol;
        $content .= $this->getPadding(3).'<block type="core/template" name="catalog.wysiwyg.js" template="catalog/wysiwyg/js.phtml"/>'.$eol;
        $content .= $this->getPadding(2).'</reference>';
        return $content;
    }
    /**
     * get param name for before save
     * @access public
     * @return mixed
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getBeforeSaveParam() {
        return 'Varien_Object';
    }
    /**
     * entity attribute set string
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEntityAttributeSetId() {
        $module = $this->getEntity()->getModule()->getLowerModuleName();
        $entity = strtolower($this->getEntity()->getNameSingular());
        return $this->getEol().$this->getPadding()."->setAttributeSetId(Mage::getModel('".$module.'/'.$entity."')->getDefaultAttributeSetId())";
    }
    /**
     * filter method name
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getFilterMethod() {
        return 'addAttributeToFilter';
    }
}
