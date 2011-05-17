<?php
require_once 'functions.php';

function json_elem($key, $val, $escape=true) {
  if ($escape) {
    $val =  "\"$val\"";
  }
  echo '"' . $key .'" : ' . $val . ',' . "\n";
}

$id = clean_str($_GET['id']);

$conn = get_dbconnection();

$res = pg_prepare($conn, "query", "SELECT * FROM pizza_order WHERE user_uuid=$1 OR admin_uuid=$1");
$res = pg_execute($conn, "query", array($id));
$row = pg_fetch_assoc($res);

if (!$row)
  return "no such poodle";


echo  "[{";
json_elem("driver", $row['driver']);
json_elem("collector", $row['collector']);
json_elem("is_admin", $row['admin_uuid'] == $id);
json_elem("order_id", $row['id'], false);
json_elem("pizza_place", $row['pizza_place'], false);
json_elem("pickup_time", $row['pickup_time'], false);
json_elem("order_time", $row['order_time']);
echo "}]";





?>