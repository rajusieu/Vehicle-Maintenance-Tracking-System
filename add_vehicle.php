<?php
require_once 'db.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $make = trim($_POST['make']);
    $model = trim($_POST['model']);
    $year = intval($_POST['year']);
    $vin = trim($_POST['vin']);
    $mileage = intval($_POST['mileage']);

    if (!empty($make) && !empty($model) && !empty($year)) {
        $stmt = $pdo->prepare("INSERT INTO vehicles (make, model, year, vin, mileage) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$make, $model, $year, $vin, $mileage]);
        header("Location: index.php");
        exit;
    } else {
        $message = "Please fill in all required fields (Make, Model, Year).";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Vehicle - VMTS</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f4f4f9; color: #333; }
        .container { max-width: 500px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #d9534f; font-size: 24px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="number"] { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        button { background: #0275d8; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; }
        button:hover { background: #025aa5; }
        .error { color: red; margin-bottom: 15px; }
        a { display: inline-block; margin-top: 15px; color: #0275d8; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add a New Vehicle</h1>
        <?php if (!empty($message)): ?>
            <div class="error"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Make *</label>
                <input type="text" name="make" required placeholder="e.g., Nissan">
            </div>
            <div class="form-group">
                <label>Model *</label>
                <input type="text" name="model" required placeholder="e.g., Frontier">
            </div>
            <div class="form-group">
                <label>Year *</label>
                <input type="number" name="year" required placeholder="e.g., 2012">
            </div>
            <div class="form-group">
                <label>VIN</label>
                <input type="text" name="vin" placeholder="Optional 17-digit VIN">
            </div>
            <div class="form-group">
                <label>Mileage</label>
                <input type="number" name="mileage" placeholder="e.g., 120000">
            </div>
            <button type="submit">Save Vehicle</button>
        </form>
        <a href="index.php">← Back to Dashboard</a>
    </div>

    <!-- JavaScript Front-End Validation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            
            form.addEventListener('submit', function(event) {
                const make = document.querySelector('input[name="make"]').value.trim();
                const model = document.querySelector('input[name="model"]').value.trim();
                const year = document.querySelector('input[name="year"]').value.trim();

                if (!make || !model || !year) {
                    event.preventDefault(); // Stop form submission
                    alert('⚠️ Please fill out all required fields (Make, Model, and Year) before saving.');
                }
            });
        });
    </script>
</body>
</html>