<?php

if( !defined( 'DVWA_WEB_PAGE_TO_ROOT' ) ) {
	die( 'DVWA System error- WEB_PAGE_TO_ROOT undefined' );
	exit;
}

if (!file_exists(DVWA_WEB_PAGE_TO_ROOT . 'config/config.inc.php')) {
	die ("DVWA System error - config file not found. Copy config/config.inc.php.dist to config/config.inc.php and configure to your environment.");
}

// Include configs
require_once DVWA_WEB_PAGE_TO_ROOT . 'config/config.inc.php';

// Declare the $html variable
if( !isset( $html ) ) {
	$html = "";
}

// Valid security levels
$security_levels = array('low', 'medium', 'high', 'impossible');
if( !isset( $_COOKIE[ 'security' ] ) || !in_array( $_COOKIE[ 'security' ], $security_levels ) ) {
	// Set security cookie to impossible if no cookie exists
	if( in_array( $_DVWA[ 'default_security_level' ], $security_levels) ) {
		dvwaSecurityLevelSet( $_DVWA[ 'default_security_level' ] );
	} else {
		dvwaSecurityLevelSet( 'impossible' );
	}
}

// This will setup the session cookie based on
// the security level.

if (dvwaSecurityLevelGet() == 'impossible') {
	$httponly = true;
	$samesite = true;
}
else {
	$httponly = false;
	$samesite = false;
}

$maxlifetime = 86400;
$secure = false;
$domain = parse_url($_SERVER['HTTP_HOST'], PHP_URL_HOST);

session_set_cookie_params([
	'lifetime' => $maxlifetime,
	'path' => '/',
	'domain' => $domain,
	'secure' => $secure,
	'httponly' => $httponly,
	'samesite' => $samesite
]);
session_start();

if (!array_key_exists ("default_locale", $_DVWA)) {
	$_DVWA[ 'default_locale' ] = "en";
}

dvwaLocaleSet( $_DVWA[ 'default_locale' ] );

// DVWA version
function dvwaVersionGet() {
	return '1.10 *Development*';
}

// DVWA release date
function dvwaReleaseDateGet() {
	return '2015-10-08';
}


// Start session functions --

function &dvwaSessionGrab() {
	if( !isset( $_SESSION[ 'dvwa' ] ) ) {
		$_SESSION[ 'dvwa' ] = array();
	}
	return $_SESSION[ 'dvwa' ];
}


function dvwaPageStartup( $pActions ) {
	if (in_array('authenticated', $pActions)) {
		if( !dvwaIsLoggedIn()) {
			dvwaRedirect( DVWA_WEB_PAGE_TO_ROOT . 'login.php' );
		}
	}
}

function dvwaLogin( $pUsername ) {
	$dvwaSession =& dvwaSessionGrab();
	$dvwaSession[ 'username' ] = $pUsername;
}


function dvwaIsLoggedIn() {
	global $_DVWA;

... (536 lignes restantes)