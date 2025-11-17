<?php
// Central session and security settings
if (session_status() === PHP_SESSION_NONE) {
    // set secure cookie params; these are safe for local dev but recommended for production with HTTPS
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params([
        'lifetime' => $cookieParams['lifetime'],
        'path' => $cookieParams['path'],
        'domain' => $cookieParams['domain'],
        'secure' => false, // set to true in production (HTTPS)
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

function login_regenerate() {
    // regenerate session id on login
    if (session_status() !== PHP_SESSION_ACTIVE) return;
    session_regenerate_id(true);
}

// Simple login attempt tracker (per-session). You can extend to use DB or cache per-IP.
function login_attempt_increment() {
    if (!isset($_SESSION['login_attempts'])) $_SESSION['login_attempts'] = 0;
    $_SESSION['login_attempts']++;
    if (!isset($_SESSION['login_lock_until'])) $_SESSION['login_lock_until'] = null;
    if ($_SESSION['login_attempts'] >= 5) {
        // lock for 5 minutes
        $_SESSION['login_lock_until'] = time() + 300;
    }
}
function login_attempt_allowed() {
    if (!isset($_SESSION['login_lock_until']) || $_SESSION['login_lock_until'] === null) return true;
    if (time() > $_SESSION['login_lock_until']) {
        // reset
        $_SESSION['login_attempts'] = 0;
        $_SESSION['login_lock_until'] = null;
        return true;
    }
    return false;
}
