<?php
// This controller loads home medicines and category list.
// Search is done only through the medicine name.
// AJAX search saves the last text in session and cookie.
function homeCtrl($conn) {
    $categories = getCategories($conn);
    $categoryId = intval($_GET['category'] ?? 0);
    $q = trim($_GET['q'] ?? ($_SESSION['last_search'] ?? ($_COOKIE['last_search'] ?? '')));

    $_SESSION['last_search'] = $q;
    setcookie('last_search', $q, time() + (86400 * 7), '/');

    $medicines = searchMedicinesByNameAndCategory($conn, $q, $categoryId);
    require 'views/home.php';
}

function medicineSearchAjax($conn) {
    header('Content-Type: application/json');
    $q = trim($_GET['q'] ?? '');
    $categoryId = intval($_GET['category'] ?? 0);
    $_SESSION['last_search'] = $q;
    setcookie('last_search', $q, time() + (86400 * 7), '/');
    echo json_encode(searchMedicinesByNameAndCategory($conn, $q, $categoryId));
    exit;
}
?>
