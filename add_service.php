<?php
require_once 'db.php';

$message = '';
$vehicles = $pdo->query("SELECT id, make, model, year FROM vehicles ORDER BY year DESC")->fetchAll();
$mechanics = $pdo->query("SELECT id, name, shop_name FROM mechanics ORDER BY name ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vehicle_id = intval($_POST['vehicle_id']);
    $mechanic_id = !empty($_POST['mechanic_id']) ? intval($_POST['mechanic_id']) : null;
    $service_date = $_POST['service_date'];
    $mileage_at_service = intval($_POST['mileage_at_service']);
    $cost = floatval($_POST['cost']);
    $description = trim($_POST['description']);

    if ($vehicle_id > 0 && !empty($service_date) && !empty($description)) {
        $stmt = $pdo->prepare("INSERT INTO service_records (vehicle_id, mechanic_id, service_date, mileage_at_service, cost, description) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$vehicle_id, $mechanic_id, $service_date, $mileage_at_service, $cost, $description]);
        
        $pdo->prepare("UPDATE vehicles SET mileage = GREATEST(mileage, ?) WHERE id = ?")->execute([$mileage_at_service, $vehicle_id]);

        header("Location: index.php");
        exit;
    } else {
        $message = "Please fill in all required fields (Vehicle, Date, Description).";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log Service Record - VMTS</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f4f4f9; color: #333; }
        .container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #d9534f; font-size: 24px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="number"], input[type="date"], select, textarea { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        textarea { height: 100px; }
        button { background: #0275d8; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; }
        button:hover { background: #025aa5; }
        .error { color: red; margin-bottom: 15px; }
        a { display: inline-block; margin-top: 15px; color: #0275d8; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Log Maintenance / Service Record</h1>
        <?php if (!empty($message)): ?>
            <div class="error"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Select Vehicle *</label>
                <select name="vehicle_id" required>
                    <option value="">-- Choose Vehicle --</option>
                    <?php foreach ($vehicles as $v): ?>
                        <option value="<?php echo $v['id']; ?>">
                            <?php echo htmlspecialchars($v['year'] . ' ' . $v['make'] . ' ' . $v['model']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Mechanic / Shop</label>
                <select name="mechanic_id">
                    <option value="">-- Self / DIY or None Specified --</option>
                    <?php foreach ($mechanics as $m): ?>
                        <option value="<?php echo $m['id']; ?>">
                            <?php echo htmlspecialchars($m['name'] . ($m['shop_name'] ? ' (' . $m['shop_name'] . ')' : '')); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Service Date *</label>
                <input type="date" name="service_date" required value="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="form-group">
                <label>Mileage at Service</label>
                <input type="number" name="mileage_at_service" placeholder="e.g., 125000">
            </div>
            <div class="form-group">
                <label>Cost ($)</label>
                <input type="number" step="0.01" name="cost" placeholder="e.g., 45.50">
            </div>
            <div class="form-group">
                <label>Service Description & Parts Used *</label>
                <textarea name="description" required placeholder="e.g., Oil change with synthetic 5W-30 and WIX filter."></textarea>
            </div>
            <button type="submit">Save Service Record</button>
        </form>
        <a href="index.php">← Back to Dashboard</a>
    </div>
</body>
</html>