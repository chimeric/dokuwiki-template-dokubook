<?php
/**
 * DokuWiki Template DokuBook
 *
 * This is the template you need to change for the overall look
 * of DokuWiki.
 *
 * You should leave the doctype at the very top - It should
 * always be the very first line of a document.
 *
 * @link   http://wiki.splitbrain.org/template:dokubook
 * @author Andreas Gohr <andi@splitbrain.org>
 * @author Michael Klier <chi@chimeric.de>
 */

// must be run from within DokuWiki
if (!defined('DOKU_INC')) die();
require_once(DOKU_TPLINC.'tpl_functions.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $conf['lang']?>"
 lang="<?php echo $conf['lang']?>" dir="<?php echo $lang['direction']?>">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>
    <?php tpl_pagetitle()?>
    [<?php echo strip_tags($conf['title'])?>]
  </title>

  <?php tpl_metaheaders()?>

  <link rel="shortcut icon" href="<?php echo DOKU_TPL?>images/favicon.ico" />

  <?php /*old includehook*/ @include(dirname(__FILE__).'/meta.html')?>

  <!-- change link borders dynamically -->
  <style type="text/css">
    <?php if($ACT == 'show' || $ACT == 'edit') { ?>
    div.dokuwiki ul#top__nav a.edit,
    div.dokuwiki ul#top__nav a.show,
    div.dokuwiki ul#top__nav a.source
    <?php } else { ?>
    div.dokuwiki ul#top__nav a.<?php echo $ACT;?>
    <?php } ?>
    {
      border-color: #fabd23;
      border-bottom: 1px solid #fff;
      font-weight: bold;
    }
  </style>
</head>

<body>
<?php /*old includehook*/ @include(dirname(__FILE__).'/topheader.html')?>
<div class="dokuwiki">
  <?php html_msgarea()?>

  <div id="sidebar_<?php echo tpl_getConf('sb_position')?>" class="sidebar">
    <?php tpl_logo()?> 
    <?php tpl_sidebar()?>
  </div>

  <div id="dokubook_container_<?php echo tpl_getConf('sb_position')?>">

    <div class="stylehead">

      <div class="header">
        <?php /*old includehook*/ @include(dirname(__FILE__).'/pageheader.html')?>
        <?php /*old includehook*/ @include(dirname(__FILE__).'/header.html')?>
        <div class="logo">
          <?php tpl_link(wl(),$conf['title'],'name="dokuwiki__top" accesskey="h" title="[ALT+H]"')?>
        </div>
      </div>

      <ul id="top__nav">
        <?php
			if(!plugin_isdisabled('npd') && ($npd =& plugin_load('helper', 'npd'))) {
                $npb = $npd->html_new_page_button(true);
                if($npb) {
                    print '<li>' . $npb . '</li>' . DOKU_LF;
                }
            }
            foreach(array('edit', 'history', 'subscribe', 'subscribens') as $act) {
                ob_start();
                print '<li>';
                if(tpl_actionlink($act)) {
                    print '</li>' . DOKU_LF;
                    ob_end_flush();
                } else {
                    ob_end_clean();
                }
            }
        ?>
      </ul>

    </div>

    <?php flush()?>

    <div class="page">

      <?php if($conf['breadcrumbs']){?>
      <div class="breadcrumbs">
        <?php tpl_breadcrumbs()?>
      </div>
      <?php }?>

      <?php if($conf['youarehere']){?>
      <div class="breadcrumbs">
        <?php tpl_youarehere() ?>
      </div>
      <?php }?>

      <!-- wikipage start -->
      <?php tpl_content()?>
      <!-- wikipage stop -->

      <div class="meta">
        <div class="doc">
          <?php tpl_pageinfo()?>
        </div>
      </div>

      <?php tpl_actionlink('top')?>

      <div class="clearer"></div>

    </div>

    <?php flush()?>

    <div class="clearer"></div>

    <?php tpl_footer() ?>

    <div class="stylefoot">
      <?php /*old includehook*/ @include(dirname(__FILE__).'/pagefooter.html')?>
    </div>

    <?php /*old includehook*/ @include(dirname(__FILE__).'/footer.html')?>

  </div>

</div>
<div class="no"><?php /* provide DokuWiki housekeeping, required in all templates */ tpl_indexerWebBug()?></div>
</body>
</html>
