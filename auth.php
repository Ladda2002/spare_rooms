<?php

// ตรวจสอบว่า session 'ucode' ถูกตั้งค่าและไม่ว่าง
if (!isset($_SESSION['ucode']) || (isset($_SESSION['ucode']) && empty($_SESSION['ucode']))) {
    // ถ้า session 'ucode' ไม่ถูกตั้งค่า หรือว่าง ให้ตรวจสอบว่าไฟล์ปัจจุบันไม่ใช่ 'login.php'
    if (strstr($_SERVER['PHP_SELF'], 'login.php') === false) {
        header('location:login.php');
    }
} else {
    // ถ้า session 'ucode' ถูกตั้งค่าและไม่ว่าง
    // ตรวจสอบว่าไฟล์ปัจจุบันไม่ใช่ 'index.php'
    if (strstr($_SERVER['PHP_SELF'], 'index.php') === false) {
        header('location:index.php');
    }
}
