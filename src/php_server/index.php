<?php
require_once 'Auth/Yubico.php';
include "config.php";
require_once 'lib/limonade.php';

dispatch_post('/validate', 'single_validation');
dispatch_post('/validatepass', 'double_validation');
dispatch_post('/validateuser', 'user_validation');

function dataPost($name) {
    return filter_input(INPUT_POST, $name, FILTER_SANITIZE_SPECIAL_CHARS);
}

function get_identity($yubikey) {
    global $client_id;
    global $client_key;
    $error = null;
    $identity = null;

    $ret = Auth_Yubico::parsePasswordOTP($yubikey);
    if($ret) {
        $key = htmlspecialchars($ret['otp']);
        $yubi = new Auth_Yubico($client_id, $client_key);
        $auth = $yubi->verify($key);
        if(PEAR::isError($auth)) {
            $error = 'Not valid yubikey';
        } else {
            $identity = htmlspecialchars($ret['prefix']);
        }
    } else {
        $error = 'Not valid yubikey syntax';
    }
    return [
        "value" => $identity,
        "error" => $error
    ];
}

function single_validation() {
    $response = [ "response" => "unauthorized" ];
    $yubikey = dataPost("yubikey");

    if($yubikey) {
        $identity = get_identity($yubikey);
        if(!$identity["error"] && $identity["value"]) {
            $response = [
                "response" => [
                    "yubikey" => $yubikey,
                    "identity" => $identity["value"]
                ]
            ];
        } else {
            $response = [ "response" => $identity["error"] ];
            status(401);
        }

    } else {
        $response = [ "response" => "Not yubikey" ];
        status(401);
    }
    return json($response);
}

function double_validation() {
    $password = dataPost("password");
    $yubikey = dataPost("yubikey");

    if($yubikey) {
        $identity = get_identity($yubikey);
        if(!$identity["error"] && $identity["value"]) {
            //validate password
            //get user from memory array
            $user = DB::get($identity["value"]);

            if($user) {
                if($user["password"] == $password) {
                    $response = [
                        "response" => [
                            "yubikey" => $yubikey,
                            "identity" => $identity["value"],
                            "user" => $user["username"]
                        ]
                    ];
                } else {
                    $response = [ "response" => "Invalid password" ];
                    status(401);
                }
            } else {
                $response = [ "response" => "User not found" ];
                status(401);
            }

        } else {
            $response = [ "response" => $identity["error"] ];
            status(401);
        }

    } else {
        $response = [ "response" => "Not yubikey" ];
        status(401);
    }
    return json($response);
}

function user_validation() {
    $username = dataPost("user");
    $password = dataPost("password");
    $yubikey = dataPost("yubikey");

    if($yubikey) {
        $identity = get_identity($yubikey);
        if(!$identity["error"] && $identity["value"]) {
            //validate password
            //get user from memory array
            $user = DB::get($identity["value"]);

            if($user && $user["username"] == $username) {
                if($user["password"] == $password) {
                    $response = [
                        "response" => [
                            "yubikey" => $yubikey,
                            "identity" => $identity["value"],
                            "user" => $user["username"]
                        ]
                    ];
                } else {
                    $response = [ "response" => "Invalid password" ];
                    status(401);
                }
            } else {
                $response = [ "response" => "User not found" ];
                status(401);
            }

        } else {
            $response = [ "response" => $identity["error"] ];
            status(401);
        }

    } else {
        $response = [ "response" => "Not yubikey" ];
        status(401);
    }
    return json($response);
}

run();
?>
