-- Remove Wishlist Feature Migration
-- This migration drops the wishlist table from the database

-- Drop wishlist table
DROP TABLE IF EXISTS `wishlist`;

-- Note: All wishlist data will be permanently deleted
-- Make sure to backup data if needed before running this migration
