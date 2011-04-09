<?php
@include 'functions.php';

$request_id = "";
$todays_id = get_todays_poodle_user_id();  
$id = $todays_id;

if (isset($_GET['id'])) {
  $request_id = clean_str($_GET['id']);
  $id = $request_id;
}

$conn = get_dbconnection();
$res = pg_prepare($conn, "query", "SELECT * FROM pizza_order WHERE user_uuid=$1 OR admin_uuid=$1");
$res = pg_execute($conn, "query", array($id));
$row = pg_fetch_assoc($res);

if( !$row ) {
  if ($id == $request_id) {
    echo create_header("no such poodle");

    echo "<h1><a href=\"index.php?id=". $todays_id . "\" />todays poodle is " . $todays_id . "</a></h1>";
    echo "no such poodle";
    echo create_poodle();
    echo create_unicornpoodle();
    create_footer();
    return;
    
  } else {
    echo create_header("where do you want to eat today?");
    echo "<h1><a href=\"index.php?id=". $todays_id . "\" />todays poodle is " . $todays_id . "</a></h1>";

    echo "create a new poodle?";

    echo create_form();
    echo create_footer();
    return;
  }
}

$driver = $row['driver'];
$collector = $row['collector'];
$is_admin = $row['admin_uuid'] == $id;
$order_id = $row['id'];
$pizza_place = $row['pizza_place'];
$pickup_time = $row['pickup_time'];
$order_time = $row['order_time'];


if (isset($_POST['ADD']) ) {
  $username =clean_str($_POST['username']);
  $pizza_id =clean_str($_POST['pizza_id']);
  $comment =clean_str($_POST['comment']);

  $res = pg_prepare($conn, "add", "INSERT INTO pizza(order_id, username, pizza_id, comment) values ($1, $2, $3, $4)");
  check_error($res);
  $res = pg_execute($conn, "add", array($order_id, $username, $pizza_id, $comment));
  check_error($res);

} else if (isset($_POST['EDIT']) ) {
  $username =clean_str($_POST['username']);
  $pizza_id =clean_str($_POST['pizza_id']);
  $comment =clean_str($_POST['comment']);
  $price =clean_str($_POST['price']);
  $paid = ($_POST['paid'] == "") ? "0" : "1";
  //    echo "paid is $paid";
  $pid =clean_str($_POST['pid']);

  $res = pg_prepare($conn, "update", "UPDATE pizza SET username=$1, pizza_id=$2, comment=$3, price=$4, paid=$5  WHERE id=$6");
  check_error($res);
  $res = pg_execute($conn, "update", array($username, $pizza_id, $comment, $price, $paid, $pid));
  check_error($res);

} else if (isset($_POST['DELETE']) ) {
  $pid =clean_str($_POST['pid']);

  $res = pg_prepare($conn, "delete", "DELETE FROM pizza WHERE id=$1");
  check_error($res);
  $res = pg_execute($conn, "delete", array($pid));
  check_error($res);
}



$res = pg_prepare($conn, "pizzas", "SELECT * FROM pizza WHERE order_id=$1 ORDER BY id");
check_error($res);
$res = pg_execute($conn, "pizzas", array($order_id));
check_error($res);

if ($is_admin) {
  echo create_header("poodle master");
  echo "<h1>Administer order</h1>";
  echo pizza_place($conn, $pizza_place);
  echo "<hr />";
  echo "pickup at: " .$pickup_time . "  ".  date("H:i:s", strtotime($pickup_time)) . "<br />";
  echo "ordered at: " .$order_time . "  ".  date("H:i:s", strtotime($order_time))  . "<br />";
  echo "<hr />";
  echo "<table>";
  echo "<tr>";
  cell_h("username");
  cell_h("pizza_id");
  cell_h("comment");
  cell_h("price");
  cell_h( "paid?");

  echo "</tr>";
  while ($row = pg_fetch_assoc($res)) {
    echo edit_pizza($row, $id);
  }
  cell("&nbsp;");
  cell("&nbsp;");
  cell("<b>total</b>");


  cell("<b>" .  get_total($conn, $order_id) . "</b>");

  cell("(" . get_paid($conn, $order_id) . ")");
  cell("&nbsp;");
   



  echo "</table>";
} else {
  echo "Hello";
  echo create_header("let's poodle");
  echo pizza_place($conn, $pizza_place);
  echo "<h1>See order</h1>";
  echo "<table>\n";
  echo "<tr>";
  cell_h("username");
  cell_h("pizza_id");
  cell_h("comment");
  cell_h("price");
  cell_h( "paid?");

  echo "</tr>";

  while ($row = pg_fetch_assoc($res)) {
    echo "<tr>\n";
    cell($row['username']);
    cell($row['pizza_id']);
    cell($row['comment']);
    cell($row['price']);
    cell( $row['paid']=='t' ? "paid" : "not paid");
    echo "</tr>\n";
  }
  echo "</table>\n";
  echo pizza_adder($id);
  echo create_footer();
}


?>

<?php
pg_close($conn);
?>