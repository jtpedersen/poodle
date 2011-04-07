<?php


function cell($s) {
    echo "<td>" . $s . "</td>\n";
 }

function cell_h($s) {
    echo "<th>" . $s . "</th>\n";
 }

function create_header($title) {
return "
<html>
<head>
<title>$title</title>
</head>
<body>
";
}

function create_footer() {
    return "</body>\n<html>\n";
}

function create_poodle() {
    return '<img src="./images/poodle.png" alt="no such poodle" />';
}

function create_unicornpoodle() {
        return '<img src="./images/unicorn.jpg" alt="no such poodle" />';
}

function get_dbconnection() {
    $tmp = pg_connect("dbname=poodle user=poodle") or die("Could not connect");
    return $tmp;
}

function pizza_adder($orderid) {
    $str=<<<EOT
        <form name="input" action="index.php?id=$orderid" method="post">
        <table>
        <tr>
        <td>username:</td>
        <td><input type="text" name="username"/> </td>
        </tr>

        <tr>
        <td>pizza id:</td>
        <td><input type="text" name="pizza_id"/> (fx 12a)</td>
        </tr>
        <tr>
        <td>comment:</td>
        <td><input type="text" name="comment"/> (fx "please draw a unicorn on the box")</td>
        </tr>
        </table>
        <input type="submit" name="ADD" value="Submit" />
        </form>

EOT;
    return $str;
}

function clean_str($s) {
    //TODO escape ;less for \'
    return htmlspecialchars(pg_escape_string($s));
}

function edit_pizza($row, $poodle_id) {
    $checked = $row[paid]=='t' ? "checked" : "";
    $str=<<<EOT
        <tr>
        <form name="input" action="index.php?id=$poodle_id" method="post">
        <input type="hidden" name="pid" value="$row[id]" />
        <td><input type="text" name="username" value="$row[username]"/> </td>
        <td><input type="text" name="pizza_id" value="$row[pizza_id]"/> </td>
        <td><input type="text" name="comment" value="$row[comment]"/> </td>
        <td><input type="text" name="price" value="$row[price]"/> </td>
        <!--- TODO onclick submit --->
        <td><input type="checkbox" name="paid" $checked /> </td>
        <td> <input type="submit" name="EDIT" value="edit" /> </td>
        <td> <input type="submit" name="DELETE" value="delete" /> </td>
        </form>
        </tr>
EOT;
    return $str;
}

function check_error($res) {
    if (!$res) {
        echo "errorhandling";
        echo create_unicornpoodle();
    }
}

function create_form() {
    $conn = get_dbconnection();
    $res = pg_prepare($conn, "create", "SELECT name, id FROM pizza_place ORDER BY id"); 
    check_error($res);
    $res = pg_execute($conn, "create", array());

    $str = '<form name="input" action="create.php" method="post">
            pizza place: <select name="pizza_place">';

    while ($row = pg_fetch_assoc($res)) {
        $val = $row['id'];
        $name = $row['name'];
        $str .= "<option value=\"$val\">$name</option>";
    }

    $str .= "</select> <br />";

    $str .= '
      collector: <input type="text" name="collector" />
        <br />
      driver: <input type="text" name="driver" />
        <br />
        <input type="submit" value="Submit" />
      </form>';

    pg_close($conn);

    return $str;
}


function pizza_place($conn, $id) {
    $res = pg_prepare($conn, "place", "SELECT * FROM pizza_place WHERE id=$1");
    check_error($res);
    $res = pg_execute($conn, "place", array($id));
    check_error($res);
    $row = pg_fetch_assoc($res);
    check_error($row);
    $str = '<a target="_blank" href="' . $row['url'] . '" >' . $row['name'] . '</a><br />';
    //TODO use a logo image some how
    $str .= 'Call them at ' . $row['phone_1'];
    $phone_2 = $row['phone_2'];
    if ($phone_2 != NULL)
        $str .= " or at $phone_2";
    $str .= ' <br /> <a target="_blank" href="' . $row['catalog_url'] . '" > se menuen </a>'; 
    return $str;
}

function get_paid($conn, $order_id) {

    $res = pg_prepare($conn, "paid", "SELECT SUM(price) FROM pizza WHERE order_id=$1 AND paid");
    check_error($res);
    $res = pg_execute($conn, "paid", array($order_id));
    check_error($res);
    $row = pg_fetch_row($res);
    check_error($row);
    return $row[0];

}


function get_total($conn, $order_id) {

    $res = pg_prepare($conn, "total", "SELECT SUM(price) FROM pizza WHERE order_id=$1");
    check_error($res);
    $res = pg_execute($conn, "total", array($order_id));
    check_error($res);
    $row = pg_fetch_row($res);
    check_error($row);
    return $row[0];

}



?>