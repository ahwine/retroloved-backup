-- Add Order Management Improvements (Step 3)
-- Menambahkan tracking number, admin notes, dan order history/timeline

-- 1. Add tracking_number column for shipping tracking
ALTER TABLE `orders` 
ADD COLUMN `tracking_number` VARCHAR(100) NULL AFTER `status`;

-- 2. Add admin_notes column for internal notes
ALTER TABLE `orders` 
ADD COLUMN `admin_notes` TEXT NULL AFTER `tracking_number`;

-- 3. Create order_history table for tracking status changes
CREATE TABLE IF NOT EXISTS `order_history` (
    `history_id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT NOT NULL,
    `status` VARCHAR(50) NOT NULL,
    `tracking_number` VARCHAR(100) NULL,
    `notes` TEXT NULL,
    `changed_by` INT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (changed_by) REFERENCES users(user_id) ON DELETE SET NULL,
    INDEX idx_order_id (order_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Populate order_history with existing orders (initial status)
INSERT INTO order_history (order_id, status, created_at)
SELECT order_id, status, created_at 
FROM orders
WHERE order_id NOT IN (SELECT DISTINCT order_id FROM order_history);
