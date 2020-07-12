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

function transfer_blog(&$data)
{
	global $xoopsModule, $xoopsConfig, $xoopsUser, $xoopsModuleConfig;
	
	$current_path = __FILE__;
	if ( DIRECTORY_SEPARATOR != "/" ) $current_path = str_replace( strpos( $current_path, "\\\\", 2 ) ? "\\\\" : DIRECTORY_SEPARATOR, "/", $current_path);
	$root_path = dirname($current_path);
	require($root_path."/config.php");
	
	$hiddens["art_author"] = $data["author"];
	$hiddens["art_title"] = $data["title"];
	$hiddens["art_source"] = $data["url"];
	$hiddens["text"] = $data["content"];
	
	include XOOPS_ROOT_PATH."/header.php";
	xoops_confirm($hiddens, XOOPS_URL."/modules/".$config["module"]."/action.article.php", $config["title"]);
	$GLOBALS["xoopsOption"]['output_type'] = "plain";
	include XOOPS_ROOT_PATH."/footer.php";
	exit();
}
?>