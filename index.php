<?php 
@include 'functions.php';

if (!isset($_GET['id']) ) {
    echo create_header("Please create a new poodle");
    echo create_form();
    echo create_footer();
    return;
}



echo create_header("poodle fun");


$conn = pg_connect("dbname=poodle user=poodle") or die("Could not connect");
$id = clean_str($_GET['id']);
$res = pg_prepare($conn, "query", "SELECT * FROM pizza_order WHERE user_uuid=$1 OR admin_uuid=$1");
$res = pg_execute($conn, "query", array($id));
$row = pg_fetch_assoc($res);
if( ! $row ) {
    echo "no such poodle";
    echo create_poodle();
    echo create_unicornpoodle();
    create_footer();
    return;
}

$driver = $row['driver'];
$collector = $row['collector'];
$is_admin = $row['admin_uuid'] == $id;
$order_id = $row['id'];



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
$res = pg_execute($conn, "pizzas", array($order_id));

if ($is_admin) {
    echo "<h1>Administer order</h1>";
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

    echo "</table>";
} else {
    echo "<h1>See order</h1>";
    echo "<table>\n";
    
    while ($row = pg_fetch_assoc($res)) {
        echo "<tr>\n";
        cell($row['username']);
        cell($row['pizza_id']);
        cell($row['comment']);
        cell($row['price']);
        cell( $row['paid'] ? "paid" : "not paid");
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