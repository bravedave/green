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

$dbc = \sys::dbCheck( 'users');

$dbc->defineField( 'name', 'varchar', 100);
$dbc->defineField( 'email', 'varchar', 100);
$dbc->defineField( 'mobile', 'varchar');
$dbc->defineField( 'password', 'varchar');
$dbc->defineField( 'group', 'varchar');
$dbc->defineField( 'admin', 'tinyint');
$dbc->defineField( 'active', 'tinyint');
$dbc->defineField( 'mail_type', 'varchar');
$dbc->defineField( 'mail_server', 'varchar');
$dbc->defineField( 'mail_account', 'varchar');
$dbc->defineField( 'mail_password', 'varchar');
$dbc->defineField( 'birthdate', 'date');
$dbc->defineField( 'start_date', 'date');
$dbc->defineField( 'created', 'datetime');
$dbc->defineField( 'updated', 'datetime');

$dbc->check();
