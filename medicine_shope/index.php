<?php
// This file is the front controller of the Medicine Shop project.
// It loads config, models, controllers and sends every request to the correct MVC controller.
session_start();

require 'config.php';
require 'models/UserModel.php';
require 'models/CategoryModel.php';
require 'models/MedicineModel.php';
require 'models/CartModel.php';
require 'models/OrderModel.php';
require 'controllers/AuthController.php';
require 'controllers/HomeController.php';
require 'controllers/ProfileController.php';
require 'controllers/AdminController.php';
require 'controllers/CartController.php';

function requireLogin(){ if(!isset($_SESSION['user'])){ header('Location: index.php?page=login'); exit; } }
function requireAdmin(){ requireLogin(); if($_SESSION['user']['role']!=='admin'){ header('Location: index.php?page=home'); exit; } }
function requireCustomer(){ requireLogin(); if($_SESSION['user']['role']!=='customer'){ header('Location: index.php?page=home'); exit; } }

$page = $_GET['page'] ?? 'home';
switch($page){
    case 'home': homeCtrl($conn); break;
    case 'login': loginCtrl($conn); break;
    case 'register': registerCtrl($conn); break;
    case 'logout': logoutCtrl($conn); break;
    case 'profile': profileCtrl($conn); break;
    case 'ajax_medicine_search': medicineSearchAjax($conn); break;
    case 'admin': adminDashboardCtrl($conn); break;
    case 'categories': categoryCtrl($conn); break;
    case 'medicines': medicineCtrl($conn); break;
    case 'customers': customersCtrl($conn); break;
    case 'orders': ordersCtrl($conn); break;
    case 'ajax_order_status': orderStatusAjax($conn); break;
    case 'history': historyCtrl($conn); break;
    case 'cart': cartCtrl($conn); break;
    case 'ajax_cart_add': cartAddAjax($conn); break;
    case 'ajax_cart_update': cartUpdateAjax($conn); break;
    case 'ajax_cart_remove': cartRemoveAjax($conn); break;
    case 'checkout': checkoutCtrl($conn); break;
    case 'invoice': invoiceCtrl($conn); break;
    case 'my_orders': myOrdersCtrl($conn); break;
    default: header('Location: index.php?page=home'); exit;
}
mysqli_close($conn);
?>
