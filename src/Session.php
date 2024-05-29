<?php

namespace Hive;

class Session {
    public function __construct() {
        session_start();
    }

    // get session variable
    public function getFromSession(string $key): mixed {
        return $_SESSION[$key] ?? null;
    }

    // set session variable
    public function setOnSession(string $key, mixed $value): void {
        $_SESSION[$key] = $value;
    }
}