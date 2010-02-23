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
 * @package    Application_Model_Comments
 * @copyright  Copyright (c) 2009-2010 Chris Tankersley
 * @license    license/manuscript-LICENSE.txt     New BSD License
 */

/**
 * Class to interact with the User Accounts information
 *
 * @category   Manuscript
 * @package    Application_Model_Comments
 * @copyright  Copyright (c) 2009-2010 Chris Tankersley
 * @license    license/manuscript-LICENSE.txt     New BSD License
 */
class Application_Model_Comments
{
    /**
     * Backend Table
     * @var Zend_Db_Table_Abstract
     */
    protected $_dbTable;

    /**
     * Inserts a comment into the database
     *
     * @return int
     */
    public function add(array $commentData)
    {
        $commentData['dateAdded'] = date('Y-m-d h:i:s');

        return $this->getDbTable()->insert($commentData);
    }

    /**
     * Returns all the comments in the database
     *
     * @param string $where
     * @param string $order
     * @param string $count
     * @param string $offset
     * @return Zend_Db_Rowset
     */
    public function fetchAll($where = null, $order = null, $count = null, $offset = null)
    {
        $select = $this->getDbTable()->select()->from(array('c' => 'comments'))
                                               ->join(array('f' => 'files'), 'c.fileId = f.id', array('title'))
                                               ->join(array('d' => 'files_detail'), 'f.detailId = d.id', array('fsFilename'))
                                               ->setIntegrityCheck(false);

        if($order != null) {
            $select->order($order);
        }

        if($count != null) {
            $select->limit($count);
        }
        
        return $this->getDbTable()->fetchAll($select);
    }

    /**
     * Returns a specified comment
     *
     * This does not join all the elements together like fetchAll
     * 
     * @param int $id
     * @return Zend_Db_Row
     */
    public function find($id)
    {
        if( is_numeric($id) ) {
            $result = $this->getDbTable()->find($id);
            $file = $result->current();
        } else {
            throw new Exception('Comment ID must be numeric');
        }

        return $file;
    }

    /**
     * Returns the backend table
     *
     * @return Zend_Db_Table_Abstract
     */
    public function getDbTable()
    {
        if($this->_dbTable === null) {
            $this->setDbTable('Application_Model_DbTable_Comments');
        }

        return $this->_dbTable;
    }

    /**
     * Sets the backend table
     * @param mixed $table
     */
    public function setDbTable($table)
    {
        if(is_string($table)) {
            $this->_dbTable = new $table();
        } elseif($table instanceof Zend_Db_Table_Abstract) {
            $this->_dbTable = $table;
        } else {
            throw new Exception('Not a valid table gateway for Comments Model');
        }
    }
}