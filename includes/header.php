<?php
// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Sistem Pencatatan Keuangan Pribadi</title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
</head>
<body>
    <header class="main-header">
        <div class="container">
            <h1 class="logo">💰 Pencatatan Keuangan Pribadi</h1>
            <nav class="main-nav">
                <ul>
                    <li><a href="<?php echo SITE_URL; ?>/index.php">Dashboard</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/pages/pemasukan.php">Pemasukan</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/pages/pengeluaran.php">Pengeluaran</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/pages/laporan.php">Laporan</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/pages/sharing.php">Berbagi</a></li>
                    <?php if (isset($_SESSION['username'])): ?>
                        <li style="margin-left: 1rem; padding-left: 1rem; border-left: 1px solid rgba(255,255,255,0.3);">
                            <span style="opacity: 0.9;">👤 <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        </li>
                        <li><a href="<?php echo SITE_URL; ?>/auth/logout.php">Logout</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    
    <main class="main-content">

