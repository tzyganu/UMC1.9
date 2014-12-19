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
 * @copyright      Copyright (c) 2012
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */ 
/**
 * Zip writer
 * 
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */   
class Ultimate_ModuleCreator_Model_Writer extends Mage_Connect_Package_Writer
{
    /**
     * prefix for path
     *
     * @var string
     */
    protected $_pathPrefix = '';

    /**
     * set path prefix
     *
     * @param $pathPrefix
     * @return $this
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function setPathPrefix($pathPrefix)
    {
        $this->_pathPrefix = $pathPrefix;
        return $this;
    }

    /**
     * build the package
     *
     * @access public
     * @return Ultimate_ModuleCreator_Model_Writer
     * @see Mage_Connect_Package_Writer::composePackage()
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function composePackage()
    {
        @mkdir(self::PATH_TO_TEMPORARY_DIRECTORY, 0777, true);
        $root = self::PATH_TO_TEMPORARY_DIRECTORY . basename($this->_namePackage);
        @mkdir($root, 0777, true);
        foreach ($this->_files as $file) {
            if (is_dir($file) || is_file($file)) {
                $fileName = basename($file);
                $filePath = dirname($file);
                if (substr($filePath, 0, strlen($this->_pathPrefix)) == $this->_pathPrefix) {
                    $filePath = substr($filePath, strlen($this->_pathPrefix));
                }
                @mkdir($root . DS . $filePath, 0777, true);
                if (is_file($file)) {
                    copy($file, $root . DS . $filePath . DS . $fileName);
                } else {
                    @mkdir($root . DS . $filePath . $fileName, 0777);
                }
            }
        }
        $this->_temporaryPackageDir = $root;
        return $this;
    }

    /**
     * set the package name
     *
     * @access public
     * @param string $name
     * @return Ultimate_ModuleCreator_Model_Writer
     * @author Marius Strajeru <ultimate.module.creator@gmail.com>
     */
    public function setNamePackage($name)
    {
        $this->_namePackage = $name;
        return $this;
    }
}
