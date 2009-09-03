#!/usr/bin/env php
<?php
//
// Created on: <01-Sep-2009 10:00:00 xrow>
//
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.3
// BUILD VERSION: 23650
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//

require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "xrow CDN Shell script\n" .
                                                        "Allows to handle a Cloud Distribution Network\n" .
                                                        "\n" .
                                                        "./extension/xrowcdn/bin/xrowcdn.php --update=distribution|database|all --clear=namespace --since=1970-01-01T00:00:00 --clear-all" ),
                                     'use-session' => false,
                                     'use-modules' => false,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[update:][clear:][clear-all][show-time][since:]",
                                "",
                                array( 'update'    => 'Updates either distribution, database or all',
                                       'clear'     => 'Clears the bucket if provided',
                                       'show-time' => 'Shows the time of the last updates',
                                       'clear-all' => 'Clears all available buckets',
                                       'since'     => 'Allows to update since a special time, format: YYYY-MM-DDTHH:MM:SS', ) );
$sys = eZSys::instance();

$script->initialize();

$cli->output( "Running xrowCDN Shell Script..." );

$action = false;
if ( $options['show-time'] )
{
    $time = xrowCDN::getLatestUpdateDistribution();
    $cli->output( "Last distribution update was " . $time->format( DateTime::ISO8601 ) );
    $time = xrowCDN::getLatestUpdateDatabase();
    $cli->output( "Last database update was " . $time->format( DateTime::ISO8601 ) );
    $script->shutdown();
}
if ( $options['clear'] )
{
	$action = true;
	$cli->output( "Trying to clear the namespace'" . $options['clear'] . "'..." );
	
	$cdn = xrowCDN::getInstance( );
    xrowCDN::clean( $options['clear'] );
    $newtime = new DateTime( '1970-01-01T00:00:00' );
    xrowCDN::setLatestDistributionUpdate();( $newtime );
    xrowCDN::setLatestDatabaseUpdate();( $newtime );
	$cli->output( "Cleaning the namespace '" . $options['clear'] . "' finished..." );
}

if ( $options['clear-all'] )
{
	$action = true;
    $cli->output( "Clearing all files from all buckets..." );
    xrowCDN::cleanAll( );
}

if ( $options['since'] AND $options['update'] )
{
    $action = true;
    $update_db = false;
    $update_di = false;
    
    if( in_array( $options['update'], array( 'database', 'distribution', 'all' )) )
    {
        $since = new DateTime( $options['since'] );
        $newtime = new DateTime();
        if( $options['update'] == "distribution" )
        {
        	$update_db = false;
            $update_di = true;
        }
        if( $options['update'] == "database" )
        {
            $update_db = true;
            $update_di = false;
        }
        if( $options['update'] == "all" )
        {
            $update_db = true;
            $update_di = true;
        }
    }
    else
    {
        $cli->output( "Unknown update procedure, choose from these: distribution, database or all." );
        $script->shutdown( 1 );
    }
    
    $cli->output( "Updating '". $options['update'] . "' since " . $options['since'] . "." );
    
    if( $update_di )
    {
    	$cli->output( "Trying to update Distribution files..." );
	    xrowCDN::updateDistributionFiles( $since );
	    xrowCDN::setLatestDistributionUpdate( $newtime );
    }
    if( $update_db )
    {
    	$cli->output( "Trying to update Database files..." );
	    xrowCDN::updateDatabaseFiles( $since2 );
	    xrowCDN::setLatestDatabaseUpdate( $newtime );
    }
} // Update AND Since

if ( $options['update'] AND !$options['since'] )
{
    $action = true;
    $update_db = false;
    $update_di = false;
    
    if( in_array( $options['update'], array( 'database', 'distribution', 'all' )) )
    {
        if( $options['update'] == "distribution" )
        {
            $update_db = false;
            $update_di = true;
        }
        if( $options['update'] == "database" )
        {
            $update_db = true;
            $update_di = false;
        }
        if( $options['update'] == "all" )
        {
            $update_db = true;
            $update_di = true;
        }
    }
    else
    {
        $cli->output( "Unknown update procedure, choose from these: distribution, database or all." );
        $script->shutdown( 1 );
    }
    
    $cli->output( "Updating '". $options['update'] . "' ..." );
    
    if( $update_di )
    {
    	$newtime = new DateTime();
    	$since = xrowCDN::getLatestUpdateDistribution();
        $cli->output( "Trying to update Distribution files since " . $since->format( DateTime::ISO8601 ) . "..." );
        xrowCDN::updateDistributionFiles( $since );
        xrowCDN::setLatestDistributionUpdate( $newtime );
    }
    if( $update_db )
    {
    	$newtime = new DateTime();
    	$since2 = xrowCDN::getLatestUpdateDatabase();
        $cli->output( "Trying to update Database files since " . $since2->format( DateTime::ISO8601 ) . "..." );
        xrowCDN::updateDatabaseFiles( $since2 );
        xrowCDN::setLatestDatabaseUpdate( $newtime );
    }
} // Update AND Since


if ( !$action )
{
    $cli->output( "Default: Update of distribution and database files." );
    
    $cdn = xrowCDN::getInstance( );

    $newtime = new DateTime();
    $since = xrowCDN::getLatestUpdateDistribution();
    $cli->output( "Trying to update Distribution files..." );
    xrowCDN::updateDistributionFiles( $since );
    xrowCDN::setLatestDistributionUpdate( $newtime );
    
    $since2 = xrowCDN::getLatestUpdateDatabase();
    $cli->output( "Trying to update Database files..." );
    xrowCDN::updateDatabaseFiles( $since2 );
    xrowCDN::setLatestDatabaseUpdate( $newtime );
    
}

$script->shutdown();

?>
