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
    $res = pg_prepare($conn, "add", "INSERT INTO pizza(order_id, username, pizza_id, comment) values ($1, $2, $3, $4)");
    $username =clean_str($_POST['username']);
    $pizza_id =clean_str($_POST['pizza_id']);
    $comment =clean_str($_POST['comment']);

    $res = pg_execute($conn, "add", array($order_id, $username, $pizza_id, $comment));
}


if ($is_admin) {
    echo "<h1>Administer order</h1>";
} else {
    echo "<h1>See order</h1>";
}


$res = pg_prepare($conn, "pizzas", "SELECT * FROM pizza WHERE order_id=$1");
$res = pg_execute($conn, "pizzas", array($order_id));
echo "<table>\n";

while ($row = pg_fetch_assoc($res)) {
    echo "<tr>\n";
    cell($row['username']);
    cell($row['pizzaid']);
    cell($row['comment']);
    cell($row['price']);
    cell( $row['paid'] ? "paid" : "not paid");
    echo "</tr>\n";
}

echo "</table>\n";

?>

             <hr />
             <ul>
             <li>
             <?=$driver?> is driving
             </li>
             <li>
             <?=$collector?> is collecting
             </li>
             <li>
</ul>

    <hr />  
  
<?php
    echo pizza_adder($id);
    
    echo create_footer();
pg_close($conn);
?>