<header>
    <div class="container">
        <div><a href="index.php" class="logo">ZAWAN SACCO</a></div>
        <nav>
            <ul>
                <?php if (isset($_SESSION['user_id'])) : ?>
                    <li class="welcome">Welcome, <?php echo $_SESSION['user_fname'] . " " . $_SESSION['user_lname'] ?></li>
                    <?php if ($_SESSION['user_type'] == 'admin') : ?>
                        <li><a href="admin.php">Dashboard</a></li>
                    <?php elseif ($_SESSION['user_type'] == 'clerk') : ?>
                        <li><a href="clerk.php">Dashboard</a></li>
                    <?php elseif ($_SESSION['user_type'] == 'client') : ?>
                        <li><a href="client.php">Dashboard</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">Logout</a></li>
                <?php else : ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>