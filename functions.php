<?php


function cell($s) {
    echo "<td>" . $s . "</td>\n";
 }

function cell_h($s) {
    echo "<th>" . $s . "</th>\n";
 }

function create_form() {
    return '
<form name="input" action="create.php" method="post">
      collector: <input type="text" name="collector" />
        <br />
      driver: <input type="text" name="driver" />
        <br />

        <input type="submit" value="Submit" />
      </form>
';
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
    return '<img src="poodle.png" alt="no such poodle" />';
}

function create_unicornpoodle() {
        return '<img src="http://www.myunusual.com/Pix/Pets%20Pix/PoodleCuts/unicorn.jpg" alt="no such poodle" />';
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
        <td><input type="text" name="pizzaid"/> (fx 11a)</td>
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


?>