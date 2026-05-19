<?php
\ = 'c:/xampp/htdocs/Agrifober/app/Http/Controllers/Admin/DashboardController.php';
\ = file_get_contents(\);
\ = chr(9).chr(123).chr(36).'this}';
\ = chr(9).chr(36).' = [';
\ = '->orderBy(chr(39).'created_at'.chr(39).', ';
\ = '->with(chr(39).'user'.chr(39).')';
\ = chr(9).chr(36).'this->';   // fixes garbled {\}
\ = chr(9).chr(36).'stats = [';
\ = '';
\ = '';
\ = str_replace(\, \, \);
\ = str_replace(\, \, \);
file_put_contents(\, \);
echo 'DONE'.PHP_EOL;