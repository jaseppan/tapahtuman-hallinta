<?php
function random($len) {
    $bytes = openssl_random_pseudo_bytes($len);
    $hex   = bin2hex($bytes);
    return $hex;
}
?>
