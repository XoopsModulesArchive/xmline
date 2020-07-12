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
include '../../../include/cp_header.php';
include_once XOOPS_ROOT_PATH . "/class/xoopslists.php";
include_once XOOPS_ROOT_PATH."/class/xoopstree.php";
include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");
include_once(XOOPS_ROOT_PATH.'/modules/xmline/include/vars.php');
include_once(XOOPS_ROOT_PATH.'/modules/xmline/include/functions.php');
include_once(XOOPS_ROOT_PATH.'/modules/xmline/class/uploader.php');

define("_AM_XMLINE_CONFIG_LINK","<a href='index.php' target='_self'>"._AM_XMLINE_CONFIG."</a>");
$op = ( isset( $_GET['op'] ) )? $_GET['op']:'';
$digest_id = ( isset( $_GET['digest_id'] ) )?intval( $_GET['digest_id'] ):0;
if ( isset( $_POST ) ){
    foreach ( $_POST as $k => $v ) {
        ${$k} = $v;
    }
}
$action = empty($op)?"":$op;
$op = "";

$category_handler =& xoops_getmodulehandler('category', 'xmline');
$digest_handler =& xoops_getmodulehandler('digestxml', 'xmline');

xoops_cp_header();
echo "<h4>" . _AM_XMLINE_CONFIG_LINK . "</h4>";

function serverstatus()
{
	global $module_handler, $xoopsModuleConfig;

    $XML_ok = (extension_loaded('xml'))? _AM_XMLINE_OK : _AM_XMLINE_NOK;
    $CURL_ok = (function_exists('curl_init'))? _AM_XMLINE_OK : _AM_XMLINE_NOK;
    $fsockopen_ok = (function_exists('fsockopen')) ? _AM_XMLINE_OK : _AM_XMLINE_NOK;
    $allow_url_fopen_ok = (ini_get('allow_url_fopen')) ? _AM_XMLINE_OK : _AM_XMLINE_NOK;
	if(!is_object($module_handler)) $module_handler =& xoops_gethandler('module');
	$iconv_ok = (function_exists("iconv"))? _AM_XMLINE_OK : _AM_XMLINE_NOK;
	$xconv_ok = 0;
	$xconv =& $module_handler->getByDirname('xconv');
	if(is_object($xconv)) $xconv_ok = $xconv->getVar('isactive');
	$xconv_ok = ($xconv_ok)? _AM_XMLINE_OK : _AM_XMLINE_NOK;
	$module_ok = ( $XML_ok && (($CURL_ok == _AM_XMLINE_OK)||($fsockopen_ok == _AM_XMLINE_OK)||($allow_url_fopen_ok == _AM_XMLINE_OK)))? _AM_XMLINE_MODULE_OK : _AM_XMLINE_MODULE_NOK;
	$langconv_ok = (($iconv_ok == _AM_XMLINE_OK)||($xconv_ok == _AM_XMLINE_OK))? _AM_XMLINE_LANGCONV_OK : _AM_XMLINE_LANGCONV_NOK;
	$imagepath_ok = (@is_writable(XOOPS_ROOT_PATH.'/'.$xoopsModuleConfig['image_path']))? _AM_XMLINE_OK : _AM_XMLINE_NOK;
	$updateapi_ok = (@is_readable(XMLINE_API_FILE))? _AM_XMLINE_OK : _AM_XMLINE_NOK;
	$sform = new XoopsThemeForm(_AM_XMLINE_SERVERSTATUS, "", "");
	$sform -> addElement(new XoopsFormLabel(_AM_XMLINE_XML, $XML_ok));
	$sform -> addElement(new XoopsFormLabel(_AM_XMLINE_CURL, $CURL_ok));
	$sform -> addElement(new XoopsFormLabel(_AM_XMLINE_FSOCKOPEN, $fsockopen_ok));
	$sform -> addElement(new XoopsFormLabel(_AM_XMLINE_ALLOW_URL_FOPEN, $allow_url_fopen_ok));
	$sform -> addElement(new XoopsFormLabel('=>'._AM_XMLINE_MODULE, $module_ok));
	$sform -> addElement(new XoopsFormLabel(_AM_XMLINE_ICONV, $iconv_ok));
	$sform -> addElement(new XoopsFormLabel(_AM_XMLINE_XCONV, $xconv_ok));
	$sform -> addElement(new XoopsFormLabel('=>'._AM_XMLINE_LANGCONV, $langconv_ok));
	$sform -> addElement(new XoopsFormLabel(_AM_XMLINE_IMAGEPATH.'<br />'.XOOPS_ROOT_PATH.'/<strong>'.$xoopsModuleConfig['image_path'].'</strong>', $imagepath_ok.'<br /><a href=index.php?op=createimagepath>'._AM_XMLINE_CREATE_IMAGEPATH.'</a>'));
	$sform -> addElement(new XoopsFormLabel(_AM_XMLINE_UPDATEAPI.'<br />'.XMLINE_API_FILE.' <a href="#" title="'._AM_XMLINE_UPDATEAPI_DESC.'"><strong>?</strong></a>', $updateapi_ok.'<br /><a href=index.php?op=createapi>'._AM_XMLINE_CREATE_APIFILE.'</a>'));
	$sform -> display();
}

function digestList()
{
    global $digest_handler, $category_id;
    $digestarray =& $digest_handler->getByCategory($category_id, '', false);
    if ( is_array($digestarray)&&count( $digestarray ) > 0 ){
		    echo "<table border='0' width='100%' cellpadding = '2' cellspacing ='1' class = 'outer'>";
	        echo "<tr><td align='center' colspan='6' class='even'>" . _AM_XMLINE_DIGESTLIST . "</td></tr>";
		    echo "<tr>";
		    echo "<td class = 'head' align='center' height ='16px' ><strong>ID</strong></td>";
		    echo "<td class = 'head' align='left'><strong>" . _AM_XMLINE_TITLE . "</strong></td>";
		   	echo "<td class = 'head' align='center' width='5%'>" . _EDIT . "</td>";
		   	echo "<td class = 'head' align='center' width='5%'>" . _DELETE . "</td>";
		    echo "<td class = 'head' align='center'>" . _AM_XMLINE_UPDATE . "</td>";
		    echo "<td class = 'head' align='center' width='5%'>" . _AM_XMLINE_EMPTY . "</td>";
		    echo "</tr>\n";
	    	$cid = 0;
	        foreach( $digestarray as $digest ) {
		        if($cid && $cid != $digest -> getVar('category_id')){
		         	echo "<tr><td class = 'even' colspan='6' height='10px'></td></tr>";
		        }
		        $cid = $digest -> getVar('category_id');
	            echo "<tr>\n";
	         	echo "<td class = 'odd' nowrap>" . $cid . " -- " . $digest -> getVar('digest_id') . "</td>\n";
	         	echo "<td class = 'odd'><a href='" . $digest -> getVar('rss') . "' target='_blank'>" . $digest -> getVar('title') . "</a>  : ".$digest -> getVar('description')."</td>\n";
	         	echo "<td class = 'odd'><a href='index.php?op=edit&amp;digest_id=" . $digest -> getVar('digest_id') . "'>" . _EDIT . "</a></td>\n";
	            echo "<td class = 'odd'><a href='index.php?op=delete&amp;digest_id=" . $digest -> getVar('digest_id') . "'>" . _DELETE . "</a></td>\n";
	            echo "<td class = 'odd'><a href='index.php?op=update&amp;digest_id=" . $digest -> getVar('digest_id') . "'>" . formatTimestamp($digest -> getVar('lastupdate'),"Y-m-d H:i") . "</a> -&gt;".formatTimestamp($digest -> getVar('lastupdate') +$digest -> getVar('updatetime') * 60,"d-H:i")."</td>\n";
	            echo "<td class = 'odd'><a href='index.php?op=empty&amp;digest_id=" . $digest -> getVar('digest_id') . "'>" . _AM_XMLINE_EMPTY . "</a></td>\n";
	        	echo "</tr>";
	        }
	        echo"</table>";
	        echo "<br />";
    }
}


function CategoryList()
{
    global $category_handler, $category_id;
    $categoryarray = $category_handler->getAll();
    if ( count($categoryarray) > 0 )
    {
	    echo "<table border='0' width='100%' cellpadding = '2' cellspacing ='1' class = 'outer'>";
        echo "<tr><td align='center' colspan='6' class='even'>" . _AM_XMLINE_CATEGORYLIST . "</td></tr>";
	    echo "<tr>";
	    echo "<td class = 'head' align='center' height ='16px' ><strong>ID</strong></td>";
	    echo "<td class = 'head' align='left'><strong>" . _AM_XMLINE_TITLE . "</strong></td>";
	   	echo "<td class = 'head' align='center' width='5%'>" . _EDIT . "</td>";
	   	echo "<td class = 'head' align='center' width='5%'>" . _DELETE . "</td>";
	    echo "</tr>\n";
        foreach( $categoryarray as $category ) {
            echo "<tr>\n";
         	echo "<td class = 'odd' nowrap>" . $category -> getVar('category_id') . "</td>\n";
         	echo "<td class = 'odd'>" . $category -> getVar('title') ."</td>\n";
         	echo "<td class = 'odd'><a href='index.php?op=modCategory&amp;category_id=" . $category -> getVar('category_id') . "'>" . _EDIT . "</a></td>\n";
            echo "<td class = 'odd'><a href='index.php?op=delCategory&amp;category_id=" . $category -> getVar('category_id') . "'>" . _DELETE . "</a></td>\n";
        	echo "</tr>";
        }
        echo"</table>";
        echo "<br />";
    }
}

switch ( $action )
{
    case "edit":
        echo "<br />";
        echo "<h4>" . _AM_XMLINE_EDITSITE . "</h4>";
        $digest = & $digest_handler->get( $digest_id );
        $title = $digest ->getVar('title', 'Edit');
        $rss = $digest ->getVar('rss', 'Edit');
        $url = $digest ->getVar('url', 'Edit');
        $description = $digest ->getVar('description', 'Edit');
        $image = $digest ->getVar('image', 'Edit');
        $charset = $digest ->getVar('charset', 'Edit');
        $charset_inter = $digest ->getVar('charset_inter', 'Edit');
        $digest_order = $digest ->getVar('digest_order');
        $online = $digest ->getVar('online');
        $maxitems = $digest ->getVar('maxitems');
        $updatetime = $digest ->getVar('updatetime');
        $category_id = $digest ->getVar('category_id');
        include "../include/digestform.inc.php";
        break;

    case "siteManager":
        digestList();
        echo "<br />";
        echo "<h4>" . _AM_XMLINE_NEWSITE . "</h4>";
        $title = '';
        $rss = '';
        $url = '';
        $description = '';
        $image = '';
        $charset = '';
        $charset_inter = '';
        $digest_order = 1;
        $online = 1;
        $maxitems = 20;
        $updatetime = 60;
        $category_id = 0;
        include "../include/digestform.inc.php";
        break;

    case "update":
		$digest = $digest_handler->get( $digest_id );
		$digest_handler->update( $digest, true );
        redirect_header( 'index.php?op=siteManager', 1, _AM_XMLINE_DBUPDATED );
        exit();
        break;

    case "empty":
        $digest = & $digest_handler->get( $digest_id );
        $digest -> emptyItems();
        redirect_header( 'index.php?op=siteManager', 1, _AM_XMLINE_DBUPDATED );
        exit();
        break;

    case "save":
	    $error_upload = '';
	    if (!empty($_FILES['userfile']['name'])) {
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
	    $image = empty($image)?(empty($_POST['image'])?"":$_POST['image']):$image;

        if ( empty( $digest_id ) ) $digest = & $digest_handler->create();
        else $digest = & $digest_handler->get( $digest_id );
        $digest -> setVar( 'category_id', intval($category_id) );
        $digest -> setVar( 'title', $title );
        $digest -> setVar( 'online', $online );
        $digest -> setVar( 'digest_order', intval($digest_order) );
        $digest -> setVar( 'rss', $rss );
        $digest -> setVar( 'url', $url );
        $digest -> setVar( 'description', $description );
        $digest -> setVar( 'image', $image );
        $digest -> setVar( 'maxitems', $maxitems );
        $digest -> setVar( 'charset', $charset );
        $digest -> setVar( 'charset_inter', $charset_inter );
        $digest -> setVar( 'updatetime', $updatetime );
        $digest_handler->insert( $digest );
        redirect_header( 'index.php?op=siteManager', 2, _AM_XMLINE_DBUPDATED );
        break;

    case "delete":
        if ( !empty( $ok ) ){
            $digest = & $digest_handler->get( $digest_id );
            $digest_handler->delete( $digest );
            redirect_header( 'index.php?op=siteManager', 1, _AM_XMLINE_DBUPDATED );
            exit();
        }else{
            xoops_confirm( array( 'op' => 'delete', 'digest_id' => $digest_id, 'ok' => 1 ), 'index.php', _AM_XMLINE_DELETECONFIRM );
        }
        break;

    case "digestOrder":
	    $digestarray =& $digest_handler->getByCategory($category_id, '', false);
	    if ( is_array($digestarray)&&count( $digestarray ) > 0 ){
		    echo "<form name='digestorder' METHOD='post'>";
		    echo "<table border='0' width='100%' cellpadding = '2' cellspacing ='1' class = 'outer'>";
	        echo "<tr><td align='center' colspan='3' class='even'>" . _AM_XMLINE_DIGEST_ORDER . "</td></tr>";
		    echo "<tr>";
		    echo "<td class = 'head' align='center' height ='16px' ><strong>ID</strong></td>";
		    echo "<td class = 'head' align='left'><strong>" . _AM_XMLINE_TITLE . "</strong></td>";
		    echo "<td class = 'head' align='center' width='5%'><strong>" . _AM_XMLINE_ORDER . "</strong></td>";
		    echo "</tr>";
	    	$cid = 0;
	        foreach( $digestarray as $digest ) {
		        if($cid && $cid != $digest -> getVar('category_id')){
		         	echo "<tr><td class = 'even' colspan='3' height='10px'></td></tr>";
		        }
		        $cid = $digest -> getVar('category_id');
	            echo "<tr>\n";
	         	echo "<td class = 'odd' nowrap>" . $cid . " -- " . $digest -> getVar('digest_id') . "<input type='hidden' name='digest_id[]' value='" . $digest -> getVar('digest_id') . "' ></td>\n";
	         	echo "<td class = 'odd'><a href='" . $digest -> getVar('rss') . "' target='_blank'>" . $digest -> getVar('title') . "</a>  : ".$digest -> getVar('description')."</td>\n";
	         	echo "<td class = 'odd'><input type='text' name='digest_order[]' value='" . $digest -> getVar('digest_order') . "' size='8'></td>\n";
	        	echo "</tr>";
	        }
	        echo "<input type='hidden' name='op' value='digestOrderSave' >";
	        echo "<tr><td align='center' colspan='3' class='even'><input type='submit' name='submit' value='" . _SUBMIT . "'></td></tr>";
	        echo"</table>";
    		echo "</form>";
	        echo "<br />";
	    }
        break;

    case "digestOrderSave":
	    if(!isset($_POST['digest_order'])) return;
	    $ids = $_POST['digest_id'];
	    $ods = $_POST['digest_order'];

	    for($i=0;$i<count($ids);$i++){
		    $digest = $digest_handler->get($ids[$i]);
		    $digest_handler->setOrder($digest, $ods[$i]);
	    }
	    redirect_header( 'index.php?op=digestOrder', 1, _AM_XMLINE_DBUPDATED );
        break;

    case "categoryOrder":
	    $categoryarray =& $category_handler->getAll();
	    if ( is_array($categoryarray)&&count( $categoryarray ) > 0 ){
		    echo "<form name='categoryorder' METHOD='post'>";
		    echo "<table border='0' width='100%' cellpadding = '2' cellspacing ='1' class = 'outer'>";
	        echo "<tr><td align='center' colspan='3' class='even'>" . _AM_XMLINE_CATEGORY_ORDER . "</td></tr>";
		    echo "<tr>";
		    echo "<td class = 'head' align='center' width='5%' height ='16px' ><strong>ID</strong>";
		    echo "<td class = 'head' align='left' width='30%'><strong>" . _AM_XMLINE_TITLE . "</strong></td>";
		    echo "<td class = 'head' align='center' width='5%'><strong>" . _AM_XMLINE_ORDER . "</strong></td>";
		    echo "</tr>";
	        foreach( $categoryarray as $category ) {
	            echo "<tr>\n";
	         	echo "<td class = 'odd'>" . $category -> getVar('category_id') . "<input type='hidden' name='category_id[]' value='" . $category -> getVar('category_id') . "' ></td>\n";
	         	echo "<td class = 'odd'>" . $category -> getVar('title') . "</td>\n";
	         	echo "<td class = 'odd'><input type='text' name='category_order[]' value='" . $category -> getVar('category_order') . "' size='8'></td>\n";
	        	echo "</tr>";
	        }
	        echo "<input type='hidden' name='op' value='categoryOrderSave' >";
	        echo "<tr><td align='center' colspan='3' class='even'><input type='submit' name='submit' value='" . _SUBMIT . "'></td></tr>";
	        echo"</table>";
    		echo "</form>";
	        echo "<br />";
	    }
        break;

    case "categoryOrderSave":
	    if(!isset($_POST['category_order'])) return;
	    $ids = $_POST['category_id'];
	    $ods = $_POST['category_order'];

	    for($i=0;$i<count($ids);$i++){
		    $category = $category_handler->get($ids[$i]);
		    $category_handler->setOrder($category, $ods[$i]);
	    }
	    redirect_header( 'index.php?op=categoryOrder', 1, _AM_XMLINE_DBUPDATED );
        break;

    case "categoryManager":
	    categoryList();
	    $category_id = 0;
	    $title = '';
	    $image = '';
	    $category_order = 0;
	    include "../include/categoryform.inc.php";
        break;

    case "delCategory":
	    if ($_POST['ok'] != 1 ){
	        xoops_confirm( array( 'op' => 'delCategory', 'category_id' => intval( $_GET['category_id'] ), 'ok' => 1 ), 'index.php', _AM_XMLINE_DELETECONFIRM );
	    }else{
	    	$category =& $category_handler->get(intval($_POST['category_id']));
	        $digest_arr =& $digest_handler->getBycategory( intval($_POST['category_id']), '', false );
	        foreach($digest_arr as $digest_id => $digest){
	            $digest_handler -> delete($digest);
	        }
	        $category_handler->delete($category);
	        redirect_header( 'index.php?op=categoryManager', 1, _AM_XMLINE_DBUPDATED );
	        exit();
	    }
        break;

    case "modCategory":
	    $category_id = intval($_POST['category_id']);
	    $category_id = empty($category_id)?intval($_GET['category_id']):0;
	    $category =& $category_handler->get($category_id);
	    $title = $category->getVar('title','e');
	    $image = $category->getVar('image','e');
	    $category_order = $category->getVar('category_order');
	    include "../include/categoryform.inc.php";
        break;

    case "categorySave":
	    $error_upload = '';
	    if (!empty($_FILES['userfile']['name'])) {
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
	    $image = empty($image)?(empty($_POST['image'])?"":$_POST['image']):$image;

        if ( empty( $category_id ) ) $category = & $category_handler->create();
        else $category = & $category_handler->get( $category_id );
	    $category->setVar('title',$_POST['title']);
	    $category->setVar('image',$image);
	    $category->setVar('category_order',intval($_POST['category_order']));
	    $category_handler->insert($category);
	    redirect_header( 'index.php?op=categoryManager', 1, _AM_XMLINE_DBUPDATED );
        break;

    case "createapi":
        xmline_createUpdateApi($xoopsModule);
        redirect_header( 'index.php?op=serverstatus', 1, _AM_XMLINE_DBUPDATED );
        exit();
        break;

    case "createimagepath":
        xmline_mkdir(XOOPS_ROOT_PATH.'/'.$xoopsModuleConfig['image_path']);
        redirect_header( 'index.php?op=serverstatus', 1, _AM_XMLINE_DBUPDATED );
        exit();
        break;

    case "serverstatus":
        serverstatus();
        break;

    case "exportDb":
    	if(!empty($_POST['export_digests'])){
	    	$export_file = xmline_export($_POST['export_digests']);
        	redirect_header( 'index.php?op=exportDb', 10, _AM_XMLINE_DBUPDATED. "<br /><a href=\"".$export_file."\"><strong>". _AM_XMLINE_EXPORTFILE . "</a>");
        	exit();
    	}
	    $digestarray =& $digest_handler->getByCategory(0, '', false);
    	$digests[0] = _ALL;
		if ( count( $digestarray ) > 0 ) foreach($digestarray as $digest){
			$name = $digest->getVar('description');
			$digests[$digest->getVar('digest_id')] = empty($name)?$digest->getVar('title'):$name;
    	}
		$form = new XoopsThemeForm(_AM_XMLINE_EXPORT, "exportform", "index.php");
		$sel_digest = new XoopsFormSelect('', 'export_digests', 0, 5, true);
		$sel_digest->addOptionArray($digests);
		$form->addElement($sel_digest);
		$form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
		$form->addElement(new XoopsFormHidden('op', "exportDb"));
		$form->display();
        break;

    case "importDb":
	    if (!empty($_FILES['userfile']['name'])) {
	        $uploader = new xmline_uploader(XOOPS_CACHE_PATH);
	        if ( $uploader->fetchMedia( $_POST['xoops_upload_file'][0]) ) {
	            if ( !$uploader->upload() ){
	                $error_upload = $uploader->getErrors();
            	}
            	$file = XOOPS_CACHE_PATH. "/". $uploader->getSavedFileName();
	    		xmline_import($file, $_POST['category_id']);
        		redirect_header( 'index.php?op=importDb', 2, _AM_XMLINE_DBUPDATED );
        		exit();
    		}
    	}
		$form = new XoopsThemeForm(_AM_XMLINE_IMPORT, "importform", "index.php");
		$form->setExtra('enctype="multipart/form-data"');
		ob_start();
		$mytree = new XoopsTree($xoopsDB->prefix("xmline_categories"), "category_id", "0");
		$mytree->makeMySelBox("title", "category_id", $category_id);
		$form->addElement(new XoopsFormLabel(_AM_XMLINE_CATEGORY, ob_get_contents()));
		ob_end_clean();
		$form->addElement(new XoopsFormFile(_AM_XMLINE_IMPORTFILE, 'userfile',''));
		$form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
		$form->addElement(new XoopsFormHidden('op', "importDb"));
		$form->display();
        break;

    case "default":
    default:
        echo"<table width='100%' border='0' cellspacing='1' class='outer'><tr><td class=\"odd\">";
        echo " - <strong><a href='" . XOOPS_URL . '/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod=' . $xoopsModule -> getVar( 'mid' ) . "'>" . _AM_XMLINE_GENERALCONF . "</a></strong><br /><br />\n";
        echo " - <strong><a href='index.php?op=categoryManager'>" . _AM_XMLINE_CATEGORY_MANAGEMENT . "</a> -- <a href='index.php?op=categoryOrder'>" . _AM_XMLINE_CATEGORY_ORDER . "</a></strong><br /><br />\n";
        echo " - <strong><a href='index.php?op=siteManager'>" . _AM_XMLINE_SITE_MANAGEMENT . "</a> -- <a href='index.php?op=digestOrder'>" . _AM_XMLINE_DIGEST_ORDER . "</a></strong><br /><br />\n";
        echo " - <strong><a href='index.php?op=importDb'>" . _AM_XMLINE_IMPORT . "</a> -- <a href='index.php?op=exportDb'>" . _AM_XMLINE_EXPORT . "</a></strong><br /><br />\n";
        echo " - <strong><a href='index.php?op=serverstatus'>" . _AM_XMLINE_SERVERSTATUS . "</a></strong><br /><br />\n";
        echo " - <strong><a href='about.php'>" . _AM_XMLINE_ABOUT . "</a></strong>";
        echo"</td></tr></table>";
        break;
}

xoops_cp_footer();

?>
