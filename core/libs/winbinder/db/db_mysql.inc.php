<?php

/**
* WINBINDER - The native Windows binding for PHP for PHP
*
* Copyright © Hypervisual - see LICENSE.TXT for details
* Authors: Rubem Pechansky and Hans Rebel
*
* Database wrapper functions for WinBinder (MySQL-specific)
* version 2b
*/

if(PHP_VERSION >= "5")
	if(!extension_loaded('mysql'))
		if(!@dl('php_mysql.dll')) {
			wb_message_box(null, "MySQL extension could not be loaded.", "Error", WBC_STOP);
			trigger_error("MySQL extension could not be loaded.\n", E_USER_ERROR);
		}

// -------------------------------------------------------------------- CONSTANTS
define("DB_MYSQL_WRAP", "db_v2b");
define("FETCH_BOTH", MYSQL_BOTH);
define("FETCH_NUM", MYSQL_NUM);
define("FETCH_ASSOC", MYSQL_ASSOC);
// ----------------------------------------------------------- DATABASE VERSION CHECK

/**
* raw_get_db_version()
* Returns the version of the database library.
*
* @return string
*/
function raw_get_db_version()
{
	return mysql_get_server_info();
}

if (DB_WRAPVERSION != DB_MYSQL_WRAP) {
  die(" db_common.inc.php has different version number than db_mysql.inc.php ");
}
// ----------------------------------------------------------- DATABASE FUNCTIONS
/**
* raw_db_open_database()
* Opens and connects an existing database.
*
* @param  $database
* @param string $server
* @param string $username
* @param string $password
* @return resource or FALSE
*/
function raw_db_open_database($database, $server = "", $username = "", $password = "")
{
  global $curr_db;

  $conn = mysql_connect($server, $username, $password);
  if (!$conn) {
    trigger_error(__FUNCTION__ . ": " . mysql_error());
    return false;
  } else {
    $curr_db = $database;
    if (!mysql_select_db($database)) {
      trigger_error(__FUNCTION__ . ": " . mysql_error());
      return false;
    }
  }
  return $conn;
}

/**
* raw_db_create_database()
* Creates a database if it does not exist.
*
* @param  $database
* @param string $server
* @param string $username
* @param string $password
* @return resource or FALSE
*/
function raw_db_create_database($database, $server = "", $username = "", $password = "")
{
  global $curr_db;

  $conn = mysql_connect($server, $username, $password);
  if (!$conn) {
    trigger_error(__FUNCTION__ . ": " . mysql_error());
    return false;
  } else {
	if (!mysql_query("CREATE DATABASE IF NOT EXISTS " . $database))
	  die(mysql_error());
    $curr_db = $database;
    if (!mysql_select_db($database)) {
      trigger_error(__FUNCTION__ . ": " . mysql_error());
      return false;
    }
  }
  return $conn;
}

/**
* raw_db_list_database_tables()
* Returns an array with the list of tables of the current database.
*
* @return array or FALSE
*/
function raw_db_list_database_tables()
{
  global $curr_db;

  $hresult = mysql_query("SHOW TABLES FROM $curr_db");
  if (!$hresult) {
    // no Tables in $database
    return false;
  } else {
    while ($row = mysql_fetch_array($hresult, MYSQL_NUM)) {
      $tables[] = $row[0];
    } // while
    return $tables;
  }
}

/**
* raw_db_close_database()
*
* @return bool
*/
function raw_db_close_database()
{
  return mysql_close();
}
// -------------------------------------------------------------- TABLE FUNCTIONS
/**
* raw_db_table_exists()
*
* @param  $tablename
* @return bool
*/
function raw_db_table_exists($tablename)
{
  global $g_current_db;

  $sql = "SELECT 1 FROM $tablename LIMIT 0";
  $res = mysql_query($sql);
  if ($res) {
    return true;
  } ;
  return false;
}

/**
* raw_db_rename_table()
*
* @param  $tablename
* @param  $newname
* @return bool
*/
function raw_db_rename_table($tablename, $newname)
{
  global $g_lasttable;

  if (!$tablename)
    $tablename = $g_lasttable;

  $g_lasttable = $newname;
  $res = mysql_query("RENAME TABLE $tablename TO $newname");
  return $res;
}

/**
* raw_db_list_table_fields_def()
* lists fieldnames or fieldattributes according type
*
* @param  $tablename
* @param boolean $type
* @return array or FALSE
*/
function raw_db_list_table_fields_def($tablename, $type = false)
{
  $result = mysql_query("SHOW COLUMNS FROM $tablename");
  if ($result === false) return false;
  $coltype = array();
  $colnames = array();
  if (mysql_num_rows($result) > 0) {
    while ($row = mysql_fetch_assoc($result)) {
      $colnames[] = $row['Field'];
      $coltype[] = $row['Type'];
    } // while
  }
  if (mysql_free_result($result) == false) return false;
  return ($type ? $coltype : $colnames);
}
// -------------------------------------------------------------- FIELD FUNCTIONS
/**
* raw_db_create_field()
*
* @param  $tablename
* @param  $field
* @param  $type
* @return bool
*/
function raw_db_create_field($tablename, $field, $type)
{
  global $g_lasttable;

  if (!$tablename)
    $tablename = $g_lasttable;
  $g_lasttable = $tablename;

  $res = mysql_query("ALTER TABLE $tablename ADD $field $type");
  return $res;
}

/**
* raw_db_delete_field()
*
* @param  $tablename
* @param  $field
* @return bool
*/
function raw_db_delete_field($tablename, $field)
{
  global $g_lasttable;

  if (!$tablename)
    $tablename = $g_lasttable;
  $g_lasttable = $tablename;

  $res = mysql_query("ALTER TABLE $tablename DROP $field");
  return $res;
}

/**
* raw_db_rename_field()
*
* @param  $tablename
* @param  $field
* @param  $newname
* @param  $type
* @return bool
*/
function raw_db_rename_field($tablename, $field, $newname, $type)
{
  global $g_lasttable;

  if (!$tablename)
    $tablename = $g_lasttable;
  $g_lasttable = $tablename;

  $res = mysql_query("ALTER TABLE $tablename CHANGE $field $newname $type");
  return $res;
}

/**
* raw_db_edit_field()
*
* @param  $tablename
* @param  $field
* @param  $type
* @return bool
*/
function raw_db_edit_field($tablename, $field, $type)
{
  global $g_lasttable;

  if (!$tablename)
    $tablename = $g_lasttable;
  $g_lasttable = $tablename;

  $res = mysql_query("ALTER TABLE $tablename MODIFY $field $type");
  return $res;
}
// ---------------------------------------------------------------- SQL FUNCTIONS
/**
* raw_db_query()
* queries the database with SQL
*
* @param string $query
* @return resource on success for SELECT,SHOW,DESCRIBE ans EXPLAIN
*            TRUE on success for UPDATE, DELETE, DROP etc
*            FALSE on errors
*/
function raw_db_query($query)
{
  $res = mysql_query($query);
  return $res;
}

/**
* raw_db_fetch_array()
* get the value of SQL-query, row by row
*
* @param  $result
* @param unknown $type
* @return array of row, FALSE if no more rows
*/
function raw_db_fetch_array($result, $type = FETCH_BOTH)
{
  return mysql_fetch_array($result, $type);
}

/**
* raw_db_free_result()
*
* @param  $result
* @return bool
*/
function raw_db_free_result($result)
{
  mysql_free_result($result);
}

/**
* raw_db_escape_string()
*
* @param  $str
* @return escaped string
*/
function raw_db_escape_string($str)
{
  return mysql_real_escape_string($str);
}
// ------------------------------------------------------------------ END OF FILE

?>
