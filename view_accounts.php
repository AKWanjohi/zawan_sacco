<?php
include 'components/main-top.php';

include 'components/header.php';

require 'components/database.php';

$statement = $pdo->prepare("SELECT * FROM account WHERE account_client_id=:id");
$statement->bindValue(":id", $_SESSION['user_id']);
$statement->execute();
$accounts = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<main>
    <div class="container">
        <div class="main-header">
            <h1>View Accounts</h1>
            <a href="client.php" class="btn btn-cancel">Back</a>
        </div>
        <hr>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Balance</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($accounts as $i => $account) : ?>
                    <tr>
                        <td><?php echo $i + 1 ?></td>
                        <td><?php echo $account['account_no'] ?></td>
                        <td><?php echo $account['account_name'] ?></td>
                        <td><?php echo $account['account_desc'] ?></td>
                        <td>Kshs. <?php echo number_format($account['account_total_balance']) ?></td>
                        <td class="actions"><a href="edit_account.php?id=<?php echo $account['account_id'] ?>">Edit</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include 'components/main-bottom.php'; ?>