<?php
/**
 * Libre - Modern version of MonoBook with fresh look and many usability
 * improvements.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Skins
 */

$GLOBALS['wgExtensionCredits']['skin'][] = array(
	'path' => __FILE__,
	'name' => 'Libre',
	'namemsg' => 'skinname-libre',
	'descriptionmsg' => 'libre-skin-desc',
	'url' => 'https://librewiki.net/wiki/Skin:Libre',
	'author' => array( '이츠레아' ),
	'license-name' => 'GPLv2+',
	'version' => '0.1',
);

// Register files
$GLOBALS['wgAutoloadClasses']['SkinLibre'] = __DIR__ . '/SkinLibre.php';
$GLOBALS['wgAutoloadClasses']['LibreTemplate'] = __DIR__ . '/LibreTemplate.php';
$GLOBALS['wgMessagesDirs']['Libre'] = __DIR__ . '/i18n';

// Register skin
$GLOBALS['wgValidSkinNames']['libre'] = 'Libre';

// Register config
$GLOBALS['wgConfigRegistry']['libre'] = 'GlobalVarConfig::newInstance';

// Configuration options
/**
 * Search form look.
 *  - true = use an icon search button
 *  - false = use Go & Search buttons
 */
$GLOBALS['wgLibreUseSimpleSearch'] = true;

/**
 * Watch and unwatch as an icon rather than a link.
 *  - true = use an icon watch/unwatch button
 *  - false = use watch/unwatch text link
 */
$GLOBALS['wgLibreUseIconWatch'] = true;

// Register modules
$GLOBALS['wgResourceModules']['skins.libre.styles'] = array(
	'styles' => array(
		'bootstrap/css/bootstrap.css' => array ( 'media' => 'all' ),
		'libre.css' => array ( 'media' => 'all' ),
		'darklibre.css' => array ( 'media' => 'all' ),
	),
	'remoteSkinPath' => 'Libre',
	'localBasePath' => __DIR__,
);

$GLOBALS['wgResourceModules']['skins.libre.js'] = array(
	'scripts' => array(
		'js/jquery.min.js',
		'js/collapsibleTabs.js',
		'bootstrap/js/bootstrap.min.js',
		'js/tocmove.js',
		'js/libre.js',
		'js/liveRecents.js',
		'js/alertmsg.js',
		'js/jquery.cookie.js',
		'js/cite.js',
		'js/scroll.js',
		'js/darklibre.js',
	),
	'position' => 'bottom',
	'dependencies' => array(
		'jquery.throttle-debounce',
		'jquery.tabIndex',
	),
	'remoteSkinPath' => 'Libre',
	'localBasePath' => __DIR__,
);

// Apply module customizations
$GLOBALS['wgResourceModuleSkinStyles']['libre'] = array(
);

