-- Create the database
CREATE DATABASE IF NOT EXISTS vehicle_maintenance_db;
USE vehicle_maintenance_db;

-- 1. Mechanics Table: Stores technician/shop details
CREATE TABLE IF NOT EXISTS mechanics (
    mechanic_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    specialization VARCHAR(100),
    phone VARCHAR(20),
    email VARCHAR(100)
) ENGINE=InnoDB;

-- 2. Vehicles Table: Stores vehicle specs and current mileage tracking
CREATE TABLE IF NOT EXISTS vehicles (
    vehicle_id INT AUTO_INCREMENT PRIMARY KEY,
    make VARCHAR(50) NOT NULL,
    model VARCHAR(50) NOT NULL,
    vehicle_year INT NOT NULL CHECK (vehicle_year >= 1900 AND vehicle_year <= 2100),
    vin VARCHAR(17) UNIQUE NOT NULL,
    current_mileage INT NOT NULL CHECK (current_mileage >= 0),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 3. Maintenance Schedules Table: Defines interval constraints for routine services
CREATE TABLE IF NOT EXISTS maintenance_schedules (
    schedule_id INT AUTO_INCREMENT PRIMARY KEY,
    service_type VARCHAR(100) NOT NULL, -- e.g., 'Oil Change', 'Brake Fluid Flush'
    interval_miles INT NOT NULL CHECK (interval_miles > 0), -- e.g., every 5000 miles
    interval_months INT NOT NULL CHECK (interval_months > 0), -- e.g., every 6 months
    description TEXT
) ENGINE=InnoDB;

-- 4. Service Records Table: The transaction log linking vehicles and mechanics
CREATE TABLE IF NOT EXISTS service_records (
    record_id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle_id INT NOT NULL,
    mechanic_id INT NOT NULL,
    service_date DATE NOT NULL,
    mileage_at_service INT NOT NULL CHECK (mileage_at_service >= 0),
    service_description TEXT NOT NULL,
    cost DECIMAL(10, 2) NOT NULL CHECK (cost >= 0.00),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    -- Foreign Key Constraints ensuring referential integrity
    CONSTRAINT fk_service_vehicle FOREIGN KEY (vehicle_id) 
        REFERENCES vehicles(vehicle_id) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_service_mechanic FOREIGN KEY (mechanic_id) 
        REFERENCES mechanics(mechanic_id) 
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Indexes for performance optimization on frequent queries and joins
CREATE INDEX idx_vehicle_vin ON vehicles(vin);
CREATE INDEX idx_service_date ON service_records(service_date);