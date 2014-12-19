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
 * module collection
 * 
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */ 
class Ultimate_ModuleCreator_Model_Module_Collection extends Varien_Data_Collection_Filesystem
{
    /**
     * Files and folders regexsp
     * @var string
     */
    protected $_allowedDirsMask     = '/^[a-z0-9\.\-]+$/i';
    protected $_allowedFilesMask    = '/^[a-z0-9\.\-\_]+\.(xml)$/i';
    protected $_disallowedFilesMask = '/^package\.xml$/i';

    /**
     * Base dir where packages are located
     *
     * @var string
     */
    protected $_baseDir = '';

    /**
     * Set base dir
     *
     * @access public
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function __construct()
    {
        $this->_baseDir = Mage::getBaseDir('var') . DS . 'modulecreator'.DS.'package';
        $io = new Varien_Io_File();
        $io->setAllowCreateFolders(true);
        $io->createDestinationDir($this->_baseDir);
        $this->addTargetDir($this->_baseDir);
    }

    /**
     * Row generator
     *
     * @access public
     * @param string $filename
     * @return array
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    protected function _generateRow($filename)
    {
        $row = parent::_generateRow($filename);
        $row['package']     = preg_replace('/\.(xml)$/', '', str_replace($this->_baseDir . DS, '', $filename));
        $row['filename_id'] = $row['package'];
        $row['safe_id']     = strtr(base64_encode($row['package']), '+/=', '-_,');
        $folder             = explode(DS, $row['package']);
        $row['folder']      = DS;
        array_pop($folder);
        if (!empty($folder)) {
            $row['folder'] = implode(DS, $folder) . DS;
        }
        return $row;
    }

    /**
     * Get all folders as options array
     *
     * @access public
     * @return array
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function collectFolders()
    {
        $collectFiles = $this->_collectFiles;
        $collectDirs = $this->_collectDirs;
        $this->setCollectFiles(false)->setCollectDirs(true);

        $this->_collectRecursive($this->_baseDir);
        $result = array(DS => DS);
        foreach ($this->_collectedDirs as $dir) {
            $dir = str_replace($this->_baseDir . DS, '', $dir) . DS;
            $result[$dir] = $dir;
        }
        $this->setCollectFiles($collectFiles)->setCollectDirs($collectDirs);
        return $result;
    }
}
