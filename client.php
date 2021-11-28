<?php
include 'components/main-top.php';

include 'components/header.php';

?>

<main>
    <div class="container">
        <div class="login-notification">
            <p>Logged in as Client</p>
        </div>
        <div class="controls">
            <a href="view_accounts.php" class="control">View Accounts</a>
            <a href="savings.php" class="control">Save Money</a>
            <a href="request_loan.php" class="control">Request Loan</a>
            <a href="view_loans.php" class="control">View Loans</a>
            <a href="view_charges.php" class="control">View Charges</a>
            <a href="payment_history.php" class="control">Payment History</a>
        </div>
    </div>
</main>

<?php include 'components/main-bottom.php'; ?>