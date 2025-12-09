-- Fix order 69 yang statusnya kosong
UPDATE orders 
SET status = 'Completed' 
WHERE order_id = 69 AND (status IS NULL OR status = '');

-- Check hasilnya
SELECT order_id, status, CHAR_LENGTH(status) as status_len, current_status_detail 
FROM orders 
WHERE order_id = 69;
