<?php session_start(); ?>

<body>

    <header>

        <img src="images/GMI.png" alt="GMI Profile Logo">

        <div class="social-links">
            <a href="https://www.facebook.com/GlobalMarketItems/" target="_blank" class="fb" title="Facebook" rel="noopener noreferrer"></a>
            <a href="https://www.instagram.com/global_market_items/" target="_blank" class="insta" title="Instagram" rel="noopener noreferrer"></a>
            <a href="https://wa.me/+94784680171" target="_blank" class="wtsp" title="Whatsapp" rel="noopener noreferrer"></a>
        </div>

        <nav>

            <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>" style="border-top-left-radius: 10px; border-bottom-left-radius: 10px;">Home</a>
            <a href="items.php" class="<?= basename($_SERVER['PHP_SELF']) == 'items.php' ? 'active' : '' ?>">Items</a>
            <a href="cart.php" class="<?= basename($_SERVER['PHP_SELF']) == 'cart.php' ? 'active' : '' ?>">Cart</a>
            <a href="order.php" class="<?= basename($_SERVER['PHP_SELF']) == 'order.php' ? 'active' : '' ?>">Order</a>
            <a href="signup.php"
            class="<?= basename($_SERVER['PHP_SELF']) == 'signup.php' ? 'active' : '' ?>" 
            <?php if (isset($_SESSION["firstName"])) echo 'style="border-top-right-radius: 10px; border-bottom-right-radius: 10px;"'; ?>
            >Signup</a>

    
            <?php

            if (isset($_SESSION["firstName"])) {
                echo '<a href="profile.php" class="' . (basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : '') . '" style="margin: 0px 5px;">'.htmlspecialchars($_SESSION["firstName"]).'</a>';
                echo '<form action="includes/logout.inc.php" method="post" class="logout-btn">
                      <button class="logout-btn" type="submit">Logout</button>
                      </form>';               
            } else {
                echo '<a href="login.php" class="' . (basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : '') . '" style="border-top-right-radius: 10px; border-bottom-right-radius: 10px;">Login</a>';
            }

            ?>
   
        </nav>

    </header>

</body>    
