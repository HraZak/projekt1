<?php
require_once('./includes/db_connect.inc.php');
require_once('./includes/functions.php');

$tableOrder = filter_input(INPUT_GET, 'order', FILTER_SANITIZE_STRING);

$query = 'SELECT room_id, no, name, phone FROM room';
switch ($tableOrder) {
    case null:
        break;
    case 'name_asc':
        $query .= ' ORDER BY name ASC';
        break;
    case 'name_desc':
        $query .= ' ORDER BY name DESC';
        break;
    case 'no_asc':
        $query .= ' ORDER BY no ASC';
        break;
    case 'no_desc':
        $query .= ' ORDER BY no DESC';
        break;
    case 'phone_asc':
        $query .= ' ORDER BY phone ASC';
        break;
    case 'phone_desc':
        $query .= ' ORDER BY phone DESC';
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
    <title>Seznam místností</title>
</head>

<body class="container">
    <h1>Seznam místností</h1>
    <?php
    if ($stmt->rowCount()) {
        echo '<table class="table table-striped table-hover">';

        echo '<thead><tr><th>Název ';
        echo addOrder('./mistnosti.php', $tableOrder, 'name');

        echo '</th><th>Číslo ';
        echo addOrder('./mistnosti.php', $tableOrder, 'no');

        echo '</th><th>Telefon ';
        echo addOrder('./mistnosti.php', $tableOrder, 'phone');

        echo '</th></tr></thead><tbody>';

        foreach ($stmt as $row) {
            echo '<tr>';
            echo '<td><a href="mistnost.php?room_id=' . checkValue($row['room_id'], 'int') . '">' . checkValue($row['name']) . '</a></td>';
            echo '<td>' . checkValue($row['no']) . '</td>';
            echo '<td>' . checkValue($row['phone']) . '</td>';
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