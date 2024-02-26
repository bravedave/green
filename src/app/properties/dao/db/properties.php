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

$dbc = \sys::dbCheck('properties');

$dbc->defineField('address_street', 'varchar', 100);
$dbc->defineField('street_index', 'varchar');
$dbc->defineField('google_index', 'varchar', 100);
$dbc->defineField('address_suburb', 'varchar');
$dbc->defineField('address_state', 'varchar', 20);
$dbc->defineField('address_postcode', 'varchar', 4);
$dbc->defineField('description_type', 'varchar');
$dbc->defineField('description_beds', 'varchar', 10);
$dbc->defineField('description_bath', 'tinyint');
$dbc->defineField('description_car', 'tinyint');
$dbc->defineField('forrent', 'tinyint');
$dbc->defineField('people_id', 'bigint');
$dbc->defineField('options', 'mediumtext');
$dbc->defineField('created', 'datetime');
$dbc->defineField('updated', 'datetime');
$dbc->check();
