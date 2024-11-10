<?php
class conn extends mysqli
{
	function __construct()
	{
		parent::__construct("junction.proxy.rlwy.net", "root", "ruWRVbUxmnvKTYWmqqoZJAIbxKGmrSWE", "railway", 51007);
		if (mysqli_connect_error()) {
			die("Error de conexión: " . mysqli_connect_error());
		}
	}
}
$conn = new conn();
