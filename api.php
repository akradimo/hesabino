<?php
header('Content-Type: application/json');
require_once 'includes/db.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'get_products':
        $sql = "SELECT * FROM products";
        $result = $db->query($sql);
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        echo json_encode($products);
        break;

    case 'get_sales':
        $sql = "SELECT sales.*, products.name as product_name, services.name as service_name 
                FROM sales 
                LEFT JOIN products ON sales.product_id = products.id 
                LEFT JOIN services ON sales.service_id = services.id";
        $result = $db->query($sql);
        $sales = [];
        while ($row = $result->fetch_assoc()) {
            $sales[] = $row;
        }
        echo json_encode($sales);
        break;

    default:
        echo json_encode(['error' => 'عملیات نامعتبر است.']);
        break;
}
?>