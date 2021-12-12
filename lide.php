<?php
require_once('./includes/db_connect.inc.php');
require_once('./includes/functions.php');

$tableOrder = filter_input(INPUT_GET, 'order', FILTER_SANITIZE_STRING);

$query = 'SELECT employee.employee_id, employee.name, employee.surname, employee.job, room.name AS room_name, room.phone FROM employee LEFT JOIN room ON employee.room=room.room_id';
switch ($tableOrder) {
    case null:
        break;
    case 'name_asc':
        $query .= ' ORDER BY surname ASC, name ASC';
        break;
    case 'name_desc':
        $query .= ' ORDER BY surname DESC, name DESC';
        break;
    case 'room_asc':
        $query .= ' ORDER BY room ASC';
        break;
    case 'room_desc':
        $query .= ' ORDER BY room DESC';
        break;
    case 'phone_asc':
        $query .= ' ORDER BY phone ASC';
        break;
    case 'phone_desc':
        $query .= ' ORDER BY phone DESC';
        break;
    case 'job_asc':
        $query .= ' ORDER BY job ASC';
        break;
    case 'job_desc':
        $query .= ' ORDER BY job DESC';
        break;
    default:
        http_response_code(400);
        break;
}
$stmt = $pdo->query($query);
?>
<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <title>Seznam zaměstnanců</title>
</head>

<body class="container">
    <h1>Seznam zaměstnanců</h1>
    <?php
    if ($stmt->rowCount()) {
        echo '<table class="table table-striped table-hover">';

        echo '<thead><tr><th>Jméno ';
        echo addOrder('./lide.php', $tableOrder, 'name');

        echo '</th><th>Místnost ';
        echo addOrder('./lide.php', $tableOrder, 'room');

        echo '</th><th>Telefon ';
        echo addOrder('./lide.php', $tableOrder, 'phone');

        echo '</th><th>Pozice ';
        echo addOrder('./lide.php', $tableOrder, 'job');

        echo '</th></tr></thead><tbody>';

        foreach ($stmt as $row) {
            echo '<tr>';
            echo '<td><a href="clovek.php?employee_id=' . checkValue($row['employee_id'], 'int') . '">' . checkValue($row['surname']) . ' ' . checkValue($row['name']) . '</a></td>';
            echo '<td>' . checkValue($row['room_name']) . '</td>';
            echo '<td>' . checkValue($row['phone']) . '</td>';
            echo '<td>' . checkValue($row['job']) . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<div>Žádné místnosti</div>';
    }
    unset($stmt);
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>