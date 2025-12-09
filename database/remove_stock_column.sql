-- Script untuk menghapus kolom stock dari tabel products dan quantity dari cart
-- Karena ini adalah penjualan barang bekas yang mayoritas hanya 1 item
-- Fitur stock dan quantity tidak diperlukan lagi

-- Hapus kolom stock dari tabel products
ALTER TABLE products DROP COLUMN stock;

-- Hapus kolom quantity dari tabel cart
ALTER TABLE cart DROP COLUMN quantity;

-- Catatan: 
-- Pastikan backup database sudah dilakukan sebelum menjalankan script ini
-- Script ini akan menghapus kolom stock dan quantity secara permanen
