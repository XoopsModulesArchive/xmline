<?php
// $Id: Category.php,v 1.1 2004/09/08 22:47:17 phppp Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
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
// URL: http://xoops.org.cn                                                  //
// ------------------------------------------------------------------------- //

class Category extends XoopsObject
{
    function Category() 
    {
        $this->db = & Database :: getInstance();
        $this->table = $this -> db -> prefix( "xmline_categories" );
        $this->initVar('category_id', XOBJ_DTYPE_INT);
        $this->initVar('title', XOBJ_DTYPE_TXTBOX);
        $this->initVar('image', XOBJ_DTYPE_TXTBOX);
        $this->initVar('category_order', XOBJ_DTYPE_INT);
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
                    $cleanv = ($v['changed'])?$cleanv:(empty($v['value'])?'':$v['value']);
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
}

class XmlineCategoryHandler extends XoopsObjectHandler
{

    function &get($id) 
    {
	    $category = null;
	    $id = intval($id);
	    if(!$id) {
		    return $category;
	    }
        $sql = 'SELECT * FROM '.$this->db->prefix('xmline_categories').' WHERE category_id='.$id;
        if($array = $this->db->fetchArray($this->db->query($sql))){
	        $category =& $this->create(false);
	        $category->assignVars($array);
        }
        return $category;
    }

    function &getAll($order = "category_order") 
    {
	    $order = empty($order)?"category_order":$order;
    	$sql = 'SELECT * FROM '.$this->db->prefix('xmline_categories')." ORDER BY $order, category_id";
        $result = $this->db->query($sql);
        $ret = array();
        while ($myrow = $this->db->fetchArray($result)) {
            $category =& $this->create(false);
            $category->assignVars($myrow);
            $ret[$myrow['category_id']] = $category;
           	unset($category);
        }
        return $ret;
    }

    function &getItemsByCategory($category=0)
    {
	    $cat_id = is_object($category)?$category->getVar('category_id'):intval($category);
	    $id= empty($cat_id)?'':" WHERE category_id=".$cat_id;
    	$sql = 'SELECT * FROM '.$this->db->prefix('xmline_digests').$id;
        $result = $this->db->query($sql);
        $ret = array();
        while ($myrow = $this->db->fetchArray($result)) {
            $category =& $this->create(false);
            $category->assignVars($myrow);
            $ret[$myrow['category_id']] = $category;
           	unset($category);
        }
        return $ret;
    }

    function &create($isNew = true)
    {
        $category = new Category();
        if ($isNew) {
            $category->setNew();
        }
        return $category;
    }

    function &getByDigest($id) 
    {
	    $category = null;
        $sql = "SELECT t.* FROM ".$this->db->prefix('xmline_categories')." t, ".$this->db->prefix('xmline_digests')." d WHERE t.category_id = d.category_id AND d.digest_id = ".intval($id);
        if( $result = $this->db->query($sql) && $row = $this->db->fetchArray($result)){
	        $category =& $this->create(false);
	        $category->assignVars($row);
        }
        return $category;
    }

    function getDigestCount($category=null)
    {
	    $cat_id = is_object($category)?$category->getVar('category_id'):intval($category);
	    $id= empty($cat_id)?'':" WHERE category_id=".$cat_id;
        $result = $this->db->query("SELECT COUNT(storyid) FROM ".$db->prefix('xmline_digests').$id);
        list($count) = $this->db->fetchRow($result);
		return $count;
    }

    function getCategoryCount()
    {
        $result = $this->db->query("SELECT COUNT(category_id) FROM ".$this->db->prefix('xmline_categories'));
        list($count) = $this->db->fetchRow($result);
		return $count;
    }

    function insert(& $category) 
    {
        if (!$category->isDirty())  return true;
        if (!$category->cleanVars())return false;
        $category->prepareVars();
        foreach ($category->cleanVars as $k => $v) {
            ${$k} = $v;
        }

       if ( $category->isNew() )
        {
            $category_id = $this->db->genId($category->table."_category_id_seq");
            $sql = "INSERT INTO ".$category->table."
            			( category_id,  title,  image,  category_order)
					VALUES
                    	($category_id, $title, $image, $category_order)";
          	if ( !$result = $this->db->queryF($sql) ) {
                echo "<br />Insert category error:".$sql;
                return false;
            }
            if ( $category_id == 0 ) $category_id = $this->db->getInsertId();
      		$category->setVar('category_id',$category_id);
        }else{
			$sql = "UPDATE ".$category->table." SET category_order = $category_order, title = $title, image = $image WHERE category_id = ".$category->getVar('category_id');
            if ( !$result = $this->db->queryF($sql) ) {
            	echo "<br />update category error:".$sql;
                return false;
            }
        }
        return $category->getVar('category_id');
    }

    function setOrder(& $category, $order = 0)
    {
		$sql = "UPDATE ".$category->table." SET category_order = ".intval($order)." WHERE category_id = ".$category->getVar('category_id');
        if ( !$result = $this->db->queryF($sql) ) {
        	//echo "<br />update digest order error:".$sql;
            return false;
        }
        return $category->getVar('category_order');
    }

    function delete(&$category)
    {
        $sql = "DELETE FROM ".$category->table." WHERE category_id= ". $category->getVar('category_id');
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        return true;
    }
}
?>