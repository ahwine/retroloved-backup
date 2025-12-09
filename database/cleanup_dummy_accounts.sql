-- =====================================================
-- CLEANUP DUMMY ACCOUNTS - RetroLoved System
-- Run this after testing is complete
-- =====================================================

-- Delete dummy user accounts
DELETE FROM users 
WHERE username IN ('testfix', 'omen', 'devon', 'testbaru', 'testuser2');

-- Delete by email (alternative)
DELETE FROM users 
WHERE email IN (
    'testfix@email.com', 
    'omen@email.com', 
    'devon@email.com',
    'testbaru@email.com',
    'test2@email.com'
);

-- Clean up password reset records
DELETE FROM password_resets 
WHERE email IN (
    'testfix@email.com', 
    'omen@email.com', 
    'devon@email.com',
    'testbaru@email.com',
    'test2@email.com'
);

-- Verify cleanup
SELECT COUNT(*) as remaining_test_users 
FROM users 
WHERE username IN ('testfix', 'omen', 'devon', 'testbaru', 'testuser2');

-- Expected result: remaining_test_users = 0
