<?php
$passwords = [
    'Alaa' => 'Loloistheadmin',
    'Maram' => 'loliistheadmin',
    'Test' => 'testtesttest'
];

foreach ($passwords as $user => $password) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    echo "UPDATE spider_passwords SET current_password = '$hash' WHERE spider_name = '$user';<br>";
}
?>