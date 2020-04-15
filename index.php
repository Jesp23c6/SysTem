<?php

include('header.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<?php

    /**
     * Here I call the class SysDB from SysDB.php to use for the get_row function.
     */
    $db = new SysTem\SysDB();

    $get_row = $db->get_row("cars", "1", "");

    echo("get_row method: <br>");
    var_dump($get_row);

    echo("<br><br>get_col method: <br>");
    $get_col = $db->get_col("cars", "ble");

    var_dump($get_col);
?>

    
</body>
</html>