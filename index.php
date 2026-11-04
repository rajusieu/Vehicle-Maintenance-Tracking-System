<?php
require_once 'db.php';

$vehicles = $pdo->query("SELECT * FROM vehicles ORDER BY created_at DESC")->fetchAll();
$mechanics = $pdo->query("SELECT * FROM mechanics ORDER BY name ASC")->fetchAll();

$services_stmt = $pdo->query("
    SELECT sr.*, v.make, v.model, v.year, m.name AS mechanic_name, m.shop_name 
    FROM service_records sr 
    JOIN vehicles v ON sr.vehicle_id = v.id 
    LEFT JOIN mechanics m ON sr.mechanic_id = m.id 
    ORDER BY sr.service_date DESC
");
$service_records = $services_stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vehicle Maintenance Tracking System (VMTS)</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f4f4f9; color: #333; }
        .container { max-width: 950px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #d9534f; margin-top: 0; }
        .actions { margin-bottom: 20px; }
        .btn { background: #0275d8; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; display: inline-block; margin-right: 10px; margin-bottom: 10px; }
        .btn:hover { background: #025aa5; }
        .btn-green { background: #5cb85c; }
        .btn-green:hover { background: #4cae4c; }
        .btn-purple { background: #6f42c1; }
        .btn-purple:hover { background: #5a32a3; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; font-size: 14px; }
        th { background: #f8f9fa; }
        section { margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Vehicle Maintenance Tracking System</h1>
        <p>✅ Running live on XAMPP local server.</p>
        <hr>
        
        <div class="actions">
            <a href="add_vehicle.php" class="btn">+ Add Vehicle</a>
            <a href="add_mechanic.php" class="btn btn-purple">+ Add Mechanic</a>
            <a href="add_service.php" class="btn btn-green">+ Log Service Record</a>
        </div>

        <section>
            <h3>Registered Vehicles</h3>
            <?php if (count($vehicles) > 0): ?>
                <table>
                    <tr>
                        <th>Vehicle</th>
                        <th>Year</th>
                        <th>VIN</th>
                        <th>Current Mileage</th>
                    </tr>
                    <?php foreach ($vehicles as $v): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($v['make'] . ' ' . $v['model']); ?></strong></td>
                            <td><?php echo htmlspecialchars($v['year']); ?></td>
                            <td><?php echo htmlspecialchars($v['vin'] ?: 'N/A'); ?></td>
                            <td><?php echo number_format($v['mileage']); ?> mi</td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>No vehicles added yet.</p>
            <?php endif; ?>
        </section>

        <section>
            <h3>Saved Mechanics & Shops</h3>
            <?php if (count($mechanics) > 0): ?>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Shop Name</th>
                        <th>Phone</th>
                        <th>Specialization</th>
                    </tr>
                    <?php foreach ($mechanics as $m): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($m['name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($m['shop_name'] ?: 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($m['phone'] ?: 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($m['specialization'] ?: 'General'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>No mechanics added yet (DIY or none specified).</p>
            <?php endif; ?>
        </section>

        <section>
            <h3>Maintenance & Service History</h3>
            <?php if (count($service_records) > 0): ?>
                <table>
                    <tr>
                        <th>Date</th>
                        <th>Vehicle</th>
                        <th>Description</th>
                        <th>Performed By</th>
                        <th>Mileage</th>
                        <th>Cost</th>
                    </tr>
                    <?php foreach ($service_records as $sr): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($sr['service_date']); ?></td>
                            <td><strong><?php echo htmlspecialchars($sr['year'] . ' ' . $sr['make'] . ' ' . $sr['model']); ?></strong></td>
                            <td><?php echo nl2br(htmlspecialchars($sr['description'])); ?></td>
                            <td><?php echo htmlspecialchars($sr['mechanic_name'] ? $sr['mechanic_name'] . ($sr['shop_name'] ? ' (' . $sr['shop_name'] . ')' : '') : 'DIY / Self'); ?></td>
                            <td><?php echo number_format($sr['mileage_at_service']); ?> mi</td>
                            <td>$<?php echo number_format($sr['cost'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>No service records logged yet.</p>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>