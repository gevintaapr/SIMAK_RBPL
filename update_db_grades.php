<?php
require_once __DIR__ . '/config/config.php';

$sql = "ALTER TABLE nilai_magang 
        DROP COLUMN nilai_disiplin,
        DROP COLUMN nilai_kinerja,
        DROP COLUMN nilai_laporan,
        ADD COLUMN job_knowledge DECIMAL(3,2) DEFAULT 0,
        ADD COLUMN quantity_of_work DECIMAL(3,2) DEFAULT 0,
        ADD COLUMN quality_of_work DECIMAL(3,2) DEFAULT 0,
        ADD COLUMN character_val DECIMAL(3,2) DEFAULT 0,
        ADD COLUMN personality DECIMAL(3,2) DEFAULT 0,
        ADD COLUMN courtesy DECIMAL(3,2) DEFAULT 0,
        ADD COLUMN personal_appearance DECIMAL(3,2) DEFAULT 0,
        ADD COLUMN attendance DECIMAL(3,2) DEFAULT 0";

if (mysqli_query($conn, $sql)) {
    echo "Database updated successfully.\n";
} else {
    echo "Error updating database: " . mysqli_error($conn) . "\n";
}
?>
