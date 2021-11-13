<?php
include 'components/main-top.php';

include 'components/header.php';

require 'components/database.php';

$statement = $pdo->prepare(
    "SELECT * FROM loan WHERE loan_status IS NULL"
);
$statement->execute();
$loans = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<main>
    <div class="container">
        <div class="main-header">
            <h1>Pending Loans</h1>
            <a href="clerk.php" class="btn btn-cancel">Back</a>
        </div>
        <hr>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Type</th>
                    <th>Amount Requested</th>
                    <th>Date Requested</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($loans as $i => $loan) : ?>
                    <tr>
                        <td><?php echo $i + 1 ?></td>
                        <td>
                            <?php
                            $statement = $pdo->prepare(
                                "SELECT loan_type_name FROM loan_type WHERE loan_type_id=:id"
                            );
                            $statement->bindValue(":id", $loan['loan_loan_type_id']);
                            $statement->execute();
                            $loan_type_name = $statement->fetch(PDO::FETCH_COLUMN);
                            echo $loan_type_name;
                            ?>
                        </td>
                        <td>
                            <?php echo 'Kshs. ' . number_format($loan['loan_requested_amount']) ?>
                        </td>
                        <td>
                            <?php echo date_format(date_create($loan['loan_requested_date']), 'd-m-Y') ?>
                        </td>
                        <td class="actions">
                            <a href="loan_details.php?&id=<?php echo $loan['loan_id'] ?>">Details</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include 'components/main-bottom.php'; ?>