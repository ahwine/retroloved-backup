-- Add is_sold column to products table
-- This column tracks whether a product has been sold or not

ALTER TABLE products 
ADD COLUMN is_sold TINYINT(1) NOT NULL DEFAULT 0 
AFTER is_featured;

-- Update existing products to not sold (default)
UPDATE products SET is_sold = 0 WHERE is_sold IS NULL;

-- Add index for better query performance
CREATE INDEX idx_is_sold ON products(is_sold);
CREATE INDEX idx_active_sold ON products(is_active, is_sold);
