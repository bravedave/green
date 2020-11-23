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

$dbc = \sys::dbCheck( 'people');

$dbc->defineField( 'name', 'varchar', 100);
$dbc->defineField( 'mobile', 'varchar');
$dbc->defineField( 'telephone', 'varchar');
$dbc->defineField( 'telephone_business', 'varchar');
$dbc->defineField( 'email', 'varchar', 100);
$dbc->defineField( 'salute', 'varchar', 3);
$dbc->defineField( 'address_street', 'varchar', 100);
$dbc->defineField( 'address_state', 'varchar');
$dbc->defineField( 'address_suburb', 'varchar');
$dbc->defineField( 'address_postcode', 'varchar', 4);
$dbc->defineField( 'postal_address', 'varchar', 100);
$dbc->defineField( 'postal_suburb', 'varchar');
$dbc->defineField( 'postal_postcode', 'varchar', 4);
$dbc->defineField( 'created', 'datetime');
$dbc->defineField( 'updated', 'datetime');

$dbc->check();
