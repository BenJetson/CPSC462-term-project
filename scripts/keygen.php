#!/usr/bin/env php
<?php

require_once './src/includes/autoload.php';

$key = random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES);

echo "\n";
echo "The secret key is:\n";
echo base64_encode($key) . "\n";
echo "\n";
echo "This is key is " . mb_strlen($key, '8bit') . " bytes in length.\n";
echo "\n";
echo "Put this key in your .env file for LOGIN_SECRET.\n";
echo "Copy the whole line, even the final necessary = for base64 padding. \n";
echo "\n";
