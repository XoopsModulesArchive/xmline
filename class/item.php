<?php
// $Id: item.php,v 1.2 2004/09/19 22:25:28 phppp Exp $
//  ------------------------------------------------------------------------ //
//                        DIGEST for XOOPS                                   //
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
// Credits: Wang Jue (alias wjue) http://www.wjue.org                        //
// ------------------------------------------------------------------------- //
include_once XOOPS_ROOT_PATH."/class/snoopy.php";

class Item
{
	var $url;
	var $charset;
	var $maxitems;
	var $content;
	var $items = array();

	function Item()
	{
	}

	function setVar($var, $val)
	{
		$this->$var = $val;

		return true;
	}

	function getVar($var)
	{
		return $this->$var;
	}

	function validate()
	{
	    return $this->fetchContent();
	}

	function fetchItems()
	{
	    if (!$this->getVar('content')) $this->fetchContent();
	    //echo "<br />content:".$this->getVar('content');
	    if(!$this->_parse()) {
			echo "<br />Item::fetchItems: parse error";
		    return false;
	    }
	    return $this->getVar('items');
	}

    function _getUrl()
    {
	    $url = $this->getVar('url');
	    $url = ( preg_match('|^http://(.*)|i', $url, $match) ) ? $url : 'http://'.$url;
	    return rawurldecode($url);
    }

    function _parse()
    {
	    //return true;
		require_once XOOPS_ROOT_PATH.'/modules/xmline/class/rss_parse_magpie.inc.php';

	    $charset = $this->getVar('charset');
	    if(empty($charset)) $this->setVar('charset', $this->_getPageCharset());

	    $this->_preConvContent();
		$_parser = new MagpieRSS( $this->getVar('content'), $this->getVar('charset'), $this->getVar('charset'), false );
		if (!$_parser) {
			return false;
		}
		$data = array();
		$data['channel'] = $_parser->channel;
		$data['image'] = $_parser->image;
		$item_count = 0;
		foreach($_parser->items as $item){
			$item['description'] = empty($item['description'])?"":$item['description'];
			if(!empty($item['content']['encoded'])){
				$item['description'] = $item['content']['encoded'];
			}
			if(!empty($item['atom_content'])){
				$item['description'] = $item['atom_content'];
			}
			$data['items'][] = $item;
			unset($item);
			$item_count ++;
			if( $this->getVar('maxitems') >0 &&$item_count >= $this->getVar('maxitems')) break;		
		}
		$this->setVar('items', $data);
		unset($data);
		return true;
	}

	function _preConvContent()
	{
		$_supported_encodings = array('utf-8', 'us-ascii', 'iso-8859-1');

		//$content = str_replace('\n', '<br />', str_replace( '\"', '&quot;', $this->getVar('content')));
		//$this->setVar('content', $content);

		if(in_array(strtolower($this->getVar('charset')), $_supported_encodings)) {
			//echo "<br />No need to convert:".$this->getVar('charset');
			return;
		}
		$this->setVar('content', xoops_utf8_encode($this->getVar('content')));
		$this->setVar('charset', 'utf-8');
	}

    Function _getPageCharset()
    {
		if (preg_match('/<?xml.*encoding=[\'"](.*?)[\'"].*?>/m', $this->getVar('content'), $match)) {
			return strtoupper($match[1]);
		}else {
			return 'utf-8';
		}
    }

    function fetchContent()
    {
	    if($this->_fetchCURL()) return true;
		//echo "<br/>CURL failed";
	    if($this->_fetchSnoopy()) return true;
		//echo "<br/>Snoopy failed";
		if($this->_fetchFopen()) return true;
		//echo "<br/>fopen failed";
	   	return false;
    }

	function _fetchSnoopy()
	{
		$snoopy = new Snoopy;
		$data = "";
		if (@$snoopy->fetch($this->_getUrl())){
        	$data = (is_array($snoopy->results))?implode("\n",$snoopy->results):$snoopy->results;
			//echo "<br/>Snoopy fetched:$data";
    	}
		if($data) {
			$this->setVar('content', $data);
			return true;
		}
		return false;
    }

    function _fetchCURL()
    {
	    if (!function_exists('curl_init') ) return false;
        $ch = curl_init();    // initialize curl handle
        curl_setopt($ch, CURLOPT_URL, $this->_getUrl()); // set url to post to
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // times out after 31s
        $data = curl_exec($ch); // run the whole process
        $this->setVar('url', curl_getinfo($ch, CURLINFO_EFFECTIVE_URL));
        curl_close($ch);
		//echo "<br/>CURL data:<pre>";print_r($data); echo "</pre>";
		if($data) {
			$this->setVar('content', $data);
			return true;
		}
		return false;
    }

    function _fetchFopen()
    {
    	if(!$fp = @fopen ($this->_getUrl(), 'r')) return false;
        $data = "";
        while (!feof($fp)) {
            $data .= fgets ($fp, 1024);
        }
        fclose($fp);
		if($data) {
			$this->setVar('content', $data);
			return true;
		}
        return false;
    }
}

class XmlineItemHandler extends XoopsObjectHandler
{
    function &get($url) {
	    $item = new Item();
	    $item -> setVar('url', $url);
	    if($item->validate()) return $item;
	    return false;
    }

    function destroy($item)
    {
	    /*
	     * Any further actions?
	     *
	     */
	    unset($item);
	    return true;
    }
}
?>