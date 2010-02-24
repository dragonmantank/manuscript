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
 * @package    Application_Model_Mimetypes
 * @copyright  Copyright (c) 2009-2010 Chris Tankersley
 * @license    license/manuscript-LICENSE.txt     New BSD License
 */

/**
 * Class to interact with the stored Mimetype information
 *
 * @category   Manuscript
 * @package    Application_Model_Mimetypes
 * @copyright  Copyright (c) 2009-2010 Chris Tankersley
 * @license    license/manuscript-LICENSE.txt     New BSD License
 */

class Application_Model_Mimetypes
{
    /**
     * Backend table for Mimetypes
     * @var Zend_Db_Table_Abstract
     */
    protected $_dbTable;

    /**
     * View for getting all undescribed mimetypes
     * @var Zend_Db_Table_Abstract
     */
    protected $_newMimetypesTable;

    /**
     * Adds a new Mimetype into the database
     * @param array $data
     * @return int
     */
    public function add($data)
    {
        $d['mimetype']      = $data['newMimetype'];
        $d['description']   = $data['description'];
        
        return $this->getDbTable()->insert($d);
    }

    /**
     * Returns the Description of a Mimetype
     * @param string $mimetype
     * @return string
     */
    public function fetchDescription($mimetype)
    {
        return $this->getDbTable()->fetchDescription($mimetype);
    }

    /**
     * Returns all the Undescribed mimetypes that have been entered by users
     * @return Zend_Db_Rowset
     */
    public function fetchNew()
    {
        $result = $this->getNewMimetypesTable()->fetchAll();
        $mimetypes  = array();
        foreach($result as $row) {
            $mimetypes[$row->newMimetype] = $row->newMimetype;
        }
        return $mimetypes;
    }

    /**
     * Returns the main Mimetype Table
     * @return Zend_Db_Table_Abstract
     */
    public function getDbTable()
    {
        if($this->_dbTable === null) {
            $this->setDbTable('Application_Model_DbTable_Mimetypes');
        }

        return $this->_dbTable;
    }

    /**
     * Sets the Mimetype Table object
     * @param mixed $table
     */
    public function setDbTable($table)
    {
        if(is_string($table)) {
            $this->_dbTable = new $table();
        } elseif($table instanceof Zend_Db_Table_Abstract) {
            $this->_dbTable = $table;
        } else {
            throw new Exception('Not a valid table gateway for Mimetypes Model');
        }
    }

    /**
     * Returns the New Mimetypes table
     * @return Zend_Db_Table_Abstract
     */
    public function getNewMimetypesTable()
    {
        if($this->_newMimetypesTable === null) {
            $this->setNewMimetypesTable('Application_Model_DbTable_NewMimetypes');
        }

        return $this->_newMimetypesTable;
    }

    /**
     * Sets the New Mimetype table object
     * @param mixed $table
     */
    public function setNewMimetypesTable($table)
    {
        if(is_string($table)) {
            $this->_newMimetypesTable = new $table();
        } elseif($table instanceof Zend_Db_Table_Abstract) {
            $this->_newMimetypesTable = $table;
        } else {
            throw new Exception('Not a valid table gateway for New Mimetypes Model');
        }
    }
}
