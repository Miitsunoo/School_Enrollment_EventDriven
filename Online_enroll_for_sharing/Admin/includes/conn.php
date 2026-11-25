<?php
error_reporting(E_ALL & ~E_DEPRECATED);

$GLOBALS['online_enroll_admin_connection'] = mysqli_connect('localhost', 'root', '', 'online_enroll');

if (!$GLOBALS['online_enroll_admin_connection']) {
	trigger_error('Database connection failed: ' . mysqli_connect_error(), E_USER_ERROR);
}

mysqli_set_charset($GLOBALS['online_enroll_admin_connection'], 'utf8');

if (!function_exists('mysql_connect')) {
	function mysql_connect($server = null, $username = null, $password = null)
	{
		if (!$GLOBALS['online_enroll_admin_connection']) {
			trigger_error('Database connection failed', E_USER_ERROR);
		}
		return $GLOBALS['online_enroll_admin_connection'];
	}

	function mysql_select_db($database_name, $link_identifier = null)
	{
		$connection = $link_identifier ?: $GLOBALS['online_enroll_admin_connection'];
		return mysqli_select_db($connection, $database_name);
	}

	function mysql_query($query, $link_identifier = null)
	{
		$connection = $link_identifier ?: $GLOBALS['online_enroll_admin_connection'];
		$result = mysqli_query($connection, $query);
		if ($result === false) {
			trigger_error('Database query failed: ' . mysqli_error($connection), E_USER_ERROR);
		}
		return $result;
	}

	function mysql_fetch_array($result, $result_type = MYSQLI_BOTH)
	{
		return mysqli_fetch_array($result, $result_type);
	}

	function mysql_num_rows($result)
	{
		return mysqli_num_rows($result);
	}
}