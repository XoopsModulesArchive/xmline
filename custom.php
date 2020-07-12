<?php
// $Id: index.php,v 1.7 2004/09/20 22:34:55 phppp Exp $
//  ------------------------------------------------------------------------ //
//                        XMLINE for XOOPS                                   //
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
// URL: http://xoops.org.cn                                                  //
// ------------------------------------------------------------------------- //
include 'header.php';
include_once(XOOPS_ROOT_PATH.'/header.php');
include_once(XOOPS_ROOT_PATH.'/modules/xmline/class/uploader.php');
// include the default language file for the admin interface
if ( file_exists( "./language/" . $xoopsConfig['language'] . "/admin.php" ) ) {
    include_once("./language/" . $xoopsConfig['language'] . "/admin.php");
}elseif ( file_exists( "./language/english/admin.php" ) ) {
    include_once("./language/english/admin.php");
}

$category_id = empty($_GET['category_id'])?0:intval(($_GET['category_id']));

echo "<h4>" . _MD_XMLINE_CUSTOM . "</h4>";

if ( isset( $_POST ) ){
    foreach ( $_POST as $k => $v ) {
        ${$k} = $v;
    }
}

$error_upload = '';
if (!empty($_FILES['userfile']['name'])
	&& (is_object($xoopsUser) && $xoopsUser->isAdmin())
	) {
    $uploader = new xmline_uploader(
    	XOOPS_ROOT_PATH . "/".$xoopsModuleConfig['image_path'],
    	$xoopsModuleConfig['allowed_extension']
    );
    if ( $uploader->fetchMedia( $_POST['xoops_upload_file'][0]) ) {
        if ( !$uploader->upload() ){
            $error_upload = $uploader->getErrors();
    	}elseif ( is_file( $uploader->getSavedDestination() )){
                $image = $uploader->getSavedFileName();
        }
    }else{
        $error_upload = $uploader->getErrors();
    }
}

if(!empty($_POST['submit'])){
	$digest_handler =& xoops_getmodulehandler('digestxml', 'xmline');
	$digest = $digest_handler->create();
    $digest -> setVar( 'category_id', $category_id );
    $digest -> setVar( 'title', $title );
    $digest -> setVar( 'rss', $rss );
    $digest -> setVar( 'online', $online );
    $digest -> setVar( 'digest_order', $digest_order );
    $digest -> setVar( 'url', trim($url) );
    $digest -> setVar( 'description', trim($description) );
    $digest -> setVar( 'image', $image );
    $digest -> setVar( 'maxitems', $maxitems );
    $digest -> setVar( 'charset', $charset );
    $digest -> setVar( 'charset_inter', $charset_inter );
    $digest -> setVar( 'updatetime', $updatetime );
    $digest_handler->insert( $digest );
	echo "<br />"._MD_XMLINE_SUBMITTED;
	echo "<hr>";
}elseif(!empty($_POST['fetch'])){
	$url = trim($url);
	$description = trim($description);
	$digest_handler =& xoops_getmodulehandler('digestxml', 'xmline');
	$digest = $digest_handler->create();
    $digest -> setVar( 'title', $title );
    $digest -> setVar( 'rss', $rss );
    $digest -> setVar( 'url', $url );
    $digest -> setVar( 'description', $description );
    $digest -> setVar( 'image', $image );
    $digest -> setVar( 'maxitems', $maxitems );
    $digest -> setVar( 'charset', $charset );
    $digest -> setVar( 'charset_inter', $charset_inter );
	$feed = $digest->fetchItems();
	$url = empty($url)?$feed['channel']['link']:$url;
	$description = empty($description)?$feed['channel']['title']:$description;
	$tpl = new XoopsTpl();
	$tpl->assign('feed', $feed);
	$tpl->display('db:xmline_feed.html');
}else{
	$title = '';
	$rss = '';
	$url = '';
	$description = '';
	$image = '';
	$charset = '';
	$charset_inter = '';
	$digest_order = 1;
	$online = empty($xoopsModuleConfig['auto_approve'])?0:1;
	$maxitems = 20;
	$updatetime = 60;
	$category_id = empty($_GET['category_id'])?0:intval($_GET['category_id']);
}
include "include/digestform.inc.php";

include_once XOOPS_ROOT_PATH.'/footer.php';
?>