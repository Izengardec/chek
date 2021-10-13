<?php
// Открываем на чтение поток ввода
$f = fopen('php://input', 'r');

// Получаем содержимое потока
$data = stream_get_contents($f);

if ($data) {
    // Код обработки

    print_r(json_decode($data));
}
?>

