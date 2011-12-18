<?php 
@require_once 'functions.php';

if (isset($_POST['collector']) ) {
    // create the entries in the database
    $conn = get_dbconnection();

    
    $res = pg_prepare($conn, "create", "INSERT INTO pizza_order (admin_uuid, user_uuid, driver, collector, pizza_place, order_time, order_title) VALUES ($1, $2, $3, $4, $5, $6, $7)"); 
    
    $collector = clean_str($_POST['collector']);
    $driver = clean_str($_POST['driver']);
    $pizza_place = clean_str($_POST['pizza_place']);
    $order_time = get_timestamp("17:30");
    $order_title = clean_str($_POST['order_title']);

    $luck = 7;
    do {
        $admin_id = get_poodle_uuid();
        $user_id = get_poodle_uuid();
        $res = pg_execute($conn, "create", array($admin_id, $user_id, $driver, $collector, $pizza_place, $order_time, $order_title));

    } while ($res==false && --$luck > 0);
    
    echo template_header("100% poodle");

    echo "<h2> tonight we dine at </h2>\n";
    echo pizza_place($conn, $pizza_place);
    
    pg_close($conn);
    //show the funny urls
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
             You can access the admin url at <a href="index.php?id=<?=$admin_id?>" > index.php?id=<?=$admin_id?> </a>
             </li>
             <li>
             You can access the user url at <a href="index.php?id=<?=$user_id?>" > index.php?id=<?=$user_id?> </a>
             </li>
             </ul>

<?php

} else {
//show the form
    echo create_header("where do you want to eat today?");
    echo create_form();
?>


<?php
        } // end show form
echo template_footer();
?>


