-- Add image_url_3 and image_url_4 columns to products table
ALTER TABLE products ADD COLUMN image_url_3 VARCHAR(255) NULL AFTER image_url_2;
ALTER TABLE products ADD COLUMN image_url_4 VARCHAR(255) NULL AFTER image_url_3;
