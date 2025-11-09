
INSERT INTO `property_list` (`property_id`, `pr_status`, `pr_display_status`, `pr_list_type_id`, `pr_type_id`, `pr_sub_type_id`, `mls_num`, `size_sq_ft`, `size_sq_mt`, `size_lot`, `size_units`, `address`, `unit_num`, `city`, `state`, `county`, `zip`, `area`, `bedrooms`, `bathrooms`, `half_baths`, `third_baths`, `fourth_baths`, `year_built`, `zoning_id`, `virtual_tour`, `map_embed`, `property_title`, `property_desc`, `longitude`, `latitude`, `is_visible`, `is_foreclosure`, `is_featured`, `is_construction`, `month_built`, `size_lot_mt`, `room_type`, `release_notes`) VALUES

(NULL, 'active', 'Beachfront Luxury', 18, 20, 61, 'CN-PLAYA-1001', 4800.00, 446.00, 9000.00, 0, 'Calle Coral 45', '', '1810', '23', '25', '77710', '1', 5, 6, 1, 0, 0, 2024, 18, 'https://youtu.be/luxuryvilla', NULL, 'Spectacular 5BR Beachfront Villa in Playacar', 'This newly built luxury villa offers 5 bedrooms, 6.5 bathrooms, and direct access to the white sand beaches of Playacar. Features include a private infinity pool, rooftop terrace with ocean views, gourmet kitchen, smart home automation, elevator, and underground parking. The master suite has a spa bathroom and private balcony. Amenities: 24/7 security, gym, kids club, and private pier. Perfect for high-end living or vacation rental investment.', '-87.073900', '20.629600', 1, 0, 1, 0, 5, 836.00, 'villa', 'Released October 2025'),

(NULL, 'active', 'Eco Jungle Condo', 18, 20, 64, 'CN-TULUM-2002', 1850.00, 172.00, 0.00, 0, 'Avenida Coba 789', '204', '1811', '23', '65', '77780', '23', 2, 3, 1, 0, 0, 2023, 18, 'https://youtu.be/junglecondo', NULL, 'Modern 2BR Condo with Cenote Views in Aldea Zama', 'Experience Tulum living in this modern 2-bedroom, 3-bathroom condo with cenote and jungle views. Open-plan living, floor-to-ceiling windows, private terraces, and eco-friendly features like solar panels and rainwater collection. Amenities: rooftop pool, yoga pavilion, coworking space, 24/7 security, and concierge. Walking distance to Tulum ruins and beach clubs. Fully furnished and move-in ready.', '-87.465400', '20.211400', 1, 0, 1, 0, 2, 0.00, 'condo', 'Ready for occupancy October 2025');

INSERT INTO `property_pricing` (`price_id`, `price_name`, `price_desc`, `price_amt`, `price_type`, `property_id`, `price_currency`) VALUES
(NULL, 'Total sale', 'Asking price for this luxury villa', 2750000.00, '1', 10001, 'usd'),
(NULL, 'Condo Fee', 'Monthly HOA/Condo fee', 950.00, '7', 10001, 'usd'),
(NULL, 'Tax', 'Annual property tax', 4500.00, '8', 10001, 'usd'),
(NULL, 'Total sale', 'Asking price for this eco-jungle condo', 850000.00, '1', 10002, 'usd'),
(NULL, 'Condo Fee', 'Monthly HOA/Condo fee', 420.00, '7', 10002, 'usd'),
(NULL, 'Tax', 'Annual property tax', 1850.00, '8', 10002, 'usd');

INSERT INTO `property_features` (`feature_id`, `property_id`, `feature_name`, `feature_val`, `forder`, `lang`) VALUES
(NULL, 10001, 'Pool', 'Infinity', 1, 'en'),
(NULL, 10001, 'Bedrooms', '5', 2, 'en'),
(NULL, 10001, 'Bathrooms', '6.5', 3, 'en'),
(NULL, 10001, 'Garage', 'Underground, 3 cars', 4, 'en'),
(NULL, 10001, 'Smart Home', 'Yes', 5, 'en'),
(NULL, 10001, 'Rooftop Terrace', 'Yes', 6, 'en'),
(NULL, 10001, 'Private Beach', 'Yes', 7, 'en'),
(NULL, 10001, 'Security', '24/7', 8, 'en'),
(NULL, 10001, 'Elevator', 'Yes', 9, 'en'),
(NULL, 10001, 'Furnished', 'Optional', 10, 'en'),
(NULL, 10002, 'Pool', 'Rooftop', 1, 'en'),
(NULL, 10002, 'Bedrooms', '2', 2, 'en'),
(NULL, 10002, 'Bathrooms', '3', 3, 'en'),
(NULL, 10002, 'Terrace', 'Private', 4, 'en'),
(NULL, 10002, 'Eco Features', 'Solar panels, rainwater collection', 5, 'en'),
(NULL, 10002, 'Security', '24/7', 6, 'en'),
(NULL, 10002, 'Coworking', 'Yes', 7, 'en'),
(NULL, 10002, 'Yoga Pavilion', 'Yes', 8, 'en'),
(NULL, 10002, 'Furnished', 'Yes', 9, 'en');

INSERT INTO `property_photos` (`photo_id`, `img_name`, `img_folder`, `property_id`, `img_type`, `img_order`) VALUES
(NULL, 'villa_beachfront_001.jpg', 'listings/10001/', 10001, 'listing_gallery', 1),
(NULL, 'villa_beachfront_002.jpg', 'listings/10001/', 10001, 'listing_gallery', 2),
(NULL, 'villa_beachfront_003.jpg', 'listings/10001/', 10001, 'listing_gallery', 3),
(NULL, 'condo_cenote_views_001.jpg', 'listings/10002/', 10002, 'listing_gallery', 1),
(NULL, 'condo_cenote_views_002.jpg', 'listings/10002/', 10002, 'listing_gallery', 2),
(NULL, 'condo_cenote_views_003.jpg', 'listings/10002/', 10002, 'listing_gallery', 3);