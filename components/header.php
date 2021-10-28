<header>
    <div class="container">
        <div><a href="index.php" class="logo">ZAWAN SACCO</a></div>
        <nav>
            <ul>
                <?php if (isset($_SESSION['user_id'])) : ?>
                    <li>Welcome, <?php echo $_SESSION['user_fname'] . " " . $_SESSION['user_lname'] ?></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else : ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>