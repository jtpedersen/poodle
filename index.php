
<html>
<head>
<title>pizza land</title>
</head>
<body>

<?php 

function cell($s) {
    echo "<td>" . $s . "</td>\n";
 }

function cell_h($s) {
    echo "<th>" . $s . "</th>\n";
 }


if (isset($_GET['id']) ) {
    $conn = pg_connect("dbname=poodle user=poodle") or die("Could not connect");
    $id = pg_escape_string($_GET['id']);
    
    $res = pg_prepare($conn, "query", "SELECT * FROM pizza_order WHERE user_uuid=$1 OR admin_uuid=$1");
    $res = pg_execute($conn, "query", array($id));
    while ($row = pg_fetch_assoc($res)) {
        $driver = $row['driver'];
        $collector = $row['collector'];
        $is_admin = $row['admin_uuid'] == $id;
        $order_id = $row['id'];
    }
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




<form name="input" action="index.php?id=<?=$_GET['id']?>" method="post">
<?php
echo "<table>\n";


    echo "<tr>\n";
    cell_h('username');
    cell_h('pizzaid');
    cell_h('comment');
    echo "</tr>\n";

    echo "<tr>\n";
echo '<td><input type="text" name="username"/> </td>';
echo '<td><input type="text" name="pizzaid"/>  </td>';
echo '<td><input type="text" name="comment"/>  </td>';
    echo "</tr>\n";


echo "</table>\n";


?>
        <input type="submit" value="Submit" />
      </form>



  
      
      </body>
      <html>

<?php
                      pg_close($conn);
?>