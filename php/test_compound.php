<?php

$b = 0;
$a = (function () use (&$b) { 3; $b = 4; return 7;})();

echo "a: $a; b: $b";