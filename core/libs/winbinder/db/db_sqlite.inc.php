<?php

/**
* WINBINDER - The native Windows binding for PHP for PHP
*
* Copyright © Hypervisual - see LICENSE.TXT for details
* Authors: Rubem Pechansky and Hans Rebel
*
* Database wrapper functions for WinBinder (SQLite-specific)
* version 2b
*/

if(PHP_VERSION < "5")
	if(!extension_loaded('sqlite'))
		if(!@dl('php_sqlite.dll')) {
			wb_message_box(null, "SQLite extension could not be loaded.", "Error", WBC_STOP);
			trigger_error("SQLite extension could not be loaded.\n", E_USER_ERROR);
		}


// -------------------------------------------------------------------- CONSTANTS
define("FETCH_BOTH", SQLITE_BOTH);
define("FETCH_NUM", SQLITE_NUM);
define("FETCH_ASSOC", SQLITE_ASSOC);
// -------------------------------------------------------------------- CONSTANTS
define("DB_SQLITE_WRAP", "db_v2b");
// ----------------------------------------------------------- DATABASE VERSION CHECK

/**
* raw_get_db_version()
* Returns the version of the database library.
*
* @return string
*/
function raw_get_db_version()
{
	return sqlite_libversion();
}

if (DB_WRAPVERSION != DB_SQLITE_WRAP) {
  die(" db_common.inc.php has different version number than db_mysql.inc.php ");
}
// ----------------------------------------------------------- DATABASE FUNCTIONS
/**
* raw_db_open_database()
* Opens and connects a database. Create the database if it does not exist.
*
* @param  $database
* @param string $server
* @param string $username
* @param string $password
* @return resource or FALSE
*/
function raw_db_open_database($database, $path = "", $u = null, $p = null)
{
  global $g_current_db;

  if (!$path) {
    $path = pathinfo(__FILE__);
    $path = $path["dirname"] . "/";
  }

  if (!file_exists($database))

    $database = $path . "sqlite_" . $database . ".db";

  $g_current_db = sqlite_open($database, 0666, $sql_error);
  if (!$g_current_db) {
    trigger_error(__FUNCTION__ . $sql_error);
    return false;
  } else return $g_current_db;
}

/**
* raw_db_list_database_tables()
* Returns an array with the list of tables of the current database.
*
* @return array or FALSE
*/
function raw_db_list_database_tables()
{
  global $g_current_db;

  $tables = array();
  $sql = "SELECT name FROM sqlite_master WHERE (type = 'table')";
  $res = sqlite_query($g_current_db, $sql);
  if ($res) {
    while (sqlite_has_more($res)) {
      $tables[] = sqlite_fetch_single($res);
    }
  } else return false;
  return $tables;
}

/**
* raw_db_close_database()
*
* @return bool
*/
function raw_db_close_database()
{
  global $g_current_db;

  sqlite_close($g_current_db);
  return true;
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

  $sql = "SELECT name FROM sqlite_master WHERE (type = 'table' AND name ='$tablename')";
  $res = sqlite_query($g_current_db, $sql);
  $count = intval(sqlite_fetch_array($res));
  return $count > 0;
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
  return __alter_table($tablename, "rename $tablename $newname");
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
  $result = db_query("SELECT sql FROM sqlite_master WHERE (tbl_name = '" . $tablename . "');");
  if ($result === false) return false;

  $all = db_fetch_array($result);
  $origsql = trim(preg_replace("/[\s]+/", " ", str_replace(",", ", ", preg_replace("/[\(]/", "( ", $all[0], 1))));
  $origsql = substr($origsql, 0, strlen($origsql)-1);
  $oldcols = preg_split("/[,]+/", substr(trim($origsql), strpos(trim($origsql), '(') + 1), -1, PREG_SPLIT_NO_EMPTY);

  $colnames = array();
  $coltype = array();
  for($i = 0;$i < sizeof($oldcols);$i++) {
    $colparts = preg_split("/[\s]+/", $oldcols[$i], -1, PREG_SPLIT_NO_EMPTY);
    $colnames[] = $colparts[0];
    $coltype[] = implode(" ", array_slice($colparts, 1));
  }

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

  return __alter_table($tablename, "ADD $field $type");
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

  return __alter_table($tablename, "DROP $field");
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

  return __alter_table($tablename, "CHANGE $field $newname $type");
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

  return __alter_table($tablename, "CHANGE $field $field $type");
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
  global $g_current_db;

  return sqlite_query($g_current_db, $query);
}

/**
* raw_db_fetch_array()
* get the value of SQL-query, row by row
*
* @param  $result
* @param unknown $type
* @return array of row, FALSE if no more rows
*/
function raw_db_fetch_array($result, $type = SQLITE_BOTH)
{
  return sqlite_fetch_array($result, $type);
}

/**
* raw_db_free_result()
*
* @param  $result
* @return bool
*/
function raw_db_free_result($result)
{
  // Not required in SQLite
  return true;
}

/**
* raw_db_escape_string()
*
* @param  $str
* @return escaped string
*/
function raw_db_escape_string($str)
{
  return sqlite_escape_string($str);
}
// ------------------------------------------------------------ PRIVATE FUNCTIONS
/**
* __alter_table()
* This function implements a subset of commands from ALTER TABLE
* Adapted from http://code.jenseng.com/db/
*
* @param  $table
* @param  $alterdefs string for ALTER TABLE
* @return bool
*/
function __alter_table($table, $alterdefs)
{
  global $g_current_db;

  $sql = "SELECT sql,name,type FROM sqlite_master WHERE tbl_name = '" . $table . "' ORDER BY type DESC";
  $result = sqlite_query($g_current_db, $sql);

  if (($result === false) || (sqlite_num_rows($result) <= 0)) {
    trigger_error('no such table: ' . $table, E_USER_WARNING);
    return false;
  }
  // ------------------------------------- Build the queries
  $row = sqlite_fetch_array($result);
  $tmpname = 't' . time();
  $origsql = trim(preg_replace("/[\s]+/", " ", str_replace(",", ", ", preg_replace("/[\(]/", "( ", $row['sql'], 1))));
  $createtemptableSQL = 'CREATE TEMPORARY ' . substr(trim(preg_replace("'" . $table . "'", $tmpname, $origsql, 1)), 6);
  $origsql = substr($origsql, 0, strlen($origsql)-1); // chops the ) at end
  $createindexsql = array();
  $i = 0;
  $defs = preg_split("/[,]+/", $alterdefs, -1, PREG_SPLIT_NO_EMPTY);
  $prevword = $table;
  $oldcols = preg_split("/[,]+/", substr(trim($createtemptableSQL), strpos(trim($createtemptableSQL), '(') + 1), -1, PREG_SPLIT_NO_EMPTY);
  $oldcols = preg_split("/[,]+/", substr(trim($origsql), strpos(trim($origsql), '(') + 1), -1, PREG_SPLIT_NO_EMPTY);
  $newcols = array();

  for($i = 0;$i < sizeof($oldcols);$i++) {
    $colparts = preg_split("/[\s]+/", $oldcols[$i], -1, PREG_SPLIT_NO_EMPTY);
    $oldcols[$i] = $colparts[0];
    $newcols[$colparts[0]] = $colparts[0];
  }

  $newcolumns = '';
  $oldcolumns = '';
  reset($newcols);

  while (list($key, $val) = each($newcols)) {
    $newcolumns .= ($newcolumns?', ':'') . $val;
    $oldcolumns .= ($oldcolumns?', ':'') . $key;
  }

  $copytotempsql = 'INSERT INTO ' . $tmpname . '(' . $newcolumns . ') SELECT ' . $oldcolumns . ' FROM ' . $table;
  $dropoldsql = 'DROP TABLE ' . $table;
  $createtesttableSQL = $createtemptableSQL;

  $newname = "";

  foreach($defs as $def) {
    $defparts = preg_split("/[\s]+/", $def, -1, PREG_SPLIT_NO_EMPTY);
    $action = strtolower($defparts[0]);

    switch ($action) {
      case 'add':

        if (sizeof($defparts) <= 2) {
          /**
          * *  mySQL gives no such user_warning
          * trigger_error('near "' . $defparts[0] . ($defparts[1]?' ' . $defparts[1]:'') . '": SQLITE syntax error', E_USER_WARNING);
          *
          * //
          */
          return false;
        }
        $createtesttableSQL = substr($createtesttableSQL, 0, strlen($createtesttableSQL)-1) . ',';
        for($i = 1;$i < sizeof($defparts);$i++)
        $createtesttableSQL .= ' ' . $defparts[$i];
        $createtesttableSQL .= ')';
        break;

      case 'change':

        if (sizeof($defparts) <= 2) {
          trigger_error('near "' . $defparts[0] . ($defparts[1]?' ' . $defparts[1]:'') . ($defparts[2]?' ' . $defparts[2]:'') . '": SQLITE syntax error', E_USER_WARNING);
          return false;
        }
        if ($severpos = strpos($createtesttableSQL, ' ' . $defparts[1] . ' ')) {
          if ($newcols[$defparts[1]] != $defparts[1]) {
            trigger_error('unknown column "' . $defparts[1] . '" in "' . $table . '"', E_USER_WARNING);
            return false;
          }
          $newcols[$defparts[1]] = $defparts[2];
          $nextcommapos = strpos($createtesttableSQL, ',', $severpos);
          $insertval = '';
          for($i = 2;$i < sizeof($defparts);$i++)
          $insertval .= ' ' . $defparts[$i];
          if ($nextcommapos)
            $createtesttableSQL = substr($createtesttableSQL, 0, $severpos) . $insertval . substr($createtesttableSQL, $nextcommapos);
          else
            $createtesttableSQL = substr($createtesttableSQL, 0, $severpos - (strpos($createtesttableSQL, ',')?0:1)) . $insertval . ')';
        } else {
          trigger_error('unknown column "' . $defparts[1] . '" in "' . $table . '"', E_USER_WARNING);
          return false;
        }
        break;

      case 'drop';

        if (sizeof($defparts) < 2) {
          trigger_error('near "' . $defparts[0] . ($defparts[1]?' ' . $defparts[1]:'') . '": SQLITE syntax error', E_USER_WARNING);
          return false;
        }
        /**
        * if ($severpos = strpos($createtesttableSQL, ' ' . $defparts[1] . ' ')) {
        * could end with , or ) if no type!!!!
        *
        * //
        */
        if (($severpos = strpos($createtesttableSQL, ' ' . $defparts[1] . ' ')) || ($severpos = strpos($createtesttableSQL, ' ' . $defparts[1] . ',')) || ($severpos = strpos($createtesttableSQL, ' ' . $defparts[1] . ')'))) {
          $nextcommapos = strpos($createtesttableSQL, ',', $severpos);
          if ($nextcommapos)
            $createtesttableSQL = substr($createtesttableSQL, 0, $severpos) . substr($createtesttableSQL, $nextcommapos + 1);
          else
            $createtesttableSQL = substr($createtesttableSQL, 0, $severpos - (strpos($createtesttableSQL, ',')?0:1)) . ')';
          unset($newcols[$defparts[1]]);
          /* RUBEM */ $createtesttableSQL = str_replace(",)", ")", $createtesttableSQL);
        } else {
          trigger_error('unknown column "' . $defparts[1] . '" in "' . $table . '"', E_USER_WARNING);
          return false;
        }
        break;

      case 'rename'; // RUBEM
        if (sizeof($defparts) < 2) {
          trigger_error('near "' . $defparts[0] . ($defparts[1]?' ' . $defparts[1]:'') . '": SQLITE syntax error', E_USER_WARNING);
          return false;
        }
        $newname = $defparts[2];
        break;

      default:

        trigger_error('near "' . $prevword . '": SQLITE syntax error', E_USER_WARNING);
        return false;
    } // switch
    $prevword = $defparts[sizeof($defparts)-1];
  } // foreach
  // This block of code generates a test table simply to verify that the columns specifed are valid
  // in an sql statement. This ensures that no reserved words are used as columns, for example
  sqlite_query($g_current_db, $createtesttableSQL);
  $err = sqlite_last_error($g_current_db);
  if ($err) {
    trigger_error("Invalid SQLITE code block: " . sqlite_error_string($err) . "\n", E_USER_WARNING);
    return false;
  }
  $droptempsql = 'DROP TABLE ' . $tmpname;
  sqlite_query($g_current_db, $droptempsql);
  // End test block
  // Is it a Rename?
  if (strlen($newname) > 0) {
    // $table = preg_replace("/([a-z]_)[a-z_]*/i", "\\1" . $newname, $table);
    // what do want with the regex? the expression should be [a-z_]! hans
    // why not just
    $table = $newname;
  }
  $createnewtableSQL = 'CREATE ' . substr(trim(preg_replace("'" . $tmpname . "'", $table, $createtesttableSQL, 1)), 17);

  $newcolumns = '';
  $oldcolumns = '';
  reset($newcols);

  while (list($key, $val) = each($newcols)) {
    $newcolumns .= ($newcolumns?', ':'') . $val;
    $oldcolumns .= ($oldcolumns?', ':'') . $key;
  }
  $copytonewsql = 'INSERT INTO ' . $table . '(' . $newcolumns . ') SELECT ' . $oldcolumns . ' FROM ' . $tmpname;
  // ------------------------------------- Perform the actions
  if (sqlite_query($g_current_db, $createtemptableSQL) === false) return false; //create temp table
  if (sqlite_query($g_current_db, $copytotempsql) === false) return false; //copy to table
  if (sqlite_query($g_current_db, $dropoldsql) === false) return false; //drop old table
  if (sqlite_query($g_current_db, $createnewtableSQL) === false) return false; //recreate original table
  if (sqlite_query($g_current_db, $copytonewsql) === false) return false; //copy back to original table
  if (sqlite_query($g_current_db, $droptempsql) === false) return false; //drop temp table
  return true;
}
// ------------------------------------------------------------------ END OF FILE

?>
