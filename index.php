<?php

// This page shows the records of the database.

include "connect.php";

$search = null;
$query = null;

if (isset($_GET['search']) && strlen($_GET['search']) > 0) {
    $search = trim(htmlspecialchars(stripslashes($_GET['search'])));

    $query = "SELECT * FROM storage WHERE id LIKE '%$search%' OR company LIKE '%$search%' OR payment LIKE '%$search%'";
} else {
    $query = "SELECT * FROM storage";
}

$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>7EA977</title>
</head>
<body>
    <div>
        <a href="create.php">Add Storage Information</a>
        <a href="transact.php">Transaction Module</a>
    </div>
    <hr>
    <div>
        <form action="index.php" method="get">
            <input value="<?= $search ?>" type="text" name="search" id="search">
            <button type="submit">Search</button>
        </form>
    </div>
    <hr>
    <div>
        <h1>Storage Information</h1>
        <table>
            <thead>
                <th>Storage ID</th>
                <th>Company Name</th>
                <th>Gross Weight (kg)</th>
                <th>Payment Mode</th>
                <th>Actions</th>
            </thead>
            <tbody>
                <?php
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['company'] ?></td>
                            <td><?= number_format($row['weight'], 0) ?></td>
                            <td>
                                <span class="<?= $row['payment'] == "CASH" ? 'cash' : 'installment' ?>">
                                <?= $row['payment'] ?>
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="edit.php?id=<?= $row['id'] ?>">Edit</a>
                                    <form action="delete.php" method="post">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <button name="delete" type="submit">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>