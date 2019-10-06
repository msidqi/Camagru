<?php
$in = fopen('./yourimage.png', "r");
$data = fread($in, 8);
file_put_contents('./1.png', $data . "hello");
fclose($in);
