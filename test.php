<?php
// echo password_hash('secret', PASSWORD_DEFAULT, array('cost' => 12)); // sto god je veci broj na kraju, to je funkcija sporija i bolja ono password default je algoritam sa sajta
echo password_hash('secret', PASSWORD_BCRYPT, array('cost' => 12)); // ovaj bcrypt je blowfish

phpinfo();
?>