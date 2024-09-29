<?php
	class conn extends mysqli{
		function __construct(){
			parent::__construct("srv1627.hstgr.io","u519086947_systempos","Systempos2024*","u519086947_systempos"); 
			if (mysqli_connect_error()) {
				print("error de conexion");
			}
		}
	}
$conn = new conn();
?>