<?php
include 'components/main-top.php';

include 'components/header.php';

require 'components/database.php';

$statement = $pdo->prepare(
    "SELECT * FROM loan_type WHERE loan_type_id=:id"
);
$statement->bindValue(":id", $_GET['id']);
$statement->execute();
$loan_type = $statement->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $statement = $pdo->prepare(
        "DELETE FROM loan_type WHERE loan_type_id=:id"
    );
    $statement->bindValue(":id", $_POST['loan_type_id']);
    $statement->execute();

    header('Location: view_loan_types.php');
}

?>

<main>
    <div class="container">
        <div class="main-header">
            <h1>Delete Loan Type</h1>
            <a href="view_loan_types.php" class="btn btn-cancel">Back</a>
        </div>
        <hr>
        <div>
            <form class="delete-form" method="POST">
                <input type="text" name="loan_type_id" value="<?php echo $loan_type['loan_type_id'] ?>" hidden>
                <p class="confirm-delete">
                    Are you sure you want to delete loan type "<?php echo $loan_type['loan_type_name'] ?>"?
                </p>
                <button type="submit">Confirm</button>
            </form>
        </div>
    </div>
</main>

<?php include 'components/main-bottom.php'; ?>