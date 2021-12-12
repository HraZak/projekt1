<?php
require_once('./includes/db_connect.inc.php');
require_once('./includes/functions.php');

$state = 'ok';

$roomId = filter_input(INPUT_GET, 'room_id', FILTER_VALIDATE_INT);

if (!$roomId) {
    $state = 'badRequest';
    http_response_code(400);
} else {
    $query = 'SELECT no, name, phone FROM room WHERE room_id=?';

    $stmt = $pdo->prepare($query);
    $stmt->execute([$roomId]);

    if ($stmt->rowCount()) {
        $room = $stmt->fetch();

        $query = 'SELECT employee_id, name, surname FROM employee WHERE room=?';
        $stmtemployee = $pdo->prepare($query);
        $stmtemployee->execute([$roomId]);

        $query = 'SELECT AVG(wage) AS wage FROM employee WHERE room=?';
        $stmtwage = $pdo->prepare($query);
        $stmtwage->execute([$roomId]);
        $wage = $stmtwage->fetch();

        $query = 'SELECT employee.employee_id, employee.name, employee.surname FROM `key` LEFT JOIN employee on `key`.employee=employee.employee_id WHERE `key`.room=?';
        $stmtkeys = $pdo->prepare($query);
        $stmtkeys->execute([$roomId]);
    } else {
        $state = 'notFound';
        http_response_code(404);
    }
}

$title = $state == 'ok' ? ('Karta místnosti č. ' . $room['no']) : ($state == 'badRequest' ? 'Error: Bad Request' : 'Error: Not Found');
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
        echo '<h1>Místnost č. ' . checkValue($room['no']) . '</h1>';

        echo '<dl>';
        echo '<dt>Číslo</dt><dd>' . checkValue($room['no']) . '</dd>';
        echo '<dt>Název</dt><dd>' . checkValue($room['name']) . '</dd>';
        echo '<dt>Telefon</dt><dd>' .  checkValue($room['phone']) . '</dd>';

        if ($stmtemployee->rowCount()) {
            echo '<dt>Lidé</dt>';

            foreach ($stmtemployee as $employee) {
                echo '<dd><a href="./clovek.php?employee_id=' . checkValue($employee['employee_id'], 'int') . '">' . checkValue($employee['surname']) . ' ' . checkValue($employee['name']) . '</a></dd>';
            }
        }

        if ($wage['wage']) {
            echo '<dt>Průměrná mzda</dt><dd>' .  checkValue($wage['wage']) . '</dd>';
        }

        if ($stmtkeys->rowCount()) {
            echo '<dt>Klíče</dt>';

            foreach ($stmtkeys as $key) {
                echo '<dd><a href="./clovek.php?employee_id=' . checkValue($key['employee_id'], 'int') . '">' . checkValue($key['surname']) . ' ' . checkValue($key['name']) . '</a></dd>';
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
    <a href="./mistnosti.php"><i class="bi bi-box-arrow-in-left"></i> Zpět na seznam místností</a>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>