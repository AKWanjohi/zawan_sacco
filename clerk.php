<?php
include 'components/main-top.php';

include 'components/header.php';

?>

<main>
    <div class="container controls">
        <a href="edit_user.php?user=clerk&id=<?php echo $_SESSION['user_id'] ?>" class="control">Edit Details</a>
        <a href="pending_loans.php" class="control">Pending Loans</a>
    </div>
</main>

<?php include 'components/main-bottom.php' ?>