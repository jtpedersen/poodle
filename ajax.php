<?php
require_once 'functions.php';

$id = clean_str($_GET['id']);

$conn = get_dbconnection();

$res = pg_prepare($conn, "query", "SELECT * FROM pizza_order WHERE user_uuid=$1 OR admin_uuid=$1");
$res = pg_execute($conn, "query", array($id));
$row = pg_fetch_assoc($res);

if (!$row)
  return "no such poodle";

echo json_encode($row);

?>