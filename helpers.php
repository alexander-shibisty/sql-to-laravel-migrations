<?php

function dd(): void {
    echo '<pre>';
        print_r(func_get_args());
    die('</pre>');
}

function addSpaces(int $spaces): string {
    $spacesString = '';

    for($i = 0; $i < $spaces; $i++) {
        $spacesString .= "    ";
    }

    return $spacesString;
}