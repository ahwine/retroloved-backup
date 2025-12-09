<?php
/**
 * Halaman Profil Customer (Settings & Profile Management)
 * Mengelola informasi profil, password, dan alamat pengiriman
 * RetroLoved E-Commerce System
 */

// Mulai session
session_start();

// Include koneksi database
require_once '../config/database.php';

// Validasi: Hanya customer yang bisa akses halaman ini
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header('Location: ../index.php');
    exit();
}

// Ambil ID user yang sedang login
$user_id = $_SESSION['user_id'];
$base_url = '../';

// Ambil halaman/tab yang aktif (default: profile)
$page = isset($_GET['page']) ? $_GET['page'] : 'profile';

// Ambil data user dari database
$user_query = "SELECT * FROM users WHERE user_id = '$user_id'";
$user = mysqli_fetch_assoc(query($user_query));

// Ambil semua alamat pengiriman user (urutkan berdasarkan default dan tanggal)
$addresses_query = "SELECT * FROM shipping_addresses WHERE user_id = '$user_id' ORDER BY is_default DESC, created_at DESC";
$addresses = query($addresses_query);

// Handle profile update
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['update_profile'])) {
        $full_name = escape($_POST['full_name']);
        $birth_date = !empty($_POST['birth_date']) ? escape($_POST['birth_date']) : NULL;
        
        // Handle profile picture upload if present
        $profile_picture_updated = false;
        if(isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $filename = $_FILES['profile_picture']['name'];
            $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $filesize = $_FILES['profile_picture']['size'];
            
            // Max 2MB
            $max_size = 2 * 1024 * 1024;
            
            if($filesize > $max_size) {
                set_message('error', 'Ukuran file terlalu besar! Maksimal 2MB.');
                header('Location: profile.php?page=profile');
                exit();
            } elseif(!in_array($filetype, $allowed)) {
                set_message('error', 'Format file tidak didukung! Gunakan JPG, PNG, GIF, atau WEBP (max 2MB).');
                header('Location: profile.php?page=profile');
                exit();
            } else {
                // Delete old profile picture if exists
                if(!empty($user['profile_picture']) && file_exists('../assets/images/profiles/' . $user['profile_picture'])) {
                    unlink('../assets/images/profiles/' . $user['profile_picture']);
                }
                
                $new_filename = 'profile_' . $user_id . '_' . time() . '.' . $filetype;
                $upload_path = '../assets/images/profiles/' . $new_filename;
                
                if(move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_path)) {
                    $profile_picture_updated = true;
                    if($birth_date !== NULL) {
                        $update_query = "UPDATE users SET full_name = '$full_name', birth_date = '$birth_date', profile_picture = '$new_filename' WHERE user_id = '$user_id'";
                    } else {
                        $update_query = "UPDATE users SET full_name = '$full_name', birth_date = NULL, profile_picture = '$new_filename' WHERE user_id = '$user_id'";
                    }
                } else {
                    set_message('error', 'Gagal upload foto profil! Periksa permission folder.');
                    header('Location: profile.php?page=profile');
                    exit();
                }
            }
        } else {
            // No file uploaded - just update name and birth date
            if($birth_date !== NULL) {
                $update_query = "UPDATE users SET full_name = '$full_name', birth_date = '$birth_date' WHERE user_id = '$user_id'";
            } else {
                $update_query = "UPDATE users SET full_name = '$full_name', birth_date = NULL WHERE user_id = '$user_id'";
            }
        }
        
        // Handle delete picture flag (this will override the query above if delete is requested)
        if(isset($_POST['delete_picture_flag']) && $_POST['delete_picture_flag'] == '1') {
            if(!empty($user['profile_picture']) && file_exists('../assets/images/profiles/' . $user['profile_picture'])) {
                unlink('../assets/images/profiles/' . $user['profile_picture']);
            }
            if($birth_date !== NULL) {
                $update_query = "UPDATE users SET full_name = '$full_name', birth_date = '$birth_date', profile_picture = NULL WHERE user_id = '$user_id'";
            } else {
                $update_query = "UPDATE users SET full_name = '$full_name', birth_date = NULL, profile_picture = NULL WHERE user_id = '$user_id'";
            }
        }
        
        // Execute the update query
        $result = query($update_query);
        
        if($result) {
            // Update berhasil, baik ada perubahan atau tidak
            $_SESSION['full_name'] = $full_name;
            set_message('success', 'Profile berhasil diupdate!');
        } else {
            // Only show error if query actually failed
            global $conn;
            $error = mysqli_error($conn);
            set_message('error', 'Gagal update profile! Error: ' . $error);
        }
        
        // Check if there's a redirect URL from JavaScript
        if(isset($_POST['redirect_after_save']) && !empty($_POST['redirect_after_save'])) {
            $redirect_url = $_POST['redirect_after_save'];
            // Sanitize the URL to prevent XSS
            $redirect_url = filter_var($redirect_url, FILTER_SANITIZE_URL);
            header('Location: ' . $redirect_url);
        } else {
            header('Location: profile.php?page=profile');
        }
        exit();
    }
    
    // Handle account update (email, username, & password)
    if(isset($_POST['update_account'])) {
        $email = escape($_POST['email']);
        $username = escape($_POST['username']);
        
        // Validate email
        if(!validate_email($email)) {
            set_message('error', 'Format email tidak valid!');
        } else {
            // Check if email already used by other user
            $email_check = query("SELECT user_id FROM users WHERE email = '$email' AND user_id != '$user_id'");
            if(mysqli_num_rows($email_check) > 0) {
                set_message('error', 'Email sudah digunakan oleh user lain!');
            } else {
                // Check if username already used by other user
                $username_check = query("SELECT user_id FROM users WHERE username = '$username' AND user_id != '$user_id'");
                if(mysqli_num_rows($username_check) > 0) {
                    set_message('error', 'Username sudah digunakan oleh user lain!');
                } else {
                    // Check if password fields are filled
                    $password_changed = false;
                    if(!empty($_POST['current_password']) && !empty($_POST['new_password']) && !empty($_POST['confirm_password'])) {
                        $current_password = md5($_POST['current_password']);
                        $new_password = $_POST['new_password'];
                        $confirm_password = $_POST['confirm_password'];
                        
                        // Validate password change
                        if($current_password != $user['password']) {
                            set_message('error', 'Password lama tidak sesuai!');
                            header('Location: profile.php?page=account');
                            exit();
                        } elseif($new_password != $confirm_password) {
                            set_message('error', 'Password baru dan konfirmasi tidak cocok!');
                            header('Location: profile.php?page=account');
                            exit();
                        } elseif(strlen($new_password) < 8) {
                            set_message('error', 'Password minimal 8 karakter!');
                            header('Location: profile.php?page=account');
                            exit();
                        } elseif(!preg_match('/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/', $new_password)) {
                            set_message('error', 'Password harus mengandung huruf besar, huruf kecil, dan angka!');
                            header('Location: profile.php?page=account');
                            exit();
                        } else {
                            $hashed_password = md5($new_password);
                            $update_query = "UPDATE users SET email = '$email', username = '$username', password = '$hashed_password' WHERE user_id = '$user_id'";
                            $password_changed = true;
                        }
                    } else {
                        $update_query = "UPDATE users SET email = '$email', username = '$username' WHERE user_id = '$user_id'";
                    }
                    
                    if(query($update_query)) {
                        $_SESSION['username'] = $username;
                        if($password_changed) {
                            set_message('success', 'Account dan password berhasil diupdate! Silakan login kembali dengan password baru.');
                            session_destroy();
                            header('Location: ../index.php');
                            exit();
                        } else {
                            set_message('success', 'Account berhasil diupdate!');
                        }
                    } else {
                        set_message('error', 'Gagal update account!');
                    }
                }
            }
        }
        
        // Check if there's a redirect URL from JavaScript
        if(isset($_POST['redirect_after_save']) && !empty($_POST['redirect_after_save'])) {
            $redirect_url = $_POST['redirect_after_save'];
            // Sanitize the URL to prevent XSS
            $redirect_url = filter_var($redirect_url, FILTER_SANITIZE_URL);
            header('Location: ' . $redirect_url);
        } else {
            header('Location: profile.php?page=account');
        }
        exit();
    }
    
    // Handle profile picture upload - REMOVED DUPLICATE (moved to line 193)
    
    // Handle add shipping address
    if(isset($_POST['add_address'])) {
        $recipient_name = escape($_POST['recipient_name']);
        $phone = escape($_POST['phone']);
        $full_address = escape($_POST['full_address']);
        $city = escape($_POST['city']);
        $province = escape($_POST['province']);
        $postal_code = escape($_POST['postal_code']);
        $latitude = escape($_POST['latitude']);
        $longitude = escape($_POST['longitude']);
        $is_default = isset($_POST['is_default']) ? 1 : 0;
        
        // If this is set as default, remove default from other addresses
        if($is_default) {
            query("UPDATE shipping_addresses SET is_default = 0 WHERE user_id = '$user_id'");
        }
        
        $insert_query = "INSERT INTO shipping_addresses (user_id, recipient_name, phone, full_address, city, province, postal_code, latitude, longitude, is_default) 
                        VALUES ('$user_id', '$recipient_name', '$phone', '$full_address', '$city', '$province', '$postal_code', '$latitude', '$longitude', '$is_default')";
        
        if(query($insert_query)) {
            set_message('success', 'Alamat berhasil ditambahkan!');
        } else {
            set_message('error', 'Gagal menambahkan alamat!');
        }
        header('Location: profile.php?page=address');
        exit();
    }
    
    // Handle edit/update shipping address
    if(isset($_POST['update_address'])) {
        $address_id = escape($_POST['address_id']);
        $recipient_name = escape($_POST['recipient_name']);
        $phone = escape($_POST['phone']);
        $full_address = escape($_POST['full_address']);
        $city = escape($_POST['city']);
        $province = escape($_POST['province']);
        $postal_code = escape($_POST['postal_code']);
        $latitude = escape($_POST['latitude']);
        $longitude = escape($_POST['longitude']);
        $is_default = isset($_POST['is_default']) ? 1 : 0;
        
        // If this is set as default, remove default from other addresses
        if($is_default) {
            query("UPDATE shipping_addresses SET is_default = 0 WHERE user_id = '$user_id'");
        }
        
        $update_query = "UPDATE shipping_addresses SET 
                        recipient_name = '$recipient_name', 
                        phone = '$phone', 
                        full_address = '$full_address', 
                        city = '$city', 
                        province = '$province', 
                        postal_code = '$postal_code', 
                        latitude = '$latitude', 
                        longitude = '$longitude', 
                        is_default = '$is_default' 
                        WHERE address_id = '$address_id' AND user_id = '$user_id'";
        
        if(query($update_query)) {
            set_message('success', 'Alamat berhasil diupdate!');
        } else {
            set_message('error', 'Gagal mengupdate alamat!');
        }
        header('Location: profile.php?page=address');
        exit();
    }
    
    // Handle delete address
    if(isset($_POST['delete_address'])) {
        $address_id = escape($_POST['address_id']);
        query("DELETE FROM shipping_addresses WHERE address_id = '$address_id' AND user_id = '$user_id'");
        set_message('success', 'Alamat berhasil dihapus!');
        header('Location: profile.php?page=address');
        exit();
    }
    
    // Handle set default address
    if(isset($_POST['set_default'])) {
        $address_id = escape($_POST['address_id']);
        query("UPDATE shipping_addresses SET is_default = 0 WHERE user_id = '$user_id'");
        query("UPDATE shipping_addresses SET is_default = 1 WHERE address_id = '$address_id' AND user_id = '$user_id'");
        set_message('success', 'Alamat default berhasil diubah!');
        header('Location: profile.php?page=address');
        exit();
    }
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings - RetroLoved</title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/toast.css">
    <style>
        body {
            background: #E5E7EB;
        }
        
        .profile-wrapper {
            display: flex;
            max-width: 1200px;
            margin: 40px auto;
            gap: 20px;
            padding: 0 20px;
        }
        
        /* Sidebar */
        .profile-sidebar {
            width: 250px;
            background: white;
            border-radius: 12px;
            padding: 20px 0;
            height: fit-content;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-menu li {
            margin: 0;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px 20px;
            color: #6B7280;
            text-decoration: none;
            transition: all 0.2s;
            font-weight: 500;
        }
        
        .sidebar-menu a:hover {
            background: #F9FAFB;
            color: #1F2937;
        }
        
        .sidebar-menu a.active {
            background: #FEF3C7;
            color: #D97706;
            font-weight: 600;
            border-left: 3px solid #D97706;
        }
        
        .sidebar-menu a.active svg {
            stroke: #D97706;
        }
        
        .sidebar-menu svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
            margin-top: 2px;
        }
        
        /* Mobile Navigation Tabs */
        .mobile-nav-tabs {
            display: none;
            background: #FFFFFF;
            border-bottom: 1px solid #E5E7EB;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .mobile-nav-tabs ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 0;
        }
        
        .mobile-nav-tabs li {
            margin: 0;
            flex: 1;
        }
        
        .mobile-nav-tabs a {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px 8px;
            color: #6B7280;
            text-decoration: none;
            font-weight: 500;
            font-size: 12px;
            text-align: center;
            border-bottom: 3px solid transparent;
            transition: all 0.2s;
            background: transparent;
        }
        
        .mobile-nav-tabs a:hover {
            color: #D97706;
            background: rgba(217, 119, 6, 0.05);
        }
        
        .mobile-nav-tabs a.active {
            color: #D97706;
            font-weight: 600;
            border-bottom-color: #D97706;
            background: transparent;
        }
        
        /* Content Area */
        .profile-content {
            flex: 1;
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .content-header h1 {
            font-size: 24px;
            color: #1F2937;
            margin-bottom: 8px;
        }
        
        .content-header p {
            color: #6B7280;
            margin-bottom: 30px;
        }
        
        /* Profile Picture */
        .profile-picture-section {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .profile-picture-display {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #D97706, #F59E0B);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            font-weight: 700;
            color: white;
            overflow: hidden;
            flex-shrink: 0;
        }
        
        .profile-picture-display img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .profile-picture-actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1;
            align-items: flex-start;
        }
        
        .profile-buttons-row {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
            width: 100%;
        }
        
        .profile-requirement-text {
            color: #6B7280;
            font-size: 12px;
            line-height: 1.5;
            margin-left: 0;
        }
        
        .btn-change-photo {
            padding: 10px 20px;
            background: #1F2937;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 40px;
        }
        
        .btn-change-photo:hover {
            background: #374151;
        }
        
        .btn-delete-photo {
            padding: 10px 20px;
            background: white;
            color: #DC2626;
            border: 1.5px solid #E5E7EB;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            height: 40px;
        }
        
        .btn-delete-photo:hover {
            background: #FEE2E2;
            border-color: #DC2626;
        }
        
        /* Form Styles */
        .form-group {
            margin-bottom: 24px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #374151;
            font-weight: 600;
            font-size: 14px;
        }
        
        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #E5E7EB;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.2s;
            font-family: inherit;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #D97706;
        }
        
        .form-input:disabled {
            background: #F9FAFB;
            color: #9CA3AF;
        }
        
        /* Password Requirements Indicator */
        .password-requirements {
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 16px;
            background: #F9FAFB;
            border-radius: 8px;
            border: 1px solid #E5E7EB;
        }
        
        .requirement-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            color: #6B7280;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .requirement-item .req-icon {
            flex-shrink: 0;
            opacity: 0.3;
        }
        
        .requirement-item.valid {
            color: #059669;
        }
        
        .requirement-item.valid .req-icon {
            opacity: 1;
            stroke: #059669;
        }
        
        .requirement-item.invalid {
            color: #DC2626;
        }
        
        .requirement-item.invalid .req-icon {
            opacity: 1;
            stroke: #DC2626;
        }
        
        .req-icon {
            flex-shrink: 0;
            stroke: currentColor;
        }
        
        .req-text {
            line-height: 1.4;
        }
        
        /* Password Input Wrapper with Toggle */
        .password-input-wrapper {
            position: relative;
        }
        
        .password-input-wrapper .form-input {
            padding-right: 45px;
        }
        
        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9CA3AF;
            transition: color 0.2s;
        }
        
        .toggle-password:hover {
            color: #6B7280;
        }
        
        .toggle-password svg {
            width: 20px;
            height: 20px;
        }
        
        .btn-submit {
            padding: 12px 24px;
            background: #1F2937;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            flex: 1;
        }
        
        .btn-submit:hover {
            background: #374151;
        }
        
        .btn-cancel {
            padding: 12px 24px;
            background: white;
            color: #6B7280;
            border: 1.5px solid #E5E7EB;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            flex: 1;
        }
        
        .btn-cancel:hover {
            background: #F9FAFB;
            border-color: #9CA3AF;
            color: #374151;
        }
        
        #profileFormButtons,
        #accountFormButtons {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }
        
        /* Confirmation Modal */
        .confirmation-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 10000;
            justify-content: center;
            align-items: center;
        }
        
        .confirmation-modal-content {
            background: white;
            border-radius: 12px;
            padding: 30px;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
            margin: 20px;
        }
        
        .confirmation-modal-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }
        
        .confirmation-modal-header svg {
            color: #F59E0B;
            flex-shrink: 0;
        }
        
        .confirmation-modal-header h3 {
            font-size: 18px;
            font-weight: 700;
            color: #1F2937;
            margin: 0;
        }
        
        .confirmation-modal-body {
            color: #6B7280;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        
        .confirmation-modal-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            flex-wrap: wrap;
        }
        
        .confirmation-modal-actions button {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            flex: 1;
            min-width: 120px;
        }
        
        .btn-modal-cancel {
            background: white;
            color: #6B7280;
            border: 1.5px solid #E5E7EB !important;
        }
        
        .btn-modal-cancel:hover {
            background: #F9FAFB;
        }
        
        .btn-modal-save {
            background: #1F2937;
            color: white;
        }
        
        .btn-modal-save:hover {
            background: #374151;
        }
        
        .btn-modal-discard {
            background: #DC2626;
            color: white;
        }
        
        .btn-modal-discard:hover {
            background: #B91C1C;
        }
        
        input[type="file"] {
            display: none;
        }
        
        .link-change {
            color: #1F2937;
            text-decoration: underline;
            font-size: 14px;
            cursor: pointer;
            font-weight: 600;
        }
        
        .link-change:hover {
            color: #D97706;
        }
        
        /* Account Settings */
        .account-item {
            margin-bottom: 24px;
            padding-bottom: 24px;
            border-bottom: 1px solid #E5E7EB;
        }
        
        .account-item:last-child {
            border-bottom: none;
        }
        
        .account-item label {
            display: block;
            color: #1F2937;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .account-item-value {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .account-item-value span {
            color: #1F2937;
            font-size: 15px;
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
        }
        
        .alert-success {
            background: #D1FAE5;
            color: #065F46;
        }
        
        .alert-error {
            background: #FEE2E2;
            color: #991B1B;
        }
        
        @media (max-width: 768px) {
            .profile-wrapper {
                flex-direction: column;
                margin: 0;
                padding: 0;
                gap: 0;
            }
            
            .profile-sidebar {
                display: none;
            }
            
            .mobile-nav-tabs {
                display: block;
            }
            
            .profile-content {
                border-radius: 0;
                margin-top: 0;
            }
            
            .profile-picture-section {
                flex-direction: row;
                align-items: center;
                gap: 12px;
            }
            
            .profile-picture-display {
                width: 80px;
                height: 80px;
                font-size: 32px;
            }
            
            .profile-picture-actions {
                flex: 1;
                align-items: flex-start;
                justify-content: center;
            }
            
            .profile-buttons-row {
                flex-wrap: wrap;
                gap: 8px;
            }
            
            .btn-change-photo,
            .btn-delete-photo {
                padding: 8px 14px;
                font-size: 13px;
            }
            
            .profile-requirement-text {
                text-align: left;
                font-size: 11px;
            }
            
            .confirmation-modal-content {
                padding: 20px;
                width: 95%;
                margin: 10px;
            }
            
            .confirmation-modal-header {
                flex-wrap: wrap;
            }
            
            .confirmation-modal-header h3 {
                font-size: 16px;
            }
            
            .confirmation-modal-body {
                font-size: 14px;
            }
            
            .confirmation-modal-actions {
                flex-direction: column;
            }
            
            .confirmation-modal-actions button {
                width: 100%;
                min-width: auto;
            }
            
            .btn-submit {
                width: 100%;
                float: none;
            }
            
            #profileFormButtons,
            #accountFormButtons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <!-- BREADCRUMB -->
    <div class="breadcrumb-container">
        <div class="container">
            <nav class="breadcrumb">
                <a href="../index.php" class="breadcrumb-item">Home</a>
                <span class="breadcrumb-separator">/</span>
                <span class="breadcrumb-item active">Profile Settings</span>
            </nav>
        </div>
    </div>
    
    <!-- Mobile Navigation Tabs -->
    <nav class="mobile-nav-tabs">
        <ul>
            <li>
                <a href="profile.php?page=profile" class="<?php echo $page == 'profile' ? 'active' : ''; ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px; margin-right: 4px;">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    Info Pribadi
                </a>
            </li>
            <li>
                <a href="profile.php?page=account" class="<?php echo $page == 'account' ? 'active' : ''; ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px; margin-right: 4px;">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    Keamanan
                </a>
            </li>
            <li>
                <a href="profile.php?page=address" class="<?php echo $page == 'address' ? 'active' : ''; ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px; margin-right: 4px;">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                    Alamat
                </a>
            </li>
        </ul>
    </nav>
    
    <div class="profile-wrapper">
        <!-- Sidebar -->
        <aside class="profile-sidebar">
            <ul class="sidebar-menu">
                <li>
                    <a href="profile.php?page=profile" class="<?php echo $page == 'profile' ? 'active' : ''; ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <div>
                            <div style="font-weight: 600;">Informasi Pribadi</div>
                            <div style="font-size: 12px; color: #9CA3AF;">Nama & foto profil</div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="profile.php?page=account" class="<?php echo $page == 'account' ? 'active' : ''; ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        <div>
                            <div style="font-weight: 600;">Keamanan Akun</div>
                            <div style="font-size: 12px; color: #9CA3AF;">Username & password</div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="profile.php?page=address" class="<?php echo $page == 'address' ? 'active' : ''; ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <div>
                            <div style="font-weight: 600;">Alamat Pengiriman</div>
                            <div style="font-size: 12px; color: #9CA3AF;">Kelola alamat pengiriman</div>
                        </div>
                    </a>
                </li>
            </ul>
        </aside>
        
        <!-- Content -->
        <main class="profile-content">
            <?php 
            // Display message using new system
            if(isset($_SESSION['message'])): 
                $msg = $_SESSION['message'];
                $type = is_array($msg) ? $msg['type'] : 'info';
                $text = is_array($msg) ? $msg['text'] : $msg;
            ?>
                <div class="alert alert-<?php echo $type; ?>">
                    <?php 
                        echo htmlspecialchars($text);
                        unset($_SESSION['message']);
                    ?>
                </div>
            <?php endif; ?>
            
            <?php if($page == 'profile'): ?>
                <!-- PROFILE PAGE -->
                <div class="content-header">
                    <h1>Profil</h1>
                    <p>Atur dan perbarui profil toko kamu di sini.</p>
                </div>
                
                <!-- Profile Picture Section -->
                <div class="profile-picture-section">
                    <div class="profile-picture-display" id="profilePictureDisplay">
                        <?php if(!empty($user['profile_picture']) && file_exists('../assets/images/profiles/' . $user['profile_picture'])): ?>
                            <img id="profileImage" src="<?php echo $base_url; ?>assets/images/profiles/<?php echo $user['profile_picture']; ?>" alt="Profile">
                        <?php else: ?>
                            <span id="profileInitial"><?php echo strtoupper(substr($user['full_name'], 0, 1)); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="profile-picture-actions">
                        <div class="profile-buttons-row">
                            <label for="profilePictureInput" class="btn-change-photo" style="cursor: pointer;">Ganti gambar</label>
                            
                            <?php if(!empty($user['profile_picture'])): ?>
                                <button type="button" onclick="deleteProfilePicture()" class="btn-delete-photo">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    </svg>
                                    Hapus
                                </button>
                            <?php endif; ?>
                        </div>
                        <span class="profile-requirement-text">Format: JPG, PNG, GIF, WEBP (Max 2MB)</span>
                    </div>
                </div>
                
                <form method="POST" enctype="multipart/form-data" id="profileForm">
                    <input type="file" name="profile_picture" id="profilePictureInput" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" style="display: none;" onchange="previewProfilePicture(this)">
                    <input type="hidden" name="delete_picture_flag" id="deletePictureFlag" value="0">
                    
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="full_name" id="fullNameInput" class="form-input" value="<?php echo $user['full_name']; ?>" required oninput="markFormChanged()">
                    </div>
                    
                    <div class="form-group">
                        <label>Tanggal lahir</label>
                        <input type="date" name="birth_date" id="birthDateInput" class="form-input" value="<?php echo $user['birth_date'] ?? ''; ?>" 
                               style="color: #9CA3AF;" 
                               onfocus="this.style.color='#1F2937'" 
                               onblur="if(this.value=='') this.style.color='#9CA3AF'"
                               onchange="markFormChanged()">
                    </div>
                    
                    <div id="profileFormButtons">
                        <button type="submit" name="update_profile" class="btn-submit">Simpan</button>
                        <button type="button" onclick="cancelProfileChanges()" class="btn-cancel" id="profileCancelBtn" style="display: none;">Batal</button>
                    </div>
                </form>
                
            <?php elseif($page == 'account'): ?>
                <!-- ACCOUNT PAGE -->
                <div class="content-header">
                    <h1>Account settings</h1>
                    <p>Atur info akun kamu di sini, mulai dari email, username, sampai ganti password.</p>
                </div>
                
                <form method="POST" id="accountForm">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" id="usernameInput" class="form-input" value="<?php echo $user['username']; ?>" required oninput="markAccountFormChanged()" autocomplete="off">
                    </div>
                    
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" id="emailInput" class="form-input" value="<?php echo $user['email']; ?>" required oninput="markAccountFormChanged()" autocomplete="off">
                    </div>
                    
                    <div style="border-top: 2px solid #E5E7EB; padding-top: 24px; margin-top: 24px; margin-bottom: 24px;">
                        <h3 style="font-size: 16px; color: #1F2937; margin-bottom: 16px;">Ganti Password (Opsional)</h3>
                        <p style="color: #6B7280; font-size: 14px; margin-bottom: 20px;">Kosongkan jika tidak ingin mengubah password</p>
                        
                        <div class="form-group">
                            <label>Password Lama</label>
                            <div class="password-input-wrapper">
                                <input type="password" name="current_password" id="currentPasswordInput" class="form-input" oninput="markAccountFormChanged()" autocomplete="new-password">
                                <button type="button" class="toggle-password" onclick="togglePasswordVisibility('currentPasswordInput', this)">
                                    <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    <svg class="eye-off-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: none;">
                                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                        <line x1="1" y1="1" x2="23" y2="23"></line>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Password Baru</label>
                            <div class="password-input-wrapper">
                                <input type="password" name="new_password" id="newPasswordInput" class="form-input" minlength="8" oninput="markAccountFormChanged(); validatePassword()" autocomplete="new-password">
                                <button type="button" class="toggle-password" onclick="togglePasswordVisibility('newPasswordInput', this)">
                                    <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    <svg class="eye-off-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: none;">
                                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                        <line x1="1" y1="1" x2="23" y2="23"></line>
                                    </svg>
                                </button>
                            </div>
                            
                            <!-- Password Requirements Indicator -->
                            <div class="password-requirements" style="margin-top: 12px;">
                                <div class="requirement-item" id="req-length">
                                    <svg class="req-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 6L9 17l-5-5"></path>
                                    </svg>
                                    <span class="req-text">Minimum of 8 characters</span>
                                </div>
                                <div class="requirement-item" id="req-case">
                                    <svg class="req-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 6L9 17l-5-5"></path>
                                    </svg>
                                    <span class="req-text">Uppercase, lowercase letters and one number</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Konfirmasi Password Baru</label>
                            <div class="password-input-wrapper">
                                <input type="password" name="confirm_password" id="confirmPasswordInput" class="form-input" minlength="8" oninput="markAccountFormChanged()" autocomplete="new-password">
                                <button type="button" class="toggle-password" onclick="togglePasswordVisibility('confirmPasswordInput', this)">
                                    <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    <svg class="eye-off-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: none;">
                                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                        <line x1="1" y1="1" x2="23" y2="23"></line>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div id="accountFormButtons">
                        <button type="submit" name="update_account" class="btn-submit">Simpan</button>
                        <button type="button" onclick="cancelAccountChanges()" class="btn-cancel" id="accountCancelBtn" style="display: none;">Batal</button>
                    </div>
                </form>
                
            <?php elseif($page == 'address'): ?>
                <!-- ADDRESS PAGE -->
                <div class="content-header">
                    <h1>Alamat pengiriman</h1>
                    <p>Untuk barang yang Anda beli, pastikan alamat pengiriman selalu terbaru.</p>
                </div>
                
                <button onclick="showAddAddressModal()" style="display: flex; align-items: center; gap: 8px; padding: 12px 20px; background: #1F2937; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; margin-bottom: 24px; transition: all 0.3s;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Tambahkan alamat
                </button>
                
                <?php if(mysqli_num_rows($addresses) > 0): ?>
                    <?php while($address = mysqli_fetch_assoc($addresses)): ?>
                        <div style="background: white; border: 2px solid <?php echo $address['is_default'] ? '#D97706' : '#E5E7EB'; ?>; border-radius: 12px; padding: 20px; margin-bottom: 16px; position: relative;">
                            <?php if($address['is_default']): ?>
                                <span style="position: absolute; top: 16px; right: 16px; background: #FEF3C7; color: #D97706; padding: 4px 12px; border-radius: 6px; font-size: 12px; font-weight: 600;">Default</span>
                            <?php endif; ?>
                            
                            <h3 style="font-size: 16px; font-weight: 700; color: #1F2937; margin-bottom: 8px;">
                                <?php echo $address['recipient_name']; ?>
                            </h3>
                            <p style="color: #6B7280; margin-bottom: 4px;"><?php echo $address['phone']; ?></p>
                            <p style="color: #1F2937; line-height: 1.6; margin-bottom: 12px;">
                                <?php echo $address['full_address']; ?><br>
                                <?php echo $address['city']; ?>, <?php echo $address['province']; ?> <?php echo $address['postal_code']; ?>
                            </p>
                            
                            <div style="display: flex; gap: 12px; margin-top: 16px;">
                                <button type="button" onclick='showEditAddressModal(<?php echo json_encode($address); ?>)' style="padding: 8px 16px; background: white; color: #3B82F6; border: 1.5px solid #3B82F6; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 14px; transition: all 0.3s;">
                                    Edit
                                </button>
                                
                                <?php if(!$address['is_default']): ?>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="address_id" value="<?php echo $address['address_id']; ?>">
                                        <button type="submit" name="set_default" style="padding: 8px 16px; background: white; color: #D97706; border: 1.5px solid #D97706; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 14px; transition: all 0.3s;">
                                            Set as Default
                                        </button>
                                    </form>
                                <?php endif; ?>
                                
                                <form method="POST" style="display: inline;" id="deleteAddressForm<?php echo $address['address_id']; ?>">
                                    <input type="hidden" name="address_id" value="<?php echo $address['address_id']; ?>">
                                    <button type="button" onclick="showDeleteAddressConfirm(<?php echo $address['address_id']; ?>)" style="padding: 8px 16px; background: white; color: #DC2626; border: 1.5px solid #E5E7EB; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 14px; transition: all 0.3s;">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div style="text-align: center; padding: 60px 20px; background: #F9FAFB; border-radius: 12px; border: 2px dashed #E5E7EB;">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" style="margin: 0 auto 16px;">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <p style="color: #6B7280; font-size: 16px;">Belum ada alamat tersimpan</p>
                    </div>
                <?php endif; ?>
                
            <?php endif; ?>
            
            <!-- Add Address Modal -->
            <div id="addressModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; overflow-y: auto;">
                <div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px;">
                    <div style="background: white; border-radius: 16px; max-width: 600px; width: 100%; max-height: 90vh; overflow-y: auto; position: relative;">
                        <div style="padding: 24px; border-bottom: 1px solid #E5E7EB; position: sticky; top: 0; background: white; z-index: 10;">
                            <h2 style="font-size: 20px; font-weight: 700; color: #1F2937;">Tambah Alamat Baru</h2>
                            <button onclick="closeAddAddressModal()" style="position: absolute; top: 24px; right: 24px; background: none; border: none; cursor: pointer; color: #6B7280;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </div>
                        
                        <form method="POST" id="addressForm" style="padding: 24px;">
                            
                            <div class="form-group">
                                <label>Nama Penerima</label>
                                <input type="text" name="recipient_name" class="form-input" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Nomor Telepon</label>
                                <input type="tel" name="phone" class="form-input" required placeholder="08123456789">
                            </div>
                            
                            <div class="form-group">
                                <label>Kota</label>
                                <input type="text" name="city" class="form-input" required placeholder="Contoh: Surabaya">
                            </div>
                            
                            <div class="form-group">
                                <label>Provinsi</label>
                                <input type="text" name="province" class="form-input" required placeholder="Contoh: Jawa Timur">
                            </div>
                            
                            <div class="form-group">
                                <label>Alamat Lengkap</label>
                                <textarea name="full_address" class="form-input" rows="4" required placeholder="Jalan, nomor rumah, RT/RW, kelurahan, kecamatan"></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label>Kode Pos</label>
                                <input type="text" name="postal_code" class="form-input" required placeholder="60123">
                            </div>
                            
                            <input type="hidden" name="latitude" value="0">
                            <input type="hidden" name="longitude" value="0">
                            
                            <div style="margin-bottom: 20px;">
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; user-select: none;">
                                    <input type="checkbox" name="is_default" style="width: 16px; height: 16px; cursor: pointer; flex-shrink: 0; accent-color: #10B981;">
                                    <span style="color: #374151; font-weight: 500; font-size: 14px;">Jadikan alamat utama</span>
                                </label>
                            </div>
                            
                            <button type="submit" name="add_address" class="btn-submit" style="width: 100%; float: none;">Simpan Alamat</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Edit Address Modal -->
            <div id="editAddressModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; overflow-y: auto;">
                <div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px;">
                    <div style="background: white; border-radius: 16px; max-width: 600px; width: 100%; max-height: 90vh; overflow-y: auto; position: relative;">
                        <div style="padding: 24px; border-bottom: 1px solid #E5E7EB; position: sticky; top: 0; background: white; z-index: 10;">
                            <h2 style="font-size: 20px; font-weight: 700; color: #1F2937;">Edit Alamat</h2>
                            <button onclick="closeEditAddressModal()" style="position: absolute; top: 24px; right: 24px; background: none; border: none; cursor: pointer; color: #6B7280;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </div>
                        
                        <form method="POST" id="editAddressForm" style="padding: 24px;">
                            <input type="hidden" name="address_id" id="edit_address_id">
                            
                            <div class="form-group">
                                <label>Nama Penerima</label>
                                <input type="text" name="recipient_name" id="edit_recipient_name" class="form-input" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Nomor Telepon</label>
                                <input type="tel" name="phone" id="edit_phone" class="form-input" required placeholder="08123456789">
                            </div>
                            
                            <div class="form-group">
                                <label>Kota</label>
                                <input type="text" name="city" id="edit_city" class="form-input" required placeholder="Contoh: Surabaya">
                            </div>
                            
                            <div class="form-group">
                                <label>Provinsi</label>
                                <input type="text" name="province" id="edit_province" class="form-input" required placeholder="Contoh: Jawa Timur">
                            </div>
                            
                            <div class="form-group">
                                <label>Alamat Lengkap</label>
                                <textarea name="full_address" id="edit_full_address" class="form-input" rows="4" required placeholder="Jalan, nomor rumah, RT/RW, kelurahan, kecamatan"></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label>Kode Pos</label>
                                <input type="text" name="postal_code" id="edit_postal_code" class="form-input" required placeholder="60123">
                            </div>
                            
                            <input type="hidden" name="latitude" id="edit_latitude" value="0">
                            <input type="hidden" name="longitude" id="edit_longitude" value="0">
                            
                            <div style="margin-bottom: 20px;">
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; user-select: none;">
                                    <input type="checkbox" name="is_default" id="edit_is_default" style="width: 16px; height: 16px; cursor: pointer; flex-shrink: 0; accent-color: #10B981;">
                                    <span style="color: #374151; font-weight: 500; font-size: 14px;">Jadikan alamat utama</span>
                                </label>
                            </div>
                            
                            <button type="submit" name="update_address" class="btn-submit" style="width: 100%; float: none;">Update Alamat</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Confirmation Modal (Navigation) -->
    <div class="confirmation-modal" id="confirmationModal">
        <div class="confirmation-modal-content">
            <div class="confirmation-modal-header">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
                <h3>Perubahan Belum Disimpan</h3>
            </div>
            <div class="confirmation-modal-body">
                <p>Anda memiliki perubahan yang belum disimpan. Apa yang ingin Anda lakukan?</p>
            </div>
            <div class="confirmation-modal-actions">
                <button type="button" class="btn-modal-cancel" onclick="stayOnPage()">Tetap di Halaman Ini</button>
                <button type="button" class="btn-modal-discard" onclick="discardChanges()">Buang Perubahan</button>
                <button type="button" class="btn-modal-save" onclick="saveBeforeLeave()">Simpan & Lanjutkan</button>
            </div>
        </div>
    </div>
    
    
    <?php include '../includes/footer.php'; ?>
    
    <script src="<?php echo $base_url; ?>assets/js/toast.js"></script>
    <script src="<?php echo $base_url; ?>assets/js/modal.js"></script>
    <script src="<?php echo $base_url; ?>assets/js/loading.js"></script>
    <script src="<?php echo $base_url; ?>assets/js/validation.js"></script>
    <script src="<?php echo $base_url; ?>assets/js/accessibility.js"></script>
    <script src="<?php echo $base_url; ?>assets/js/script.js"></script>
    
    <script>
        // Store original values
        let originalProfileData = {
            fullName: '<?php echo addslashes($user['full_name']); ?>',
            birthDate: '<?php echo addslashes($user['birth_date'] ?? ''); ?>',
            profilePicture: '<?php echo addslashes($user['profile_picture'] ?? ''); ?>',
            hasImage: <?php echo !empty($user['profile_picture']) ? 'true' : 'false'; ?>
        };
        
        let originalAccountData = {
            username: '<?php echo addslashes($user['username']); ?>',
            email: '<?php echo addslashes($user['email']); ?>'
        };
        
        let profileFormChanged = false;
        let accountFormChanged = false;
        let pictureDeleted = false;
        let pendingNavigation = null;
        let isSubmitting = false;
        
        // Profile form functions
        function previewProfilePicture(input) {
            console.log('previewProfilePicture called');
            if (input.files && input.files[0]) {
                const file = input.files[0];
                console.log('File selected:', file.name, file.size, file.type);
                
                // Validate file size (2MB max)
                if (file.size > 2 * 1024 * 1024) {
                    toastError('Ukuran file terlalu besar! Maksimal 2MB.');
                    input.value = '';
                    return;
                }
                
                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    toastError('Format file tidak didukung! Gunakan JPG, PNG, GIF, atau WEBP.');
                    input.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const display = document.getElementById('profilePictureDisplay');
                    display.innerHTML = '<img id="profileImage" src="' + e.target.result + '" alt="Profile">';
                    
                    pictureDeleted = false;
                    document.getElementById('deletePictureFlag').value = '0';
                    
                    // Mark form changed - this will check if picture actually changed
                    markFormChanged();
                };
                reader.readAsDataURL(file);
            }
        }
        
        function deleteProfilePicture() {
            console.log('deleteProfilePicture called');
            
            // Only allow deletion if there's currently a picture
            if (!originalProfileData.hasImage) {
                console.log('No picture to delete');
                return;
            }
            
            const display = document.getElementById('profilePictureDisplay');
            const initial = '<?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>';
            display.innerHTML = '<span id="profileInitial">' + initial + '</span>';
            
            // Clear the file input
            const fileInput = document.getElementById('profilePictureInput');
            fileInput.value = '';
            
            pictureDeleted = true;
            document.getElementById('deletePictureFlag').value = '1';
            
            // Mark form changed - this will detect the deletion
            markFormChanged();
        }
        
        function markFormChanged() {
            const currentPage = '<?php echo $page; ?>';
            console.log('markFormChanged called, currentPage:', currentPage);
            
            if (currentPage === 'profile') {
                // Check if profile data actually changed
                const currentFullName = document.getElementById('fullNameInput').value;
                const currentBirthDate = document.getElementById('birthDateInput').value;
                const hasNewPicture = document.getElementById('profilePictureInput').files.length > 0;
                
                const nameChanged = currentFullName !== originalProfileData.fullName;
                const birthDateChanged = currentBirthDate !== originalProfileData.birthDate;
                const pictureChanged = hasNewPicture || pictureDeleted;
                
                console.log('Profile change detection:');
                console.log('- Name changed:', nameChanged, '(', originalProfileData.fullName, '->', currentFullName, ')');
                console.log('- Birth date changed:', birthDateChanged, '(', originalProfileData.birthDate, '->', currentBirthDate, ')');
                console.log('- Picture changed:', pictureChanged, '(hasNew:', hasNewPicture, ', deleted:', pictureDeleted, ')');
                
                if (nameChanged || birthDateChanged || pictureChanged) {
                    profileFormChanged = true;
                    const cancelBtn = document.getElementById('profileCancelBtn');
                    if (cancelBtn) {
                        cancelBtn.style.display = 'block';
                        console.log('Profile has real changes - showing cancel button');
                    }
                } else {
                    profileFormChanged = false;
                    const cancelBtn = document.getElementById('profileCancelBtn');
                    if (cancelBtn) {
                        cancelBtn.style.display = 'none';
                        console.log('Profile has NO real changes - hiding cancel button');
                    }
                }
            } else if (currentPage === 'account') {
                accountFormChanged = true;
                const cancelBtn = document.getElementById('accountCancelBtn');
                if (cancelBtn) {
                    cancelBtn.style.display = 'block';
                    console.log('Account cancel button shown, accountFormChanged:', accountFormChanged);
                } else {
                    console.error('Account cancel button not found!');
                }
            }
        }
        
        function cancelProfileChanges() {
            console.log('cancelProfileChanges called');
            
            // Reset form values
            document.getElementById('fullNameInput').value = originalProfileData.fullName;
            document.getElementById('birthDateInput').value = originalProfileData.birthDate;
            document.getElementById('profilePictureInput').value = '';
            document.getElementById('deletePictureFlag').value = '0';
            
            // Reset picture display
            const display = document.getElementById('profilePictureDisplay');
            if (originalProfileData.hasImage && originalProfileData.profilePicture) {
                display.innerHTML = '<img id="profileImage" src="../assets/images/profiles/' + originalProfileData.profilePicture + '" alt="Profile">';
            } else {
                const initial = '<?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>';
                display.innerHTML = '<span id="profileInitial">' + initial + '</span>';
            }
            
            // Reset all flags
            pictureDeleted = false;
            profileFormChanged = false;
            
            // Hide cancel button
            document.getElementById('profileCancelBtn').style.display = 'none';
            
            console.log('Profile changes cancelled, all data reset to original');
        }
        
        // Handle profile form submission
        const profileFormElement = document.getElementById('profileForm');
        if (profileFormElement) {
            profileFormElement.addEventListener('submit', function(e) {
                console.log('Profile form submit event triggered');
                console.log('Form data check:', new FormData(this));
                
                // Set flags to allow navigation
                isSubmitting = true;
                profileFormChanged = false;
                
                // Let form submit naturally - DO NOT preventDefault
                // Form will be submitted to server normally via POST
            });
        }
        
        // Prevent autofill from triggering change detection
        let isPageLoaded = false;
        window.addEventListener('load', function() {
            setTimeout(function() {
                isPageLoaded = true;
                console.log('Page fully loaded, change detection now active');
            }, 500);
        });
        
        // Account form functions
        function markAccountFormChanged() {
            // Ignore changes if page just loaded (autofill)
            if (!isPageLoaded) {
                console.log('Page not fully loaded yet, ignoring autofill changes');
                return;
            }
            
            const currentUsername = document.getElementById('usernameInput').value;
            const currentEmail = document.getElementById('emailInput').value;
            const currentPassword = document.getElementById('currentPasswordInput')?.value || '';
            const newPassword = document.getElementById('newPasswordInput')?.value || '';
            const confirmPassword = document.getElementById('confirmPasswordInput')?.value || '';
            
            const usernameChanged = currentUsername !== originalAccountData.username;
            const emailChanged = currentEmail !== originalAccountData.email;
            const passwordChanged = currentPassword || newPassword || confirmPassword;
            
            console.log('Account change detection:');
            console.log('- Username changed:', usernameChanged, '(', originalAccountData.username, '->', currentUsername, ')');
            console.log('- Email changed:', emailChanged, '(', originalAccountData.email, '->', currentEmail, ')');
            console.log('- Password changed:', passwordChanged);
            
            if (usernameChanged || emailChanged || passwordChanged) {
                accountFormChanged = true;
                const cancelBtn = document.getElementById('accountCancelBtn');
                if (cancelBtn) {
                    cancelBtn.style.display = 'inline-block';
                    console.log('Account has real changes - showing cancel button');
                }
            } else {
                accountFormChanged = false;
                const cancelBtn = document.getElementById('accountCancelBtn');
                if (cancelBtn) {
                    cancelBtn.style.display = 'none';
                    console.log('Account has NO real changes - hiding cancel button');
                }
            }
        }
        
        function cancelAccountChanges() {
            // Reset form values
            document.getElementById('usernameInput').value = originalAccountData.username;
            document.getElementById('emailInput').value = originalAccountData.email;
            document.getElementById('currentPasswordInput').value = '';
            document.getElementById('newPasswordInput').value = '';
            document.getElementById('confirmPasswordInput').value = '';
            
            // Reset password requirements to default gray
            document.getElementById('req-length').className = 'requirement-item';
            document.getElementById('req-case').className = 'requirement-item';
            
            accountFormChanged = false;
            document.getElementById('accountCancelBtn').style.display = 'none';
        }
        
        // Validate password requirements
        function validatePassword() {
            const password = document.getElementById('newPasswordInput').value;
            const reqLength = document.getElementById('req-length');
            const reqCase = document.getElementById('req-case');
            
            // If password is empty, reset to gray
            if (password === '') {
                reqLength.className = 'requirement-item';
                reqCase.className = 'requirement-item';
                return;
            }
            
            // Check length requirement (minimum 8 characters)
            if (password.length >= 8) {
                reqLength.className = 'requirement-item valid';
            } else {
                reqLength.className = 'requirement-item invalid';
            }
            
            // Check case and number requirement (uppercase, lowercase, and one number)
            const hasUppercase = /[A-Z]/.test(password);
            const hasLowercase = /[a-z]/.test(password);
            const hasNumber = /\d/.test(password);
            
            if (hasUppercase && hasLowercase && hasNumber) {
                reqCase.className = 'requirement-item valid';
            } else {
                reqCase.className = 'requirement-item invalid';
            }
        }
        
        // Toggle password visibility
        function togglePasswordVisibility(inputId, button) {
            const input = document.getElementById(inputId);
            const eyeIcon = button.querySelector('.eye-icon');
            const eyeOffIcon = button.querySelector('.eye-off-icon');
            
            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.style.display = 'none';
                eyeOffIcon.style.display = 'block';
            } else {
                input.type = 'password';
                eyeIcon.style.display = 'block';
                eyeOffIcon.style.display = 'none';
            }
        }
        
        // Handle account form submission
        const accountFormElement = document.getElementById('accountForm');
        if (accountFormElement) {
            accountFormElement.addEventListener('submit', function(e) {
                console.log('Account form submit event triggered');
                console.log('Form data check:', new FormData(this));
                
                // Set flags to allow navigation
                isSubmitting = true;
                accountFormChanged = false;
                
                // Let form submit naturally - DO NOT preventDefault
                // Form will be submitted to server normally via POST
            });
        }
        
        // Modal functions
        function showConfirmationModal() {
            document.getElementById('confirmationModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        
        function hideConfirmationModal() {
            document.getElementById('confirmationModal').style.display = 'none';
            document.body.style.overflow = 'auto';
            pendingNavigation = null;
        }
        
        function stayOnPage() {
            hideConfirmationModal();
        }
        
        function discardChanges() {
            // Reset based on current page
            const currentPage = '<?php echo $page; ?>';
            if (currentPage === 'profile') {
                cancelProfileChanges();
            } else if (currentPage === 'account') {
                cancelAccountChanges();
            }
            
            hideConfirmationModal();
            
            // Navigate if there was pending navigation
            if (pendingNavigation) {
                isSubmitting = true; // Prevent another modal
                window.location.href = pendingNavigation;
            }
        }
        
        function saveBeforeLeave() {
            console.log('saveBeforeLeave called');
            console.log('pendingNavigation:', pendingNavigation);
            
            // IMPORTANT: Save pendingNavigation to local variable BEFORE hideConfirmationModal
            // because hideConfirmationModal() resets pendingNavigation to null
            const targetUrl = pendingNavigation;
            console.log('Saved targetUrl:', targetUrl);
            
            // Set flags to prevent beforeunload popup
            isSubmitting = true;
            profileFormChanged = false;
            accountFormChanged = false;
            
            // Hide modal (this will reset pendingNavigation to null)
            hideConfirmationModal();
            
            // Get current page and form
            const currentPage = '<?php echo $page; ?>';
            let form;
            let submitButtonName;
            
            if (currentPage === 'profile') {
                form = document.getElementById('profileForm');
                submitButtonName = 'update_profile';
            } else if (currentPage === 'account') {
                form = document.getElementById('accountForm');
                submitButtonName = 'update_account';
            }
            
            if (!form) {
                console.error('Form not found!');
                // Reset flags if form not found
                isSubmitting = false;
                return;
            }
            
            // Check form validity first - ONLY check required fields that are visible
            const visibleRequiredFields = Array.from(form.querySelectorAll('[required]')).filter(field => {
                return field.offsetParent !== null; // Only check visible fields
            });
            
            let hasEmptyRequired = false;
            visibleRequiredFields.forEach(field => {
                if (!field.value.trim()) {
                    hasEmptyRequired = true;
                    console.error('Empty required field:', field.name);
                }
            });
            
            if (hasEmptyRequired) {
                console.error('Form has empty required fields! Validation failed.');
                toastError('Mohon isi semua field yang wajib diisi!');
                // Reset flags
                isSubmitting = false;
                profileFormChanged = true;
                accountFormChanged = true;
                return;
            }
            
            console.log('All required fields are filled, proceeding with submission...');
            
            console.log('Using AJAX method for better redirect control');
            
            // Use AJAX to submit form, then redirect manually
            const formData = new FormData(form);
            
            // Add submit button name
            formData.append(submitButtonName, '1');
            
            console.log('Form data prepared. Submitting via AJAX...');
            console.log('FormData contents:');
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }
            
            // Submit via AJAX
            fetch(form.action || window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Fetch response received');
                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);
                
                // Wait for response to complete (even though we don't need the body)
                return response.text().then(text => {
                    console.log('Response body length:', text.length);
                    console.log('First 200 chars:', text.substring(0, 200));
                    return response;
                });
            })
            .then(response => {
                console.log('Response fully processed');
                
                // Add a small delay to ensure server-side processing is complete
                return new Promise(resolve => {
                    setTimeout(() => {
                        console.log('Delay complete, proceeding with redirect');
                        resolve(response);
                    }, 300);
                });
            })
            .then(response => {
                // Redirect to target URL (saved before modal was hidden)
                if (targetUrl) {
                    console.log('=== REDIRECTING TO:', targetUrl, '===');
                    // Use replace for cleaner navigation
                    window.location.replace(targetUrl);
                } else {
                    console.log('=== RELOADING PAGE (no targetUrl) ===');
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Form submission failed:', error);
                toastError('Gagal menyimpan data. Silakan coba lagi.');
                // Reset flags
                isSubmitting = false;
                profileFormChanged = true;
                accountFormChanged = true;
            });
        }
        
        // NOTE: beforeunload removed - we use custom modal instead for better UX
        // The custom modal at lines 1565-1593 handles navigation interception
        
        // Prevent navigation if there are unsaved changes (using event delegation for better coverage)
        document.addEventListener('click', function(e) {
            // Check if clicked element is a link or inside a link
            const link = e.target.closest('a[href]');
            
            // If not a link, ignore
            if (!link) return;
            
            console.log('Link clicked:', link.href);
            console.log('isSubmitting:', isSubmitting);
            console.log('profileFormChanged:', profileFormChanged);
            console.log('accountFormChanged:', accountFormChanged);
            
            // Skip logout links
            if (link.href.includes('logout')) {
                console.log('Skipping logout link');
                return;
            }
            
            // Skip if link has no href or is javascript: link
            if (!link.href || link.href.startsWith('javascript:')) {
                console.log('Skipping javascript or empty link');
                return;
            }
            
            // Check if it's a hash link to the SAME page (internal anchor)
            const currentPath = window.location.pathname;
            const linkUrl = new URL(link.href, window.location.origin);
            const linkPath = linkUrl.pathname;
            
            // If same page with just hash change, skip (internal navigation)
            if (linkPath === currentPath && linkUrl.hash) {
                console.log('Skipping same-page hash link (internal anchor)');
                return;
            }
            
            // Intercept navigation if there are unsaved changes (BUT NOT if form is being submitted)
            if (!isSubmitting && (profileFormChanged || accountFormChanged)) {
                console.log('Intercepting navigation - showing modal');
                e.preventDefault();
                e.stopPropagation();
                pendingNavigation = link.href;
                showConfirmationModal();
            } else {
                console.log('Allowing navigation (no changes or submitting)');
            }
        }, true); // Use capture phase to catch links early
        
        // Address modal functions
        function showAddAddressModal() {
            document.getElementById('addressModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
        
        function closeAddAddressModal() {
            document.getElementById('addressModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        // Edit Address Modal Functions
        function showEditAddressModal(address) {
            // Populate form with address data
            document.getElementById('edit_address_id').value = address.address_id;
            document.getElementById('edit_recipient_name').value = address.recipient_name;
            document.getElementById('edit_phone').value = address.phone;
            document.getElementById('edit_city').value = address.city;
            document.getElementById('edit_province').value = address.province;
            document.getElementById('edit_full_address').value = address.full_address;
            document.getElementById('edit_postal_code').value = address.postal_code;
            document.getElementById('edit_latitude').value = address.latitude || '0';
            document.getElementById('edit_longitude').value = address.longitude || '0';
            document.getElementById('edit_is_default').checked = address.is_default == '1';
            
            // Show modal
            document.getElementById('editAddressModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
        
        function closeEditAddressModal() {
            document.getElementById('editAddressModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        // Close modal when clicking outside
        const addressModalElement = document.getElementById('addressModal');
        if (addressModalElement) {
            addressModalElement.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeAddAddressModal();
                }
            });
        }
        
        const editAddressModalElement = document.getElementById('editAddressModal');
        if (editAddressModalElement) {
            editAddressModalElement.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeEditAddressModal();
                }
            });
        }
        
        // Delete Address Confirmation Modal
        function showDeleteAddressConfirm(addressId) {
            confirmModal(
                'Yakin ingin menghapus alamat ini? Data tidak dapat dikembalikan.',
                function() {
                    if (typeof showLoadingOverlay === 'function') {
                        showLoadingOverlay();
                    }
                    document.getElementById('deleteAddressForm' + addressId).submit();
                },
                null,
                {
                    title: 'Hapus Alamat',
                    confirmText: 'Ya, Hapus',
                    cancelText: 'Batal',
                    iconType: 'warning'
                }
            );
        }
        
        // Add loading states and validation to forms
        document.addEventListener('DOMContentLoaded', function() {
            // Real-time validation for all input fields
            const inputFields = document.querySelectorAll('input[type="text"], input[type="email"], input[type="tel"], input[type="password"]');
            inputFields.forEach(field => {
                // Validate on blur
                field.addEventListener('blur', function() {
                    if (this.value.trim() !== '') {
                        validateField(this);
                    }
                });
                
                // Clear validation on focus
                field.addEventListener('focus', function() {
                    // Don't clear if it already has success state
                    if (!this.classList.contains('success')) {
                        clearFieldValidation(this);
                    }
                });
            });
            
            // Email validation
            const emailFields = document.querySelectorAll('input[type="email"], input[name="email"]');
            emailFields.forEach(field => {
                field.addEventListener('input', function() {
                    if (this.value.length > 0) {
                        if (validateEmail(this.value)) {
                            showFieldSuccess(this);
                        } else {
                            // Don't show error while typing, only on blur
                            clearFieldValidation(this);
                        }
                    }
                });
            });
            
            // Phone validation
            const phoneFields = document.querySelectorAll('input[name*="phone"], input[name*="telp"]');
            phoneFields.forEach(field => {
                field.addEventListener('input', function() {
                    // Auto-format phone number
                    let value = this.value.replace(/[^\d+]/g, '');
                    this.value = value;
                });
            });
            
            // Password strength indicator
            const newPasswordFields = document.querySelectorAll('input[name="new_password"], input[name="password"]');
            newPasswordFields.forEach(field => {
                field.addEventListener('input', function() {
                    showPasswordStrength(this);
                    showPasswordRequirements(this);
                });
            });
            
            // Confirm password validation
            const confirmPasswordFields = document.querySelectorAll('input[name="confirm_password"]');
            confirmPasswordFields.forEach(field => {
                field.addEventListener('input', function() {
                    const passwordField = document.querySelector('input[name="new_password"], input[name="password"]');
                    if (passwordField && this.value.length > 0) {
                        if (this.value === passwordField.value) {
                            showFieldSuccess(this);
                        } else {
                            clearFieldValidation(this);
                        }
                    }
                });
            });
            
            // Form submission validation - REMOVED TO FIX FORM SUBMISSION ISSUE
            // The HTML5 'required' attribute will handle basic validation
            // JavaScript should not prevent form submission
            
            // Profile picture upload
            const profilePictureInput = document.getElementById('profile_picture');
            if (profilePictureInput) {
                profilePictureInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const file = this.files[0];
                        const maxSize = 2 * 1024 * 1024; // 2MB
                        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                        
                        // Validate file size
                        if (file.size > maxSize) {
                            showAlert('Ukuran file terlalu besar. Maksimal 2MB.', 'error');
                            this.value = '';
                            return;
                        }
                        
                        // Validate file type
                        if (!allowedTypes.includes(file.type)) {
                            showAlert('Format file tidak didukung. Gunakan JPG, PNG, atau GIF.', 'error');
                            this.value = '';
                            return;
                        }
                        
                        // Show loading when file is selected
                        const form = this.closest('form');
                        if (form) {
                            setTimeout(() => {
                                const submitBtn = form.querySelector('button[type="submit"]');
                                if (submitBtn) {
                                    setButtonLoading(submitBtn);
                                }
                            }, 100);
                        }
                    }
                });
            }
            
            // Tab switching
            const tabButtons = document.querySelectorAll('.tab-button');
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Add subtle loading effect on tab switch
                    const tabs = document.querySelectorAll('.tab-content');
                    tabs.forEach(tab => {
                        tab.style.opacity = '0';
                        setTimeout(() => {
                            tab.style.transition = 'opacity 0.3s';
                            tab.style.opacity = '1';
                        }, 50);
                    });
                });
            });
        });
    </script>
</body>
</html>
