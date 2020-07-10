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

$dbc = \sys::dbCheck( 'bath_list');

$dbc->defineField( 'bath', 'varchar', 10 );
$dbc->defineField( 'description', 'varchar');
$dbc->check();
