<?php
include 'components/main-top.php';

include 'components/header.php';

require 'components/database.php';

$statement = $pdo->prepare(
    "SELECT * FROM loan_type"
);
$statement->execute();
$loan_types = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<main>
    <div class="container">
        <div class="main-header">
            <h1>View Loan Types</h1>
            <div class="buttons">
                <a href="admin.php" class="btn btn-cancel">Back</a>
                <a href="create_loan_type.php" class="btn btn-create">Create Loan Type</a>
            </div>
        </div>
        <hr>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($loan_types as $i => $loan_type) : ?>
                    <tr>
                        <td><?php echo $i + 1 ?></td>
                        <td><?php echo $loan_type['loan_type_name'] ?></td>
                        <td><?php echo $loan_type['loan_type_desc'] ?></td>
                        <td class="actions">
                            <a href="edit_loan_type.php?id=<?php echo $loan_type['loan_type_id'] ?>">Edit</a>
                            <a href="delete_loan_type.php?id=<?php echo $loan_type['loan_type_id'] ?>" class="delete">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include 'components/main-bottom.php'; ?>