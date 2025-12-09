-- Add admin_page_visits table to track which pages admin has visited
-- This replaces session-based tracking for notification badges

CREATE TABLE IF NOT EXISTS admin_page_visits (
    visit_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    page_name VARCHAR(50) NOT NULL,
    last_visit_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_page (user_id, page_name)
);

-- Add index for better performance
CREATE INDEX idx_user_page ON admin_page_visits(user_id, page_name);