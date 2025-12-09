-- ==========================================
-- MIGRATION: UPDATE OLD NOTIFICATION TYPES
-- RetroLoved E-Commerce System
-- ==========================================

-- Update notifikasi yang sudah ada dengan type lama ke type baru

-- 1. Update 'order' type yang berisi kata "diproses" atau "dikonfirmasi" → order_confirmed
UPDATE notifications 
SET type = 'order_confirmed' 
WHERE type = 'order' 
AND (message LIKE '%diproses%' OR message LIKE '%dikonfirmasi%' OR title LIKE '%Diproses%' OR title LIKE '%Dikonfirmasi%');

-- 2. Update 'shipping' type → order_shipped
UPDATE notifications 
SET type = 'order_shipped' 
WHERE type = 'shipping';

-- 3. Update 'order' type yang berisi kata "dikirim" → order_shipped (fallback)
UPDATE notifications 
SET type = 'order_shipped' 
WHERE type = 'order' 
AND (message LIKE '%dikirim%' OR title LIKE '%Dikirim%');

-- 4. Update 'order' type yang berisi kata "sampai" atau "delivered" → order_delivered
UPDATE notifications 
SET type = 'order_delivered' 
WHERE type = 'order' 
AND (message LIKE '%sampai%' OR message LIKE '%delivered%' OR title LIKE '%Sampai%');

-- 5. Update 'order' type yang berisi kata "dibatalkan" atau "cancelled" → order_cancelled
UPDATE notifications 
SET type = 'order_cancelled' 
WHERE type = 'order' 
AND (message LIKE '%dibatalkan%' OR message LIKE '%cancelled%' OR title LIKE '%Dibatalkan%');

-- 6. Update 'order' type yang berisi kata "menunggu" atau "pending" → order_pending
UPDATE notifications 
SET type = 'order_pending' 
WHERE type = 'order' 
AND (message LIKE '%menunggu%' OR message LIKE '%pending%' OR title LIKE '%Menunggu%');

-- 7. Update sisanya yang masih 'order' → order_pending (default)
UPDATE notifications 
SET type = 'order_pending' 
WHERE type = 'order';

-- Verify hasil migration
SELECT type, COUNT(*) as count 
FROM notifications 
GROUP BY type;

