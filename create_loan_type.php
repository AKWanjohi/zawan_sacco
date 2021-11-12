<?php
include 'components/main-top.php';

include 'components/header.php';

require 'components/database.php';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $statement = $pdo->prepare(
        "INSERT INTO loan_type (loan_type_name, loan_type_desc) VALUES (:name, :description)"
    );
    $statement->bindValue(":name", $_POST['loan_type_name']);
    $statement->bindValue(":description", $_POST['loan_type_desc']);
    $statement->execute();

    header('Location: view_loan_types.php');
}

?>

<main>
    <div class="container">
        <div class="main-header">
            <h1>Create Loan Type</h1>
            <div class="buttons">
                <a href="view_loan_types.php" class="btn btn-cancel">Back</a>
            </div>
        </div>
        <hr>
        <form method="POST">
            <div class="form-group">
                <label for="loan_type_name">Name</label>
                <input type="text" name="loan_type_name" id="loan_type_name" placeholder="Enter loan type name" required>
            </div>
            <div class="form-group">
                <label for="loan_type_desc">Description</label>
                <textarea name="loan_type_desc" id="loan_type_desc" placeholder="Enter loan type description"></textarea>
            </div>
            <div class="form-action-group">
                <button type="reset">Reset</button>
                <button type="submit">Create</button>
            </div>
        </form>
    </div>
</main>

<?php include 'components/main-bottom.php'; ?>