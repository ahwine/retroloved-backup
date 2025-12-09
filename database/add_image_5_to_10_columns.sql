-- Add image_url_5 to image_url_10 columns to products table for multiple image support (up to 10 images)

ALTER TABLE products ADD COLUMN IF NOT EXISTS image_url_5 VARCHAR(255) NULL AFTER image_url_4;
ALTER TABLE products ADD COLUMN IF NOT EXISTS image_url_6 VARCHAR(255) NULL AFTER image_url_5;
ALTER TABLE products ADD COLUMN IF NOT EXISTS image_url_7 VARCHAR(255) NULL AFTER image_url_6;
ALTER TABLE products ADD COLUMN IF NOT EXISTS image_url_8 VARCHAR(255) NULL AFTER image_url_7;
ALTER TABLE products ADD COLUMN IF NOT EXISTS image_url_9 VARCHAR(255) NULL AFTER image_url_8;
ALTER TABLE products ADD COLUMN IF NOT EXISTS image_url_10 VARCHAR(255) NULL AFTER image_url_9;
