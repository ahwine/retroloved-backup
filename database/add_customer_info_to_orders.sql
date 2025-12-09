-- Add customer name and email columns to orders table
-- This allows admin to see customer information in order details

ALTER TABLE `orders` 
ADD COLUMN `customer_name` VARCHAR(100) NULL AFTER `user_id`,
ADD COLUMN `customer_email` VARCHAR(100) NULL AFTER `customer_name`;

-- Update existing orders with user data
UPDATE orders o
JOIN users u ON o.user_id = u.user_id
SET o.customer_name = u.full_name,
    o.customer_email = u.email
WHERE o.customer_name IS NULL OR o.customer_email IS NULL;
