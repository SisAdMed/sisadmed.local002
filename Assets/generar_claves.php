<?php
require_once __DIR__ . '/vendor/autoload.php';

use Minishlink\WebPush\VAPID;

$keys = VAPID::createVapidKeys();

echo "=== TUS CLAVES VAPID PARA SISADMED ===\n\n";
echo "Public Key:\n" . $keys['publicKey'] . "\n\n";
echo "Private Key:\n" . $keys['privateKey'] . "\n\n";