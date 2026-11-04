<?php
require_once 'db.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $specialization = trim($_POST['specialization']);
    $shop_name = trim($_POST['shop_name']);

    if (!empty($name)) {
        $stmt = $pdo->prepare("INSERT INTO mechanics (name, phone, specialization, shop_name) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $phone, $specialization, $shop_name]);
        header("Location: index.php");
        exit;
    } else {
        $message = "Mechanic name is required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Mechanic - VMTS</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f4f4f9; color: #333; }
        .container { max-width: 500px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #d9534f; font-size: 24px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"] { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        button { background: #0275d8; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; }
        button:hover { background: #025aa5; }
        .error { color: red; margin-bottom: 15px; }
        a { display: inline-block; margin-top: 15px; color: #0275d8; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add a Mechanic / Shop</h1>
        <?php if (!empty($message)): ?>
            <div class="error"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Contact Name *</label>
                <input type="text" name="name" required placeholder="e.g., Mike Johnson">
            </div>
            <div class="form-group">
                <label>Shop Name</label>
                <input type="text" name="shop_name" placeholder="e.g., Precision Auto & Performance">
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" placeholder="e.g., 555-0199">
            </div>
            <div class="form-group">
                <label>Specialization</label>
                <input type="text" name="specialization" placeholder="e.g., Engine Tuning, Suspension, General">
            </div>
            <button type="submit">Save Mechanic</button>
        </form>
        <a href="index.php">← Back to Dashboard</a>
    </div>
</body>
</html>