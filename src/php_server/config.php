<?php

$client_id = 1851;
$client_key = 'oBVbNt7IZehZGR99rvq8d6RZ1DM=';

class DB {
    private static $users = [
        [
            "id" => "ccccccfctnin",
            "username" => "user@prueba",
            "password" => "1234"
        ]
    ];

    function get($id) {
        $result = null;

        foreach(self::$users as $user) {
            if($user["id"] == $id) {
                $result = $user;
            }
        }

        return $result;
    }
}
?>
