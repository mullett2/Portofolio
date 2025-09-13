<?php
include 'db.php';

$query = mysqli_query($conn, "SELECT id, status_berkas FROM laporan");

while ($data = mysqli_fetch_assoc($query)) {
    echo "ID: " . $data['id'] . " - Status: " . $data['status_berkas'] . "<br>";
}
