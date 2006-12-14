<?php
/**
 * template functions for dokubook template
 * 
 * @license:    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author:     Michael Klier <chi@chimeric.de>
 */

if(!defined('DW_LF')) define('DW_LF',"\n");

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
    $out .= '  <img class="logo" src="' . $logo . '" alt="' . $conf['title'] . '" /></a>' . DW_LF;

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

    // main navigation
    print '<span class="sb_label">' . $lang['navigation'] . '</span>' . DW_LF;
    print '<div id="navigation" class="box">' . DW_LF;
    if(@file_exists(wikiFN('navigation'))) {
        print p_sidebar_xhtml('navigation');
    } else {
        print html_index(cleanID($ID));
    }  
    print '</div>' . DW_LF;

    // generate the searchbox
    print '<span class="sb_label">' . strtolower($lang['btn_search']) . '</span>' . DW_LF;
    print '<div id="search">' . DW_LF;
    tpl_searchform();
    print '</div>' . DW_LF;

    // generate the toolbox
    print '<span class="sb_label">' . $lang['toolbox'] . '</span>' . DW_LF;
    print '<div id="toolbox" class="box">' . DW_LF;
    tpl_actionlink('admin');
    tpl_actionlink('index');
    tpl_actionlink('recent');
    tpl_actionlink('backlink');
    tpl_actionlink('profile');
    tpl_actionlink('login');
    print '</div>' . DW_LF;
}

/**
 * removes the TOC of the sidebar-pages and shows a edit-button if user has enough rights
 * 
 * @author Michael Klier <chi@chimeric.de>
 */
function p_sidebar_xhtml($Sb) {
    $data = p_wiki_xhtml($Sb,'',false);
    if(auth_quickaclcheck($Sb) >= AUTH_EDIT) {
        $data .= '<div class="secedit">' . html_btn('secedit',$Sb,'',array('do'=>'edit','rev'=>'','post')) . '</div>';
    }
    return preg_replace('/<div class="toc">.*?(<\/div>\n<\/div>)/s', '', $data);
}
