    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <!-- Bagian Brand -->
                <div class="footer-section">
                    <div class="footer-logo">
                        <h3>RetroLoved</h3>
                        <div class="footer-tagline">Vintage Fashion, Modern Style</div>
                    </div>
                    <p class="footer-description">Platform penjualan fashion vintage dan preloved terpercaya di Indonesia. Temukan gaya unik Anda bersama kami.</p>
                    
                    <!-- Link Media Sosial -->
                    <div class="social-links">
                        <a href="https://instagram.com/retroloved" target="_blank" rel="noopener noreferrer" class="social-icon" title="Follow us on Instagram" aria-label="Instagram">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                            </svg>
                        </a>
                        <a href="https://facebook.com/retroloved" target="_blank" rel="noopener noreferrer" class="social-icon" title="Like us on Facebook" aria-label="Facebook">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                            </svg>
                        </a>
                        <a href="https://twitter.com/retroloved" target="_blank" rel="noopener noreferrer" class="social-icon" title="Follow us on Twitter" aria-label="Twitter">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path>
                            </svg>
                        </a>
                        <a href="https://linkedin.com/company/retroloved" target="_blank" rel="noopener noreferrer" class="social-icon" title="Connect on LinkedIn" aria-label="LinkedIn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
                                <rect x="2" y="9" width="4" height="12"></rect>
                                <circle cx="4" cy="4" r="2"></circle>
                            </svg>
                        </a>
                        <a href="https://tiktok.com/@retroloved" target="_blank" rel="noopener noreferrer" class="social-icon" title="Follow us on TikTok" aria-label="TikTok">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Link Cepat -->
                <div class="footer-section">
                    <h4 class="footer-title">Link Cepat</h4>
                    <ul class="footer-links">
                        <li><a href="<?php echo isset($base_url) ? $base_url : '../'; ?>index.php">Home</a></li>
                        <li><a href="<?php echo isset($base_url) ? $base_url : '../'; ?>shop.php">Shop All</a></li>
                        <li><a href="<?php echo isset($base_url) ? $base_url : '../'; ?>index.php#about">About Us</a></li>
                        <li><a href="<?php echo isset($base_url) ? $base_url : '../'; ?>how-it-works.php">How It Works</a></li>
                        <?php if(isset($_SESSION['user_id']) && $_SESSION['role'] == 'customer'): ?>
                            <li><a href="<?php echo isset($base_url) ? $base_url : '../'; ?>customer/orders.php">My Orders</a></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Layanan Pelanggan -->
                <div class="footer-section">
                    <h4 class="footer-title">Layanan Pelanggan</h4>
                    <ul class="footer-links">
                        <li><a href="<?php echo isset($base_url) ? $base_url : '../'; ?>faq.php">FAQ</a></li>
                        <li><a href="<?php echo isset($base_url) ? $base_url : '../'; ?>shipping-delivery.php">Shipping & Delivery</a></li>
                        <li><a href="<?php echo isset($base_url) ? $base_url : '../'; ?>size-guide.php">Size Guide</a></li>
                        <li><a href="<?php echo isset($base_url) ? $base_url : '../'; ?>terms-conditions.php">Terms & Conditions</a></li>
                        <li><a href="<?php echo isset($base_url) ? $base_url : '../'; ?>privacy-policy.php">Privacy Policy</a></li>
                    </ul>
                </div>

                <!-- Informasi Kontak -->
                <div class="footer-section">
                    <h4 class="footer-title">Hubungi Kami</h4>
                    <ul class="footer-contact">
                        <li>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            <span>Jl. Raya Darmo No. 123<br>Surabaya, Jawa Timur 60264</span>
                        </li>
                        <li>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                            <a href="mailto:info@retroloved.com">info@retroloved.com</a>
                        </li>
                        <li>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                            <a href="tel:+6281234567890">+62 812-3456-7890</a>
                        </li>
                        <li>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            <span>Mon - Sat: 09:00 - 21:00<br>Sunday: 10:00 - 18:00</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bagian Bawah Footer -->
            <div class="footer-bottom">
                <div class="footer-bottom-content">
                    <p class="copyright">&copy; <?php echo date('Y'); ?> RetroLoved. Hak Cipta Dilindungi.</p>
                    <p class="credits">Dibuat dengan <span style="color: #EF4444;">♥</span> di Surabaya, Indonesia</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Contact Support Modal -->
    <div id="contactSupportModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle; margin-right: 8px;">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    Hubungi Support
                </h3>
                <button type="button" class="modal-close" onclick="closeContactSupportModal()">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <div class="contact-support-intro">
                    <div class="support-icon-wrapper">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                    </div>
                    <h4>Ada yang bisa kami bantu?</h4>
                    <p>Tim support kami siap membantu Anda. Kirimkan pesan dan kami akan merespons secepat mungkin.</p>
                    <div style="background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 8px; padding: 14px; margin-top: 16px; display: flex; align-items: start; gap: 12px; backdrop-filter: blur(10px);">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" style="min-width: 22px; margin-top: 2px;">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        <div style="flex: 1;">
                            <p style="margin: 0; font-size: 13px; color: white; line-height: 1.6;">
                                <strong style="display: block; margin-bottom: 4px;">Pesan Anda akan dikirim ke:</strong>
                                <a href="mailto:retroloved.ofc@gmail.com" style="color: white; text-decoration: none; font-weight: 600; opacity: 0.95;">retroloved.ofc@gmail.com</a>
                            </p>
                        </div>
                    </div>
                </div>

                <form id="contactSupportForm">
                    <!-- Hidden fields for logged-in users -->
                    <?php if(isset($_SESSION['user_id']) && $_SESSION['role'] == 'customer'): ?>
                        <?php
                        $user_id = $_SESSION['user_id'];
                        $user_query = query("SELECT full_name, email FROM users WHERE user_id = '$user_id'");
                        $user = mysqli_fetch_assoc($user_query);
                        ?>
                        <div style="display: none;">
                            <span data-user-name="<?php echo htmlspecialchars($user['full_name']); ?>"></span>
                            <span data-user-email="<?php echo htmlspecialchars($user['email']); ?>"></span>
                        </div>
                    <?php endif; ?>

                    <div class="support-form-group">
                        <label for="supportName">
                            Nama Lengkap <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="supportName" 
                            name="name" 
                            placeholder="Masukkan nama lengkap Anda"
                            required
                            <?php if(isset($_SESSION['user_id']) && $_SESSION['role'] == 'customer'): ?>
                                value="<?php echo htmlspecialchars($user['full_name']); ?>"
                            <?php endif; ?>
                        >
                    </div>

                    <div class="support-form-group">
                        <label for="supportEmail">
                            Email <span class="required">*</span>
                        </label>
                        <input 
                            type="email" 
                            id="supportEmail" 
                            name="email" 
                            placeholder="email@example.com"
                            required
                            <?php if(isset($_SESSION['user_id']) && $_SESSION['role'] == 'customer'): ?>
                                value="<?php echo htmlspecialchars($user['email']); ?>"
                            <?php endif; ?>
                        >
                        <div class="form-hint">Kami akan menghubungi Anda melalui email ini</div>
                    </div>

                    <div class="support-form-group">
                        <label for="supportSubject">
                            Subjek <span class="required">*</span>
                        </label>
                        <select id="supportSubject" name="subject" required>
                            <option value="">Pilih subjek...</option>
                            <option value="Pertanyaan Produk">Pertanyaan Produk</option>
                            <option value="Pertanyaan Pesanan">Pertanyaan Pesanan</option>
                            <option value="Masalah Pembayaran">Masalah Pembayaran</option>
                            <option value="Masalah Pengiriman">Masalah Pengiriman</option>
                            <option value="Pengembalian/Refund">Pengembalian/Refund</option>
                            <option value="Masalah Akun">Masalah Akun</option>
                            <option value="Saran & Feedback">Saran & Feedback</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="support-form-group">
                        <label for="supportMessage">
                            Pesan <span class="required">*</span>
                        </label>
                        <textarea 
                            id="supportMessage" 
                            name="message" 
                            placeholder="Jelaskan pertanyaan atau masalah Anda secara detail..."
                            required
                            minlength="10"
                        ></textarea>
                        <div class="form-hint">Minimal 10 karakter</div>
                    </div>

                    <div class="support-form-actions">
                        <button type="button" class="btn-cancel" onclick="closeContactSupportModal()">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                            Batal
                        </button>
                        <button type="submit" class="btn-submit">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="22" y1="2" x2="11" y2="13"></line>
                                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                            </svg>
                            Kirim Pesan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="<?php echo isset($base_url) ? $base_url : '../'; ?>assets/js/toast.js?v=1.3"></script>
    <script src="<?php echo isset($base_url) ? $base_url : '../'; ?>assets/js/modal.js?v=1.3"></script>
    <script src="<?php echo isset($base_url) ? $base_url : '../'; ?>assets/js/auth-modal.js?v=1.3"></script>
    <script src="<?php echo isset($base_url) ? $base_url : '../'; ?>assets/js/contact-support.js?v=1.3"></script>
    <script src="<?php echo isset($base_url) ? $base_url : '../'; ?>assets/js/script.js?v=1.3"></script>

    <!-- Floating Contact Support Button -->
    <?php
    // Daftar halaman yang TIDAK menampilkan floating contact button
    $hide_floating_contact = [
        'orders.php',
        'profile.php', 
        'cart.php',
        'notifications.php'
    ];
    
    // Cek apakah halaman saat ini ada di daftar hide
    $current_page = basename($_SERVER['PHP_SELF']);
    $show_floating_contact = !in_array($current_page, $hide_floating_contact);
    
    // Tampilkan floating button jika tidak di daftar hide
    if ($show_floating_contact):
    ?>
    <button id="floatingContactBtn" class="floating-contact-btn" onclick="showContactSupportModal()" aria-label="Contact Support">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            <path d="M9 10h.01"></path>
            <path d="M15 10h.01"></path>
            <path d="M9.5 15a3.5 3.5 0 0 0 5 0"></path>
        </svg>
        <span class="floating-contact-tooltip">Need Help?</span>
    </button>
    <?php endif; ?>
</body>
</html>