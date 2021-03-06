<?php

/**
 * BlueSpice for MediaWiki
 * Description: Adds functionality for business needs
 * Authors: Markus Glaser
 *
 * Copyright (C) 2016 Hallo Welt! GmbH, All rights reserved.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * For further information visit http://bluespice.com
 *
 */
/* Changelog
 */
$wgBlueSpiceExtInfo = array(
	'name' => 'BlueSpice',
	'version' => '2.23.3',
	'status' => 'stable',
	'package' => 'BlueSpice Free', //default value for BS free extensions
	'url' => 'http://bluespice.com',
	'desc' => 'Makes MediaWiki enterprise ready.',
	'author' => array(
		'[http://www.hallowelt.com Hallo Welt! GmbH]',
	)
);

$wgExtensionCredits['other'][] = array(
	'name' => 'BlueSpice',
	'version' => $wgBlueSpiceExtInfo['version'] . ' (' . $wgBlueSpiceExtInfo['status'] . ')',
	'description' => $wgBlueSpiceExtInfo['desc'],
	'author' => $wgBlueSpiceExtInfo['author'],
	'url' => $wgBlueSpiceExtInfo['url'],
);

$wgFooterIcons['poweredby']['bluespice'] = array(
	"src" => "$wgScriptPath/extensions/BlueSpiceFoundation/resources/bluespice/images/bs-poweredby_bluespice_88x31.png",
	"url" => "http://bluespice.com",
	"alt" => "Powered by BlueSpice",
);

if ( is_readable( __DIR__ . '/vendor/autoload.php' ) ) {
	include_once( __DIR__ . '/vendor/autoload.php' );
}

require_once( __DIR__."/includes/AutoLoader.php");
require_once( __DIR__."/includes/Defines.php" );
require_once( __DIR__."/includes/DefaultSettings.php" );
require_once( __DIR__."/resources/Resources.php");

$wgAjaxExportList[] = 'BsCommonAJAXInterface::getTitleStoreData';
$wgAjaxExportList[] = 'BsCommonAJAXInterface::getNamespaceStoreData';
$wgAjaxExportList[] = 'BsCommonAJAXInterface::getUserStoreData';
$wgAjaxExportList[] = 'BsCommonAJAXInterface::getCategoryStoreData';
$wgAjaxExportList[] = 'BsCommonAJAXInterface::getAsyncCategoryTreeStoreData';
$wgAjaxExportList[] = 'BsCommonAJAXInterface::getFileUrl';

$wgAPIModules['bs-filebackend-store'] = 'BSApiFileBackendStore';
$wgAPIModules['bs-user-store'] = 'BSApiUserStore';
$wgAPIModules['bs-adminuser-store'] = 'BSApiAdminUserStore';
$wgAPIModules['bs-group-store'] = 'BSApiGroupStore';
$wgAPIModules['bs-interwiki-store'] = 'BSApiInterwikiStore';
$wgAPIModules['bs-wikipage-tasks'] = 'BSApiWikiPageTasks';
$wgAPIModules['bs-wikipage-store'] = 'BSApiWikiPageStore';
$wgAPIModules['bs-titlequery-store'] = 'BSApiTitleQueryStore';
$wgAPIModules['bs-ping-tasks'] = 'BSApiPingTasks';

//I18N MW1.23+
$wgMessagesDirs['BlueSpice'] = __DIR__ . '/i18n/core';
$wgMessagesDirs['BlueSpiceCredits'] = __DIR__ . '/i18n/credits';
$wgMessagesDirs['BlueSpiceDiagnostics'] = __DIR__ . '/i18n/diagnostics';
$wgMessagesDirs['BlueSpice.ExtJS'] = __DIR__ . '/i18n/extjs';
$wgMessagesDirs['BlueSpice.ExtJS.Portal'] = __DIR__ . '/i18n/extjs-portal';
$wgMessagesDirs['BlueSpice.Deferred'] = __DIR__ . '/i18n/deferred';
$wgMessagesDirs['Validator'] = __DIR__ . '/i18n/validator';
$wgMessagesDirs['Notifications'] = __DIR__ . '/i18n/notifications';
$wgMessagesDirs['BlueSpice.API'] = __DIR__ . '/i18n/api';

//I18N Backwards compatibility
$wgExtensionMessagesFiles += array(
	'DiagnosticsAlias' => __DIR__."/languages/BlueSpice.Diagnostics.alias.php",
	'CreditsAlias' => __DIR__."/languages/BlueSpice.Credits.alias.php"
);

#$wgSpecialPages['Diagnostics'] = 'SpecialDiagnostics';
$wgSpecialPages['SpecialCredits'] = 'SpecialCredits';

if( !isset( $GLOBALS['wgParamDefinitions'] ) ) {
	$GLOBALS['wgParamDefinitions'] = array();
}

$GLOBALS['wgParamDefinitions'] += array(
	'titlelist' => array(
		'definition' => 'BSTitleListParam',
		'string-parser' => 'BSTitleParser',
		'validator' => 'BSTitleValidator',
	),
	'namespacelist' => array(
		'definition' => 'BSNamespaceListParam',
		'string-parser' => 'BSNamespaceParser',
		'validator' => 'BSNamespaceValidator',
	)
	//TODO:
	//'title', 'category', 'user', 'usergroup'
	//'categorylist', 'userlist', 'usergrouplist'
);

// Register hooks
require_once( 'BlueSpice.hooks.php' );
//Setup

$wgExtensionFunctions[] = 'BsCoreHooks::setup';

// initalise BlueSpice as first extension in a fully initialised environment
array_unshift(
	$wgExtensionFunctions,
	'BsCore::doInitialise'
);
