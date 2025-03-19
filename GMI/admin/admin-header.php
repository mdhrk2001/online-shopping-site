<?php session_start(); ?>

<body>

    <header>

        <img src="../images/GMI.png" alt="GMI Profile Logo">
        
        <h1>Admin Dashboard</h1>

        <nav>
            <?php if (isset($_SESSION["email"])): ?>
                <a href="admin-dashboard.php" 
                   class="<?= basename($_SERVER['PHP_SELF']) == 'admin-dashboard.php' ? 'active' : '' ?>" 
                   style="border-top-left-radius: 10px; border-bottom-left-radius: 10px;">
                   Manage Products
                </a>
                <a href="admin-category.php" 
                   class="<?= basename($_SERVER['PHP_SELF']) == 'admin-category.php' ? 'active' : '' ?>">
                   Manage Categories
                </a>
                <a href="admin-order.php" 
                   class="<?= basename($_SERVER['PHP_SELF']) == 'admin-order.php' ? 'active' : '' ?>" 
                   style="border-top-right-radius: 10px; border-bottom-right-radius: 10px;">
                   View Orders
                </a>
                <form action="includes/admin.logout.inc.php" method="post" class="logout-frm">
                    <button class="logout-btn" type="submit">Logout</button>
                </form>
            <?php endif; ?>
        </nav>

    </header>

</body>

