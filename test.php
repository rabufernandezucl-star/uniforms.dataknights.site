<?php

$inputPassword = "12345"; // password na tinatype mo

$hashFromDB = '2y$10$N9qo8uLOickgx2ZMRZo5i.UK6Q9e1Cdm42Hcps225y7.'; // paste hash ng user

if (password_verify($inputPassword, $hashFromDB)) {
    echo "MATCH ✅";
} else {
    echo "NOT MATCH ❌";
}
