-- Migration: Add 'Completed' to orders status ENUM
-- Date: 2025-12-09
-- Purpose: Fix bug where status becomes empty after customer confirmation

ALTER TABLE orders 
MODIFY COLUMN status ENUM('Pending','Processing','Shipped','Delivered','Completed','Cancelled') 
DEFAULT 'Pending';
