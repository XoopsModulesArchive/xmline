<?php
// $Id: admin.php,v 1.5 2004/09/20 22:37:50 phppp Exp $

if(defined('_AM_ISLOADED')) return;
else define('_AM_ISLOADED', 1);

// index.php
define("_AM_XMLINE_CONFIG", "ģ������");
define("_AM_XMLINE_OK", "<font color='green'>OK</a>");
define("_AM_XMLINE_NOK", "<font color='red'>X</a>");
define("_AM_XMLINE_MODULE_OK", "<font color='green'>��������</a>");
define("_AM_XMLINE_MODULE_NOK", "<font color='red'>�޷�����</a>");
define("_AM_XMLINE_LANGCONV_OK", "<font color='green'>��Ч</a>, ����ת��charset");
define("_AM_XMLINE_LANGCONV_NOK", "<font color='red'>��Ч</a>, ֻ������ "._CHARSET);
define("_AM_XMLINE_XML","XML ����");
define("_AM_XMLINE_CURL","CURL ����");
define("_AM_XMLINE_FSOCKOPEN","fsockopen ����");
define("_AM_XMLINE_ALLOW_URL_FOPEN","allow_url_fopen ����");
define("_AM_XMLINE_MODULE","ģ�鹦��");
define("_AM_XMLINE_ICONV","Iconv ģ��");
define("_AM_XMLINE_XCONV","Xconv ģ��");
define("_AM_XMLINE_LANGCONV","����ת������");
define("_AM_XMLINE_IMAGEPATH","ͼƬ·��");
define("_AM_XMLINE_UPDATEAPI","��ժ���º����ӿ�");
define("_AM_XMLINE_UPDATEAPI_DESC","������ļ���������, ��ֻ��ͨ��digestģ���µ�update.php�������ݸ���");
define("_AM_XMLINE_CREATE_IMAGEPATH","����ͼƬ�ļ���");
define("_AM_XMLINE_CREATE_APIFILE","���������ļ�");
define("_AM_XMLINE_CATEGORYLIST", "�������");
define("_AM_XMLINE_DIGESTLIST", "վ������");
define("_AM_XMLINE_UPDATE", "ˢ��");
define("_AM_XMLINE_EMPTY", "���");
define("_AM_XMLINE_DIGEST_ORDER", "վ������");
define("_AM_XMLINE_CATEGORY_ORDER", "�������");
define("_AM_XMLINE_DBUPDATED","���ݿ�������!");
define("_AM_XMLINE_MODIFYCATEGORY","�޸ķ���");
define("_AM_XMLINE_MODIFY","�޸�");
define("_AM_XMLINE_EDITSITE","�༭վ��");
define("_AM_XMLINE_NEWSITE","���վ��");
define("_AM_XMLINE_DELETECONFIRM","ȷ��ɾ��");
define("_AM_XMLINE_GENERALCONF","һ������");
define("_AM_XMLINE_SERVERSTATUS", "���������ü��");
define("_AM_XMLINE_CATEGORY_MANAGEMENT", "������");
define("_AM_XMLINE_IMPORT", "���ϵ���");
define("_AM_XMLINE_EXPORT", "���ϵ���");
define("_AM_XMLINE_IMPORTFILE", "�����ļ�");
define("_AM_XMLINE_EXPORTFILE", "��������ļ�");
define("_AM_XMLINE_SITE_MANAGEMENT", "վ�����");
define("_AM_XMLINE_ABOUT","���ڸ�ģ��");

// categoryform.inc.php
define("_AM_XMLINE_TITLE", "����");
define("_AM_XMLINE_ORDER", "˳��");
define("_AM_XMLINE_IMAGE_UPLOAD", "ͼƬLOGO�ϴ�");
define("_AM_XMLINE_ALLOWED_EXTENSIONS", "�������չ��");
define("_AM_XMLINE_IMAGE_SELECT", "ͼƬѡ��");
define("_AM_XMLINE_CANCEL", "ȡ��");

// digestform.inc.php
define("_AM_XMLINE_SITE", "վ������");
define("_AM_XMLINE_RSS", "RSS��ַ");
define("_AM_XMLINE_URL", "URL");
define("_AM_XMLINE_DESCRIPTION", "����");
define("_AM_XMLINE_CATEGORY", "���");
define("_AM_XMLINE_ONLINE", "����");
define("_AM_XMLINE_OFFSET", "offset");
define("_AM_XMLINE_MAXITEMS", "�����Ŀ");
define("_AM_XMLINE_MINLENGTH", "��Ч�������С����");
define("_AM_XMLINE_CHARSET", "XML����");
define("_AM_XMLINE_CHARSET_INTER", "����ת��������");
define("_AM_XMLINE_UPDATETIME", "ˢ��Ƶ��");
define("_AM_XMLINE_REGEXP", "���ֹ��˵�������ʽ");
define("_AM_XMLINE_CRITERIA", "�ɽ������ӵı�׼");
define("_AM_XMLINE_FETCH", "ץȡ����");

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