#!/usr/bin/env php -q
<?php
/**
 * @brief		Handle incoming email that has been piped to this script
 * @author		<a href='http://www.invisionpower.com'>Invision Power Services, Inc.</a>
 * @copyright	(c) 2001 - 2016 Invision Power Services, Inc.
 * @license		http://www.invisionpower.com/legal/standards/
 * @package		IPS Community Suite
 * @since		12 June 2013
 * @version		SVN_VERSION_NUMBER
 */

/* Get init.php */
require_once str_replace( 'applications/core/interface/incomingemail/piping.php', '', str_replace( '\\', '/', __FILE__ ) ) . 'init.php';

/**
 * Write the incoming email to a file for debug purposes?
 * Supply a path to the file to write to, filename included.
 */
$writeDebug	= NULL;

/**
 * Read email content from a file for debug purposes?
 * Supply a path to the file to read from.
 */
$readDebug	= NULL;
 
/* Get the email content */
$email	= file_get_contents( ( is_string($readDebug) ) ? $readDebug : 'php://stdin' );

/* Write the debug, if desired.  
	Note that writing files to disk directly in production is discouraged and that this should only be used for debugging purposes. */
if( is_string( $writeDebug ) )
{
	\file_put_contents( $writeDebug, $email );
}

/* Are we attempting to override the "to" value? */
$override	= array();

if ( isset( $_SERVER['argv'][1] ) )
{
	$override['to']	= array( $_SERVER['argv'][1] );
}

/* Parse the email and route */
$incomingEmail = new \IPS\Email\Incoming\Email( $email );
if ( isset( $override['to'] ) )
{
	$incomingEmail->to = $override['to'];
}
$incomingEmail->route();

exit;
