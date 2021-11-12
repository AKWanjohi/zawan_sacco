<?php
include 'components/main-top.php';

include 'components/header.php';

require 'components/database.php';

$statement = $pdo->prepare("SELECT * FROM loan_type WHERE loan_type_id=:id");
$statement->bindValue(":id", $_GET['id']);
$statement->execute();
$loan_type = $statement->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $statement = $pdo->prepare(
        "UPDATE loan_type SET loan_type_name=:name, loan_type_desc=:description WHERE loan_type_id=:id"
    );
    $statement->bindValue(":id", $_POST['loan_type_id']);
    $statement->bindValue(":name", $_POST['loan_type_name']);
    $statement->bindValue(":description", $_POST['loan_type_desc']);
    $statement->execute();

    header('Location: view_loan_types.php');
}

?>

<main>
    <div class="container">
        <div class="main-header">
            <h1>Edit Loan Type</h1>
            <div class="buttons">
                <a href="view_loan_types.php" class="btn btn-cancel">Back</a>
            </div>
        </div>
        <hr>
        <form method="POST">
            <input type="text" name="loan_type_id" value="<?php echo $loan_type['loan_type_id'] ?>" hidden>
            <div class="form-group">
                <label for="loan_type_name">Name</label>
                <input type="text" name="loan_type_name" id="loan_type_name" value="<?php echo $loan_type['loan_type_name'] ?>" required>
            </div>
            <div class="form-group">
                <label for="loan_type_desc">Description</label>
                <textarea name="loan_type_desc" id="loan_type_desc"><?php echo $loan_type['loan_type_desc'] ?></textarea>
            </div>
            <div class="form-action-group">
                <button type="submit">Update</button>
            </div>
        </form>
    </div>
</main>

<?php include 'components/main-bottom.php'; ?>