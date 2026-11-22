<?php require_once 'functions.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Chairbnb - <?= e($pageTitle ?? 'Home') ?></title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<nav>
    <h1>Chairbnb</h1>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="products.php">Furniture</a>
        <a href="account.php"><?= loggedIn() ? 'My Account' : 'Account' ?></a>
        <a href="inquiry.php">Contact Us</a>
        <a href="cart.php">Cart (<?= cartCount() ?>)</a>
        <?php if (loggedIn()): ?><a href="logout.php">Logout</a><?php endif; ?>
    </div>
</nav>
<main>
<?php notice(); ?>
