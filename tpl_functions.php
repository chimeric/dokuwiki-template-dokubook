<?php
/**
 * template functions for dokubook template
 * 
 * @license:    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author:     Michael Klier <chi@chimeric.de>
 */
// must be run within DokuWiki
if(!defined('DOKU_INC')) die();
if(!defined('DOKU_LF')) define('DOKU_LF', "\n");

// load language files
require_once(DOKU_TPLINC.'lang/en/lang.php');
if(@file_exists(DOKU_TPLINC.'lang/'.$conf['lang'].'/lang.php')) {
    require_once(DOKU_TPLINC.'lang/'.$conf['lang'].'/lang.php');
}

/**
 * checks if a file called logo.png or logo.jpg exists
 * and uses it as logo, uses the dokuwiki logo by default
 *
 * @author Michael Klier <chi@chimeric.de>
 */
function tpl_logo() {
    global $conf;
    
    $out = '';

    switch(true) {
        case(@file_exists(DOKU_TPLINC.'images/logo.jpg')):
            $logo = DOKU_TPL.'images/logo.jpg';
            break;
        case(@file_exists(DOKU_TPLINC.'images/logo.jpeg')):
            $logo = DOKU_TPL.'images/logo.jpeg';
            break;
        case(@file_exists(DOKU_TPLINC.'images/logo.png')):
            $logo = DOKU_TPL.'images/logo.png';
            break;
        default:
            $logo = DOKU_TPL.'images/dokuwiki-128.png';
            break;
    }

    $out .= '<a href="' . DOKU_BASE . '" name="dokuwiki__top" id="dokuwiki__top" accesskey="h" title="[ALT+H]">';
    $out .= '  <img class="logo" src="' . $logo . '" alt="' . $conf['title'] . '" /></a>' . DOKU_LF;

    print ($out);
}

/**
 * generates the sidebar contents
 *
 * @author Michael Klier <chi@chimeric.de>
 */
function tpl_sidebar() {
    global $lang;
    global $ID;
    global $INFO;

    $svID  = cleanID($ID);
    $navpn = tpl_getConf('sb_pagename');
    $path  = explode(':',$svID);
    $found = false;
    $sb    = '';

    if(tpl_getConf('closedwiki') && empty($INFO['userinfo'])) {
        print '<span class="sb_label">' . $lang['toolbox'] . '</span>' . DOKU_LF;
        print '<div id="toolbox" class="sidebar_box">' . DOKU_LF;
        tpl_actionlink('login');
        print '</div>' . DOKU_LF;
        return;
    }

    // main navigation
    print '<span class="sb_label">' . $lang['navigation'] . '</span>' . DOKU_LF;
    print '<div id="navigation" class="sidebar_box">' . DOKU_LF;

    while(!$found && count($path) > 0) {
        $sb = implode(':', $path) . ':' . $navpn;
        $found =  @file_exists(wikiFN($sb));
        array_pop($path);
    }

    if(!$found && @file_exists(wikiFN($navpn))) $sb = $navpn;

    if(@file_exists(wikiFN($sb)) && auth_quickaclcheck($sb) >= AUTH_READ) {
        print p_dokubook_xhtml($sb);
    } else {
        print p_index_xhtml(cleanID($svID));
    }

    print '</div>' . DOKU_LF;

    // generate the searchbox
    print '<span class="sb_label">' . strtolower($lang['btn_search']) . '</span>' . DOKU_LF;
    print '<div id="search">' . DOKU_LF;
    tpl_searchform();
    print '</div>' . DOKU_LF;

    // generate the toolbox
    print '<span class="sb_label">' . $lang['toolbox'] . '</span>' . DOKU_LF;
    print '<div id="toolbox" class="sidebar_box">' . DOKU_LF;
    tpl_actionlink('admin');
    tpl_actionlink('index');
    tpl_actionlink('recent');
    tpl_actionlink('backlink');
    tpl_actionlink('profile');
    tpl_actionlink('login');
    print '</div>' . DOKU_LF;

    // restore ID just in case
    $Id = $svID;
}

/**
 * prints a custom page footer
 *
 * @author Michael Klier <chi@chimeric.de>
 */
function tpl_footer() {
    global $ID;

    $svID  = $ID;
    $ftpn  = tpl_getConf('ft_pagename');
    $path  = explode(':',$svID);
    $found = false;
    $ft    = '';

    while(!$found && count($path) > 0) {
        $ft = implode(':', $path) . ':' . $ftpn;
        $found =  @file_exists(wikiFN($ft));
        array_pop($path);
    }

    if(!$found && @file_exists(wikiFN($ftpn))) $ft = $ftpn;

    if(@file_exists(wikiFN($ft)) && auth_quickaclcheck($ft) >= AUTH_READ) {
        print '<div id="footer">' . DOKU_LF;
        print p_dokubook_xhtml($ft);
        print '</div>' . DOKU_LF;
    }

    // restore ID just in case
    $ID = $svID;
}

/**
 * removes the TOC of the sidebar-pages and shows 
 * a edit-button if user has enough rights
 * 
 * @author Michael Klier <chi@chimeric.de>
 */
function p_dokubook_xhtml($wp) {
    $data = p_wiki_xhtml($wp,'',false);
    if(auth_quickaclcheck($wp) >= AUTH_EDIT) {
        $data .= '<div class="secedit">' . html_btn('secedit',$wp,'',array('do'=>'edit','rev'=>'','post')) . '</div>';
    }
    // strip TOC
    $data = preg_replace('/<div class="toc">.*?(<\/div>\n<\/div>)/s', '', $data);
    // replace headline ids for XHTML compliance
    $data = preg_replace('/(<h.*?><a.*?id=")(.*?)(">.*?<\/a><\/h.*?>)/','\1sb_\2\3', $data);
    return ($data);
}

/**
 * Renders the Index
 *
 * copy of html_index located in /inc/html.php
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 * @author Michael Klier <chi@chimeric.de>
 */
function p_index_xhtml($ns) {
  require_once(DOKU_INC.'inc/search.php');
  global $conf;
  global $ID;
  $dir = $conf['datadir'];
  $ns  = cleanID($ns);
  #fixme use appropriate function
  if(empty($ns)){
    $ns = dirname(str_replace(':','/',$ID));
    if($ns == '.') $ns ='';
  }
  $ns  = utf8_encodeFN(str_replace(':','/',$ns));

  // only extract headline
  preg_match('/<h1>.*?<\/h1>/', p_locale_xhtml('index'), $match);
  print $match[0];

  $data = array();
  search($data,$conf['datadir'],'search_index',array('ns' => $ns));
  print html_buildlist($data,'idx','_html_list_index','html_li_index');
}

/**
 * Index item formatter
 *
 * User function for html_buildlist()
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 * @author Michael Klier <chi@chimeric.de>
 */
function _html_list_index($item){
  global $ID;
  global $conf;
  $ret = '';
  $base = ':'.$item['id'];
  $base = substr($base,strrpos($base,':')+1);
  if($item['type']=='d'){
    if(@file_exists(wikiFN($item['id'].':'.$conf['start']))) {
      $ret .= '<a href="'.wl($item['id'].':'.$conf['start']).'" class="idx_dir">';
      $ret .= $base;
      $ret .= '</a>';
    } else {
      $ret .= '<a href="'.wl($ID,'idx='.$item['id']).'" class="idx_dir">';
      $ret .= $base;
      $ret .= '</a>';
    }
  }else{
    $ret .= html_wikilink(':'.$item['id']);
  }
  return $ret;
}
