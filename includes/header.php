<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Inventory System' ?></title>

    <!-- Project CSS (relative path, handled by .htaccess) -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">📦</div>
                <h3 class="sidebar-title">SB ADMIN</h3>
            </div>
            <ul class="sidebar-menu">
                <li>
                    <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
                        <span class="icon">📊</span> <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="products.php"
                        class="<?= basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : '' ?>">
                        <span class="icon">📦</span> <span>Products</span>
                    </a>
                </li>
                <li>
                    <a href="logout.php">
                        <span class="icon">🚪</span> <span>Logout</span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">