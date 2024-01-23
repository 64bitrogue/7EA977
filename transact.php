<?php

// This page is the transaction module.

include "connect.php";
include "functions.php";

$storage_query = "SELECT * FROM storage";
$storage_list = mysqli_query($conn, $storage_query);

$id = null;
$date = null;

$weight = null;
$company = null;
$payment = null;

$free = null;
$total = null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TRANSACTION</title>
</head>
<body>
    <a href="index.php">Go Back to Index</a>
    <h1>Transaction</h1>
    <hr>
    <form action="transact.php" method="post">
        <div>
            <label for="id">Storage ID</label>
            <select name="id" id="id">
                <option value="" selected disabled>Select Storage...</option>
                <?php
                if ($storage_list) {
                    while ($storage_row = mysqli_fetch_assoc($storage_list)) {
                        ?>
                        <option value="<?= $storage_row['id'] ?>" <?= $storage_row['id'] == $id ? 'selected' : '' ?>>
                        <?= $storage_row['id'] ?> - <?= $storage_row['company'] ?> (<?= number_format($storage_row['weight'], 0) ?> kg<?= $storage_row['weight'] > 1 ? 's' : '' ?>)
                        </option>
                        <?php
                    }
                }
                ?>
            </select>
        </div>
        <div>
            <label for="date">Transaction Date</label>
            <input value="<?= $date ?>" type="date" name="date" id="date">
        </div>
        <button type="submit" name="submit">Submit</button>
    </form>
    <?php
    if (isset($_POST['submit'])) {
        $id = sanitize_input($_POST['id']);
        $date = sanitize_input($_POST['date']);

        $query = "SELECT * FROM storage WHERE id = '$id'";

        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $company = $row['company'];
            $payment = $row['payment'];
            $weight = $row['weight'];

            // Check if weekend or weekday

            $date = new DateTime($date);
            $day = $date->format('N');

            if ($day == '6' || $day == '7') {
                $free = 1000;
            } else {
                $free = 500;
            }

            // Compute total cost

            $remaining_weight = $weight - $free;

            if ($remaining_weight > 1000) {
                $total = 1000 * 50;
                $remaining_weight -= 1000;
            } else {
                $total = $remaining_weight * 50;
                $remaining_weight = 0;
            }

            if ($remaining_weight > 1500) {
                $total += 1500 * 75;
                $remaining_weight -= 1500;
            } else {
                $total += $remaining_weight * 75;
                $remaining_weight = 0;
            }

            if ($remaining_weight > 0) {
                $total += $remaining_weight * 100;
            }

            if ($payment == "CASH") {
                $total -= $total * 0.05;
            } else if ($payment == "INSTALLMENT") {
                $total += $total * 0.02;
            }

            ?>
            <div>
                <div>
                    <h2>Storage Information</h2>
                    <table>
                        <tr>
                            <th>Storage ID</th>
                            <td><?= $id ?></td>
                        </tr>
                        <tr>
                            <th>Company Name</th>
                            <td><?= $company ?></td>
                        </tr>
                        <tr>
                            <th>Gross Weight</th>
                            <td><?= number_format($weight, 0) ?> kg<?= $weight == 1 ? 's' : '' ?></td>
                        </tr>
                        <tr>
                            <th>Payment Mode</th>
                            <td><?= ucfirst($payment) ?></td>
                        </tr>
                    </table>
                    <hr>
                    <table>
                        <th>Storage Fee</th>
                        <td>Php <?= number_format($total, 2) ?></td>
                    </table>
                </div>
            </div>
            <?php
        }
    }
    ?>
</body>
</html>