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

function transfer_newbb(&$data)
{
	$current_path = __FILE__;
	if ( DIRECTORY_SEPARATOR != "/" ) $current_path = str_replace( strpos( $current_path, "\\\\", 2 ) ? "\\\\" : DIRECTORY_SEPARATOR, "/", $current_path);
	$root_path = dirname($current_path);
	$config = array();
	require($root_path."/config.php");
	
	require_once(XOOPS_ROOT_PATH."/modules/".$config["module"]."/include/functions.php");
	
	$post_handler =& xoops_getmodulehandler("post", $config["module"]);
	$forumpost =& $post_handler->create();
    $forumpost->setVar("poster_ip", newbb_getIP());
    $forumpost->setVar("uid", empty($GLOBALS["xoopsUser"])?0:$GLOBALS["xoopsUser"]->getVar("uid"));
    $forumpost->setVar("forum_id", empty($data["forum_id"])?@$config["forum"]:$data["forum_id"]);
	$forumpost->setVar("subject", $data["title"]);
	$post_text = $data["content"]."<br />".
		"<a href=\"".$data["url"]."\">"._MORE."</a>";
	
	$forumpost->setVar("post_text",$post_text);
    $forumpost->setVar("dohtml", 1);
    $forumpost->setVar("dosmiley", 1);
    $forumpost->setVar("doxcode", 1);
	$post_id = $post_handler->insert($forumpost);
	$topic = $forumpost->getVar("topic_id");
	unset($forumpost);
	return sprintf($config["url"], $topic);
}
?>