<?php
/**
 * Halaman Tambah Produk - Admin Panel
 * Form untuk menambahkan produk baru dengan upload gambar (max 10 gambar)
 * RetroLoved E-Commerce System
 */

// Mulai session
session_start();

// Validasi: Hanya admin yang bisa akses halaman ini
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../index.php');
    exit();
}

// Include koneksi database
require_once '../config/database.php';

// Inisialisasi variabel untuk pesan
$error = '';
$success = '';

// ===== PROSES TAMBAH PRODUK BARU =====
if(isset($_POST['add_product'])) {
    // Ambil dan bersihkan data dari form
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $original_price = !empty($_POST['original_price']) ? mysqli_real_escape_string($conn, $_POST['original_price']) : NULL;
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $condition_item = mysqli_real_escape_string($conn, $_POST['condition_item']);
    $is_active = 1; // Produk baru selalu aktif
    $is_featured = isset($_POST['is_featured']) ? 1 : 0; // Cek apakah ditandai sebagai featured
    
    // ===== PROSES UPLOAD MULTIPLE GAMBAR (maksimal 10 gambar) =====
    if(isset($_FILES['product_images']) && !empty($_FILES['product_images']['name'][0])) {
        // Format file yang diizinkan
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        // Array untuk menyimpan nama file gambar (1-10)
        $image_filenames = array_fill(1, 10, NULL);
        $uploaded_count = 0;
        
        // Buat folder products jika belum ada
        if(!file_exists('../assets/images/products/')) {
            mkdir('../assets/images/products/', 0777, true);
        }
        
        // Loop untuk setiap file yang diupload
        foreach($_FILES['product_images']['tmp_name'] as $key => $tmp_name) {
            if($uploaded_count >= 10) break; // Batasi maksimal 10 gambar
            
            if($_FILES['product_images']['error'][$key] == 0) {
                $filename = $_FILES['product_images']['name'][$key];
                $filetype = pathinfo($filename, PATHINFO_EXTENSION);
                
                if(!in_array(strtolower($filetype), $allowed)) {
                    $error = "Only JPG, JPEG, PNG, GIF & WEBP files are allowed!";
                    break;
                }
                
                $new_filename = 'product_' . time() . '_' . ($uploaded_count + 1) . '.' . $filetype;
                $upload_path = '../assets/images/products/' . $new_filename;
                
                if(move_uploaded_file($tmp_name, $upload_path)) {
                    $image_filenames[$uploaded_count + 1] = $new_filename;
                    $uploaded_count++;
                    usleep(100000); // 0.1 second delay for unique timestamps
                } else {
                    $error = "Failed to upload image " . ($uploaded_count + 1);
                    break;
                }
            }
        }
        
        if(!$error && $uploaded_count > 0) {
                
                $insert = query("INSERT INTO products (product_name, category, price, original_price, description, condition_item, image_url, image_url_2, image_url_3, image_url_4, image_url_5, image_url_6, image_url_7, image_url_8, image_url_9, image_url_10, is_active, is_featured, created_at) 
                                VALUES ('$product_name', '$category', '$price', " . ($original_price ? "'$original_price'" : "NULL") . ", '$description', '$condition_item', 
                                '" . $image_filenames[1] . "', 
                                " . ($image_filenames[2] ? "'" . $image_filenames[2] . "'" : "NULL") . ", 
                                " . ($image_filenames[3] ? "'" . $image_filenames[3] . "'" : "NULL") . ", 
                                " . ($image_filenames[4] ? "'" . $image_filenames[4] . "'" : "NULL") . ", 
                                " . ($image_filenames[5] ? "'" . $image_filenames[5] . "'" : "NULL") . ", 
                                " . ($image_filenames[6] ? "'" . $image_filenames[6] . "'" : "NULL") . ", 
                                " . ($image_filenames[7] ? "'" . $image_filenames[7] . "'" : "NULL") . ", 
                                " . ($image_filenames[8] ? "'" . $image_filenames[8] . "'" : "NULL") . ", 
                                " . ($image_filenames[9] ? "'" . $image_filenames[9] . "'" : "NULL") . ", 
                                " . ($image_filenames[10] ? "'" . $image_filenames[10] . "'" : "NULL") . ", 
                                '$is_active', '$is_featured', NOW())");
                
            if($insert) {
                $success = "Product added successfully with $uploaded_count image(s)!";
                header("refresh:2;url=products.php");
            } else {
                $error = "Failed to add product to database!";
            }
        }
    } else {
        $error = "Please upload at least 1 product image!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product - RetroLoved Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css?v=5.4">
    <link rel="stylesheet" href="../assets/css/toast.css">
    <script src="../assets/js/toast.js"></script>
    <script src="../assets/js/modal.js"></script>
    <style>
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }
        
        .form-group-full {
            grid-column: 1 / -1;
        }
        
        .image-preview-container {
            margin-top: 16px;
            text-align: center;
        }
        
        .image-preview-container img {
            max-width: 100%;
            max-height: 300px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .checkbox-wrapper input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
        
        .checkbox-wrapper label {
            margin: 0;
            cursor: pointer;
            font-weight: 600;
        }
        
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body class="admin-body">
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <?php $page_title = "Add New Product"; include 'includes/navbar.php'; ?>

        <div class="content-area">
            <div class="content-wrapper">
                <!-- Breadcrumbs -->
                <nav style="margin-bottom: 24px;">
                    <ol style="display: flex; align-items: center; gap: 8px; list-style: none; padding: 0; margin: 0; font-size: 14px;">
                        <li>
                            <a href="dashboard.php" style="color: #6b7280; text-decoration: none; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='#D97706'" onmouseout="this.style.color='#6b7280'">Dashboard</a>
                        </li>
                        <li style="color: #d1d5db;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </li>
                        <li>
                            <a href="products.php" style="color: #6b7280; text-decoration: none; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='#D97706'" onmouseout="this.style.color='#6b7280'">Products</a>
                        </li>
                        <li style="color: #d1d5db;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </li>
                        <li style="color: #D97706; font-weight: 600;">
                            Add Product
                        </li>
                    </ol>
                </nav>
                
                <!-- Page Title -->
                <div style="margin-bottom: 24px;">
                    <h1 style="font-size: 32px; font-weight: 800; color: #1a1a1a; margin: 0 0 8px 0;">Add New Product</h1>
                    <p style="font-size: 15px; color: #6b7280; margin: 0;">Create a new product listing for your store</p>
                </div>

            <div class="content-card" style="max-width: 1200px; margin: 0 auto;">
                <div class="card-header" style="background: #fff; border-bottom: 3px solid #10B981; padding: 24px 32px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg>
                            <div>
                                <h3 style="margin: 0; font-size: 22px; font-weight: 700; color: #1f2937;">Add New Product</h3>
                                <p style="margin: 4px 0 0 0; color: #6b7280; font-size: 14px;">Fill in product details and upload images</p>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span id="autoSaveStatus" style="font-size: 12px; color: #6b7280; display: none;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle;">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                Draft saved
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Tab Navigation -->
                <div style="border-bottom: 2px solid #e5e7eb; background: #f9fafb;">
                    <div style="display: flex; padding: 0 32px; overflow-x: auto;">
                        <button type="button" class="tab-button active" onclick="switchTab(0)" data-tab="0" style="padding: 16px 24px; border: none; background: transparent; cursor: pointer; font-weight: 600; font-size: 14px; color: #6b7280; border-bottom: 3px solid transparent; white-space: nowrap; transition: all 0.2s;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle; margin-right: 6px;">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                            </svg>
                            Basic Info
                        </button>
                        <button type="button" class="tab-button" onclick="switchTab(1)" data-tab="1" style="padding: 16px 24px; border: none; background: transparent; cursor: pointer; font-weight: 600; font-size: 14px; color: #6b7280; border-bottom: 3px solid transparent; white-space: nowrap; transition: all 0.2s;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle; margin-right: 6px;">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                <polyline points="21 15 16 10 5 21"></polyline>
                            </svg>
                            Images & Gallery
                        </button>
                        <button type="button" class="tab-button" onclick="switchTab(2)" data-tab="2" style="padding: 16px 24px; border: none; background: transparent; cursor: pointer; font-weight: 600; font-size: 14px; color: #6b7280; border-bottom: 3px solid transparent; white-space: nowrap; transition: all 0.2s;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle; margin-right: 6px;">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                            Description & Details
                        </button>
                        <button type="button" class="tab-button" onclick="switchTab(3)" data-tab="3" style="padding: 16px 24px; border: none; background: transparent; cursor: pointer; font-weight: 600; font-size: 14px; color: #6b7280; border-bottom: 3px solid transparent; white-space: nowrap; transition: all 0.2s;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle; margin-right: 6px;">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            </svg>
                            Status & Settings
                        </button>
                    </div>
                </div>

                <div style="padding: 40px;">
                    <form method="POST" enctype="multipart/form-data" id="productForm">
                        
                        <!-- TAB 1: BASIC INFO -->
                        <div class="tab-content" id="tab-0" style="display: block;">
                            <div style="margin-bottom: 32px;">
                                <h2 style="font-size: 20px; font-weight: 700; color: #1a1a1a; margin: 0 0 8px 0; display: flex; align-items: center; gap: 10px;">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                    </svg>
                                    Basic Information
                                </h2>
                                <p style="margin: 0; color: #6b7280; font-size: 14px;">Enter the essential details about your product</p>
                            </div>

                            <div class="form-grid" style="gap: 24px;">
                                <div class="form-group">
                                    <label>Product Name *</label>
                                    <input type="text" name="product_name" class="form-input" required placeholder="e.g. Vintage Denim Jacket">
                                    <small style="color: #6b7280; font-size: 12px; margin-top: 4px; display: block;">Clear and descriptive name for your product</small>
                                </div>

                                <div class="form-group">
                                    <label>Category *</label>
                                    <select name="category" class="form-input" required>
                                        <option value="">Select Category</option>
                                        <option value="Jacket">Jacket</option>
                                        <option value="Shirt">Shirt</option>
                                        <option value="T-Shirt">T-Shirt</option>
                                        <option value="Pants">Pants</option>
                                        <option value="Jeans">Jeans</option>
                                        <option value="Dress">Dress</option>
                                        <option value="Skirt">Skirt</option>
                                        <option value="Sweater">Sweater</option>
                                        <option value="Accessories">Accessories</option>
                                        <option value="Shoes">Shoes</option>
                                        <option value="Bag">Bag</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Brand</label>
                                    <input type="text" name="brand" class="form-input" placeholder="e.g. Levi's, Nike, Zara">
                                    <small style="color: #6b7280; font-size: 12px; margin-top: 4px; display: block;">Brand or manufacturer name (optional)</small>
                                </div>

                                <div class="form-group">
                                    <label>Sale Price (Rp) *</label>
                                    <input type="number" name="price" class="form-input" required min="0" placeholder="e.g. 150000">
                                    <small style="color: #6b7280; font-size: 12px; margin-top: 4px; display: block;">Current selling price</small>
                                </div>

                                <div class="form-group">
                                    <label>Original Price (Rp)</label>
                                    <input type="number" name="original_price" class="form-input" min="0" placeholder="e.g. 250000">
                                    <small style="color: #6b7280; font-size: 12px; margin-top: 4px; display: block;">Price before discount (optional)</small>
                                </div>
                            </div>

                            <!-- Navigation Buttons -->
                            <div style="margin-top: 32px; display: flex; justify-content: flex-end;">
                                <button type="button" onclick="switchTab(1)" style="background: #D97706; color: white; border: none; padding: 12px 24px; border-radius: 6px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;"
                                        onmouseover="this.style.background='#B45309'"
                                        onmouseout="this.style.background='#D97706'">
                                    Next: Images & Gallery
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- TAB 2: IMAGES & GALLERY -->
                        <div class="tab-content" id="tab-1" style="display: none;">
                            <div style="margin-bottom: 32px;">
                                <h2 style="font-size: 20px; font-weight: 700; color: #1a1a1a; margin: 0 0 8px 0; display: flex; align-items: center; gap: 10px;">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="2">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                        <polyline points="21 15 16 10 5 21"></polyline>
                                    </svg>
                                    Images & Gallery
                                </h2>
                                <p style="margin: 0; color: #6b7280; font-size: 14px;">Upload and manage product images (1-10 images)</p>
                            </div>

                            <!-- Image Upload Section -->
                            <div style="background: #f8f9fa; padding: 24px; border-radius: 12px; border: 2px solid #e5e7eb;">
                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#374151" stroke-width="2">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                        <polyline points="21 15 16 10 5 21"></polyline>
                                    </svg>
                                    <h4 style="margin: 0; font-size: 15px; font-weight: 600; color: #374151;">Product Images *</h4>
                                    <span style="background: #e5e7eb; color: #6b7280; padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;" id="imageCount">0 selected</span>
                                </div>
                                <p style="margin: 0 0 20px 0; color: #6b7280; font-size: 13px;">Select 1 to 10 images for this product (you can select multiple at once)</p>
                                
                                <div style="border: 2px dashed #cbd5e1; border-radius: 12px; padding: 40px; text-align: center; background: white;">
                                    <input type="file" name="product_images[]" id="imageInput" class="form-input" accept="image/*" multiple required
                                           onchange="previewMultipleImages(this)" style="display: none;">
                                    <div id="uploadPrompt">
                                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" style="margin: 0 auto 16px; display: block;">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="17 8 12 3 7 8"></polyline>
                                            <line x1="12" y1="3" x2="12" y2="15"></line>
                                        </svg>
                                        <p style="margin: 0 0 8px 0; font-weight: 600; font-size: 17px; color: #374151;">Upload Product Images</p>
                                        <p style="margin: 0 0 20px 0; color: #64748b; font-size: 14px;">Drag & drop or click to select 1 to 10 images</p>
                                        <button type="button" onclick="document.getElementById('imageInput').click()" 
                                                style="background: #3B82F6; color: white; border: none; padding: 12px 28px; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 15px; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px;"
                                                onmouseover="this.style.background='#2563EB'"
                                                onmouseout="this.style.background='#3B82F6'">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                <polyline points="17 8 12 3 7 8"></polyline>
                                                <line x1="12" y1="3" x2="12" y2="15"></line>
                                            </svg>
                                            Select Images
                                        </button>
                                        <p style="margin: 16px 0 0 0; color: #94a3b8; font-size: 13px;">JPG, PNG, GIF, WEBP (Max 5MB each)</p>
                                    </div>
                                    <div id="multipleImagePreview" style="display: none;">
                                        <div id="imageGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 12px; margin-bottom: 20px;"></div>
                                        <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                                            <button type="button" onclick="clearAllImages()" style="background: #EF4444; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 14px; display: inline-flex; align-items: center; gap: 6px;">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                                </svg>
                                                Clear All
                                            </button>
                                            <button type="button" onclick="document.getElementById('imageInput').click()" style="background: #3B82F6; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 14px; display: inline-flex; align-items: center; gap: 6px;">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <polyline points="23 4 23 10 17 10"></polyline>
                                                    <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
                                                </svg>
                                                Change Images
                                            </button>
                                        </div>
                                        <div style="margin: 16px 0 0 0; color: #10B981; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                <polyline points="20 6 9 17 4 12"></polyline>
                                            </svg>
                                            <span id="readyMessage">Images ready to upload</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Navigation Buttons -->
                            <div style="margin-top: 32px; display: flex; justify-content: space-between; gap: 12px;">
                                <button type="button" onclick="switchTab(0)" style="background: #6b7280; color: white; border: none; padding: 12px 24px; border-radius: 6px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;"
                                        onmouseover="this.style.background='#4b5563'"
                                        onmouseout="this.style.background='#6b7280'">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="15 18 9 12 15 6"></polyline>
                                    </svg>
                                    Previous: Basic Info
                                </button>
                                <button type="button" onclick="switchTab(2)" style="background: #D97706; color: white; border: none; padding: 12px 24px; border-radius: 6px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;"
                                        onmouseover="this.style.background='#B45309'"
                                        onmouseout="this.style.background='#D97706'">
                                    Next: Description & Details
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- TAB 3: DESCRIPTION & DETAILS -->
                        <div class="tab-content" id="tab-2" style="display: none;">
                            <div style="margin-bottom: 32px;">
                                <h2 style="font-size: 20px; font-weight: 700; color: #1a1a1a; margin: 0 0 8px 0; display: flex; align-items: center; gap: 10px;">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                    Description & Details
                                </h2>
                                <p style="margin: 0; color: #6b7280; font-size: 14px;">Provide detailed information about the product</p>
                            </div>

                            <div class="form-grid" style="gap: 24px;">
                                <div class="form-group form-group-full">
                                    <label>Product Description *</label>
                                    <textarea name="description" class="form-input" rows="6" required placeholder="Describe the product in detail...&#10;&#10;Example:&#10;- Material and fabric quality&#10;- Unique features&#10;- Condition details&#10;- Size and fit information"></textarea>
                                    <small style="color: #6b7280; font-size: 12px; margin-top: 4px; display: block;">Provide a comprehensive description to help buyers</small>
                                </div>

                                <div class="form-group">
                                    <label>Size</label>
                                    <input type="text" name="size" class="form-input" placeholder="e.g. S, M, L, XL, 38, 40">
                                    <small style="color: #6b7280; font-size: 12px; margin-top: 4px; display: block;">Product size (optional)</small>
                                </div>

                                <div class="form-group">
                                    <label>Color</label>
                                    <input type="text" name="color" class="form-input" placeholder="e.g. Blue, Red, Black">
                                    <small style="color: #6b7280; font-size: 12px; margin-top: 4px; display: block;">Primary color (optional)</small>
                                </div>

                                <div class="form-group">
                                    <label>Material</label>
                                    <input type="text" name="material" class="form-input" placeholder="e.g. Cotton, Denim, Polyester">
                                    <small style="color: #6b7280; font-size: 12px; margin-top: 4px; display: block;">Fabric or material type (optional)</small>
                                </div>

                                <div class="form-group">
                                    <label>Weight (grams)</label>
                                    <input type="number" name="weight" class="form-input" min="0" placeholder="e.g. 500">
                                    <small style="color: #6b7280; font-size: 12px; margin-top: 4px; display: block;">For shipping calculation (optional)</small>
                                </div>

                                <div class="form-group form-group-full">
                                    <label>Additional Notes</label>
                                    <textarea name="notes" class="form-input" rows="3" placeholder="Any additional information for buyers..."></textarea>
                                    <small style="color: #6b7280; font-size: 12px; margin-top: 4px; display: block;">Extra details, care instructions, etc. (optional)</small>
                                </div>
                            </div>

                            <!-- Navigation Buttons -->
                            <div style="margin-top: 32px; display: flex; justify-content: space-between; gap: 12px;">
                                <button type="button" onclick="switchTab(1)" style="background: #6b7280; color: white; border: none; padding: 12px 24px; border-radius: 6px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;"
                                        onmouseover="this.style.background='#4b5563'"
                                        onmouseout="this.style.background='#6b7280'">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="15 18 9 12 15 6"></polyline>
                                    </svg>
                                    Previous: Images
                                </button>
                                <button type="button" onclick="switchTab(3)" style="background: #D97706; color: white; border: none; padding: 12px 24px; border-radius: 6px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;"
                                        onmouseover="this.style.background='#B45309'"
                                        onmouseout="this.style.background='#D97706'">
                                    Next: Status & Settings
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- TAB 4: STATUS & SETTINGS -->
                        <div class="tab-content" id="tab-3" style="display: none;">
                            <div style="margin-bottom: 32px;">
                                <h2 style="font-size: 20px; font-weight: 700; color: #1a1a1a; margin: 0 0 8px 0; display: flex; align-items: center; gap: 10px;">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="2">
                                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                    </svg>
                                    Status & Settings
                                </h2>
                                <p style="margin: 0; color: #6b7280; font-size: 14px;">Configure product status and availability</p>
                            </div>

                            <div class="form-grid" style="gap: 24px;">
                                <div class="form-group">
                                    <label>Condition *</label>
                                    <select name="condition_item" class="form-input" required>
                                        <option value="">Select Condition</option>
                                        <option value="Excellent">Excellent - Like new, no visible wear</option>
                                        <option value="Very Good">Very Good - Minimal signs of use</option>
                                        <option value="Good">Good - Some visible wear but functional</option>
                                        <option value="Fair">Fair - Noticeable wear, still usable</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>SKU / Product Code</label>
                                    <input type="text" name="sku" class="form-input" placeholder="e.g. VDJ-001">
                                    <small style="color: #6b7280; font-size: 12px; margin-top: 4px; display: block;">Stock Keeping Unit for inventory tracking (optional)</small>
                                </div>

                                <div class="form-group form-group-full">
                                    <label style="font-weight: 600; color: #1f2937; margin-bottom: 12px; display: block; font-size: 14px;">Product Visibility</label>
                                    <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                                        <div style="flex: 1; min-width: 200px; background: #F0FDF4; border: 2px solid #10B981; border-radius: 8px; padding: 16px;">
                                            <label style="display: flex; align-items: flex-start; gap: 12px; cursor: pointer;">
                                                <input type="radio" name="status" value="active" checked style="margin-top: 4px; width: 18px; height: 18px; cursor: pointer;">
                                                <div>
                                                    <div style="font-weight: 600; color: #065F46; margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
                                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2">
                                                            <polyline points="20 6 9 17 4 12"></polyline>
                                                        </svg>
                                                        Active
                                                    </div>
                                                    <div style="font-size: 13px; color: #047857;">Product visible to customers</div>
                                                </div>
                                            </label>
                                        </div>
                                        <div style="flex: 1; min-width: 200px; background: #FEF3C7; border: 2px solid #F59E0B; border-radius: 8px; padding: 16px;">
                                            <label style="display: flex; align-items: flex-start; gap: 12px; cursor: pointer;">
                                                <input type="radio" name="status" value="draft" style="margin-top: 4px; width: 18px; height: 18px; cursor: pointer;">
                                                <div>
                                                    <div style="font-weight: 600; color: #92400E; margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
                                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#F59E0B" stroke-width="2">
                                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                            <circle cx="12" cy="12" r="3"></circle>
                                                            <line x1="1" y1="1" x2="23" y2="23"></line>
                                                        </svg>
                                                        Draft
                                                    </div>
                                                    <div style="font-size: 13px; color: #B45309;">Hidden from customers</div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group form-group-full">
                                    <div style="background: #FEF3C7; border: 2px solid #F59E0B; border-radius: 8px; padding: 16px;">
                                        <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                                            <input type="checkbox" name="is_featured" id="is_featured" style="width: 20px; height: 20px; cursor: pointer;">
                                            <div style="display: flex; align-items: center; gap: 8px;">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="#F59E0B" stroke="#F59E0B" stroke-width="2">
                                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                                </svg>
                                                <span style="font-weight: 600; color: #92400E;">Featured Product</span>
                                            </div>
                                        </label>
                                        <p style="margin: 8px 0 0 32px; color: #B45309; font-size: 13px;">Display this product prominently on the homepage</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Navigation Buttons -->
                            <div style="margin-top: 32px; display: flex; justify-content: space-between; gap: 12px;">
                                <button type="button" onclick="switchTab(2)" style="background: #6b7280; color: white; border: none; padding: 12px 24px; border-radius: 6px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;"
                                        onmouseover="this.style.background='#4b5563'"
                                        onmouseout="this.style.background='#6b7280'">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="15 18 9 12 15 6"></polyline>
                                    </svg>
                                    Previous: Description
                                </button>
                            </div>
                        </div>

                        <!-- SUBMIT BUTTON (shown on all tabs) -->
                        <div style="margin-top: 40px; padding-top: 24px; border-top: 2px solid #e5e7eb; display: flex; gap: 12px;">
                            <button type="submit" name="add_product" style="flex: 1; background: #10B981; color: white; border: none; padding: 14px 24px; border-radius: 6px; font-weight: 600; font-size: 15px; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center; gap: 8px;"
                                    onmouseover="this.style.background='#059669'"
                                    onmouseout="this.style.background='#10B981'">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                Add Product
                            </button>
                            <a href="products.php" style="flex: 1; background: #6b7280; color: white; border: none; padding: 14px 24px; border-radius: 6px; font-weight: 600; font-size: 15px; text-align: center; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s;"
                               onmouseover="this.style.background='#4b5563'"
                               onmouseout="this.style.background='#6b7280'">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            </div>
        </div>
    </div>

    <script>
        function previewMultipleImages(input) {
            const files = input.files;
            if (!files || files.length === 0) return;
            
            // Max 10 images
            if (files.length > 10) {
                toastWarning('Maksimal 10 gambar saja!', 'Peringatan');
                input.value = '';
                return;
            }
            
            // Update counter
            document.getElementById('imageCount').textContent = files.length + ' selected';
            
            // Hide upload prompt, show preview
            document.getElementById('uploadPrompt').style.display = 'none';
            document.getElementById('multipleImagePreview').style.display = 'block';
            
            // Clear previous previews
            const imageGrid = document.getElementById('imageGrid');
            imageGrid.innerHTML = '';
            
            // Create preview for each file
            Array.from(files).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const imgContainer = document.createElement('div');
                        imgContainer.style.cssText = 'position: relative; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);';
                        
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.cssText = 'width: 100%; height: 120px; object-fit: cover; display: block;';
                        
                        const badge = document.createElement('div');
                        badge.textContent = index + 1;
                        badge.style.cssText = 'position: absolute; top: 4px; left: 4px; background: #3B82F6; color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 600;';
                        
                        imgContainer.appendChild(img);
                        imgContainer.appendChild(badge);
                        imageGrid.appendChild(imgContainer);
                    }
                    
                    reader.readAsDataURL(file);
                }
            });
            
            // Update ready message
            document.getElementById('readyMessage').textContent = files.length + ' image(s) ready to upload';
        }
        
        function clearAllImages() {
            document.getElementById('imageInput').value = '';
            document.getElementById('uploadPrompt').style.display = 'block';
            document.getElementById('multipleImagePreview').style.display = 'none';
            document.getElementById('imageCount').textContent = '0 selected';
            document.getElementById('imageGrid').innerHTML = '';
        }
        
        // Show toast notifications for success/error messages
        <?php if($success): ?>
            window.addEventListener('DOMContentLoaded', function() {
                toastSuccess('<?php echo addslashes($success); ?>');
            });
        <?php endif; ?>
        
        <?php if($error): ?>
            window.addEventListener('DOMContentLoaded', function() {
                toastError('<?php echo addslashes($error); ?>');
            });
        <?php endif; ?>
    </script>

    <!-- Tab Navigation & Auto-Save Script -->
    <script>
        // Tab switching functionality
        function switchTab(tabIndex) {
            // Hide all tab contents
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach((content) => {
                content.style.display = 'none';
            });
            
            // Show selected tab content
            const selectedTab = document.getElementById('tab-' + tabIndex);
            if (selectedTab) {
                selectedTab.style.display = 'block';
            }
            
            // Update tab buttons
            const tabButtons = document.querySelectorAll('.tab-button');
            tabButtons.forEach((btn, index) => {
                if (index === tabIndex) {
                    btn.classList.add('active');
                    btn.style.color = '#D97706';
                    btn.style.borderBottomColor = '#D97706';
                } else {
                    btn.classList.remove('active');
                    btn.style.color = '#6b7280';
                    btn.style.borderBottomColor = 'transparent';
                }
            });
            
            // Scroll to top of form smoothly
            document.querySelector('.content-card').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        // Auto-save draft to localStorage
        let autoSaveInterval;
        
        function saveDraft() {
            const form = document.getElementById('productForm');
            const formData = new FormData(form);
            const draftData = {};
            
            // Save form fields (except files)
            for (let [key, value] of formData.entries()) {
                if (key !== 'product_images[]') {
                    draftData[key] = value;
                }
            }
            
            localStorage.setItem('product_draft', JSON.stringify(draftData));
            
            // Show saved indicator
            const status = document.getElementById('autoSaveStatus');
            status.style.display = 'inline-flex';
            status.style.alignItems = 'center';
            status.style.gap = '4px';
            
            setTimeout(() => {
                status.style.display = 'none';
            }, 2000);
        }

        // Load draft from localStorage
        function loadDraft() {
            const draft = localStorage.getItem('product_draft');
            if (draft) {
                const draftData = JSON.parse(draft);
                
                // Fill form fields
                for (let [key, value] of Object.entries(draftData)) {
                    const input = document.querySelector(`[name="${key}"]`);
                    if (input) {
                        if (input.type === 'checkbox') {
                            input.checked = value === 'on' || value === '1';
                        } else {
                            input.value = value;
                        }
                    }
                }
                
                // Show notification
                toastInfo('Draft loaded from auto-save');
            }
        }

        // Clear draft
        function clearDraft() {
            localStorage.removeItem('product_draft');
        }

        // Initialize auto-save
        document.addEventListener('DOMContentLoaded', function() {
            // Load draft on page load
            loadDraft();
            
            // Start auto-save every 30 seconds
            autoSaveInterval = setInterval(saveDraft, 30000);
            
            // Save on form input changes
            const form = document.getElementById('productForm');
            form.addEventListener('input', function() {
                clearTimeout(window.autoSaveTimeout);
                window.autoSaveTimeout = setTimeout(saveDraft, 3000);
            });
            
            // Clear draft on successful submit
            form.addEventListener('submit', function() {
                clearDraft();
            });
        });

        // Clear draft button (optional - can be added to UI)
        function clearDraftManually() {
            confirmModal('Are you sure you want to clear the saved draft?<br>This action cannot be undone.', function() {
                clearDraft();
                location.reload();
            });
        }
    </script>

    <!-- Tab Styling -->
    <style>
        .tab-button.active {
            color: #D97706 !important;
            border-bottom-color: #D97706 !important;
        }
        
        .tab-button:hover {
            color: #D97706 !important;
            background: rgba(217, 119, 6, 0.05);
        }
        
        .tab-content {
            animation: fadeInTab 0.3s ease-in;
        }
        
        #autoSaveStatus {
            animation: fadeIn 0.3s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes fadeInTab {
            from { 
                opacity: 0;
                transform: translateY(10px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</body>
</html>
