<?php
// Database configuration
$host = 'localhost'; // Database host
$dbname = 'db_site'; // Database name
$username = 'root'; // Database username
$password = ''; // Database password

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle form submission for adding or updating products
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['add_product'])) {
            // Handle image upload
            $imagePath = 'uploads/' . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);

            // Add product
            $stmt = $pdo->prepare("INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)");
            $stmt->execute([$_POST['name'], $_POST['price'], $_POST['description'], $imagePath]);
        } elseif (isset($_POST['update_product'])) {
            // Handle image upload
            $imagePath = 'uploads/' . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);

            // Update product
            $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, description = ?, image = ? WHERE id = ?");
            $stmt->execute([$_POST['name'], $_POST['price'], $_POST['description'], $imagePath, $_POST['id']]);
        }
    }

    // Handle product deletion
    if (isset($_GET['delete'])) {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
    }

    // Fetch all products
    $stmt = $pdo->query("SELECT * FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Page - Manage Products</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
<div id="wrapper">
    <h1>Manage Products</h1>

    <form method="POST" action="" enctype="multipart/form-data">
        <h2>Add / Update Product</h2>
        <input type="hidden" name="id" id="product_id" value="">
        <label for="name">Product Name:</label>
        <input type="text" name="name" id="name" required>
        <label for="price">Price:</label>
        <input type="number" name="price" id="price" step="0.01" required>
        <label for="description">Description:</label>
        <textarea name="description" id="description" required></textarea>
        <label for="image">Image:</label>
        <input type="file" name="image" id="image" accept="image/*" required>
        <button type="submit" name="add_product">Add Product</button>
        <button type="submit" name="update_product">Update Product</button>
    </form>

    <h2>Current Products</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Description</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['id']); ?></td>
                    <td><?php echo htmlspecialchars($product['name']); ?></t>
                    <td>$<?php echo number_format($product['price'], 2); ?></td>
                    <td><?php echo htmlspecialchars($product['description']); ?></td>
                    <td><img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" width="50"></td>
                    <td>
                        <a href="?edit=<?php echo $product['id']; ?>">Edit</a>
                        <a href="?delete=<?php echo $product['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php
    // Populate the form with product data for editing
    if (isset($_GET['edit'])) {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$_GET['edit']]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($product) {
            echo "<script>
                document.getElementById('product_id').value = " . json_encode($product['id']) . ";
                document.getElementById('name').value = " . json_encode($product['name']) . ";
                document.getElementById('price').value = " . json_encode($product['price']) . ";
                document.getElementById('description').value = " . json_encode($product['description']) . ";
            </script>";
        }
    }
    ?>
</div>

<footer class="credit">Author: shipra - Distributed By: <a title="Awesome web design code & scripts" href="https://www.codehim.com?source=demo-page" target="_blank">nadas</a></footer>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="javascript/loja.js"></script>

</body>
</html>