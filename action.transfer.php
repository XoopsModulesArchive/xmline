<?php
// $Id: index.php,v 1.5 2004/08/18 02:40:33 phppp Exp $
// ------------------------------------------------------------------------ //
// This program is free software; you can redistribute it and/or modify     //
// it under the terms of the GNU General Public License as published by     //
// the Free Software Foundation; either version 2 of the License, or        //
// (at your option) any later version.                                      //
//                                                                          //
// You may not change or alter any portion of this comment or credits       //
// of supporting developers from this source code or any supporting         //
// source code which is considered copyrighted (c) material of the          //
// original comment or credit authors.                                      //
//                                                                          //
// This program is distributed in the hope that it will be useful,          //
// but WITHOUT ANY WARRANTY; without even the implied warranty of           //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
// GNU General Public License for more details.                             //
//                                                                          //
// You should have received a copy of the GNU General Public License        //
// along with this program; if not, write to the Free Software              //
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------ //
// Author: phppp (D.J., infomax@gmail.com)                                  //
// URL: http://xoopsforge.com, http://xoops.org.cn                          //
// Project: Article Project                                                 //
// ------------------------------------------------------------------------ //

include "header.php";
require_once(XOOPS_ROOT_PATH.'/modules/xmline/include/functions.php');
require_once(XOOPS_ROOT_PATH . "/class/xoopsformloader.php");

$id = intval( empty($_GET["id"])?(empty($_POST["id"])?1:$_POST["id"]):$_GET["id"] );
$no = intval( empty($_GET["no"])?(empty($_POST["no"])?0:$_POST["no"]):$_GET["no"] );
$op = empty($_GET["op"])?(empty($_POST["op"])?@$args["op"]:$_POST["op"]):$_GET["op"];
$op = strtolower(trim($op));

if ( empty($id) )  {
	if(empty($_SERVER['HTTP_REFERER'])){
		include XOOPS_ROOT_PATH."/header.php";
		xoops_error(_NOPERM);
		$xoopsOption['output_type'] = "plain";
		include XOOPS_ROOT_PATH."/footer.php";
		exit();
	}else{
		$ref_parser = parse_url($_SERVER['HTTP_REFERER']);
		$uri_parser = parse_url($_SERVER['REQUEST_URI']);
		if(
			(!empty($ref_parser['host']) && !empty($uri_parser['host']) && $uri_parser['host'] != $ref_parser['host']) 
			|| 
			($ref_parser["path"] != $uri_parser["path"])
		){
			include XOOPS_ROOT_PATH."/header.php";
			xoops_confirm(array(), "javascript: window.close();", sprintf(_MD_TRANSFER_DONE,""), _CLOSE, $_SERVER['HTTP_REFERER']);
			$xoopsOption['output_type'] = "plain";
			include XOOPS_ROOT_PATH."/footer.php";
			exit();
		}else{
			include XOOPS_ROOT_PATH."/header.php";
			xoops_error(_NOPERM);
			$xoopsOption['output_type'] = "plain";
			include XOOPS_ROOT_PATH."/footer.php";
			exit();
		}
	}
}

$transfer_handler =& xoops_getmodulehandler("transfer", "xmline");
$op_options	=& $transfer_handler->getList();

// Display option form
if(empty($_POST["op"])){
	include XOOPS_ROOT_PATH."/header.php";
	echo "<div class=\"confirmMsg\" style=\"width: 80%; padding:20px;margin:10px auto; text-align:left !important;\"><h2>"._MD_TRANSFER_DESC."</h2><br clear=\"all\">";
	echo "<form name=\"opform\" id=\"opform\" action=\"".xoops_getenv("PHP_SELF")."\" method=\"post\"><ul>\n";
	foreach($op_options as $value=>$title){
		echo "<li><a href=\"###\" onclick=\"document.forms.opform.op.value='".$value."'; document.forms.opform.submit();\">".$title."</a></li>\n";
	}
	echo "<input type=\"hidden\" name=\"id\" id=\"id\" value=\"".$id."\">";
	echo "<input type=\"hidden\" name=\"no\" id=\"no\" value=\"".$no."\">";
	echo "<input type=\"hidden\" name=\"op\" id=\"op\" value=\"\">";
	echo "</url></form></div>";
	$xoopsOption['output_type'] = "plain";
	include XOOPS_ROOT_PATH."/footer.php";
	exit();
}else{
	$digest_handler =& xoops_getmodulehandler('digestxml', 'xmline');
	$digest = $digest_handler->get( $id );
	if(!is_object($digest)) die(_NOPERM);
	
	$feed = $digest->getVar('items');
	if(!isset($feed['items'][$no])) die(_NOPERM);
	$item =& $feed['items'][$no];
	
    $data["id"] = $id."_".$no;
	$data["title"] = $item["title"];
	$data["content"] = $item["description"];
	$data["author"] = @$item["author"];
	$data["time"] = $item["pubdate"];
	$data["url"] = $item["link"];
	
	switch($op){
		// Use regular content
		default:
			break;
	}
	
	$ret = $transfer_handler->do_transfer($_POST["op"], $data);
	
	include XOOPS_ROOT_PATH."/header.php";
	$ret = empty($ret)?"javascript: window.close();":$ret;
	xoops_confirm(array(), "javascript: window.close();", sprintf(_MD_TRANSFER_DONE,$op_options[$op]), _CLOSE, $ret);
	include XOOPS_ROOT_PATH."/footer.php";
}
?>