<?php

// This file connects the project to the database.

// Replace "7ea977" with your database name.
$conn = new mysqli("localhost", "root", "", "7ea977");

if (!$conn) {
    echo "Connection failed.";
    die();
}
