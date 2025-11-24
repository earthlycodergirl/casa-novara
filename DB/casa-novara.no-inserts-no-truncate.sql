-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 29, 2025 at 03:44 AM
-- Server version: 10.6.23-MariaDB-log
-- PHP Version: 8.3.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dougierocks_db`
--
USE `casa_novara`;

-- --------------------------------------------------------

--
-- Table structure for table `control_users`
--

CREATE TABLE `control_users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_pass` varchar(255) NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Truncate table before insert `control_users`
--

--
-- Dumping data for table `control_users`
--


-- --------------------------------------------------------

--
-- Table structure for table `listings_upload`
--

CREATE TABLE `listings_upload` (
  `listing_id` int(11) NOT NULL,
  `listing_number` int(11) NOT NULL,
  `agency_name` varchar(100) DEFAULT NULL,
  `agency_phone` varchar(25) DEFAULT NULL,
  `agency_agent` varchar(100) DEFAULT NULL,
  `co_listing_agent` varchar(100) DEFAULT NULL,
  `property_group` varchar(100) NOT NULL,
  `card_format` varchar(100) DEFAULT NULL,
  `book_section` varchar(100) DEFAULT NULL,
  `days_on_market` int(10) NOT NULL,
  `begin_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `sold_date` date DEFAULT NULL,
  `construction_date` date DEFAULT NULL,
  `fallthrough_date` date DEFAULT NULL,
  `status` varchar(5) NOT NULL DEFAULT 'A',
  `status_change` date NOT NULL,
  `date_withdrawal` date DEFAULT NULL,
  `date_cancelled` date DEFAULT NULL,
  `contingent` varchar(255) DEFAULT NULL,
  `contingent_remarks` varchar(255) DEFAULT NULL,
  `price_original` decimal(10,2) DEFAULT NULL,
  `price_current` decimal(10,2) DEFAULT NULL,
  `price_sold` decimal(10,2) DEFAULT NULL,
  `price_low` decimal(10,2) DEFAULT NULL,
  `price_high` decimal(10,2) DEFAULT NULL,
  `price_assessed` decimal(10,2) DEFAULT NULL,
  `terms` varchar(3) NOT NULL DEFAULT 'NO',
  `financing` varchar(255) DEFAULT NULL,
  `area` varchar(255) NOT NULL,
  `lb_num` varchar(50) DEFAULT NULL,
  `address` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `country` varchar(3) NOT NULL DEFAULT 'USA',
  `county` varchar(100) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `geo_county` varchar(255) DEFAULT NULL,
  `geo_block` varchar(255) DEFAULT NULL,
  `geo_lat` varchar(50) DEFAULT NULL,
  `geo_lon` varchar(50) DEFAULT NULL,
  `sqft_total` decimal(8,1) DEFAULT NULL,
  `sqft1` decimal(8,1) DEFAULT NULL,
  `sqft2` decimal(8,1) DEFAULT NULL,
  `sqft3` decimal(8,1) DEFAULT NULL,
  `sqft4` decimal(8,1) DEFAULT NULL,
  `year_built` int(4) DEFAULT NULL,
  `style` varchar(100) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `lot_size` varchar(100) DEFAULT NULL,
  `lot_acres` varchar(100) DEFAULT NULL,
  `buy_broker_com` decimal(10,2) DEFAULT NULL,
  `sell_broker_com` decimal(10,2) DEFAULT NULL,
  `other_com` decimal(10,2) DEFAULT NULL,
  `selling_agency` varchar(100) DEFAULT NULL,
  `selling_agent` varchar(100) DEFAULT NULL,
  `co_selling_agent` varchar(100) DEFAULT NULL,
  `stories` decimal(5,1) NOT NULL,
  `total_rooms` int(5) NOT NULL,
  `total_beds` int(5) NOT NULL,
  `total_bath` int(5) NOT NULL,
  `full_bath` int(5) NOT NULL,
  `half_bath` int(5) NOT NULL,
  `quarter_bath` int(5) NOT NULL,
  `garage_type` varchar(10) DEFAULT NULL,
  `garage_stall` varchar(5) DEFAULT NULL,
  `garage_remarks` varchar(255) DEFAULT NULL,
  `zoning` varchar(10) DEFAULT NULL,
  `taxes` decimal(10,2) NOT NULL,
  `tax_year` int(4) DEFAULT NULL,
  `subdivision` varchar(255) DEFAULT NULL,
  `remark1` text DEFAULT NULL,
  `remark2` text DEFAULT NULL,
  `parcel_num` varchar(50) NOT NULL,
  `legal` varchar(100) DEFAULT NULL,
  `directions` varchar(255) DEFAULT NULL,
  `owner` varchar(255) DEFAULT NULL,
  `owner_phone` varchar(50) DEFAULT NULL,
  `owner_contact` varchar(255) DEFAULT NULL,
  `how_to_show` varchar(255) DEFAULT NULL,
  `timestamp` time DEFAULT NULL,
  `school_area` varchar(255) DEFAULT NULL,
  `school_elem` varchar(255) DEFAULT NULL,
  `school_middle` varchar(255) DEFAULT NULL,
  `school_high` varchar(255) DEFAULT NULL,
  `school_other` varchar(255) DEFAULT NULL,
  `price_increase` varchar(25) DEFAULT NULL,
  `electric_paid` varchar(50) DEFAULT '',
  `fireplace` varchar(50) NOT NULL DEFAULT 'ExclusiveRightToSell',
  `lot_suffix` varchar(50) DEFAULT NULL,
  `list_type` varchar(50) DEFAULT NULL,
  `photo_url` varchar(255) DEFAULT NULL,
  `asmt_total` decimal(10,2) DEFAULT NULL,
  `asmt_improvements` decimal(10,2) DEFAULT NULL,
  `num_car_garage` decimal(10,2) NOT NULL,
  `room_info` text DEFAULT NULL,
  `features` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Truncate table before insert `listings_upload`
--

-- --------------------------------------------------------

--
-- Table structure for table `locations_areas`
--

CREATE TABLE `locations_areas` (
  `area_id` int(11) NOT NULL,
  `town_id` int(11) NOT NULL,
  `area_name` varchar(255) NOT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `latitude` decimal(11,8) DEFAULT NULL,
  `is_featured` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Truncate table before insert `locations_areas`
--

--
-- Dumping data for table `locations_areas`
--


-- --------------------------------------------------------

--
-- Table structure for table `locations_cities`
--

CREATE TABLE `locations_cities` (
  `city_id` int(11) NOT NULL,
  `state_id` int(5) NOT NULL,
  `location` varchar(255) NOT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `latitude` decimal(11,8) DEFAULT NULL,
  `is_featured` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Truncate table before insert `locations_cities`
--

--
-- Dumping data for table `locations_cities`
--


-- --------------------------------------------------------

--
-- Table structure for table `locations_states`
--

CREATE TABLE `locations_states` (
  `state_id` int(11) NOT NULL,
  `state` varchar(255) NOT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `latitude` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Truncate table before insert `locations_states`
--

--
-- Dumping data for table `locations_states`
--


-- --------------------------------------------------------

--
-- Table structure for table `locations_towns`
--

CREATE TABLE `locations_towns` (
  `town_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `town_name` varchar(255) NOT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `latitude` decimal(11,8) DEFAULT NULL,
  `is_featured` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Truncate table before insert `locations_towns`
--

--
-- Dumping data for table `locations_towns`
--


-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `ptid` int(11) NOT NULL,
  `permission_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Truncate table before insert `permissions`
--

--
-- Dumping data for table `permissions`
--


-- --------------------------------------------------------

--
-- Table structure for table `permissions_users`
--

CREATE TABLE `permissions_users` (
  `puid` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Truncate table before insert `permissions_users`
--

--
-- Dumping data for table `permissions_users`
--


-- --------------------------------------------------------

--
-- Table structure for table `property_features`
--

CREATE TABLE `property_features` (
  `feature_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `feature_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `feature_val` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `forder` int(2) NOT NULL DEFAULT 99,
  `lang` varchar(2) NOT NULL DEFAULT 'en'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Truncate table before insert `property_features`
--

--
-- Dumping data for table `property_features`
--


-- --------------------------------------------------------

--
-- Table structure for table `property_list`
--

CREATE TABLE `property_list` (
  `property_id` int(11) NOT NULL,
  `pr_status` varchar(50) NOT NULL DEFAULT 'active',
  `pr_display_status` varchar(255) DEFAULT '',
  `pr_list_type_id` int(11) NOT NULL,
  `pr_type_id` int(11) NOT NULL,
  `pr_sub_type_id` int(11) NOT NULL DEFAULT 0,
  `mls_num` varchar(50) DEFAULT NULL,
  `size_sq_ft` decimal(10,2) DEFAULT NULL,
  `size_sq_mt` decimal(10,2) NOT NULL DEFAULT 0.00,
  `size_lot` decimal(10,2) DEFAULT NULL,
  `size_units` double DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `unit_num` varchar(20) DEFAULT NULL,
  `city` varchar(255) NOT NULL DEFAULT 'Undefined',
  `state` varchar(255) DEFAULT NULL,
  `county` varchar(255) NOT NULL DEFAULT 'Undefined',
  `zip` varchar(255) NOT NULL DEFAULT '000000',
  `area` varchar(255) NOT NULL DEFAULT 'Undefined',
  `bedrooms` int(2) DEFAULT NULL,
  `bathrooms` int(2) DEFAULT NULL,
  `half_baths` int(2) DEFAULT NULL,
  `third_baths` int(2) NOT NULL,
  `fourth_baths` int(2) NOT NULL,
  `year_built` int(5) DEFAULT NULL,
  `zoning_id` int(11) NOT NULL,
  `virtual_tour` varchar(255) DEFAULT '',
  `map_embed` varchar(255) DEFAULT NULL,
  `property_title` varchar(255) NOT NULL,
  `property_desc` text DEFAULT NULL,
  `longitude` varchar(25) DEFAULT '',
  `latitude` varchar(25) DEFAULT '',
  `is_visible` int(2) NOT NULL DEFAULT 1,
  `is_foreclosure` int(2) NOT NULL DEFAULT 0,
  `is_featured` int(2) NOT NULL,
  `is_construction` int(2) NOT NULL DEFAULT 0,
  `month_built` int(11) DEFAULT NULL,
  `size_lot_mt` decimal(10,2) DEFAULT NULL,
  `room_type` varchar(50) NOT NULL DEFAULT 'bed',
  `release_notes` varchar(500) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Truncate table before insert `property_list`
--

--
-- Dumping data for table `property_list`
--


-- --------------------------------------------------------

--
-- Table structure for table `property_list_es`
--

CREATE TABLE `property_list_es` (
  `es_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL DEFAULT 0,
  `lang` varchar(2) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '0',
  `prop_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `prop_desc` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `prop_display` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

--
-- Truncate table before insert `property_list_es`
--

--
-- Dumping data for table `property_list_es`
--


-- --------------------------------------------------------

--
-- Table structure for table `property_list_types`
--

CREATE TABLE `property_list_types` (
  `list_type_id` int(11) NOT NULL,
  `type_name` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Truncate table before insert `property_list_types`
--

--
-- Dumping data for table `property_list_types`
--


-- --------------------------------------------------------

--
-- Table structure for table `property_open_houses`
--

CREATE TABLE `property_open_houses` (
  `open_house_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `oh_title` varchar(255) DEFAULT NULL,
  `oh_desc` text DEFAULT NULL,
  `oh_date` date NOT NULL,
  `oh_start_time` time NOT NULL,
  `oh_end_time` time NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Truncate table before insert `property_open_houses`
--

-- --------------------------------------------------------

--
-- Table structure for table `property_photos`
--

CREATE TABLE `property_photos` (
  `photo_id` int(11) NOT NULL,
  `img_name` varchar(255) NOT NULL,
  `img_folder` varchar(255) DEFAULT NULL,
  `property_id` int(11) NOT NULL,
  `img_type` varchar(25) NOT NULL DEFAULT 'gallery',
  `img_order` int(3) NOT NULL DEFAULT 100
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Truncate table before insert `property_photos`
--

--
-- Dumping data for table `property_photos`
--


-- --------------------------------------------------------

--
-- Table structure for table `property_pricing`
--

CREATE TABLE `property_pricing` (
  `price_id` int(11) NOT NULL,
  `price_name` varchar(255) DEFAULT NULL,
  `price_desc` varchar(255) DEFAULT NULL,
  `price_amt` decimal(10,2) NOT NULL,
  `price_type` varchar(255) DEFAULT NULL,
  `property_id` int(11) NOT NULL,
  `price_currency` varchar(3) NOT NULL DEFAULT 'usd'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Truncate table before insert `property_pricing`
--

--
-- Dumping data for table `property_pricing`
--


-- --------------------------------------------------------

--
-- Table structure for table `property_pricing_types`
--

CREATE TABLE `property_pricing_types` (
  `pr_type_id` int(11) NOT NULL,
  `price_type` varchar(255) NOT NULL,
  `price_type_es` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Truncate table before insert `property_pricing_types`
--

--
-- Dumping data for table `property_pricing_types`
--


-- --------------------------------------------------------

--
-- Table structure for table `property_requests`
--

CREATE TABLE `property_requests` (
  `request_id` int(11) NOT NULL,
  `client_name` varchar(255) NOT NULL DEFAULT '0',
  `client_email` varchar(255) NOT NULL DEFAULT '0',
  `client_phone` varchar(50) DEFAULT '0',
  `client_notes` tinytext DEFAULT NULL,
  `property_id` int(11) NOT NULL,
  `request_timestamp` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Truncate table before insert `property_requests`
--

--
-- Dumping data for table `property_requests`
--


-- --------------------------------------------------------

--
-- Table structure for table `property_tags`
--

CREATE TABLE `property_tags` (
  `tag_id` int(11) NOT NULL,
  `tag_name` varchar(255) NOT NULL,
  `property_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Truncate table before insert `property_tags`
--

-- --------------------------------------------------------

--
-- Table structure for table `property_types`
--

CREATE TABLE `property_types` (
  `pr_type_id` int(11) NOT NULL,
  `type_desc` varchar(255) NOT NULL,
  `type_desc_es` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Truncate table before insert `property_types`
--

--
-- Dumping data for table `property_types`
--


-- --------------------------------------------------------

--
-- Table structure for table `property_types_sub`
--

CREATE TABLE `property_types_sub` (
  `sub_id` int(11) NOT NULL,
  `pr_type_id` int(11) NOT NULL,
  `sub_desc` varchar(255) NOT NULL,
  `sub_desc_es` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Truncate table before insert `property_types_sub`
--

--
-- Dumping data for table `property_types_sub`
--


-- --------------------------------------------------------

--
-- Table structure for table `property_zones`
--

CREATE TABLE `property_zones` (
  `zone_id` int(11) NOT NULL,
  `zone_name` varchar(255) NOT NULL,
  `zone_desc` text DEFAULT NULL,
  `zone_name_es` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Truncate table before insert `property_zones`
--

--
-- Dumping data for table `property_zones`
--


-- --------------------------------------------------------

--
-- Table structure for table `site_blog`
--

CREATE TABLE `site_blog` (
  `blog_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `author` varchar(255) NOT NULL,
  `posted` datetime NOT NULL,
  `lang` char(50) NOT NULL DEFAULT 'en',
  `main_img` varchar(500) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Truncate table before insert `site_blog`
--

-- --------------------------------------------------------

--
-- Table structure for table `site_contact`
--

CREATE TABLE `site_contact` (
  `contact_id` int(11) NOT NULL,
  `contact_type` varchar(50) NOT NULL DEFAULT '0',
  `contact_value` varchar(500) NOT NULL DEFAULT '0',
  `contact_display` varchar(50) DEFAULT NULL,
  `contact_section` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Truncate table before insert `site_contact`
--

--
-- Dumping data for table `site_contact`
--


-- --------------------------------------------------------

--
-- Table structure for table `site_testimonials`
--

CREATE TABLE `site_testimonials` (
  `test_id` int(11) NOT NULL,
  `testimonial` text NOT NULL,
  `test_user` varchar(255) DEFAULT NULL,
  `test_date` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Truncate table before insert `site_testimonials`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `uid` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `active` int(2) NOT NULL DEFAULT 1,
  `admin` int(2) NOT NULL DEFAULT 0,
  `last_login` timestamp NULL DEFAULT NULL,
  `see_all` int(2) UNSIGNED ZEROFILL NOT NULL,
  `creation_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Truncate table before insert `users`
--

--
-- Dumping data for table `users`
--


-- --------------------------------------------------------

--
-- Table structure for table `user_types`
--

CREATE TABLE `user_types` (
  `user_type_id` int(11) NOT NULL,
  `user_title` varchar(255) NOT NULL,
  `tramite_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Truncate table before insert `user_types`
--

--
-- Dumping data for table `user_types`
--


--
-- Indexes for dumped tables
--

--
-- Indexes for table `control_users`
--
ALTER TABLE `control_users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `listings_upload`
--
ALTER TABLE `listings_upload`
  ADD PRIMARY KEY (`listing_id`);

--
-- Indexes for table `locations_areas`
--
ALTER TABLE `locations_areas`
  ADD PRIMARY KEY (`area_id`),
  ADD KEY `town_key_idx` (`town_id`);

--
-- Indexes for table `locations_cities`
--
ALTER TABLE `locations_cities`
  ADD PRIMARY KEY (`city_id`),
  ADD UNIQUE KEY `loc_id_UNIQUE` (`city_id`),
  ADD KEY `state_key_idx` (`state_id`);

--
-- Indexes for table `locations_states`
--
ALTER TABLE `locations_states`
  ADD PRIMARY KEY (`state_id`);

--
-- Indexes for table `locations_towns`
--
ALTER TABLE `locations_towns`
  ADD PRIMARY KEY (`town_id`),
  ADD UNIQUE KEY `city_id_UNIQUE` (`town_id`),
  ADD KEY `city_key_idx` (`city_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`ptid`);

--
-- Indexes for table `permissions_users`
--
ALTER TABLE `permissions_users`
  ADD PRIMARY KEY (`puid`);

--
-- Indexes for table `property_features`
--
ALTER TABLE `property_features`
  ADD PRIMARY KEY (`feature_id`);

--
-- Indexes for table `property_list`
--
ALTER TABLE `property_list`
  ADD PRIMARY KEY (`property_id`);

--
-- Indexes for table `property_list_es`
--
ALTER TABLE `property_list_es`
  ADD PRIMARY KEY (`es_id`),
  ADD UNIQUE KEY `property_id` (`property_id`);

--
-- Indexes for table `property_list_types`
--
ALTER TABLE `property_list_types`
  ADD PRIMARY KEY (`list_type_id`);

--
-- Indexes for table `property_open_houses`
--
ALTER TABLE `property_open_houses`
  ADD PRIMARY KEY (`open_house_id`);

--
-- Indexes for table `property_photos`
--
ALTER TABLE `property_photos`
  ADD PRIMARY KEY (`photo_id`);

--
-- Indexes for table `property_pricing`
--
ALTER TABLE `property_pricing`
  ADD PRIMARY KEY (`price_id`);

--
-- Indexes for table `property_pricing_types`
--
ALTER TABLE `property_pricing_types`
  ADD PRIMARY KEY (`pr_type_id`);

--
-- Indexes for table `property_requests`
--
ALTER TABLE `property_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `PROPID` (`property_id`);

--
-- Indexes for table `property_tags`
--
ALTER TABLE `property_tags`
  ADD PRIMARY KEY (`tag_id`);

--
-- Indexes for table `property_types`
--
ALTER TABLE `property_types`
  ADD PRIMARY KEY (`pr_type_id`);

--
-- Indexes for table `property_types_sub`
--
ALTER TABLE `property_types_sub`
  ADD PRIMARY KEY (`sub_id`);

--
-- Indexes for table `property_zones`
--
ALTER TABLE `property_zones`
  ADD PRIMARY KEY (`zone_id`);

--
-- Indexes for table `site_blog`
--
ALTER TABLE `site_blog`
  ADD PRIMARY KEY (`blog_id`);

--
-- Indexes for table `site_contact`
--
ALTER TABLE `site_contact`
  ADD PRIMARY KEY (`contact_id`);

--
-- Indexes for table `site_testimonials`
--
ALTER TABLE `site_testimonials`
  ADD PRIMARY KEY (`test_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `user_types`
--
ALTER TABLE `user_types`
  ADD PRIMARY KEY (`user_type_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `control_users`
--
ALTER TABLE `control_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `listings_upload`
--
ALTER TABLE `listings_upload`
  MODIFY `listing_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=300;

--
-- AUTO_INCREMENT for table `locations_areas`
--
ALTER TABLE `locations_areas`
  MODIFY `area_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=232;

--
-- AUTO_INCREMENT for table `locations_cities`
--
ALTER TABLE `locations_cities`
  MODIFY `city_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2457;

--
-- AUTO_INCREMENT for table `locations_states`
--
ALTER TABLE `locations_states`
  MODIFY `state_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `locations_towns`
--
ALTER TABLE `locations_towns`
  MODIFY `town_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `ptid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `permissions_users`
--
ALTER TABLE `permissions_users`
  MODIFY `puid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- AUTO_INCREMENT for table `property_features`
--
ALTER TABLE `property_features`
  MODIFY `feature_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35051;

--
-- AUTO_INCREMENT for table `property_list`
--
ALTER TABLE `property_list`
  MODIFY `property_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=737;

--
-- AUTO_INCREMENT for table `property_list_es`
--
ALTER TABLE `property_list_es`
  MODIFY `es_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=658;

--
-- AUTO_INCREMENT for table `property_list_types`
--
ALTER TABLE `property_list_types`
  MODIFY `list_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `property_open_houses`
--
ALTER TABLE `property_open_houses`
  MODIFY `open_house_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `property_photos`
--
ALTER TABLE `property_photos`
  MODIFY `photo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11806;

--
-- AUTO_INCREMENT for table `property_pricing`
--
ALTER TABLE `property_pricing`
  MODIFY `price_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11677;

--
-- AUTO_INCREMENT for table `property_pricing_types`
--
ALTER TABLE `property_pricing_types`
  MODIFY `pr_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `property_requests`
--
ALTER TABLE `property_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `property_tags`
--
ALTER TABLE `property_tags`
  MODIFY `tag_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `property_types`
--
ALTER TABLE `property_types`
  MODIFY `pr_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `property_types_sub`
--
ALTER TABLE `property_types_sub`
  MODIFY `sub_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `property_zones`
--
ALTER TABLE `property_zones`
  MODIFY `zone_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `site_blog`
--
ALTER TABLE `site_blog`
  MODIFY `blog_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `site_contact`
--
ALTER TABLE `site_contact`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `site_testimonials`
--
ALTER TABLE `site_testimonials`
  MODIFY `test_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `user_types`
--
ALTER TABLE `user_types`
  MODIFY `user_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `locations_areas`
--
ALTER TABLE `locations_areas`
  ADD CONSTRAINT `town_key` FOREIGN KEY (`town_id`) REFERENCES `locations_towns` (`town_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `locations_cities`
--
ALTER TABLE `locations_cities`
  ADD CONSTRAINT `state_key` FOREIGN KEY (`state_id`) REFERENCES `locations_states` (`state_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `locations_towns`
--
ALTER TABLE `locations_towns`
  ADD CONSTRAINT `city_key` FOREIGN KEY (`city_id`) REFERENCES `locations_cities` (`city_id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
