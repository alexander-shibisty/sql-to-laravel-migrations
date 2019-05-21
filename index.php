<?php

require 'vendor/autoload.php';

const INPUT_DIR = __DIR__.'/input';
const OUTPUT_DIR = __DIR__.'/output';

new \SqlParser\SqlParser(INPUT_DIR, OUTPUT_DIR);