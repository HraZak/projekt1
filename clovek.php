<?php
require_once('./includes/db_connect.inc.php');
require_once('./includes/functions.php');

$state = 'ok';

$employeeId = filter_input(INPUT_GET, 'employee_id', FILTER_VALIDATE_INT);

if (!$employeeId) {
    $state = 'badRequest';
    http_response_code(400);
} else {
    $query = 'SELECT employee.name, employee.surname, employee.job, employee.wage, room.room_id, room.name AS room_name FROM employee LEFT JOIN room ON employee.room=room.room_id WHERE employee.employee_id=?';

    $stmt = $pdo->prepare($query);
    $stmt->execute([$employeeId]);

    if ($stmt->rowCount()) {
        $employee = $stmt->fetch();

        $query = 'SELECT room.room_id, room.name FROM `key` LEFT JOIN room ON `key`.room=room.room_id WHERE `key`.employee=?';

        $stmtkeys = $pdo->prepare($query);
        $stmtkeys->execute([$employeeId]);
    } else {
        $state = 'notFound';
        http_response_code(404);
    }
}

$title = $state == 'ok' ? ('Karta osoby ' . $employee['surname']) : ($state == 'badRequest' ? 'Error: Bad Request' : 'Error: Not Found');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <title><?php echo  $title; ?></title>
</head>

<body class="container">
    <?php
    if ($state == 'ok') {
        echo '<h1>Karta osoby: ' . checkValue($employee['surname']) . '</h1>';

        echo '<dl>';
        echo '<dt>Jméno</dt><dd>' . checkValue($employee['name']) . '</dd>';
        echo '<dt>Příjmení</dt><dd>' . checkValue($employee['surname']) . '</dd>';
        echo '<dt>Pozice</dt><dd>' . checkValue($employee['job']) . '</dd>';
        echo '<dt>Plat</dt><dd>' . checkValue($employee['wage']) . '</dd>';
        echo '<dt>Místnost</dt><dd><a href="./mistnost.php?room_id=' . checkValue($employee['room_id'], 'int') . '">' . checkValue($employee['room_name']) . '</a></dd>';

        if ($stmtkeys->rowCount()) {
            echo '<dt>Klíče</dt>';

            foreach ($stmtkeys as $key) {
                echo '<dd><a href="./mistnost.php?room_id=' . checkValue($key['room_id'], 'int') . '">' . checkValue($key['name']) . '</a></dd>';
            }
        }

        echo '</dl>';
    } else if ($state == 'notFound') {
        echo '<h1>404</h1>';
        echo '<div>Not found<div>';
    } else {
        echo '<h1>400</h1>';
        echo '<div>Bad Request<div>';
    }
    ?>
    <a href="./lide.php"><i class="bi bi-box-arrow-in-left"></i> Zpět na seznam zaměstnanců</a>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>