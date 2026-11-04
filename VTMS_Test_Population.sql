USE vmts;

-- Temporarily disable foreign key checks so we can truncate tables safely
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE service_records;
TRUNCATE TABLE vehicles;
TRUNCATE TABLE mechanics;
TRUNCATE TABLE maintenance_schedules;
SET FOREIGN_KEY_CHECKS = 1;

-- 1. POPULATE MECHANICS
INSERT INTO mechanics (name, specialization, phone, email) VALUES
('Moe Johnson', 'Engine & Transmission Diagnostics', '891-2388', 'moe.j@autocare.com'),
('Ravi Jenkins', 'Electrical & Performance Tuning', '224-0045', 'r.jenkins@precisiontuning.com'),
('Nav Ruiz', 'General Maintenance & Brakes', '218-0746', 'nav.ruiz@mobilefix.com');

-- 2. POPULATE VEHICLES
INSERT INTO vehicles (make, model, vehicle_year, vin, current_mileage) VALUES
('Nissan', 'Frontier SV', 2012, '1N6AA0EC3CC123456', 82500),
('Acura', 'RSX Type-S', 2003, 'JH4DC53855S009876', 198000),
('Hyundai', 'Elantra', 2018, '5TFHY5F1JFX998877', 115000);

-- 3. POPULATE MAINTENANCE SCHEDULES
INSERT INTO maintenance_schedules (service_type, interval_miles, interval_months, description) VALUES
('Synthetic Oil Change', 5000, 6, 'Replace engine oil and filter using high-grade synthetic oil.'),
('Brake Fluid Flush', 30000, 24, 'Bleed and replace old brake fluid with fresh DOT fluid.'),
('Transmission Fluid Change', 45000, 36, 'Drain and refill transmission fluid to ensure smooth gear shifts.');

-- 4. POPULATE SERVICE RECORDS
INSERT INTO service_records (vehicle_id, mechanic_id, service_date, mileage_at_service, service_description, cost) VALUES
(1, 1, '2026-01-15', 74000, 'Performed synthetic oil change and replaced cabin air filter.', 85.50),
(2, 2, '2026-02-10', 124500, 'Diagnosed rough idle and replaced ignition coils and spark plugs.', 320.00),
(1, 3, '2026-05-20', 81000, 'Complete brake pad replacement and rotor resurfacing.', 245.00),
(3, 1, '2026-06-05', 102000, 'Routine oil change and tire rotation.', 95.00);