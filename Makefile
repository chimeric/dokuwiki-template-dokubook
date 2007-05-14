# Makefile for DokuWiki Template Dokubook
#
# @author Michael Klier <chi@chimeric.de>

DIST_VERSION=`cat VERSION`
DIST_NAME=template-dokubook-$(DIST_VERSION)
DIST_DIR=../dokubook

# {{{ DOCS
DOCS=$(DIST_DIR)/README \
	 $(DIST_DIR)/COPYING \
	 $(DIST_DIR)/VERSION
# }}}

# {{{ CSS
CSS=$(DIST_DIR)/dokubook_design.css \
	$(DIST_DIR)/dokubook_layout.css \
	$(DIST_DIR)/dokubook_print.css \
	$(DIST_DIR)/design.css \
	$(DIST_DIR)/layout.css \
	$(DIST_DIR)/media.css \
	$(DIST_DIR)/print.css \
	$(DIST_DIR)/rtl.css
# }}}

# {{{ STYLE_INI
STYLE_INI=$(DIST_DIR)/style.ini \
		  $(DIST_DIR)/style.ini.dist
# }}}

# {{{ PHP
PHP=$(DIST_DIR)/detail.php \
	$(DIST_DIR)/main.php \
	$(DIST_DIR)/mediamanager.php \
	$(DIST_DIR)/tpl_functions.php
# }}}

# {{{ HTML
HTML=$(DIST_DIR)/footer.html \
# }}}

# {{{ IMAGES
IMAGES=$(DIST_DIR)/images/bullet.gif \
	   $(DIST_DIR)/images/button-cc.gif \
	   $(DIST_DIR)/images/button-chimeric-de.png \
	   $(DIST_DIR)/images/button-css.png \
	   $(DIST_DIR)/images/button-donate.gif \
	   $(DIST_DIR)/images/button-dw.png \
	   $(DIST_DIR)/images/button-firefox.png \
	   $(DIST_DIR)/images/button-php.gif \
	   $(DIST_DIR)/images/button-rss.png \
	   $(DIST_DIR)/images/buttonshadow.png \
	   $(DIST_DIR)/images/button-xhtml.png \
	   $(DIST_DIR)/images/closed.gif \
	   $(DIST_DIR)/images/dokuwiki-128.png \
	   $(DIST_DIR)/images/dwbg.jpg \
	   $(DIST_DIR)/images/favicon.ico \
	   $(DIST_DIR)/images/inputshadow.png \
	   $(DIST_DIR)/images/link_icon.gif \
	   $(DIST_DIR)/images/mail_icon.gif \
	   $(DIST_DIR)/images/open.gif \
	   $(DIST_DIR)/images/tocdot2.gif \
	   $(DIST_DIR)/images/user.gif \
	   $(DIST_DIR)/images/windows.gif \
	   $(DIST_DIR)/images/tool-admin.png \
	   $(DIST_DIR)/images/tool-backlink.png \
	   $(DIST_DIR)/images/tool-edit.png \
	   $(DIST_DIR)/images/tool-index.png \
	   $(DIST_DIR)/images/tool-login.png \
	   $(DIST_DIR)/images/tool-logout.png \
	   $(DIST_DIR)/images/tool-profile.png \
	   $(DIST_DIR)/images/tool-recent.png \
	   $(DIST_DIR)/images/tool-revisions.png \
	   $(DIST_DIR)/images/tool-source.png \
	   $(DIST_DIR)/images/tool-subscribe.png
# }}}

# {{{ CONF
CONF=$(DIST_DIR)/conf/default.php \
     $(DIST_DIR)/conf/metadata.php
# }}}

# {{{ LANG
LANG=$(DIST_DIR)/lang/en/settings.php \
	 $(DIST_DIR)/lang/en/lang.php
# }}}

DIST_FILES= $(DOCS) $(CSS) $(HTML) $(PHP) $(STYLE_INI) $(IMAGES) $(CONF) $(LANG)

dist:
	tar czf $(DIST_NAME).tgz $(DIST_FILES)

clean: 
	rm $(DIST_NAME).tgz

# setup vim:ts=4:sw=4:fdm:marker:
