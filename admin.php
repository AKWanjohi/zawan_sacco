<?php
include 'components/main-top.php';

include 'components/header.php';

?>

<main>
    <div class="container">
        <div class="login-notification">
            <p>Logged in as Admin</p>
        </div>
        <div class="controls">
            <a href="view_users.php?user=admins" class="control">View Admins</a>
            <a href="view_users.php?user=clerks" class="control">View Clerks</a>
            <a href="view_users.php?user=clients" class="control">View Clients</a>
            <a href="create_account.php" class="control">Create Client Account</a>
            <a href="view_loan_types.php" class="control">View Loan Types</a>
            <a href="charge_members.php" class="control">Charge Members</a>
        </div>
    </div>
</main>

<?php include 'components/main-bottom.php'; ?>