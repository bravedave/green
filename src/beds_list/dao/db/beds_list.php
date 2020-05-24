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

$dbc = 'sqlite' == \config::$DB_TYPE ?
	new \dvc\sqlite\dbCheck( $this->db, 'beds_list' ) :
	new \dao\dbCheck( $this->db, 'beds_list' );

$dbc->defineField( 'beds', 'varchar', 10 );
$dbc->defineField( 'description', 'varchar', 50 );

$dbc->check();
