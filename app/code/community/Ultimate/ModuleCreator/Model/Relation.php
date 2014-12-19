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
class Ultimate_ModuleCreator_Model_Relation extends Ultimate_ModuleCreator_Model_Abstract
{
    /**
     * relation type constants
     */
    const RELATION_TYPE_NONE    = 'none';
    const RELATION_TYPE_CHILD   = 'child';
    const RELATION_TYPE_PARENT  = 'parent';
    const RELATION_TYPE_SIBLING = 'sibling';

    /**
     * @var Ultimate_ModuleCreator_Model_Entity
     */
    protected $_entity1;

    /**
     * @var Ultimate_ModuleCreator_Model_Entity
     */
    protected $_entity2;

    /**
     * relation type
     *
     * @var string
     */
    protected $_type;

    /**
     * set entities in relation
     *
     * @access public
     * @param Ultimate_ModuleCreator_Model_Entity $entity1
     * @param Ultimate_ModuleCreator_Model_Entity $entity2
     * @param int $type
     * @return Ultimate_ModuleCreator_Model_Relation
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function setEntities(
        Ultimate_ModuleCreator_Model_Entity $entity1,
        Ultimate_ModuleCreator_Model_Entity $entity2,
        $type
    ) {
        $this->_entity1 = $entity1;
        $this->_entity2 = $entity2;
        $this->_type    = $type;
        switch($type) {
            case self::RELATION_TYPE_NONE:
                break;
            case self::RELATION_TYPE_CHILD:
                $this->_entity1->addRelatedEntity($type, $this->_entity2);
                $this->_entity2->addRelatedEntity(self::RELATION_TYPE_PARENT, $this->_entity1);
                break;
            case self::RELATION_TYPE_PARENT:
                $this->_entity1->addRelatedEntity($type, $this->_entity2);
                $this->_entity2->addRelatedEntity(self::RELATION_TYPE_CHILD, $this->_entity1);
                break;
            case self::RELATION_TYPE_SIBLING:
                $this->_entity1->addRelatedEntity($type, $this->_entity2);
                $this->_entity2->addRelatedEntity(self::RELATION_TYPE_SIBLING, $this->_entity1);
                break;
            default:
                break;
        }
        return $this;
    }

    /**
     * get the relation type
     *
     * @access public
     * @return int
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * get relation entities
     *
     * @access public
     * @return Ultimate_ModuleCreator_Model_Entity[]
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEntities()
    {
        return array($this->_entity1, $this->_entity2);
    }

    /**
     * relation to xml
     *
     * @access protected
     * @param array $arrAttributes
     * @param string $rootName
     * @param bool $addOpenTag
     * @param bool $addCdata
     * @return string
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function toXml(array $arrAttributes = array(), $rootName = 'relation', $addOpenTag=false, $addCdata=false)
    {
        $xml = '';
        $eol = $this->getEol();
        if ($rootName) {
            $xml .= '<'.$rootName.'>'.$eol;
        }
        $entities = $this->getEntities();
        $xml .= '<'.$entities[0]->getNameSingular().'_'.$entities[1]->getNameSingular().'>';
        $xml .= $this->getType();
        $xml .= '</'.$entities[0]->getNameSingular().'_'.$entities[1]->getNameSingular().'>'.$eol;
        if ($rootName) {
            $xml .= '</'.$rootName.'>'.$eol;
        }
        return $xml;
    }

    /**
     * check if siblings can be listed in the entity view page
     *
     * @access public
     * @param int $index
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getShowFrontendRelationSiblings($index)
    {
        if ($this->getType() != self::RELATION_TYPE_SIBLING) {
            return false;
        }
        $index = !!$index;
        $entities = $this->getEntities();
        $e = $entities[$index];
        return $e->getCreateView();
    }

    /**
     * check if children can be listed in the parent view page
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getShowFrontendRelationChildren()
    {
        if ($this->getType() == self::RELATION_TYPE_CHILD) {
            $index = 1;
        } elseif ($this->getType() == self::RELATION_TYPE_PARENT) {
            $index = 0;
        } else {
            return false;
        }
        $entities = $this->getEntities();
        $e = $entities[$index];
        return $e->getCreateView();
    }

    /**
     * check if a relations has tree entities
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getHasTree()
    {
        return $this->_entity1->getIsTree() || $this->_entity2->getIsTree();
    }

    /**
     * check if a relations does not have tree entities
     *
     * @access public
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getNotHasTree()
    {
        return !$this->getHasTree();
    }

    /**
     * check if one of the entities behaves as tree
     *
     * @access public
     * @param int $index
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEntityIsTree($index)
    {
        $entities = $this->getEntities();
        return $entities[$index]->getIsTree();
    }

    /**
     * check if one of the entities behaves as tree
     *
     * @access public
     * @param int $index
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getSiblingIsTree($index)
    {
        return $this->getEntityIsTree(1 - $index);
    }

    /**
     * check if one of the entities does not behave as tree
     *
     * @access public
     * @param int $index
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getSiblingIsNotTree($index)
    {
        return !$this->getSiblingIsTree($index);
    }

    /**
     * check if entity has API
     *
     * @access public
     * @param int $index
     * @return bool
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function getEntityHasApi($index)
    {
        $entities = $this->getEntities();
        return $entities[$index]->getApi();
    }
}
