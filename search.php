<?php
header('Content-Type: application/json');
require_once 'includes/db.php';

$searchName = isset($_POST['searchName']) ? $_POST['searchName'] : '';
$searchBrand = isset($_POST['searchBrand']) ? $_POST['searchBrand'] : '';
$sortBy = isset($_POST['sortBy']) ? $_POST['sortBy'] : 'newest';

$sql = "SELECT id, title, description, price, brand, created_at, image FROM services WHERE 1=1";
$params = [];

if (!empty($searchName)) {
    $sql .= " AND title LIKE ?";
    $params[] = "%$searchName%";
}
if (!empty($searchBrand)) {
    $sql .= " AND brand LIKE ?";
    $params[] = "%$searchBrand%";
}

switch ($sortBy) {
    case 'price_asc': $sql .= " ORDER BY price ASC"; break;
    case 'price_desc': $sql .= " ORDER BY price DESC"; break;
    case 'newest': $sql .= " ORDER BY created_at DESC"; break;
    default: $sql .= " ORDER BY created_at DESC"; break;
}

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($results as &$result) {
        if (empty($result['image'])) {
            $result['image'] = 'https://via.placeholder.com/300';
        }
    }
    echo json_encode($results);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>