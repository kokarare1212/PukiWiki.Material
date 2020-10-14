<?php
// PukiWiki - Yet another WikiWikiWeb clone.
// pukiwiki.skin.php
// Copyright
//   2002-2020 PukiWiki Development Team
//   2001-2002 Originally written by yu-ji
// License: GPL v2 or (at your option) any later version
//
// PukiWiki default skin

// ------------------------------------------------------------
// Settings (define before here, if you want)

// Set site identities
$_IMAGE['skin']['logo']     = 'pukiwiki.png';
$_IMAGE['skin']['favicon']  = ''; // Sample: 'image/favicon.ico';

// SKIN_DEFAULT_DISABLE_TOPICPATH
//   1 = Show reload URL
//   0 = Show topicpath
if (! defined('SKIN_DEFAULT_DISABLE_TOPICPATH'))
	define('SKIN_DEFAULT_DISABLE_TOPICPATH', 1); // 1, 0

// Show / Hide navigation bar UI at your choice
// NOTE: This is not stop their functionalities!
if (! defined('PKWK_SKIN_SHOW_NAVBAR'))
	define('PKWK_SKIN_SHOW_NAVBAR', 1); // 1, 0

// Show / Hide toolbar UI at your choice
// NOTE: This is not stop their functionalities!
if (! defined('PKWK_SKIN_SHOW_TOOLBAR'))
	define('PKWK_SKIN_SHOW_TOOLBAR', 1); // 1, 0

// ------------------------------------------------------------
// Code start

// Prohibit direct access
if (! defined('UI_LANG')) die('UI_LANG is not set');
if (! isset($_LANG)) die('$_LANG is not set');
if (! defined('PKWK_READONLY')) die('PKWK_READONLY is not set');

$lang  = & $_LANG['skin'];
$link  = & $_LINK;
$image = & $_IMAGE['skin'];
$rw    = ! PKWK_READONLY;

// MenuBar
$menu = arg_check('read') && exist_plugin_convert('menu') ? do_plugin_convert('menu') : FALSE;
// RightBar
$rightbar = FALSE;
if (arg_check('read') && exist_plugin_convert('rightbar')) {
	$rightbar = do_plugin_convert('rightbar');
}
// ------------------------------------------------------------
// Output

// HTTP headers
pkwk_common_headers();
header('Cache-control: no-cache');
header('Pragma: no-cache');
header('Content-Type: text/html; charset=' . CONTENT_CHARSET);

?>
<!DOCTYPE html>
<html lang="<?php echo LANG ?>">
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CONTENT_CHARSET ?>">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php if ($nofollow || ! $is_read)  { ?> <meta name="robots" content="NOINDEX,NOFOLLOW"><?php } ?>
<?php if ($html_meta_referrer_policy) { ?> <meta name="referrer" content="<?php echo htmlsc(html_meta_referrer_policy) ?>"><?php } ?>

 <title><?php echo $title ?> - <?php echo $page_title ?></title>

 <link rel="SHORTCUT ICON" href="<?php echo $image['favicon'] ?>">
 <!-- <link rel="stylesheet" type="text/css" href="<?php echo SKIN_DIR ?>pukiwiki.css"> -->
 <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
 <link rel="stylesheet" href="<?php echo SKIN_DIR ?>materialize.min.css">
 <link rel="alternate" type="application/rss+xml" title="RSS" href="<?php echo $link['rss'] ?>"><?php // RSS auto-discovery ?>
 <script type="text/javascript" src="skin/main.js" defer></script>
 <script type="text/javascript" src="skin/search2.js" defer></script>
 <script src="skin/materialize.min.js"></script>
 <script>onload=()=>{M.AutoInit()};</script>
<?php echo $head_tag ?>
</head>
<body>
<?php echo $html_scripting_data ?>
<div class="navbar-fixed">
	<?php if(PKWK_SKIN_SHOW_NAVBAR) { ?>
	<?php
	function _navigator($key){
		$link = & $GLOBALS['_LINK'];
		$lang = & $GLOBALS['_LANG']['skin'];
		if (! isset($link[$key])) { return; }
		if (! isset($lang[$key])) { return; }
		print("<li><a href=\"{$link[$key]}\" class=\"waves-effect\">{$lang[$key]}</a></li>");
	}
	function _navigatorText($key){
		$lang = & $GLOBALS['_LANG']['skin'];
		if (! isset($lang[$key])) { return; }
		print($lang[$key]);
	}
	function _navigatorLink($key){
		$link = & $GLOBALS['_LINK'];
		if (! isset($link[$key])) { return; }
		print($link[$key]);
	}
	?>
	<?php } ?>
	<?php if ($is_page || $rw) { ?>
	<ul id="nav_dropdown_edit" class="dropdown-content">
		<?php if ($is_page) { ?>
		<?php if ($rw) { ?>
  	<?php _navigator('edit') ?>
		<?php if ($is_read && $function_freeze) { ?>
		<?php (! $is_freeze) ? _navigator('freeze') : _navigator('unfreeze') ?>
		<?php } ?>
		<?php } ?>
		<?php _navigator('diff') ?>
 		<?php if ($do_backup) { ?>
		<?php _navigator('backup') ?>
		<?php } ?>
 		<?php if ($rw && (bool)ini_get('file_uploads')) { ?>
		<?php _navigator('upload') ?>
		<?php } ?>
		<?php _navigator('reload') ?>
		<?php } ?>
		<?php if ($rw) { ?>
		<?php _navigator('new') ?>
		<?php } ?>
	</ul>
	<?php } ?>
	<ul id="nav_dropdown_tool" class="dropdown-content">
		<?php _navigator('list') ?>
		<?php if (arg_check('list')) { ?>
		<?php _navigator('filelist') ?>
 		<?php } ?>
		<?php _navigator('search') ?>
		<?php _navigator('recent') ?>
		<?php _navigator('help')   ?>
	</ul>
  <nav>
    <div class="nav-wrapper">
      <a href="/" class="brand-logo">
			<img id="logo" src="<?php echo IMAGE_DIR . $image['logo'] ?>" style="vertical-align: middle;padding: .5rem;" width="60" height="60" alt="[PukiWiki]" title="[PukiWiki]">
			<?php echo $page_title ?>
			</a>
			<a href="#" data-target="sidenav" class="sidenav-trigger"><i class="material-icons">menu</i></a>
			<?php if(PKWK_SKIN_SHOW_NAVBAR) { ?>
      <ul class="right hide-on-med-and-down">
        <?php _navigator('top') ?>
				<?php if ($is_page || $rw) { ?>
				<li><a class="dropdown-trigger" href="#!" data-target="nav_dropdown_edit"><?php _navigatorText('edit') ?><i class="material-icons right">arrow_drop_down</i></a></li>
				<?php } ?>
				<li><a class="dropdown-trigger" href="#!" data-target="nav_dropdown_tool"><?php _navigatorText('tool') ?><i class="material-icons right">arrow_drop_down</i></a></li>
				<?php if ($enable_login) { ?>
				<?php _navigator('login') ?>
				<?php } ?>
 				<?php if ($enable_logout) { ?>
				<?php _navigator('logout') ?>
 				<?php } ?>
			</ul>
			<?php } // PKWK_SKIN_SHOW_NAVBAR ?>
    </div>
  </nav>
</div>
<ul class="sidenav" id="sidenav">
	<li>
		<a>
		<img id="logo" src="<?php echo IMAGE_DIR . $image['logo'] ?>" style="vertical-align: middle;" width="35" height="35" alt="[PukiWiki]" title="[PukiWiki]">
		<?php echo $page_title ?>
		</a>
	</li>
	<?php _navigator('top') ?>
  <?php if ($is_page || $rw) { ?>
	<li><div class="divider"></div></li>
  <li><a class="subheader"><?php _navigatorText('edit') ?></a></li>
	<?php if ($is_page) { ?>
	<?php if ($rw) { ?>
  <?php _navigator('edit') ?>
	<?php if ($is_read && $function_freeze) { ?>
	<?php (! $is_freeze) ? _navigator('freeze') : _navigator('unfreeze') ?>
	<?php } ?>
	<?php } ?>
	<?php _navigator('diff') ?>
 	<?php if ($do_backup) { ?>
	<?php _navigator('backup') ?>
	<?php } ?>
 	<?php if ($rw && (bool)ini_get('file_uploads')) { ?>
	<?php _navigator('upload') ?>
	<?php } ?>
	<?php _navigator('reload') ?>
	<?php } ?>
	<?php if ($rw) { ?>
	<?php _navigator('new') ?>
	<?php } ?>
	<?php } ?>
	<li><div class="divider"></div></li>
  <li><a class="subheader"><?php _navigatorText('tool') ?></a></li>
	<?php _navigator('list') ?>
 	<?php if (arg_check('list')) { ?>
	<?php _navigator('filelist') ?>
 	<?php } ?>
	<?php _navigator('search') ?>
	<?php _navigator('recent') ?>
	<?php _navigator('help')   ?>
	<?php if ($enable_login) { ?>
	<li><div class="divider"></div></li>
	<?php _navigator('login') ?>
	<?php } ?>
 	<?php if ($enable_logout) { ?>
	<li><div class="divider"></div></li>
	<?php _navigator('logout') ?>
 	<?php } ?>
</ul>

<div id="contents" class="row" style="display: flex;">
 <div id="body" class="col s12 m9" style="overflow-wrap: break-word;order: 2;flex-grow: 1;"><?php echo $body ?></div>
<?php if ($menu !== FALSE) { ?>
 <div id="menubar" class="col s12 m3" style="overflow-wrap: break-word;order: 1;flex-grow: 0;"><div class="card blue-grey lighten-4"><div class="card-content"><?php echo $menu ?></div></div></div>
<?php } ?>
<?php if ($rightbar) { ?>
 <div id="rightbar"><?php echo $rightbar ?></div>
<?php } ?>
</div>

<?php if ($notes != '') { ?>
<div id="note" class="row"><div class="col s12"><div class="card"><div class="card-content"><?php echo $notes ?></div></div></div></div>
<?php } ?>

<?php if ($attaches != '') { ?>
<div id="attach">
<div class="col s12"><div class="card"><div class="card-content">
<?php echo $attaches ?>
</div></div></div>
</div>
<?php } ?>

<?php if (PKWK_SKIN_SHOW_TOOLBAR) { ?>
<!-- Toolbar -->
<div id="toolbar">
<?php

// Set toolbar-specific images
$_IMAGE['skin']['reload']   = 'reload.png';
$_IMAGE['skin']['new']      = 'new.png';
$_IMAGE['skin']['edit']     = 'edit.png';
$_IMAGE['skin']['freeze']   = 'freeze.png';
$_IMAGE['skin']['unfreeze'] = 'unfreeze.png';
$_IMAGE['skin']['diff']     = 'diff.png';
$_IMAGE['skin']['upload']   = 'file.png';
$_IMAGE['skin']['copy']     = 'copy.png';
$_IMAGE['skin']['rename']   = 'rename.png';
$_IMAGE['skin']['top']      = 'top.png';
$_IMAGE['skin']['list']     = 'list.png';
$_IMAGE['skin']['search']   = 'search.png';
$_IMAGE['skin']['recent']   = 'recentchanges.png';
$_IMAGE['skin']['backup']   = 'backup.png';
$_IMAGE['skin']['help']     = 'help.png';
$_IMAGE['skin']['rss']      = 'rss.png';
$_IMAGE['skin']['rss10']    = & $_IMAGE['skin']['rss'];
$_IMAGE['skin']['rss20']    = 'rss20.png';
$_IMAGE['skin']['rdf']      = 'rdf.png';

function _toolbar($key, $x = 20, $y = 20){
	$lang  = & $GLOBALS['_LANG']['skin'];
	$link  = & $GLOBALS['_LINK'];
	$image = & $GLOBALS['_IMAGE']['skin'];
	if (! isset($lang[$key]) ) { echo 'LANG NOT FOUND';  return FALSE; }
	if (! isset($link[$key]) ) { echo 'LINK NOT FOUND';  return FALSE; }
	if (! isset($image[$key])) { echo 'IMAGE NOT FOUND'; return FALSE; }

	echo '<li style="float: left;width: 50%;"><a class="grey-text text-lighten-4" href="' . $link[$key] . '"><span>'.$lang[$key].'</span>' .
		'<img src="' . IMAGE_DIR . $image[$key] . '" width="' . $x . '" height="' . $y . '" ' .
			'alt="' . $lang[$key] . '" title="' . $lang[$key] . '">' .
		'</a></li>';
	return TRUE;
}
?>

</div>
<?php } // PKWK_SKIN_SHOW_TOOLBAR ?>

<footer class="page-footer">
  <div class="container">
    <div class="row">
      <div class="col l6 s12">
				<h5 class="white-text"><?php echo $page_title ?></h5>
				<?php if ($lastmodified != '') { ?>
				<p>Last-modified: <?php echo $lastmodified ?></p>
				<?php } ?>
				<?php if ($related != '') { ?>
				<h6>Link</h6>
				<ul>
				<?php echo $related ?>
				</ul>
				<?php } ?>
				<p>Site admin: <a class="grey-text text-lighten-4" href="<?php echo $modifierlink ?>"><?php echo $modifier ?></a></p>
      </div>
      <div class="col l4 offset-l2 s12">
        <h5 class="white-text">Action</h5>
        <ul style="width: 100%;">
				<?php _toolbar('top') ?>

<?php if ($is_page) { ?>
 <?php if ($rw) { ?>
	<?php _toolbar('edit') ?>
	<?php if ($is_read && $function_freeze) { ?>
		<?php if (! $is_freeze) { _toolbar('freeze'); } else { _toolbar('unfreeze'); } ?>
	<?php } ?>
 <?php } ?>
 <?php _toolbar('diff') ?>
<?php if ($do_backup) { ?>
	<?php _toolbar('backup') ?>
<?php } ?>
<?php if ($rw) { ?>
	<?php if ((bool)ini_get('file_uploads')) { ?>
		<?php _toolbar('upload') ?>
	<?php } ?>
	<?php _toolbar('copy') ?>
	<?php _toolbar('rename') ?>
<?php } ?>
 <?php _toolbar('reload') ?>
<?php } ?>
 
<?php if ($rw) { ?>
	<?php _toolbar('new') ?>
<?php } ?>
 <?php _toolbar('list')   ?>
 <?php _toolbar('search') ?>
 <?php _toolbar('recent') ?>
 <?php _toolbar('help') ?>
 <?php _toolbar('rss10', 36, 14) ?>
        </ul>
      </div>
    </div>
  </div>
  <div class="footer-copyright">
    <div class="container center">
			<?php echo S_COPYRIGHT ?>.
 			Powered by PHP <?php echo PHP_VERSION ?>. HTML convert time: <?php echo elapsedtime() ?> sec.
    </div>
  </div>
</footer>
</body>
</html>
