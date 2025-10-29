<!DOCTYPE html>
<html>
<head>
    <title>Products List</title>
    <style>
        body { font-family: Arial; background: #bbbbbbff; padding: 20px; }
        .form-container { background: #8f8d8dff; padding: 20px; border-radius: 10px; width: 500px; margin: auto; box-shadow: 0 2px 8px rgba(0,0,0,0.2); }
        table { width: 100%; border-collapse: collapse; margin-top: 30px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background: #ac2504ff; color: white; }
        input[type="text"], input[type="number"], textarea, select {
            width: 100%; padding: 10px; margin: 8px 0; border-radius: 5px; border: 1px solid #b4b3b3ff;
        }
        input[type="submit"] {
            background: #bb0303ff; color: white; border: none; padding: 12px 20px; border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover { background: #0056b3; }
        h2 { text-align: center; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Products List</h2>
    <form method="POST">
        <label>Product Name:</label>
        <input type="text" name="product_name" required>

        <label>Category:</label>
        <select name="category" required>
            <option value="">Select Category</option>
            <option>Electronics</option>
            <option>Clothing</option>
            <option>Home & Kitchen</option>
            <option>Sports</option>
            <option>Books</option>
        </select>

        <label>Price:</label>
        <input type="number" step="0.01" name="price" required>

        <label>Stock:</label>
        <input type="number" name="stock" required>

        <label>Supplier:</label>
        <input type="text" name="supplier">

        <label>Description:</label>
        <textarea name="description" rows="3"></textarea>

        <input type="submit" name="submit" value="Add Product">
    </form>
</div>

<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "Product";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
        $product_name = $_POST['product_name'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $supplier = $_POST['supplier'];
        $description = $_POST['description'];

        $stmt = $pdo->prepare("INSERT INTO products (product_name, category, price, stock, supplier, description)
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$product_name, $category, $price, $stock, $supplier, $description]);

        echo "<script>alert('Product added successfully!');</script>";
    }

    $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($products) {
        echo "<table><tr>
                <th>Product</th><th>Category</th><th>Price</th><th>Stock</th>
                <th>Supplier</th><th>Description</th><th>Created At</th>
              </tr>";
        foreach ($products as $p) {
            echo "<tr>
                    <td>{$p['product_name']}</td>
                    <td>{$p['category']}</td>
                    <td>{$p['price']}</td>
                    <td>{$p['stock']}</td>
                    <td>{$p['supplier']}</td>
                    <td>{$p['description']}</td>
                    <td>{$p['created_at']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='text-align:center;'>No products found.</p>";
    }
} catch (PDOException $e) {
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
}
?>

</body>
</html>
