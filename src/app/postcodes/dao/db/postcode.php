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

$dbc = \sys::dbCheck( 'postcodes');
$dbc->defineField( 'suburb', 'varchar');
$dbc->defineField( 'state', 'varchar');
$dbc->defineField( 'postcode', 'varchar', 4);

$dbc->defineIndex('idx_postcode_suburb', 'suburb' );
$dbc->defineIndex('idx_postcode_postcode', 'postcode' );

$dbc->check();
