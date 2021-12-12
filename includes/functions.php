<?php
function checkValue($value, $type = 'text')
{
    if ($type == 'text') {
        $value = htmlspecialchars($value);
    } elseif ($type == 'int') {
        $value = filter_var($value, FILTER_VALIDATE_INT);
        if ($value === false && $value !== 0) {
            $value = '';
        }
    }

    return $value;
}

function addOrder($href, $tableOrder, $order)
{
    $value = '<a href="' . $href;

    switch ($tableOrder) {
        case $order . '_asc':
            $value .= '?order=' . $order . '_desc"><i class="bi bi-arrow-up">';
            break;
        case $order . '_desc':
            $value .= '"><i class="bi bi-arrow-down">';
            break;
        default:
            $value .= '?order=' . $order . '_asc"><i class="bi bi-arrow-down-up">';
            break;
    }

    $value .= '</i></a>';

    return $value;
}
