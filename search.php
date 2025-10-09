<?php
include('config.php');

$searchBy = $_POST['searchBy'] ?? '';
$value = trim($_POST['value'] ?? '');

if (empty($value)) {
  echo "<div class='alert alert-warning text-center mt-4'>Please enter a search value.</div>";
  exit;
}

$where = '';
$params = [];

switch ($searchBy) {
  case 'order_id':
    $where = "o.order_id = :value";
    $params[':value'] = $value;
    break;
  case 'mobile':
    $where = "c.contact LIKE :value";
    $params[':value'] = "%$value%";
    break;
  case 'name':
    $where = "c.customer_name LIKE :value";
    $params[':value'] = "%$value%";
    break;
  case 'email':
    $where = "c.email LIKE :value";
    $params[':value'] = "%$value%";
    break;
  default:
    echo "<div class='alert alert-danger text-center mt-4'>Invalid search type.</div>";
    exit;
}

$sql = "SELECT o.*, c.customer_name, c.city, c.pincode, c.shipping_address, c.contact, c.email 
        FROM order_tbl o 
        JOIN customer_tbl c ON o.customer_id = c.customer_id 
        WHERE $where";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll();

if (!$results) {
  echo "<div class='alert alert-danger text-center mt-4'>No results found for your search.</div>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Order Result</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f3f3f3;
      font-family: Arial, sans-serif;
    }

    .result-box {
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 6px;
      padding: 15px 20px;
      margin: 20px auto;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      max-width: 1133px;
    }

    .order-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      border-bottom: 1px solid #eee;
      padding-bottom: 10px;
      margin-bottom: 10px;
      font-size: 14px;
    }

    .order-row div {
      margin-right: 10px;
    }

    .cod-badge {
      color: #fff;
      padding: 3px 10px;
      border-radius: 5px;
      font-size: 13px;
      margin-left: 10px;
    }

    .track-btn {
      background: #666;
      color: #fff;
      border: none;
      border-radius: 4px;
      padding: 5px 10px;
      margin-left: 5px;
      font-size: 13px;
      text-decoration: none;
      cursor: pointer;
    }

    .track-btn:hover {
      background: #444;
    }

    .buyer-details {
      line-height: 1.4;
    }

    .product-box {
      display: flex;
      align-items: flex-start;
      gap: 15px;
      margin-top: 10px;
      font-size: 13px;
    }

    .product-img {
      width: 70px;
      height: 70px;
      border: 1px solid #ddd;
      border-radius: 5px;
      object-fit: cover;
    }

    .product-box a {
      text-decoration: none;
      color: #0056b3;
    }

    .product-box a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<?php foreach ($results as $row):
  $paymentType = strtoupper($row['payment']);
  $badgeColor = ($paymentType == 'COD') ? 'orange' : 'green';
  $statusColor = ($row['order_status'] == 'Delivered') ? 'green' : 'red';
  $total = ($row['price'] * $row['quantity']) + $row['delivery_charge'] - $row['discount'];
?>
  <div class="result-box">
    
    <div class="order-row">
      <div><strong>Order Date:</strong> <?php echo htmlspecialchars($row['order_done']); ?></div>
      <div><strong>Order ID:</strong> 
        <a href="#" style="color:#007bff;text-decoration:none;"><?php echo htmlspecialchars($row['order_id']); ?></a>
        <span class="cod-badge" style="background:<?php echo $badgeColor; ?>"><?php echo $paymentType; ?></span>
      </div>
      <div class="buyer-details">
        <strong>Buyer:</strong> <?php echo htmlspecialchars($row['customer_name']); ?><br>
        <?php echo htmlspecialchars($row['city']); ?>, <?php echo htmlspecialchars($row['pincode']); ?><br>
        <?php echo htmlspecialchars($row['email']); ?> | <?php echo htmlspecialchars($row['contact']); ?>
      </div>
      <div><strong>Total:</strong> ₹<?php echo number_format($total, 2); ?></div>
      <div><strong>Status:</strong> <span style="color:<?php echo $statusColor; ?>"><?php echo htmlspecialchars($row['order_status']); ?></span></div>
      <div>
        <button class="track-btn">TRACK</button>
        <a href="invoice.php?id=<?php echo urlencode($row['order_id']); ?>" class="track-btn">Generate Invoice</a>
      </div>
    </div>

    
    <div class="product-box">
      <img src="https://via.placeholder.com/80" alt="Product" class="product-img">
      <div>
        <a href="<?php echo htmlspecialchars($row['product_link']); ?>" target="_blank">
          <strong><?php echo htmlspecialchars($row['product_name']); ?></strong>
        </a><br>
        <div>Model: <?php echo htmlspecialchars($row['product_model']); ?></div>
        <div>Qty: <?php echo htmlspecialchars($row['quantity']); ?></div>
        <div>Price: ₹<?php echo htmlspecialchars($row['price']); ?></div>
        <div>Delivery Charge: ₹<?php echo htmlspecialchars($row['delivery_charge']); ?></div>
        <div>Discount: ₹<?php echo htmlspecialchars($row['discount']); ?></div>
      </div>
    </div>
  </div>
<?php endforeach; ?>

</body>
</html>
