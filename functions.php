<?php
if (session_status() === PHP_SESSION_NONE) session_start();
function e($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function loggedIn() { return isset($_SESSION['user_id']); }
function requireLogin() {
    if (!loggedIn()) { header("Location: account.php?message=Please sign in first."); exit; }
}
function cartCount() {
    return array_sum($_SESSION['cart'] ?? []);
}
function notice() {
    if (!empty($_SESSION['notice'])) {
        echo '<p class="notice">'.e($_SESSION['notice']).'</p>';
        unset($_SESSION['notice']);
    }
}
?>
