-- Database modifications for enhanced contact information
-- Run these SQL commands to add the new fields

-- Add new columns to site_contact table
ALTER TABLE `site_contact` ADD COLUMN `contact_title` VARCHAR(255) DEFAULT NULL AFTER `contact_value`;
ALTER TABLE `site_contact` ADD COLUMN `contact_description` TEXT DEFAULT NULL AFTER `contact_title`;
ALTER TABLE `site_contact` ADD COLUMN `is_whatsapp` TINYINT(1) DEFAULT 0 AFTER `contact_description`;
ALTER TABLE `site_contact` ADD COLUMN `contact_meta` TEXT DEFAULT NULL AFTER `is_whatsapp`;

-- Insert default availability and office location entries
INSERT INTO `site_contact` (`contact_type`, `contact_value`, `contact_title`, `contact_description`, `contact_section`, `contact_meta`) 
VALUES 
('availability', '', 'Business Hours', 'Our office availability schedule', 'office_info', '{}'),
('location', '', 'Office Location', 'Our main office address', 'office_info', '{"address":"","latitude":"","longitude":""}');