<?php
// $Id: digest.php,v 1.9 2004/09/28 01:41:41 phppp Exp $
//  ------------------------------------------------------------------------ //
//                        DIGEST for XOOPS                                   //
//             Copyright (c) 2004 Xoops China Community                      //
//                    <http://www.xoops.org.cn/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: D.J.(phppp) php_pp@hotmail.com                                    //
// URL: http://www.xoops.org.cn                                              //
// ------------------------------------------------------------------------- //
if(defined("XMLINE_DIGEST")) return;
define("XMLINE_DIGEST", 1);

class Digestxml extends XoopsObject
{
    var $table;
	var $db;

	function Digestxml()
	{
		$this->db =& Database::getInstance();
		$this->table = $this->db->prefix("xmline_digests");
		$this->initVar('online', XOBJ_DTYPE_INT);
		$this->initVar('digest_id', XOBJ_DTYPE_INT);
		$this->initVar('category_id', XOBJ_DTYPE_INT);
		$this->initVar('digest_order', XOBJ_DTYPE_INT);
		$this->initVar('title', XOBJ_DTYPE_TXTBOX);
		$this->initVar('rss', XOBJ_DTYPE_TXTBOX);
		$this->initVar('url', XOBJ_DTYPE_TXTBOX);
		$this->initVar('description', XOBJ_DTYPE_TXTBOX);
		$this->initVar('image', XOBJ_DTYPE_TXTBOX);
		$this->initVar('maxitems', XOBJ_DTYPE_INT);
		$this->initVar('updatetime', XOBJ_DTYPE_INT);
		$this->initVar('lastupdate', XOBJ_DTYPE_INT);
		$this->initVar('charset', XOBJ_DTYPE_TXTBOX);
		$this->initVar('charset_inter', XOBJ_DTYPE_TXTBOX);
		$this->initVar('items', XOBJ_DTYPE_ARRAY, array());
		$this->local_charset = _CHARSET;
	}

    function prepareVars()
    {
        foreach ($this->vars as $k => $v) {
            $cleanv = $this->cleanVars[$k];
            switch ($v['data_type']) {
                case XOBJ_DTYPE_TXTBOX:
                case XOBJ_DTYPE_TXTAREA:
                case XOBJ_DTYPE_SOURCE:
                case XOBJ_DTYPE_EMAIL:
                    $cleanv = ($v['changed'])?$cleanv:(empty($v['value'])?"":$v['value']);
                    if (!isset($v['not_gpc']) || !$v['not_gpc']) {
                        $cleanv = $this->db->quoteString($cleanv);
                    }
                    break;
                case XOBJ_DTYPE_INT:
                    $cleanv = ($v['changed'])?intval($cleanv):(empty($v['value'])?0:$v['value']);
                    break;
                case XOBJ_DTYPE_ARRAY:
                    $cleanv = ($v['changed'])?$cleanv:serialize((count($v['value'])>0)?$v['value']:array());
                    break;
                case XOBJ_DTYPE_STIME:
                case XOBJ_DTYPE_MTIME:
                case XOBJ_DTYPE_LTIME:
                    $cleanv = ($v['changed'])?$cleanv:(empty($v['value'])?0:$v['value']);
                    break;

                default:
                    break;
            }
            $this->cleanVars[$k] = &$cleanv;
            unset($cleanv);
        }
        return true;
    }

	function _saveItems()
	{
		$sql = "UPDATE ".$this->table." SET lastupdate = ".time().", items = ".$this->db->quoteString(serialize($this->getVar('items')))." WHERE digest_id = ".$this->getVar('digest_id');
		if ( !$result = $this->db->queryF($sql) ) {
			//echo "<br/>update error:$sql";
			return false;
		}
		return true;
	}

	function updateItems($forced = false)
	{
		if(!$forced&&!($this->_checkUpdateStatus())) return true;
		$items = array();
		if(!$this->_digestItems($items)) return false;
		if(!$this->_langConvItems($items)) return false;
		if(!$this->getVar('url')) {
			$this->setVar('url', $items['channel']['link']);
		}
		if(!$this->getVar('description')) {
			$this->setVar('description', $items['channel']['title']);
		}
		$this->setVar('items', serialize($items));
		if(!$this->_saveItems()) return false;
		return true;
	}

	function &fetchItems()
	{
		$items = array();
		if(!$this->_digestItems($items)) {
			echo "<br />fetchItems::_digestItems: error";
			return false;
		}
		if(!$this->_langConvItems($items)) {
			echo "<br />fetchItems::_langConvItems: error";
			return false;
		}
		if(!$this->getVar('url')) {
			$this->setVar('url', $items['channel']['link']);
		}
		if(!$this->getVar('description')) {
			$this->setVar('description', $items['channel']['title']);
		}
		return $items;
	}

	function _checkUpdateStatus()
	{
		if((time()-$this->getVar('lastupdate'))>$this->getVar('updatetime')*60){
			return true;
		}
		return false;
	}

	function _langConvItems(& $items)
	{
		if("null" == strtolower($this->getVar('charset'))) return $items;
		$charset_inter = $this->getVar('charset_inter');
		if(empty($charset_inter)){
			$items = $this->_convArray($items);
		}
		else{
			array_walk($items, array($this, "_addSplashesArray"));
			$items=serialize($items);
			$items = $this->_convItem($items, $charset_inter);
			$items = $this->_convItem($items, "", $charset_inter);
			$items = unserialize(stripslashes($items));
		}
		//echo "<br />items:<pre>";print_r($items); echo "</pre>";

		return $items;
	}

	function _addSplashesArray(&$value, $key)
	{
		if (is_array($value)) array_walk($value, array($this, "_addSplashesArray"));
		else {
			$value = addslashes($value);
		}
	}

	function _langConv($content)
	{
		$charset_inter = $this->getVar('charset_inter');
		if(empty($charset_inter)){
			$content = $this->_convItem($content);
		}
		else{
			$content = $this->_convItem($content, $charset_inter);
			$content = $this->_convItem($content, "", $charset_inter);
		}

		return $content;
	}

	function _digestItems(& $items)
	{
		$item_handler =& xoops_getmodulehandler('item', 'xmline');
		$item =& $item_handler->get($this->getVar('rss'));
		if(!is_object($item)) {
			//echo "<br />_digestItems::item: create error";
			return false;
		}

		$charset = strtolower($this->getVar('charset'));
		if(!empty($charset) && $charset!="null") $item->setVar('charset', $charset);
		$item->setVar('maxitems', $this->getVar('maxitems'));
		$items = $item->fetchItems();
		$this->setVar('charset', $item->getVar('charset'));
		if(!is_array($items)||count($items)<1) {
			//echo "<br />_digestItems::item->fetchItems: error";
		    $item_handler->destroy($item);
			return false;
		}
		$item_handler->destroy($item);

		return true;
	}

	function emptyItems()
	{
		$sql = "UPDATE ".$this->table." SET lastupdate = 0, items = NULL WHERE digest_id = ".$this->getVar('digest_id');
		if ( !$result = $this->db->queryF($sql) ) {
			//echo "<br>empty Items:: failed!!";
			return false;
		}
		return true;
	}

	function _convArray(&$value, $out_charset="", $in_charset="")
	{
		if (is_array($value)) {
			foreach($value as $key => $val)
			 $value[$key] = $this->_convArray($val, $out_charset, $in_charset);
		}
		else {
			$value = $this->_convItem($value, $out_charset, $in_charset);
		}
		return $value;
	}

	function _convItem($value, $out_charset = "", $in_charset = "")
	{
		$in_charset = empty($in_charset)?$this->getVar('charset'):$in_charset;
		$out_charset = empty($out_charset)?$this->local_charset:$out_charset;

		$converted_value =& XoopsLocal::convert_encoding($value, $out_charset, $in_charset);
		$value = empty($converted_value)?$value:$converted_value;

		return $value;
	}
}

class XmlineDigestxmlHandler extends XoopsObjectHandler
{
    function &create($isNew = true)
    {
        $digest = new Digestxml();
        if ($isNew) {
            $digest->setNew();
        }
        return $digest;
    }

    function &get($id)
    {
	    $digest = null;
        $sql = 'SELECT * FROM '.$this->db->prefix('xmline_digests').' WHERE digest_id='.intval($id);
        if($array = $this->db->fetchArray($this->db->query($sql))){
	        $digest =& $this->create(false);
	        $digest->assignVars($array);
        }
        return $digest;
    }

    function &getByIds($ids, $order = "digest_order", $isOnline = true)
    {
    	$sql = 'SELECT * FROM '.$this->db->prefix('xmline_digests');
    	$order = empty($order)?"digest_order":$order;
		$criteria_id = (is_array($ids))?" digest_id IN (".implode(',',$ids).")":"";
		$criteria_online = ($isOnline)?" online = 1 ":"";
		if ( !empty($criteria_id) || !empty($criteria_online) ) {
			$sql .= " WHERE ".$criteria_id;
			if(!empty($criteria_id)&&!empty($criteria_online)){
				$sql .= " AND ";
			}
			$sql .= $criteria_online;
		}
    	$sql .=" ORDER BY category_id, $order";
        $result = $this->db->query($sql);
        $ret = array();
        while ($myrow = $this->db->fetchArray($result)) {
            $digest =& $this->create(false);
            $digest->assignVars($myrow);
            $ret[$myrow['digest_id']] = $digest;
           	unset($digest);
        }
        return $ret;
    }


    function &getByCategory($id =0 , $order = "digest_order", $isOnline = true)
    {
    	$sql = 'SELECT * FROM '.$this->db->prefix('xmline_digests');
    	$order = empty($order)?"digest_order":$order;
		$criteria_id = (intval($id))?" category_id=".intval($id):"";
		$criteria_online = ($isOnline)?" online = 1 ":"";
		if ( !empty($criteria_id) || !empty($criteria_online) ) {
			$sql .= " WHERE ".$criteria_id;
			if(!empty($criteria_id)&&!empty($criteria_online)){
				$sql .= " AND ";
			}
			$sql .= $criteria_online;
		}
    	$sql .=" ORDER BY category_id, $order";
        $result = $this->db->query($sql);
        $ret = array();
        while ($myrow = $this->db->fetchArray($result)) {
            $digest =& $this->create(false);
            $digest->assignVars($myrow);
            $ret[$myrow['digest_id']] = $digest;
           	unset($digest);
        }
        return $ret;
    }

	function countByCategory($id=0, $isOnline = true)
	{
		$sql = "SELECT COUNT(*) FROM ".$this->db->prefix("xmline_digests");
		$criteria_id = (intval($id))?" category_id=".intval($id):"";
		$criteria_online = ($isOnline)?" online =1":"";
		if ( !empty($criteria_id) || !empty($criteria_online) ) {
			$sql .= " WHERE ".$criteria_id;
			if(!empty($criteria_id)&&!empty($criteria_online)){
				$sql .= " AND ";
			}
			$sql .= $criteria_online;
		}
		$result = $db->query($sql);
		list($count) = $db->fetchRow($result);
		return $count;
	}

    function insert(& $digest)
    {
        if (!$digest->isDirty())  return true;
        if (!$digest->cleanVars())return false;
        $digest->prepareVars();
        foreach ($digest->cleanVars as $k => $v) {
            ${$k} = $v;
        }

       if ( $digest->isNew() )
        {
            $digest_id = $this->db->genId($digest->table."_digest_id_seq");
            $sql = "INSERT INTO ".$digest->table."
            			( digest_id,  category_id,  digest_order,  rss,  online,  title,  description,  url,  image,  maxitems,  charset,  charset_inter,  updatetime)
					VALUES
                    	($digest_id, $category_id, $digest_order, $rss, $online, $title, $description, $url, $image, $maxitems, $charset, $charset_inter, $updatetime)";
          	if ( !$result = $this->db->queryF($sql) ) {
                //echo "<br />Insert digest item error:".$sql;
                return false;
            }
            if ( $digest_id == 0 ) $digest_id = $this->db->getInsertId();
      		$digest->setVar('digest_id',$digest_id);
        }else{
			$sql = "UPDATE ".$digest->table." SET digest_order = $digest_order, rss = $rss, online = $online, title = $title, url = $url,	description = $description, image = $image, maxitems = $maxitems, charset = $charset, charset_inter = $charset_inter, updatetime = $updatetime, lastupdate = 0, category_id = $category_id, items= '' WHERE digest_id = ".$digest->getVar('digest_id');
            if ( !$result = $this->db->queryF($sql) ) {
            	//echo "<br />update digest error:".$sql;
                return false;
            }
        }
        return $digest->getVar('digest_id');
    }


    function setOrder(& $digest, $order = 0)
    {
		$sql = "UPDATE ".$digest->table." SET digest_order = ".intval($order)." WHERE digest_id = ".$digest->getVar('digest_id');
        if ( !$result = $this->db->queryF($sql) ) {
        	//echo "<br />update digest order error:".$sql;
            return false;
        }
        return $digest->getVar('digest_order');
    }

    function delete(&$digest)
    {
        $sql = "DELETE FROM ".$digest->table." WHERE digest_id= ". $digest->getVar('digest_id');
        if (!$result = $this->db->queryF($sql)) {
            return false;
        }
        return true;
    }

	function update($digest = false, $forced = false)
	{
		if(is_object($digest))	return $digest->updateItems($forced);
		else return $this->updateByCategory(0, $forced = false);
	}

	function updateByCategory($id = 0, $forced = false)
	{
		$digests = & $this->getByCategory(intval($id));
		foreach($digests as $digest_id=>$digest){
			$digest->updateItems($forced);
		}
		unset($digests);
		return true;
	}
}
?>