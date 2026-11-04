-- Rajindra Sieunarine
-- November 3rd, 2026
-- Project: Vehicle Maintenance Tracking System
-- Course: CIS 344 Group Project - Lehman College

CREATE DATABASE IF NOT EXISTS vmts
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;

USE vmts;

DROP VIEW IF EXISTS vw_vehicle_service_history;
DROP TABLE IF EXISTS service_records;
DROP TABLE IF EXISTS maintenance_schedules;
DROP TABLE IF EXISTS vehicles;
DROP TABLE IF EXISTS mechanics;

CREATE TABLE mechanics (
    mechanic_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    specialization VARCHAR(100),
    phone VARCHAR(20),
    email VARCHAR(100)
) ENGINE=InnoDB;

CREATE TABLE vehicles (
    vehicle_id INT AUTO_INCREMENT PRIMARY KEY,
    make VARCHAR(50) NOT NULL,
    model VARCHAR(50) NOT NULL,
    vehicle_year INT NOT NULL CHECK (vehicle_year >= 1900 AND vehicle_year <= 2100),
    vin VARCHAR(17) UNIQUE NOT NULL,
    current_mileage INT NOT NULL CHECK (current_mileage >= 0),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE maintenance_schedules (
    schedule_id INT AUTO_INCREMENT PRIMARY KEY,
    service_type VARCHAR(100) NOT NULL,
    interval_miles INT NOT NULL CHECK (interval_miles > 0),
    interval_months INT NOT NULL CHECK (interval_months > 0),
    description TEXT
) ENGINE=InnoDB;

CREATE TABLE service_records (
    record_id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle_id INT NOT NULL,
    mechanic_id INT NOT NULL,
    service_date DATE NOT NULL,
    mileage_at_service INT NOT NULL CHECK (mileage_at_service >= 0),
    service_description TEXT NOT NULL,
    cost DECIMAL(10, 2) NOT NULL CHECK (cost >= 0.00),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_service_vehicle FOREIGN KEY (vehicle_id) 
        REFERENCES vehicles(vehicle_id) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_service_mechanic FOREIGN KEY (mechanic_id) 
        REFERENCES mechanics(mechanic_id) 
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE INDEX idx_vehicle_vin ON vehicles(vin);
CREATE INDEX idx_service_date ON service_records(service_date);
CREATE INDEX idx_vehicle_service ON service_records(vehicle_id);

CREATE VIEW vw_vehicle_service_history AS
SELECT 
    sr.record_id,
    v.vehicle_id,
    v.make,
    v.model,
    v.vehicle_year,
    v.vin,
    sr.service_date,
    sr.mileage_at_service,
    sr.service_description,
    sr.cost,
    m.mechanic_id,
    m.name AS mechanic_name,
    m.specialization AS mechanic_specialization
FROM service_records sr
INNER JOIN vehicles v ON sr.vehicle_id = v.vehicle_id
INNER JOIN mechanics m ON sr.mechanic_id = m.mechanic_id;