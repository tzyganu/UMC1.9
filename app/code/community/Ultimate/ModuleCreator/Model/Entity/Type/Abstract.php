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
/**
 * abstract entity type
 *
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */
abstract class Ultimate_ModuleCreator_Model_Entity_Type_Abstract
    extends Ultimate_ModuleCreator_Model_Abstract {
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
     * @var  mixed
     */
    protected $_entity = null;
    /**
     * set the entity
     * @access public
     * @param Ultimate_ModuleCreator_Model_Entity $entity
     * @return Ultimate_ModuleCreator_Model_Entity_Type_Abstract
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function setEntity(Ultimate_ModuleCreator_Model_Entity $entity) {
        $this->_entity = $entity;
        return $this;
    }

    /**
     * get the entity object
     * @access public
     * @return Ultimate_ModuleCreator_Model_Entity|null
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEntity() {
        return $this->_entity;
    }

    /**
     * get collection attributes
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getCollectionAttributes() {
        return '';
    }
    /**
     * get admin join
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAdminJoin() {
        return '';
    }
    /**
     * prepare columns text
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getPrepareColumnsHeader() {
        return '';
    }
    /**
     * get name attribute grid eav
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getNameAttributeGridEav() {
        return '';
    }
    /**
     * check if entity has images
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getHasImage() {
        return $this->getEntity()->getData('has_image');
    }
    /**
     * check if entity has files
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getHasFile() {
        return $this->getEntity()->getData('has_file');
    }
    /**
     * check if submenu should exist
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getHasSubmenu() {
        return false;
    }
    /**
     * get Additional submenu
     * @access public
     * @param $padding
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAdditionalMenu($padding) {
        return '';
    }
    /**
     * get Additional menu acl
     * @access public
     * @param $padding
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAdditionalMenuAcl($padding) {
        return '';
    }
    /**
     * check if comments are allowed by store
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAllowCommentByStore() {
        return $this->getEntity()->getAllowComment() && $this->getEntity()->getStore();
    }
    /**
     * get attributes content for setup
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAttributesSetup(){
        return '';
    }
    /**
     * get parent class for the entity resource model
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getResourceModelParent(){
        return 'Mage_Core_Model_Resource_Db_Abstract';
    }
    /**
     * get parent class for the entity resource collection model
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getResourceCollectionModelParent(){
        return 'Mage_Core_Model_Resource_Db_Collection_Abstract';
    }

    /**
     * get related entities relations table
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getResourceRelationsTables(){
        return '';
    }
    /**
     * get related entities relations table declaration
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getResourceRelationsTablesDeclare(){
        return '';
    }
    /**
     * get admin layout content for index page
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAdminIndexLayoutContent(){
        $entity = strtolower($this->getEntity()->getNameSingular());
        $module = $this->getEntity()->getModule()->getLowerModuleName();
        return $this->getPadding(3).'<block type="'.$module.'/adminhtml_'.$entity.'" name="'.$entity.'" />'.$this->getEol();
    }
    /**
     * get the parent model class
     * @access public
     * @return mixed
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEntityParentModel(){
        return 'Mage_Core_Model_Abstract';
    }
    /**
     * get entity table alias
     * @access public
     * @return mixed
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEntityTableAlias() {
        return 'main_table';
    }
    /**
     * get additional prepare collection
     * @access public
     * @return mixed
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getAdditionalPrepareCollection(){
        return '';
    }
    /**
     * additional layout block for left section
     * @access public
     * @return mixed
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEditLayoutLeft() {
        return '';
    }
    /**
     * additional layout block edit
     * @access public
     * @return mixed
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEditLayoutAdditional() {
        return '';
    }
    /**
     * get param name for before save
     * @access public
     * @return mixed
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getBeforeSaveParam() {
        return 'Mage_Core_Model_Abstract';
    }
    /**
     * entity attribute set string
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEntityAttributeSetId() {
        return '';
    }
    /**
     * filter method name
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getFilterMethod() {
        return 'addFieldToFilter';
    }
    /**
     * convert multiple select fields to strings
     * @access public
     * @return mixed
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getMultipleSelectConvert(){
        $padding = $this->getPadding(2);
        $tab     = $this->getPadding();
        $eol     = $this->getEol();
        $content = '';
        foreach ($this->getEntity()->getAttributes() as $attribute){
            if ($attribute->getIsMultipleSelect()){
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
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getCanCreateEntityHelper(){
        return false;
    }
    /**
     * get additional code for toOptionArray()
     * @access public
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getToOptionAddition(){
        return '';
    }
}
