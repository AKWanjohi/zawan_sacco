<?php
include 'components/main-top.php';

include 'components/header.php';

?>

<main>
    <div class="container controls">
        <a href="edit_user.php?user=client&id=<?php echo $_SESSION['user_id'] ?>" class="control">Edit Details</a>
        <a href="create_account.php" class="control">Create an Account</a>
        <a href="view_accounts.php" class="control">View Accounts</a>
        <a href="savings.php" class="control">Save Money</a>
        <a href="request_loan.php" class="control">Request Loan</a>
        <a href="view_loans.php" class="control">View Loans</a>
        <a href="view_charges.php" class="control">View Charges</a>
        <a href="payment_history.php" class="control">Payment History</a>
    </div>
</main>

<?php include 'components/main-bottom.php'; ?>