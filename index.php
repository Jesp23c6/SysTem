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

    $db = new SysTem();

    $answer = $db->get_row("cars", "1", "");

    var_dump($answer);
?>

    
</body>
</html>