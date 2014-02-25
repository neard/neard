<?php

/*******************************************************************************

 WINBINDER - The native Windows binding for PHP for PHP

 Copyright � Hypervisual - see LICENSE.TXT for details
 Author: Rubem Pechansky (http://winbinder.org/contact.php)

 Windows functions

*******************************************************************************/

// so this file will not be necessary in the future

//-------------------------------------------------------------------- CONSTANTS

// Windows constants

define("BM_SETCHECK",			241);
define("LVM_FIRST",				0x1000);
define("LVM_DELETEALLITEMS",	(LVM_FIRST+9));
define("LVM_GETITEMCOUNT",		(LVM_FIRST+4));
define("LVM_GETITEMSTATE",		(LVM_FIRST+44));
define("LVM_GETSELECTEDCOUNT",	(LVM_FIRST+50));
define("LVIS_SELECTED",			2);
define("TCM_GETCURSEL",			4875);
define("CB_FINDSTRINGEXACT",	344);
define("CB_SETCURSEL",			334);
define("LB_FINDSTRINGEXACT",	418);
define("LB_SETCURSEL",			390);
define("TCM_SETCURSEL",			4876);
define("WM_SETTEXT",			12);

//------------------------------------------------------------- WINDOW FUNCTIONS

/*

Creates a window control, menu, toolbar, status bar or accelerator.

*/

function wb_create_control($parent, $class, $caption="", $xpos=0, $ypos=0, $width=0, $height=0, $id=null, $style=0, $lparam=null, $ntab=0)
{
	switch($class) {

		case Accel:
			return wbtemp_set_accel_table($parent, $caption);

		case ToolBar:
			return wbtemp_create_toolbar($parent, $caption, $width, $height, $lparam);

		case Menu:
			return wbtemp_create_menu($parent, $caption);

		case HyperLink:
			return wbtemp_create_control($parent, $class, $caption, $xpos, $ypos, $width, $height, $id, $style,
			  is_null($lparam) ? NOCOLOR : $lparam, $ntab);

		case ComboBox:
		case ListBox:
		case ListView:
			$ctrl = wbtemp_create_control($parent, $class, $caption, $xpos, $ypos, $width, $height, $id, $style, $lparam, $ntab);
			if(is_array($caption))
				wb_set_text($ctrl, $caption[0]);
			return $ctrl;

		case TreeView:
			$ctrl = wbtemp_create_control($parent, $class, $caption, $xpos, $ypos, $width, $height, $id, $style, $lparam, $ntab);
			if(is_array($caption))
				wb_set_text($ctrl, $caption[0]);
			return $ctrl;

		case Gauge:
		case Slider:
		case ScrollBar:
			$ctrl = wbtemp_create_control($parent, $class, $caption, $xpos, $ypos, $width, $height, $id, $style, $lparam, $ntab);
			if($lparam)
				wb_set_value($ctrl, $lparam);
			return $ctrl;

		default:
			return wbtemp_create_control($parent, $class, $caption, $xpos, $ypos, $width, $height, $id, $style, $lparam, $ntab);
	}
}

/*

Sets the value of a control or control item

*/

function wb_set_value($ctrl, $value, $item = null)
{
	if(!$ctrl)
		return null;

	$class = wb_get_class($ctrl);
	switch($class) {

		case ListView:		// Array with items to be checked

			if($value === null)
				break;
			elseif(is_string($value) && strstr($value, ","))
				$values = explode(",", $value);
			elseif(!is_array($value))
				$values = array($value);
			else
				$values = $value;
			foreach($values as $index)
				wbtemp_set_listview_item_checked($ctrl, $index, 1);
			break;

		case TreeView:		// Array with items to be checked

			if($item === null)
				$item = wb_get_selected($ctrl);
			return wbtemp_set_treeview_item_value($ctrl, $item, $value);

		default:

			if($value !== null) {
				return wbtemp_set_value($ctrl, $value, $item);
			}
	}
}


/*

Gets the text from a control, a control item, or a control sub-item.

*/

function wb_get_text($ctrl, $item=null, $subitem=null)
{
	if(!$ctrl)
		return null;

	if(wb_get_class($ctrl) == ListView) {

		if($item !== null) {		// Valid item

			$line = wbtemp_get_listview_text($ctrl, $item);
			if($subitem === null)
				return $line;
			else
				return $line[$subitem];

		} else {					// NULL item

			$sel = wb_get_selected($ctrl);
			if($sel === null) {		// Returns the entire table
				$items = array();
				for($i = 0;;$i++) {
					$item = wbtemp_get_listview_text($ctrl, $i);
					$all = '';
					foreach($item as $col)
						$all .= $col;
					if($all == '')
						break;
					$items[] = $item;
				}
				return $items ? $items : null;
			} else {
				$items = array();
				foreach($sel as $row)
					$items[] = wbtemp_get_listview_text($ctrl, $row);
				return $items ? $items : null;
			}
		}

	} elseif(wb_get_class($ctrl) == TreeView) {

		if($item) {
			return wbtemp_get_treeview_item_text($ctrl, $item);
		} else {
			$sel = wb_get_selected($ctrl);
			if($sel === null)
				return null;
			else {
				return wbtemp_get_text($ctrl);
			}
		}

	} elseif(wb_get_class($ctrl) == ComboBox) {

		return wbtemp_get_text($ctrl, $item === null ? -1 : $item);

	} elseif(wb_get_class($ctrl) == ListBox) {

		return wbtemp_get_text($ctrl, $item === null ? -1 : $item);

	} else {

		return wbtemp_get_text($ctrl, $item);

	}
}

/*

Sets the text of a control.
In a ListView, it creates columns: each element of the array text is a column.
In a tab control, it renames the tabs.
Sets the text of a control item.

*/

function wb_set_text($ctrl, $text, $item=null, $subitem=null)
{
	if(!$ctrl)
		return null;

	switch(wb_get_class($ctrl)) {

		case ListView:

			if($item !== null) {

				if(!is_array($text) && $subitem !== null) {

					// Set text of a ListView cell according to $item and $subitem

					wbtemp_set_listview_item_text($ctrl, $item, $subitem, $text);

				} else {

					// Set text of several ListView cells, ignoring $subitem

					for($sub = 0; $sub < count($text); $sub++) {
						if($text) {
							if(($text[$sub] !== null)) {
								wbtemp_set_listview_item_text($ctrl, $item, $sub, (string)$text[$sub]);
							}
						} else {
							wbtemp_set_listview_item_text($ctrl, $item, $sub, "");
						}
					}
				}

			} else {

				if(!is_array($text))
					$text = explode(",", $text);

				wb_delete_items($ctrl, null);

				if(!$item) {
					wbtemp_clear_listview_columns($ctrl);

					// Create column headers
					// In the loop below, passing -1 as the 'width' argument of wbtemp_create_listview_column()
					// makes it calculate the column width automatically

					for($i = 0; $i < count($text); $i++) {
						if(is_array($text[$i]))
							wbtemp_create_listview_column($ctrl, $i,
							  (string)$text[$i][0],
							  isset($text[$i][1]) ? (int)$text[$i][1] : -1,
							  isset($text[$i][2]) ? (int)$text[$i][2] : WBC_LEFT
							  );
						else
							wbtemp_create_listview_column($ctrl, $i,
							  (string)$text[$i], -1, 0);
					}
				}
			}
			break;

		case ListBox:

			if(!$text) {
				wb_delete_items($ctrl);
			} elseif(is_string($text)) {
				if(strchr($text, "\r") || strchr($text, "\n")) {
					$text = preg_split("/[\r\n,]/", $text);
					wb_delete_items($ctrl);
					foreach($text as $str)
						wbtemp_create_item($ctrl, (string)$str);
				} else {
					$index = wb_send_message($ctrl, LB_FINDSTRINGEXACT, -1, wb_get_address($text));
					wb_send_message($ctrl, LB_SETCURSEL, $index, 0);
				}
			} elseif(is_array($text)) {
				wb_delete_items($ctrl);
				foreach($text as $str)
					wbtemp_create_item($ctrl, (string)$str);
			}
			return;

		case ComboBox:

			if(!$text)
				wb_delete_items($ctrl);
			elseif(is_string($text)) {
				if(strchr($text, "\r") || strchr($text, "\n")) {
					$text = preg_split("/[\r\n,]/", $text);
					wb_delete_items($ctrl);
					foreach($text as $str)
						wbtemp_create_item($ctrl, (string)$str);
				} else {
					$index = wb_send_message($ctrl, CB_FINDSTRINGEXACT, -1, wb_get_address($text));
					wb_send_message($ctrl, CB_SETCURSEL, $index, 0);
					if($index == -1)
						wb_send_message($ctrl, WM_SETTEXT, 0, wb_get_address($text));
				}
			} elseif(is_array($text)) {
				wb_delete_items($ctrl);
				foreach($text as $str)
					wbtemp_create_item($ctrl, (string)$str);
			}
			return;

		case TreeView:

			if($item)
				return wbtemp_set_treeview_item_text($ctrl, $item, $text);
			else
				return wb_create_items($ctrl, $text, true);

		default:
			// The (string) cast below works well but is a temporary fix, must be
			// removed when wbtemp_set_text() accepts numeric types correctly
			if(is_array($text))
				return wbtemp_set_text($ctrl, $text, $item);
			else
				return wbtemp_set_text($ctrl, (string)$text, $item);
	}
}

/*

Selects one or more items. Compare with wb_set_value() which checks items instead.

*/

function wb_set_selected($ctrl, $selitems, $selected=TRUE)
{
	switch(wb_get_class($ctrl)) {

		case ComboBox:
			wb_send_message($ctrl, CB_SETCURSEL, (int)$selitems, 0);
			break;

		case ListBox:
			wb_send_message($ctrl, LB_SETCURSEL, (int)$selitems, 0);
			break;

		case ListView:

			if(is_null($selitems)) {
				return wbtemp_select_all_listview_items($ctrl, false);
			} elseif(is_array($selitems)) {
				foreach($selitems as $item)
					wbtemp_select_listview_item($ctrl, $item, $selected);
				return TRUE;
			} else
				return wbtemp_select_listview_item($ctrl, $selitems, $selected);
			break;

		case Menu:
			return wbtemp_set_menu_item_checked($ctrl, $selitems, $selected);

		case TabControl:
			wbtemp_select_tab($ctrl, (int)$selitems);
			break;

		case TreeView:
			wbtemp_set_treeview_item_selected($ctrl, $selitems);
			break;

		default:
			return false;
	}
	return true;
}

/*

Creates one or more items in a control.

*/

function wb_create_items($ctrl, $items, $clear=false, $param=null)
{
	switch(wb_get_class($ctrl)) {

		case ListView:

			if($clear)
				wb_send_message($ctrl, LVM_DELETEALLITEMS, 0, 0);

			$last = -1;

			// For each row

			for($i = 0; $i < count($items); $i++) {
				if(!is_scalar($items[$i]))
					$last = wbtemp_create_listview_item(
						$ctrl, -1, -1, (string)$items[$i][0]);
				else
					$last = wbtemp_create_listview_item(
						$ctrl, -1, -1, (string)$items[$i]);
				wbtemp_set_listview_item_text($ctrl, -1, 0, (string)$items[$i][0]);

				// For each column except the first

				for($sub = 0; $sub < count($items[$i]) - 1; $sub++) {
					if($param) {
						$result = call_user_func($param, 	// Callback function
							$items[$i][$sub + 1],			// Item value
							$i,								// Row
							$sub							// Column
						);
						wbtemp_set_listview_item_text($ctrl, $last, $sub + 1, $result);
					} else
						wbtemp_set_listview_item_text($ctrl, $last, $sub + 1, (string)$items[$i][$sub + 1]);
				}
			}
			return $last;
			break;

		case TreeView:

			if($clear)
				$handle = wb_delete_items($ctrl); // Empty the treeview

			if(!$items)
				break;
			$ret = array();
			for($i = 0; $i < count($items); $i++) {
				$ret[] = wbtemp_create_treeview_item($ctrl,
				  (string)$items[$i][0],						// Name
				  isset($items[$i][1]) ? $items[$i][1] : 0,		// Value
				  isset($items[$i][2]) ? $items[$i][2] : 0,		// Where
				  isset($items[$i][3]) ? $items[$i][3] : -1,	// ImageIndex
				  isset($items[$i][4]) ? $items[$i][4] : -1,	// SelectedImageIndex
				  isset($items[$i][5]) ? $items[$i][5] : 0		// InsertionType
				);
			}
			return (count($ret) > 1 ? $ret : $ret[0]);
			break;

		case StatusBar:
			wbtemp_create_statusbar_items($ctrl, $items, $clear, $param);
			return true;

		default:

			if(is_array($items)) {
				foreach($items as $item)
					wbtemp_create_item($ctrl, $item);
				return true;
			} else
				return wbtemp_create_item($ctrl, $items);
			break;
	}
}

/*

Opens the standard Open dialog box.

*/

function wb_sys_dlg_open($parent=null, $title=null, $filter=null, $path=null, $filename=null, $flags = null)
{
	$filter = _make_file_filter($filter ? $filter : $filename);
	return wbtemp_sys_dlg_open($parent, $title, $filter, $path, $flags);
}

/*

Opens the standard Save As dialog box.

*/

function wb_sys_dlg_save($parent=null, $title=null, $filter=null, $path=null, $filename=null, $defext=null)
{
	$filter = _make_file_filter($filter ? $filter : $filename);

	return wbtemp_sys_dlg_save($parent, $title, $filter, $path, $filename, $defext);
}

//----------------------------------------- AUXILIARY FUNCTIONS FOR INTERNAL USE

/*

Creates a file filter for Open/Save dialog boxes based on an array.

*/

function _make_file_filter($filter)
{
	if(!$filter)
		return "All Files (*.*)\0*.*\0\0";

	if(is_array($filter)) {
		$result = "";
		foreach($filter as $line)
			$result .= "$line[0] ($line[1])\0$line[1]\0";
		$result .= "\0";
		return $result;
	} else
		return $filter;
}

//-------------------------------------------------------------------------- END

?>
