<?php
include 'components/main-top.php';

include 'components/header.php';

require 'components/database.php';

if ($_GET['user'] == 'admins') {
    $statement = $pdo->prepare("SELECT * FROM admin");
} elseif ($_GET['user'] == 'clerks') {
    $statement = $pdo->prepare("SELECT * FROM clerk");
} elseif ($_GET['user'] == 'clients') {
    $statement = $pdo->prepare("SELECT * FROM client");
}

$statement->execute();
$users = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<main>
    <div class="container">
        <div class="main-header">
            <?php if ($_GET['user'] == 'admins') : ?>
                <h1>View Admins</h1>
            <?php elseif ($_GET['user'] == 'clerks') : ?>
                <h1>View Clerks</h1>
            <?php elseif ($_GET['user'] == 'clients') : ?>
                <h1>View Clients</h1>
            <?php endif ?>
            <div class="buttons">
                <a href="admin.php" class="btn btn-cancel">Back</a>
                <?php if ($_GET['user'] == 'admins') : ?>
                    <a href="create_user.php?user=admin" class="btn btn-create">Create Admin</a>
                <?php elseif ($_GET['user'] == 'clerks') : ?>
                    <a href="create_user.php?user=clerk" class="btn btn-create">Create Clerk</a>
                <?php elseif ($_GET['user'] == 'clients') : ?>
                    <a href="create_user.php?user=client" class="btn btn-create">Create Client</a>
                <?php endif ?>
            </div>
        </div>
        <hr>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Mobile No.</th>
                    <?php if ($_GET['user'] == 'clients') : ?>
                        <th>ID No.</th>
                    <?php endif; ?>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $i => $user) : ?>
                    <tr>
                        <?php if ($_GET['user'] == 'admins') : ?>
                            <td><?php echo $i + 1 ?></td>
                            <td><?php echo $user['admin_fname'] ?></td>
                            <td><?php echo $user['admin_lname'] ?></td>
                            <td><?php echo $user['admin_email'] ?></td>
                            <td><?php echo $user['admin_mobile'] ?></td>
                            <td class="actions">
                                <a href="edit_user.php?user=admin&id=<?php echo $user['admin_id'] ?>">Edit</a>
                                <?php if ($user['admin_id'] == $_SESSION['user_id']) : ?>
                                    <a href="delete_user.php?user=admin&id=<?php echo $user['admin_id'] ?>" class="delete disabled">Delete</a>
                                <?php else : ?>
                                    <a href="delete_user.php?user=admin&id=<?php echo $user['admin_id'] ?>" class="delete">Delete</a>
                                <?php endif; ?>
                            </td>
                        <?php elseif ($_GET['user'] == 'clerks') : ?>
                            <td><?php echo $i + 1 ?></td>
                            <td><?php echo $user['clerk_fname'] ?></td>
                            <td><?php echo $user['clerk_lname'] ?></td>
                            <td><?php echo $user['clerk_email'] ?></td>
                            <td><?php echo $user['clerk_mobile'] ?></td>
                            <td class="actions">
                                <a href="edit_user.php?user=clerk&id=<?php echo $user['clerk_id'] ?>">Edit</a>
                                <a href="delete_user.php?user=clerk&id=<?php echo $user['clerk_id'] ?>" class="delete">Delete</a>
                            </td>
                        <?php elseif ($_GET['user'] == 'clients') : ?>
                            <td><?php echo $i + 1 ?></td>
                            <td><?php echo $user['client_fname'] ?></td>
                            <td><?php echo $user['client_lname'] ?></td>
                            <td><?php echo $user['client_email'] ?></td>
                            <td><?php echo $user['client_mobile'] ?></td>
                            <td><?php echo $user['client_id_no'] ?></td>
                            <td class="actions">
                                <a href="edit_user.php?user=client&id=<?php echo $user['client_id'] ?>">Edit</a>
                                <a href="delete_user.php?user=client&id=<?php echo $user['client_id'] ?>" class="delete">Delete</a>
                            </td>
                        <?php endif ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include 'components/main-bottom.php'; ?>