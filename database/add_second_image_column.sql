-- Add second image column to products table
ALTER TABLE products ADD COLUMN image_url_2 VARCHAR(255) NULL AFTER image_url;

-- Update existing products with sample second image (optional - you can remove this)
-- UPDATE products SET image_url_2 = image_url WHERE image_url_2 IS NULL;
