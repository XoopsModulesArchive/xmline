<?php
// $Id: admin.php,v 1.5 2004/09/20 22:37:50 phppp Exp $

if(defined('_AM_ISLOADED')) return;
else define('_AM_ISLOADED', 1);

// index.php
define("_AM_XMLINE_CONFIG", "模块设置");
define("_AM_XMLINE_OK", "<font color='green'>OK</a>");
define("_AM_XMLINE_NOK", "<font color='red'>X</a>");
define("_AM_XMLINE_MODULE_OK", "<font color='green'>正常运行</a>");
define("_AM_XMLINE_MODULE_NOK", "<font color='red'>无法运行</a>");
define("_AM_XMLINE_LANGCONV_OK", "<font color='green'>有效</a>, 可以转换charset");
define("_AM_XMLINE_LANGCONV_NOK", "<font color='red'>无效</a>, 只能用于 "._CHARSET);
define("_AM_XMLINE_XML","XML 函数");
define("_AM_XMLINE_CURL","CURL 函数");
define("_AM_XMLINE_FSOCKOPEN","fsockopen 函数");
define("_AM_XMLINE_ALLOW_URL_FOPEN","allow_url_fopen 函数");
define("_AM_XMLINE_MODULE","模块功能");
define("_AM_XMLINE_ICONV","Iconv 模块");
define("_AM_XMLINE_XCONV","Xconv 模块");
define("_AM_XMLINE_LANGCONV","编码转换功能");
define("_AM_XMLINE_IMAGEPATH","图片路径");
define("_AM_XMLINE_UPDATEAPI","文摘更新函数接口");
define("_AM_XMLINE_UPDATEAPI_DESC","如果该文件不能生成, 则只能通过digest模块下的update.php进行内容更新");
define("_AM_XMLINE_CREATE_IMAGEPATH","重新图片文件夹");
define("_AM_XMLINE_CREATE_APIFILE","重新生成文件");
define("_AM_XMLINE_CATEGORYLIST", "类别设置");
define("_AM_XMLINE_DIGESTLIST", "站点设置");
define("_AM_XMLINE_UPDATE", "刷新");
define("_AM_XMLINE_EMPTY", "清空");
define("_AM_XMLINE_DIGEST_ORDER", "站点排序");
define("_AM_XMLINE_CATEGORY_ORDER", "类别排序");
define("_AM_XMLINE_DBUPDATED","数据库更新完成!");
define("_AM_XMLINE_MODIFYCATEGORY","修改分类");
define("_AM_XMLINE_MODIFY","修改");
define("_AM_XMLINE_EDITSITE","编辑站点");
define("_AM_XMLINE_NEWSITE","添加站点");
define("_AM_XMLINE_DELETECONFIRM","确认删除");
define("_AM_XMLINE_GENERALCONF","一般设置");
define("_AM_XMLINE_SERVERSTATUS", "服务器设置检测");
define("_AM_XMLINE_CATEGORY_MANAGEMENT", "类别管理");
define("_AM_XMLINE_IMPORT", "资料导入");
define("_AM_XMLINE_EXPORT", "资料导出");
define("_AM_XMLINE_IMPORTFILE", "导入文件");
define("_AM_XMLINE_EXPORTFILE", "点击导出文件");
define("_AM_XMLINE_SITE_MANAGEMENT", "站点管理");
define("_AM_XMLINE_ABOUT","关于该模块");

// categoryform.inc.php
define("_AM_XMLINE_TITLE", "标题");
define("_AM_XMLINE_ORDER", "顺序");
define("_AM_XMLINE_IMAGE_UPLOAD", "图片LOGO上传");
define("_AM_XMLINE_ALLOWED_EXTENSIONS", "允许的扩展名");
define("_AM_XMLINE_IMAGE_SELECT", "图片选择");
define("_AM_XMLINE_CANCEL", "取消");

// digestform.inc.php
define("_AM_XMLINE_SITE", "站点设置");
define("_AM_XMLINE_RSS", "RSS网址");
define("_AM_XMLINE_URL", "URL");
define("_AM_XMLINE_DESCRIPTION", "描述");
define("_AM_XMLINE_CATEGORY", "类别");
define("_AM_XMLINE_ONLINE", "在线");
define("_AM_XMLINE_OFFSET", "offset");
define("_AM_XMLINE_MAXITEMS", "最大条目");
define("_AM_XMLINE_MINLENGTH", "有效标题的最小长度");
define("_AM_XMLINE_CHARSET", "XML编码");
define("_AM_XMLINE_CHARSET_INTER", "编码转换过渡码");
define("_AM_XMLINE_UPDATETIME", "刷新频率");
define("_AM_XMLINE_REGEXP", "文字过滤的正则表达式");
define("_AM_XMLINE_CRITERIA", "可接受链接的标准");
define("_AM_XMLINE_FETCH", "抓取测试");

// about.php
define('_AM_XMLINE_RELEASE', "Release Date ");
define('_AM_XMLINE_AUTHOR_INFO', "Developer Information");
define('_AM_XMLINE_AUTHOR_NAME', "Developer");
define('_AM_XMLINE_AUTHOR_WEBSITE', "Developer website");
define('_AM_XMLINE_AUTHOR_EMAIL', "Developer email");
define('_AM_XMLINE_AUTHOR_CREDITS', "Credits");

define('_AM_XMLINE_MODULE_INFO', "Module Development Information");
define('_AM_XMLINE_MODULE_STATUS', "Development Status");
define('_AM_XMLINE_MODULE_DEMO', "Demo Site");
define('_AM_XMLINE_MODULE_SUPPORT', "Official support site");
define('_AM_XMLINE_AUTHOR_TRANSLATOR', "Translator");
define('_AM_XMLINE_AUTHOR_ACK', "Acknowledgement");
define('_AM_XMLINE_AUTHOR_TODO', "TODO list");
define('_AM_XMLINE_AUTHOR_BUGFIX', "Bug fix history");
define('_AM_XMLINE_MODULE_README', "Readme");
?>