<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace dao;

$dbc = \sys::dbCheck( 'property_diary');

$dbc->defineField( 'created', 'datetime' );
$dbc->defineField( 'date', 'date' );
$dbc->defineField( 'property_id', 'bigint');
$dbc->defineField( 'people_id', 'bigint');
$dbc->defineField( 'event', 'varchar' );
$dbc->defineField( 'event_type', 'varchar', 10 );
$dbc->defineField( 'subject', 'varchar', 60);
$dbc->defineField( 'comments', 'text' );
$dbc->defineField( 'user_id', 'bigint');
$dbc->defineField( 'updated', 'datetime' );
$dbc->defineField( 'update_user_id', 'bigint');

$dbc->check();