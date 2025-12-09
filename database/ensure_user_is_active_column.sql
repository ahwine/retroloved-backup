-- Ensure is_active column exists in users table for block/unblock functionality
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS is_active TINYINT(1) DEFAULT 1 COMMENT '1=Active, 0=Blocked';

-- Add index for better performance
ALTER TABLE users ADD INDEX IF NOT EXISTS idx_is_active (is_active);

-- Update existing NULL values to 1 (active)
UPDATE users SET is_active = 1 WHERE is_active IS NULL;
