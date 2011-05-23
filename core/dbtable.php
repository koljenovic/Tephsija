<?php

/* 
    WARNING: this is a really lame and old dbtable written ages ago, it sucks
    and has to be redone using PDO ASAP, use on your own responsibility. 
    You are probably better off using Doctrine or something similar for
    anything serious. Consider yourself warned. :)

    VER 0.3 Changelog: adapted for tephsija and name changed to Dbtable 
    Constructor adapted to work with a config file

    VER 0.2 Changelog: read() function improved, added field selection
 
    $bookstore = new Core_Dbtable;
    $knjiga = array(
    'title' => 'Cudesni zivot letecih medvjedica',
    'author' => 'Busta Rhimes',
    'publisher' => 'Connectum',
    'publish_year' => 1990,
    'book_price' => 4.01
    );

    $bookstore->create($knjiga);
    $bookstore->update($knjiga, 1); 
*/

class Core_Dbtable
{

    private $_dbh = null;
    private $_config = null;
    // SETTINGS
    private $_host = null;
    private $_user = null;
    private $_pass = null;
    private $_db = null;
    private $_table = null;

    public function __CONSTRUCT($table, $db = 'default')
    {
        $this->_config = new Config_Db;
        if(isset($this->_config)) {
            if(($default = $this->_config->$db) !== null) {
                $this->_host = $default['host'];
                $this->_user = $default['user'];
                $this->_pass = $default['pass'];
                $this->_db = $default['db'];
                $this->_table = $table;
                if(!$this->_dbh = mysql_connect($this->_host, $this->_user, $this->_pass)) {
                    Core_Factory::make('Core_Error')->fatal('SQL - Cannot connect: ' . mysql_error());
                }
                if(!mysql_select_db($this->_db, $this->_dbh)) {
                    Core_Factory::make('Core_Error')->fatal('SQL - Cannot connect: ' . mysql_error());
                }
            } else {
                Core_Factory::make('Core_Error')->fatal('DBTable - no config file for db present: ' . $db);
            }
        }
    }

	// CRUD
    public function create($object)
    {
        $query = 'INSERT INTO ' . $this->_table;
        $schema = '(';
        $data = '(';
        $object_length = count($object);
        $iter = 0;
        foreach ($object as $key => $element) {
            $iter++;
            $schema .= $key . ($iter != $object_length ? ', ' : '');
            $data .= ( is_string($element) ? '\'' . $element . '\'' : $element ) . ($iter != $object_length ? ', ' : '');
        }
        $schema .= ') VALUES ';
        $data .= ')';
        $query .= $schema . $data;
        if(!mysql_query($query, $this->_dbh)) {
            Core_Factory::make('Core_Error')->fatal('Error: ' . mysql_error());
        } else {
            return mysql_insert_id($this->_dbh);
        }
    }

    public function read($what = '*', $id = null, $how = null)
    {
        $return_array = array();
        $query = 'SELECT ' . $what . ' FROM ' . $this->_table . ' WHERE 1' . ($id ? ' AND id=' . $id : '') . ($how ? ' AND ' . $how : '');
        if(!$result = mysql_query($query, $this->_dbh)) {
            Core_Factory::make('Core_Error')->fatal('Error: ' . mysql_error());
        } else {
            while ($row = mysql_fetch_assoc($result)) {
                array_push($return_array, $row);
            }
        }
        return $return_array;
    }

    public function update($object, $id, $how = null)
    {
        $query = 'UPDATE ' . $this->_table . ' SET ';
        $object_length = count($object);
        $iter = 0;
        foreach ($object as $key => $element) {
            $iter++;
            $query .= $key . '=' . (is_string($element) ? '\'' . $element . '\'' : $element ) . ($iter != $object_length ? ', ' : '');
        }
        $query .= ' WHERE id=' . $id . ($how ? ' OR ' . $how : '');
        if (!mysql_query($query, $this->_dbh)) {
            Core_Factory::make('Core_Error')->fatal('Error: ' . mysql_error());
        }
    }

    public function remove($id, $how = null)
    {
        $query = 'DELETE FROM ' . $this->_table . ' WHERE id=' . $id . ($how ? ' OR ' . $how : '');
        if(!mysql_query($query, $this->_dbh)) {
            Core_Factory::make('Core_Error')->fatal('Error: ' . mysql_error());
        }
    }

    public function readCustom($query)
    {
        $return_array = array();
        if(!$result = mysql_query($query, $this->_dbh)) {
            Core_Factory::make('Core_Error')->fatal('Error: ' . mysql_error());
        } else {
            while ($row = mysql_fetch_assoc($result)) {
                array_push($return_array, $row);
            }
        }
        return $return_array;
    }
}