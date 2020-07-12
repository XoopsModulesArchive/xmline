<?php
// $Id: about.php,v 1.2 2004/09/27 16:57:52 phppp Exp $
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
// URL: http://www.xoops.org.cn                                              //
// ------------------------------------------------------------------------- //
include '../../../include/cp_header.php';
include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
xoops_cp_header();
$myts = &MyTextSanitizer::getInstance();

$module_handler =& xoops_gethandler('module');
$versioninfo =& $module_handler->get($xoopsModule->getVar('mid'));

// Left headings...
echo "<a href='index.php'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule -> dirname() . "/" . $versioninfo -> getInfo('image') . "' alt='' hspace='10' vspace='0' align='left'></a>";
echo "<div style='margin-top: 10px; color: #33538e; margin-bottom: 4px; font-size: 18px; line-height: 18px; font-weight: bold; display: block;'>" . $versioninfo->getInfo('name') . " version " . $versioninfo->getInfo('version') . "</div>";
if  ( $versioninfo->getInfo('author_realname') != '')
{
	$author_name = $versioninfo->getInfo('author') . " (" . $versioninfo->getInfo('author_realname') . ")";
} else
{
	$author_name = $versioninfo->getInfo('author');
}

echo "</div>";
echo "<div>" . _AM_XMLINE_RELEASE . ": " . $versioninfo -> getInfo('releasedate') . "</div>";

// Author Information
$sform = new XoopsThemeForm(_AM_XMLINE_AUTHOR_INFO, "", "");
$sform -> addElement(new XoopsFormLabel(_AM_XMLINE_AUTHOR_NAME, $author_name));
$author_sites = $versioninfo -> getInfo('author_website');
$author_site_info = "";
foreach($author_sites as $site){
	$author_site_info .= "<a href='" . $site['url'] . "' target='blank'>" . $site['name'] . "</a>; ";
}
$sform -> addElement(new XoopsFormLabel(_AM_XMLINE_AUTHOR_WEBSITE, $author_site_info));
$sform -> addElement(new XoopsFormLabel(_AM_XMLINE_AUTHOR_EMAIL, "<a href='mailto:" . $versioninfo -> getInfo('author_email') . "'>" . $versioninfo -> getInfo('author_email') . "</a>"));
$sform -> addElement(new XoopsFormLabel(_AM_XMLINE_AUTHOR_CREDITS, $versioninfo -> getInfo('credits')));
$sform -> display();

$sform = new XoopsThemeForm(_AM_XMLINE_MODULE_INFO, "", "");
$sform -> addElement(new XoopsFormLabel(_AM_XMLINE_MODULE_STATUS, $versioninfo -> getInfo('status')));
$sform -> addElement(new XoopsFormLabel(_AM_XMLINE_MODULE_DEMO, "<a href='" . $versioninfo -> getInfo('demo_site_url') . "' target='blank'>" . $versioninfo -> getInfo('demo_site_name') . "</a>"));
$sform -> addElement(new XoopsFormLabel(_AM_XMLINE_MODULE_README, "<a href='" . $versioninfo -> getInfo('readme') . "' target='blank'>" . _AM_XMLINE_MODULE_README . "</a>"));
$sform -> addElement(new XoopsFormLabel(_AM_XMLINE_MODULE_SUPPORT, "<a href='" . $versioninfo -> getInfo('support_site_url') . "' target='blank'>" . $versioninfo -> getInfo('support_site_name') . "</a>"));
$sform -> addElement(new XoopsFormLabel(_AM_XMLINE_AUTHOR_TRANSLATOR, $versioninfo -> getInfo('translator')));
$sform -> addElement(new XoopsFormLabel(_AM_XMLINE_AUTHOR_ACK, $versioninfo -> getInfo('ack')));
$sform -> addElement(new XoopsFormLabel(_AM_XMLINE_AUTHOR_TODO, $versioninfo -> getInfo('TODO')));
$file = "../bugfixlist.txt";
if (@file_exists($file))
{
    $fp = @fopen($file, "r");
    $bugtext = @fread($fp, filesize($file));
    @fclose($file);
	$sform -> addElement(new XoopsFormLabel(_AM_XMLINE_AUTHOR_BUGFIX, $myts->displayTarea($bugtext)));
}
$sform -> display();
xoops_cp_footer();
?>