<?php
// $Id: modinfo.php,v 1.2 2004/09/08 22:48:50 phppp Exp $

// The name of this module
define('_MI_XMLINE_NAME','聚合新闻');
define('_MI_XMLINE_DESC','网络聚合新闻');

// Names of blocks for this module (Not all module has blocks)
define('_MI_XMLINE_BLOCK_LIST','最新聚合新闻');
define('_MI_XMLINE_BLOCK_LIST_DESC','最新聚合新闻');
define('_MI_XMLINE_BLOCK_SCROLL','滚动新闻');
define('_MI_XMLINE_BLOCK_SCROLL_DESC','滚动新闻');
define('_MI_XMLINE_BLOCK_SPOTLIGHT','聚合头条');
define('_MI_XMLINE_BLOCK_SPOTLIGHT_DESC','聚合头条');

// Names of admin menu items
define('_MI_XMLINE_CATEGORY_MANAGER', '分类管理');
define('_MI_XMLINE_SITE_MANAGER', '添加/编辑网址');
define('_MI_XMLINE_ABOUT', '相关说明');

// config items

define('_MI_XMLINE_TITLELENGTH', '标题长度');
define('_MI_XMLINE_TITLELENGTH_DESC', '主页面显示的最大标题长度');

define('_MI_XMLINE_TWOCOLUMN', '两栏显示');
define('_MI_XMLINE_TWOCOLUMN_DESC', '');

define('_MI_XMLINE_ALLOWSUBMIT', '允许用户提交');
define('_MI_XMLINE_ALLOWSUBMIT_DESC', '用户可提交站点');

define('_MI_XMLINE_AUTOAPPROVE', '自动核准');
define('_MI_XMLINE_AUTOAPPROVE_DESC', '用户提交的站点不经审核直接收录');

define('_MI_XMLINE_ALLOWUPLOAD', '允许上传图片');
define('_MI_XMLINE_ALLOWUPLOAD_DESC', '作为站点LOGO');

define('_MI_XMLINE_ALLOWEXTENSION', '允许的图片格式');
define('_MI_XMLINE_ALLOWEXTENSION_DESC', '扩展名');

define('_MI_XMLINE_IMAGEPATH', '图片文件路径');
define('_MI_XMLINE_IMAGEPATH_DESC', '待上传的或已存在的. (如果允许上传，请确定该目录可写)');

define('_MI_XMLINE_ALLOWCUSTOM', '允许测试站点');
define('_MI_XMLINE_ALLOWCUSTOM_DESC', '用户可定制测试自己的站点');

define('_MI_XMLINE_HEADER', '页首');
define('_MI_XMLINE_HEADER_DESC', '可用HTML语法');

define('_MI_XMLINE_FOOTER', '页脚');
define('_MI_XMLINE_FOOTER_DESC', '可用HTML语法');
?>