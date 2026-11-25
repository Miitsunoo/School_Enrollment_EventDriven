<?php
error_reporting(E_ALL & ~E_DEPRECATED);

if (!function_exists('mysql_connect')) {
	function mysql_connect($server = null, $username = null, $password = null)
	{
		$GLOBALS['online_enroll_connection'] = mysqli_connect($server, $username, $password);

		if (!$GLOBALS['online_enroll_connection']) {
			trigger_error('Database connection failed: ' . mysqli_connect_error(), E_USER_ERROR);
		}

		return $GLOBALS['online_enroll_connection'];
	}

	function mysql_select_db($database_name, $link_identifier = null)
	{
		$connection = $link_identifier ?: $GLOBALS['online_enroll_connection'];
		$selected = mysqli_select_db($connection, $database_name);

		if (!$selected) {
			trigger_error('Database selection failed: ' . mysqli_error($connection), E_USER_ERROR);
		}

		return $selected;
	}

	function mysql_query($query, $link_identifier = null)
	{
		$connection = $link_identifier ?: $GLOBALS['online_enroll_connection'];
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

mysql_connect('localhost', 'root', '');
mysql_select_db('online_enroll');