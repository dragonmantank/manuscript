<?php

/**
 * Manuscript
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file licenses/manuscript-LICENSE.txt.
 * If you did not receive a copy of the license please send an email
 * to chris@tankersleywebsolutions.com so we can send you a copy immediately.
 *
 * @category   Manuscript
 * @package    Application_Model_Tags
 * @copyright  Copyright (c) 2009-2010 Chris Tankersley
 * @license    license/manuscript-LICENSE.txt     New BSD License
 */

/**
 * Class to interact with the stored Mimetype information
 *
 * @category   Manuscript
 * @package    Application_Model_Tags
 * @copyright  Copyright (c) 2009-2010 Chris Tankersley
 * @license    license/manuscript-LICENSE.txt     New BSD License
 */
class Application_Model_Tags
{
    /**
     * Backend Tags database
     * @var Zend_Db_Table_Abstract
     */
    protected $_dbTable;

    /**
     * Cross-Reference Table
     * @var Zend_Db_Table_Abstract
     */
    protected $_xrefTable;

    /**
     * Adds a tag into the database
     * If the tag already exists in the DB, it will return that ID
     * @param string $tagName
     * @return int
     */
    public function add($tagName)
    {
        $tagName = trim($tagName);
        if(!empty($tagName)) {
            $select = $this->getDbTable()->select()->where('name LIKE ?', $tagName);
            $result = $this->getDbTable()->fetchAll($select);

            if(count($result)) {
                return $result[0]->id;
            } else {
                return $this->getDbTable()->insert(array('name' => $tagName));
            }
        }

        return null;
    }

    /**
     * Associates a file with a set of Tags
     * @param array $tags
     * @param int $id
     */
    public function associate(array $tags, $id)
    {
        $this->disassociate($id);
        foreach($tags as $tag) {
            $tagId  = $this->add($tag);
            if($tagId !== null) {
                $this->getXrefTable()->insert(array('tagId' => $tagId, 'fileId' => $id));
            }
        }
    }

    /**
     * Removes all the tags from a File
     * @param int $fileId
     * @return bool
     */
    public function disassociate($fileId)
    {
        return $this->getXrefTable()->delete('fileId = '.$fileId);
    }

    /**
     * Returns all the tags based on the passed parameters
     * @param string $where
     * @param string $order
     * @param string $count
     * @param string $offset
     * @return Zend_Db_Rowset
     */
    public function fetchAll($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->getDbTable()->fetchAll($where, $order, $count, $offset);
    }

    /**
     * Returns all the files based on a tag
     * @param int $tagId
     * @return Zend_Db_Rowset
     */
    public function fetchFiles($tagId)
    {
        $select = $this->getXrefTable()->select()->from(array('x' => 'files_tags_xref'))
                                       ->join(array('f' => 'files'), 'f.id = x.fileId', array('title', 'revision'))
                                       ->join(array('d' => 'files_detail'), 'f.detailId = d.id', array('mimetype', 'dateUploaded', 'fsFilename'))
                                       ->where('x.tagId = ?', $tagId)
                                       ->setIntegrityCheck(false);

        return $this->getXrefTable()->fetchAll($select);
    }

    /**
     * Returns all the tags on a file
     * @param int $fileId
     * @return Zend_Db_Rowset
     */
    public function fetchTags($fileId)
    {
        $select = $this->getXrefTable()->select()->from(array('x' => 'files_tags_xref'))
                                       ->join(array('t' => 'tags'), 't.id = x.tagId', array('name'))
                                       ->where('x.fileId = ?', $fileId)
                                       ->setIntegrityCheck(false);

        return $this->getXrefTable()->fetchAll($select);
    }

    /**
     * Returns the specified tag
     * Tags can be search by Name or ID
     * @param mixed $id
     * @return Zend_Db_Row
     */
    public function find($id)
    {
        if( is_numeric($id) ) {
            $result = $this->getDbTable()->find($id);
            $file = $result->current();
        } else {
            $select = $this->getDbTable()->select()->where('name LIKE ?', $id);
            $file = $this->getDbTable()->fetchRow($select);
        }

        return $file;
    }

    /**
     * Returns the Tag table
     * @return Zend_Db_Table_Abstract
     */
    public function getDbTable()
    {
        if($this->_dbTable === null) {
            $this->setDbTable('Application_Model_DbTable_Tags');
        }

        return $this->_dbTable;
    }

    /**
     * Sets the Tag table
     * @param mixed $table
     */
    public function setDbTable($table)
    {
        if(is_string($table)) {
            $this->_dbTable = new $table();
        } elseif($table instanceof Zend_Db_Table_Abstract) {
            $this->_dbTable = $table;
        } else {
            throw new Exception('Not a valid table gateway for Tags Model');
        }
    }

    /**
     * Returns the CRoss Reference Table
     * @return Zend_Db_Table_Abstract
     */
    public function getXrefTable()
    {
        if($this->_xrefTable === null) {
            $this->setXrefTable('Application_Model_DbTable_FilesTagsXref');
        }

        return $this->_xrefTable;
    }

    /**
     * Removes a tag from the database
     * This will also remove the tag from all files associated to it
     * @param int $tagId
     */
    public function remove($tagId)
    {
        $this->getDbTable()->delete('id = '.$tagId);
        $this->getXrefTable()->delete('tagId = '.$tagId);
    }

    /**
     * Renames a tag
     * @param int $tagId
     * @param string $name
     */
    public function rename($tagId, $name)
    {
        $where  = $this->getDbTable()->getAdapter()->quoteInto('id = ?', $tagId);
        $this->getDbTable()->update(array('name' => $name), $where);
    }

    /**
     * Sets the Cross Reference Table
     * @param mixed $table
     */
    public function setXrefTable($table)
    {
        if(is_string($table)) {
            $this->_xrefTable = new $table();
        } elseif($table instanceof Zend_Db_Table_Abstract) {
            $this->_xrefTable = $table;
        } else {
            throw new Exception('Not a valid table gateway for Files to Tags Xref Model');
        }
    }
}
