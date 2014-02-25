<?php

/**
* WINBINDER - The native Windows binding for PHP for PHP
*
* Copyright © Hypervisual - see LICENSE.TXT for details
* Authors: Rubem Pechansky and Hans Rebel
*
* Database wrapper functions for WinBinder
* version 2b tested with SQLite and mySQL 7.mar
*/
// ------------------------------------------------------------ DATABASE-SPECIFIC
// You may define APPPREFIX and DB_DATABASE in the application
// APPPREFIX can be used to separate applications within one database
// allowed chars are A_Z,a-z,_  ( e.g.: "myAPP_" )
// for direct SQL use raw_db_query, keep in mind to handle APPPREFIX in your SQL
// for tablenames and possible prefixes to fieldnames in returned records
define("DB_WRAPVERSION", "db_v2b");

if (!defined("APPPREFIX")) define("APPPREFIX", "");
if (!defined("DB_DATABASE")) define("DB_DATABASE", "SQLite");

$_mainpath = pathinfo(__FILE__);
$_mainpath = $_mainpath["dirname"] . "/";

include $_mainpath . "db_" . strtolower(DB_DATABASE) . ".inc.php";

/**
* db_get_info()
* Returns database and wrapper version information
*
* @param string $what
* @return string
*/
function db_get_info($info = "")
{
  switch (strtolower($info)) {
    case "version":
      return DB_DATABASE . " " . raw_get_db_version() . " with Database Wrapper " . DB_WRAPVERSION;

    case "dbtype":
      return DB_DATABASE;

    case "dbversion":
      return raw_get_db_version();

    case "wrapversion":
      return DB_WRAPVERSION;
  }
}
// -----------------------wrapper for APPPREFIX----- DATABASE FUNCTIONS
/**
* db_open_database()
* Opens and connects an existing database.
*
* @param  $database
* @param string $server
* @param string $username
* @param string $password
* @return resource or "FALSE"
*/
function db_open_database($database, $server = "", $username = "", $password = "")
{
  return raw_db_open_database($database, $server, $username , $password);
}

/**
* db_create_database()
* Creates a database if it does not exist
*
* @param  $database
* @param string $server
* @param string $username
* @param string $password
* @return resource or "FALSE"
*/
function db_create_database($database, $server = "", $username = "", $password = "")
{
  return raw_db_create_database($database, $server, $username , $password);
}

/**
* db_list_database_tables()
* Returns an array with the list of tables of the current database
*
* @return result or "FALSE"
*/
function db_list_database_tables()
{
  $tables = raw_db_list_database_tables();
  if (!$tables) {
    return false;
  }
  $tmp_tabs = "";
  $prefixlen = strlen(trim(APPPREFIX));
  if ($prefixlen > 0) {
    foreach($tables as $table) {
      if (!(stristr(substr($table, 0, $prefixlen), APPPREFIX) === false)) {
        $tmp_tabs[] = substr($table, strlen(APPPREFIX));
      }
    }
    if ($tmp_tabs == "") {
      return false;
    }
    return $tmp_tabs;
  } else return $tables;
}
/**
* db_close_database()
*
* @return bool "TRUE" or "FALSE"
*/
function db_close_database()
{
  return raw_db_close_database();
}
// -------------------------------------------------------------- TABLE FUNCTIONS
/**
* db_table_exists()
*
* @param  $tablename of an opened database
* @return bool "TRUE" if table $tablename exists in the current database
*/
function db_table_exists($tablename)
{
  global $g_lasttable;

  if (!$tablename)
    $tablename = $g_lasttable;
  $g_lasttable = $tablename;
  return raw_db_table_exists(APPPREFIX . $tablename);
}

/**
* db_create_table()
*
* @param  $tablename
* @param  $fieldnames ( beside "id" )
* @param  $fieldattrib
* @param string $idfield ( set to "id" )
* @param array $valarray ( $valarray[0] = 1.record, $valarray[1] = 2.record, ... )
* @return bool "TRUE" or "FALSE" if Table already exists, could not create Table, could not create Records
*/
function db_create_table($tablename, $fieldnames, $fieldattrib, $idfield = "id", $valarray = null)
{
  global $g_lasttable;

  if ($tablename == null || $tablename == "")
    $tablename = $g_lasttable;
  $g_lasttable = $tablename;

  if (db_table_exists($tablename))
    return false;

  if (is_string($fieldnames))
    $fieldnames = preg_split("/[\r\n,]/", $fieldnames);
  if (is_string($fieldattrib))
    $fieldattrib = preg_split("/[\r\n,]/", $fieldattrib);
  $attribs = count($fieldattrib);
  if (count($fieldnames) != $attribs) {
    trigger_error(__FUNCTION__ . ": both arrays must be same length.");
    return false;
  }
  $sql = "CREATE TABLE " . APPPREFIX . "$tablename (";
  $sql .= "$idfield int(11) NOT NULL PRIMARY KEY ";
  if ($attribs != 0) {
    $sql .= ", ";

    for($i = 0; $i < $attribs; $i++)
    $sql .= $fieldnames[$i] . " " . $fieldattrib[$i] . ($i < $attribs - 1 ? ", " : "");
  }
  $sql .= ")";
  // Send the sql command
  $result = raw_db_query($sql);
  if (!$result) {
    trigger_error(__FUNCTION__ . ": could not create table $tablename.");
    return false;
  }

  if ($valarray)
    foreach($valarray as $values) {
    $result = db_create_record($tablename, $fieldnames, $values, $idfield);
    if ($result === false) {
      return false;
    }
  }
  return $result;
}

/**
* db_delete_table()
*
* @param  $tablename
* @return bool "TRUE" or "FALSE"
*/
function db_delete_table($tablename)
{
  global $g_lasttable;

  if (!$tablename)
    $tablename = $g_lasttable;
  $g_lasttable = $tablename;

  if ($tablename == null || $tablename == "")
    return false;
  if (db_table_exists($tablename))
    $result = raw_db_query("DROP table " . APPPREFIX . $tablename);
  return $result;
}

/**
* db_rename_table()
*
* @param  $tablename
* @param  $newname
* @return bool "TRUE" or "FALSE"
*/
function db_rename_table($tablename, $newname)
{
  return raw_db_rename_table(APPPREFIX . $tablename, APPPREFIX . $newname);
}
/**
* db_list_table_fields()
*
* @param  $tablename
* @return array with the names of the fields of table $tablename or FALSE
*/
function db_list_table_fields($tablename, $type = false)
{
  global $g_lasttable;

  if (!$tablename)
    $tablename = $g_lasttable;
  $g_lasttable = $tablename;

  $result = raw_db_list_table_fields_def(APPPREFIX . $tablename, $type);
  return $result;
}
// -------------------------------------------------------------- FIELD FUNCTIONS
/**
* db_create_field()
*
* @param  $tablename
* @param  $field
* @param  $type
* @return bool "TRUE" or "FALSE"
*/
function db_create_field($tablename, $field, $type)
{
  return raw_db_create_field(APPPREFIX . $tablename, $field, $type);
}

/**
* db_delete_field()
*
* @param  $tablename
* @param  $field
* @return bool "TRUE" or "FALSE"
*/
function db_delete_field($tablename, $field)
{
  return raw_db_delete_field(APPPREFIX . $tablename, $field);
}

/**
* db_rename_field()
*
* @param  $tablename
* @param  $field
* @param  $newname
* @param  $type
* @return bool "TRUE" or "FALSE"
*/
function db_rename_field($tablename, $field, $newname, $type)
{
  return raw_db_rename_field(APPPREFIX . $tablename, $field, $newname, $type);
}

/**
* db_edit_field()
* edit field attribute
*
* @param  $tablename
* @param  $field
* @param  $type
* @return bool "TRUE" or "FALSE"
*/
function db_edit_field($tablename, $field, $type)
{
  return raw_db_edit_field(APPPREFIX . $tablename, $field, $type);
}
// ------------------------------------------------------------- RECORD FUNCTIONS
/**
* db_create_record()
*
* Insert a new record in table $tablename.
*
* @param  $tablename Table name. If NULL uses the table used in last function call.
* @param unknown $fieldnames Array or CSV string with field names, one per line.
* @param unknown $fieldvalues Array or CSV string with field values, one per line.
* @param string $idfield
* @return id of the affected record, FALSE if not succeded
*/
function db_create_record($tablename, $fieldnames = null, $fieldvalues = null, $idfield = "id")
{
  global $g_lasttable;

  if (!$tablename)
    $tablename = $g_lasttable;
  $g_lasttable = $tablename;

    if (!$fieldnames) {
      $fieldnames = db_list_table_fields($tablename);
      array_shift($fieldnames);
    }
    if (!$fieldvalues) {
      $fieldvalues = array_fill(0, count($fieldnames), 0);

    }
  // Get next available index
  $sql = "SELECT max($idfield) FROM " . APPPREFIX . $tablename;
  $result = raw_db_query($sql);
  if ($result === false) {
    return false;
  }
  $newid = (db_fetch_array($result, FETCH_NUM)) ;
  $newid = $newid[0] + 1;
  // Build the two arrays
  $names = is_string($fieldnames) ? preg_split("/[\r\n]/", $fieldnames) : $fieldnames;
  $values = is_string($fieldvalues) ? preg_split("/[\r\n]/", $fieldvalues) : $fieldvalues;
  if (count($names) != count($values)) {
    trigger_error(__FUNCTION__ . ": both arrays must be same length.\n");
    return false;
  }
  // Build the SQL query
  $nfields = count($names);
  $fieldnames = $names;
  $fieldvalues = $values;
  for($i = 0, $names = ""; $i < $nfields; $i++)
  $names .= $fieldnames[$i] . ($i < $nfields - 1 ? ", " : "");
  for($i = 0, $values = ""; $i < $nfields; $i++)
  $values .= "'" . db_escape_string($fieldvalues[$i]) . "'" . ($i < $nfields - 1 ? ", " : "");

  $sql = "INSERT INTO " . APPPREFIX . $tablename . " ($idfield, $names) VALUES ($newid, $values)";

  $result = raw_db_query($sql);
  if (!$result) {
    trigger_error(__FUNCTION__ . ": could not create new record in table $tablename.");
    return false;
  }
  return $newid;
}

/**
* db_delete_records()
*
* Delete record from table $tablename.
*
* @param  $tablename
* @param  $idarray the id or id array
* @return bool "TRUE" or "FALSE"
*/
function db_delete_records($tablename, $idarray, $idfield = "id")
{
  global $g_lasttable;

  if ($idarray == null || $idarray <= 0)
    return false;
  if (!is_array($idarray))
    $idarray = array($idarray);

  if (!$tablename)
    $tablename = $g_lasttable;
  $g_lasttable = $tablename;

  foreach($idarray as $item) {
    // Send the SQL command
    $sql = "DELETE FROM " . APPPREFIX . $tablename . " WHERE $idfield = " . $item;
    $result = raw_db_query($sql);
    if (!$result) {
      trigger_error(__FUNCTION__ . ": could not delete record $id in table $tablename.");
      return false;
    }
  }
  return true;
}

/**
* db_edit_record()
*
* Edits a record from table $tablename. If $id is null, zero or < 0, inserts a new record.
*
* @param  $tablename If NULL uses the table used in last function call.
* @param integer $id
* @param unknown $fieldnames Array or CSV string with field names, one per line. If NULL, affects all fields.
* @param unknown $fieldvalues Array or CSV string with field values, one per line.
* @param string $idfield
* @return id of the affected record or FALSE on error
*/
function db_edit_record($tablename, $id = 0, $fieldnames = null, $fieldvalues = null, $idfield = "id")
{
  global $g_lasttable;

  if ($id == null || $id <= 0) { // Create a new record
    return db_create_record($tablename, $fieldnames, $fieldvalues, $idfield);
  } else { // Edit existing record
    if (!$tablename)
      $tablename = $g_lasttable;
    $g_lasttable = $tablename;
    // Build the two arrays
    if (!$fieldnames) {
      $fieldnames = db_list_table_fields($tablename);
      array_shift($fieldnames);
    }
    if (!$fieldvalues) {
      $fieldvalues = array_fill(0, count($fieldnames), 0);

    }

    $names = is_string($fieldnames) ? preg_split("/[\r\n]/", $fieldnames) : $fieldnames;
    $values = is_string($fieldvalues) ? preg_split("/[\r\n]/", $fieldvalues) : $fieldvalues;

    if (count($names) != count($values)) {
      trigger_error(__FUNCTION__ . ": both arrays must be same length.\n");
      return false;
    }
    // Build the SQL query
    $nfields = count($names);
    for($i = 0, $str = ""; $i < $nfields; $i++) {
      $str .= $names[$i] . "='" . db_escape_string($values[$i]) . "'" .
      ($i < $nfields - 1 ? ", " : "");
    }

    $sql = "UPDATE " . APPPREFIX . "$tablename SET $str WHERE $idfield=$id";
    // Send the SQL command
    $result = raw_db_query($sql);
    if (!$result) {
      trigger_error(__FUNCTION__ . ": could not edit record $id in table $tablename.");
      return false;
    }
    return $id;
  }
}

/**
* db_swap_records()
*
* Swaps values from two records, including the id field or not according to $xchangeid.
*
* @param  $tablename
* @param  $id1
* @param  $id2
* @param string $idfield
* @param boolean $xchangeid
* @return bool
*/
function db_swap_records($tablename, $id1, $id2, $idfield = "id", $xchangeid = true)
{
  global $g_lasttable;
  // Table name
  if (!$tablename)
    $tablename = $g_lasttable;
  $g_lasttable = $tablename;
  $table = APPPREFIX . "$tablename";
  // Build SQL strings
  $result = raw_db_query("SELECT * FROM $table WHERE $idfield = $id1");
  if (!$result) {
    trigger_error(__FUNCTION__ . ": could not read record $id1 in table $tablename.");
    return false;
  }
  $a = db_fetch_array($result, FETCH_ASSOC);
  $fieldvalues1 = array_values($a);
  $fieldnames1 = array_keys($a);
  array_shift($fieldvalues1);
  array_shift($fieldnames1);

  $result = raw_db_query("SELECT * FROM $table WHERE $idfield = $id2");
  if (!$result) {
    trigger_error(__FUNCTION__ . ": could not read record $id2 in table $tablename.");
    return false;
  }
  $a = db_fetch_array($result, FETCH_ASSOC);
  $fieldvalues2 = array_values($a);
  $fieldnames2 = array_keys($a);
  array_shift($fieldvalues2);
  array_shift($fieldnames2);
  // Exchange values
  if (db_edit_record($tablename, $id1, $fieldnames2, $fieldvalues2, $idfield) === false) return false;
  if (db_edit_record($tablename, $id2, $fieldnames1, $fieldvalues1, $idfield) === false) return false;
  // Exchange id's
  if ($xchangeid) {
    $unique = db_get_next_free_id($tablename);
    if (db_edit_record($tablename, $id1, array($idfield), array($unique), $idfield) === false) return false;
    if (db_edit_record($tablename, $id2, array($idfield), array($id1), $idfield) === false) return false;
    if (db_edit_record($tablename, $unique, array($idfield), array($id2), $idfield) === false) return false;
  }
  return true;
}

/**
* db_get_data()
*
* Reads data from table $tablename.
*
* $tablename		Table name. If NULL uses the table used in last function call.
* $id				Identifier(s). May be an array or a CSV string
* $col			Column(s) or field(s). May be an array or a CSV string
* $where			Additional WHERE clause
* $result_type	May be FETCH_ASSOC, FETCH_BOTH or FETCH_NUM
* $idfield		Name of id field
* $orderby		Additional ORDER BY clause
*
* $id		$col		returns
* --------------------------------------------------------------------
*
* int		null		array with the whole record $id
* int		str			the value of column $col from record $id
* int		str[]		array with column values in array $col of record $id
* int[]	null		array of arrays with values from all columns of the $id registers
* int[]	str			array with the values of column $col from the $id registers
* int[]	str[]		2-D array with the values of columns $col from the $id registers
* null	null		array of arrays with the whole table
* null	str			array with values of the $col column from the whole table
* null	str[]		array of arrays with the values of the columns $col from all table
*
* @param  $tablename
* @param unknown $id
* @param unknown $col
* @param string $where
* @param unknown $result_type
* @param string $idfield
* @param string $orderby
* @return result or FALSE
*/
function db_get_data($tablename, $id = null, $col = null, $where = "", $result_type = FETCH_NUM, $idfield = "id", $orderby = "")
{
  global $g_lasttable;

  if (!$tablename)
    $tablename = $g_lasttable;
  $g_lasttable = $tablename;

  if (is_array($col))
    $col = implode(",", $col);
  if ($col === null || $col === "")
    $col = "*";
  // Build the WHERE clause
  if ($id !== null) {
    if (is_string($id) && strstr($id, ",")) {
      $id = explode(",", $id);
    }
    if (is_array($id)) {
      $idcond = "";
      for($i = 0; $i < count($id); $i++)
      $idcond .= "$idfield = '{$id[$i]}'" . ($i < count($id) - 1 ? " OR " : "");
    } else
      $idcond = "$idfield = '$id'";

    $condition = $where ? " WHERE ($where) AND ($idcond)" : " WHERE ($idcond)";
  } else
    $condition = $where ? " WHERE ($where)" : "";

  $orderby = $orderby ? " ORDER BY $orderby" : "";
  // Do the query
  $sql = "SELECT $col FROM " . APPPREFIX . $tablename . $condition . $orderby;

  $result = raw_db_query($sql);
  if (!$result)
    return false;
  // Loop to build the return array
  $array = array();
  while ($row = db_fetch_array($result, $result_type)) {
    if (count($row) == 1)
      $row = array_shift($row);
    $array[] = $row;
  }
  if (db_free_result($result) === false) return false;
  // Return the result
  if (!is_array($array))
    return $array;

  switch (count($array)) {
    case 0:
      return false;

    case 1:

      $test = $array; // Copy array
      $elem = array_shift($test); // 1st element of array...
      if (is_null($elem)) // ...is it null?
        return false; // Yes: return null
      if (is_scalar($elem)) // ...is it a scalar?
        return $elem; // Yes: return the element alone
      else
        return $array; // No: return the whole array
    default:
      return $array;
  }
}

/**
* db_get_index()
*
* Returns the index of the record identified by $id
*
* @param  $tablename
* @param  $id
* @param string $idfield
* @return index or FALSE
*/

function db_get_index($tablename, $id, $idfield = "id")
{
  $data = db_get_data($tablename, null, $idfield);
  return array_search($id, $data);
}

/**
* db_get_id()
*
* Returns the id of the record indexed by $index
*
* @param  $tablename
* @param  $index
* @param string $idfield
* @return id or FALSE
*/

function db_get_id($tablename, $index, $idfield = "id")
{
  global $g_lasttable;

  if (!is_scalar($index)) {
    trigger_error(__FUNCTION__ . ": index must be an integer");
    return false;
  } else
    $index = (int)$index;

  if (!$tablename)
    $tablename = $g_lasttable;
  $g_lasttable = $tablename;

  // Do the query
  $sql = "SELECT $idfield FROM " . APPPREFIX . $tablename . " LIMIT 1 OFFSET $index";

  $result = raw_db_query($sql);
  if (!$result)
    return false;

  $ret = db_fetch_array($result, FETCH_NUM);

  if (db_free_result($result) === false)
  	return false;

  return $ret[0];
}

/**
* db_get_next_free_id()
*
* Returns the next available id in table $tablename.
*
* @param  $tablename
* @param string $idfield
* @return id or FALSE
*/
function db_get_next_free_id($tablename, $idfield = "id")
{
  global $g_current_db;
  global $g_lasttable;

  if (!$tablename)
    $tablename = $g_lasttable;
  $g_lasttable = $tablename;

  $sql = "SELECT max($idfield) FROM " . APPPREFIX . $tablename;
  $result = raw_db_query($sql);
  if (!$result) {
    return false;
  }
  $maxid = (db_fetch_array($result, FETCH_NUM)) ;

  return $maxid[0] + 1;
}
// ---------------------------------------------------------------- SQL FUNCTIONS
/**
* depricated
* exists only for compatibility to previous version
* is the same as raw_db_query
* does not handle APPPREFIX
*/

function db_query($query)
{
  return raw_db_query($query);
}

/**
* db_fetch_array()
*
* @param  $result
* @param  $type
* @return array
*/
function db_fetch_array($result, $type = FETCH_NUM)
{
  /**
  * array mysql_fetch_array ( resource result [, int result_type])
  * int type  MYSQL_ASSOC, MYSQL_NUM ( == fetch_row), and MYSQL_BOTH
  */

  return raw_db_fetch_array($result, $type);
}

/**
* db_free_result()
*
* @param  $result
* @return bool "TRUE" or "FALSE"
*/
function db_free_result($result)
{
  return raw_db_free_result($result);
}

/**
* db_escape_string()
*
* @param  $str
* @return string escaped
*/
function db_escape_string($str)
{
  /**
  * string mysql_real_escape_string ( string unescaped_string [, resource link_identifier])
  */

  return raw_db_escape_string($str);
}
// ------------------------------------------------------------------ END OF FILE

?>
