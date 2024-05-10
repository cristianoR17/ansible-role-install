#!/usr/bin/php -q
<?php
header('Content-Type: application/json');


$nr_proc=system("pgrep -f '/var/www/comunix/websockets_keep.php 1704018 ' | wc -l");
echo $nr_proc."\n";


?>
