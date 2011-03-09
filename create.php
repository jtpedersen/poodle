
<html>
<head>
<title>create a poodle </title>
</head>
<body>

<?php 

if (isset($_POST['collector']) ) {
    // create the entries in the database
    $conn = pg_connect("dbname=poodle user=poodle") or die("Could not connect");
//    echo "Connected successfully";
    
    $res = pg_prepare($conn, "create", "INSERT INTO pizza_order (admin_uuid, user_uuid, driver, collector) VALUES ($1, $2, $3, $4)"); 
    
    $collector = pg_escape_string($_POST['collector']);
    $driver = pg_escape_string($_POST['driver']);

    do {
        $admin_id = uniqid();
        $user_id = uniqid();
        $res = pg_execute($conn, "create", array($admin_id, $user_id, $driver, $collector));
    } while ($res==false);
    
    pg_close($conn);
    //show the funny urls
    ?>
        <h1>It has been created</h1>
             <hr />
             <ul>
             <li>
             <?=$driver?> is driving
             </li>
             <li>
             <?=$collector?> is collecting
             </li>
             <li>
             You can access the admin url at <a href="index.php?id=<?=$admin_id?>" > index.php?id=<?=$admin_id?> </a>
             </li>
             <li>
             You can access the user url at <a href="index.php?id=<?=$user_id?>" > index.php?id=<?=$user_id?> </a>
             </li>
             </ul>

<?php

} else {
//show the form

?>


<form name="input" action="create.php" method="post">
      collector: <input type="text" name="collector" />
        <br />
      driver: <input type="text" name="driver" />
        <br />

        <input type="submit" value="Submit" />
      </form>

<?php
        } // end show form
?>
      
      </body>
      <html>

