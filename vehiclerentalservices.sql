-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2025 at 10:13 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vehiclerentalservices`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `dealer_id` varchar(255) NOT NULL,
  `start_date` varchar(255) NOT NULL,
  `end_date` varchar(255) NOT NULL,
  `payment_status` varchar(255) NOT NULL,
  `total_price` varchar(100) NOT NULL,
  `vehicle_id` varchar(255) NOT NULL,
  `pickup_status` varchar(255) NOT NULL DEFAULT 'Not Picked Up',
  `return_status` varchar(255) NOT NULL DEFAULT 'Not Returned'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`booking_id`, `user_id`, `dealer_id`, `start_date`, `end_date`, `payment_status`, `total_price`, `vehicle_id`, `pickup_status`, `return_status`) VALUES
(26, '2', '12', '2025-04-16', '2025-04-17', 'confirmed', '200', '17', 'Picked Up', 'Returned'),
(27, '2', '12', '2025-04-16', '2025-04-17', 'confirmed', '200', '17', 'Not Picked Up', 'Not Returned'),
(28, '2', '12', '2025-04-16', '2025-04-23', 'confirmed', '1150', '22', 'Picked Up', 'Returned');

-- --------------------------------------------------------

--
-- Table structure for table `dealer`
--

CREATE TABLE `dealer` (
  `dealer_id` int(255) NOT NULL,
  `dealer_fname` varchar(255) NOT NULL,
  `dealer_lname` varchar(255) NOT NULL,
  `dealer_email` varchar(255) NOT NULL,
  `dealer_phone` varchar(255) NOT NULL,
  `dealer_pasword` varchar(255) NOT NULL,
  `dealer_address` varchar(255) NOT NULL,
  `dealer_Image` varchar(255) NOT NULL,
  `dealer_about` varchar(255) NOT NULL,
  `dealer_latitude` varchar(255) NOT NULL,
  `dealer_longitude` varchar(255) NOT NULL,
  `dealer_city` varchar(255) NOT NULL,
  `dealer_district` varchar(255) NOT NULL,
  `dealer_zipcode` varchar(255) NOT NULL,
  `dealer_state` varchar(255) NOT NULL,
  `dealer_country` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dealer`
--

INSERT INTO `dealer` (`dealer_id`, `dealer_fname`, `dealer_lname`, `dealer_email`, `dealer_phone`, `dealer_pasword`, `dealer_address`, `dealer_Image`, `dealer_about`, `dealer_latitude`, `dealer_longitude`, `dealer_city`, `dealer_district`, `dealer_zipcode`, `dealer_state`, `dealer_country`) VALUES
(12, 'TEST', 'Sir1', 'testsir1@gmail.com', '+91 1234567890', '123456789@', 'New Modern Boys Hostel, Anandapur Rd, Nazirabad', 'testsir1.jpg', 'Hello i am test sir 1 ', '22.5247232', '88.3482336', 'Kolkata', 'Kolkata', '700100', 'West Bengal', 'India'),
(14, 'test', 'sir2', 'testsir2@gmail.com', '+919876543210', '123456789@', 'Ghandhi Nagar', 'testsir1.jpg', 'Hello ', '25.5361865', '86.5692364', 'Katihar', 'Katihar', '854105', 'Bihar', 'India'),
(15, 'test', 'sir3', 'testsir3@gmail.com', '+915246987254', '123456789@', 'fdgififhuh', 'ChatGPT Image Mar 28, 2025, 09_46_27 PM.png', 'thank u', '21.5247232', '88.3982336', 'Kolkata', 'Kolkata', '700100', 'West Bengal', 'India');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_phone` varchar(255) NOT NULL,
  `user_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_name`, `user_email`, `user_password`, `user_phone`, `user_status`) VALUES
(2, 'User1', 'user1@gmail.com', '123456789@', '12356790', '');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle`
--

CREATE TABLE `vehicle` (
  `vehicle_id` int(11) NOT NULL,
  `dealer_id` varchar(255) NOT NULL,
  `vehicle_make` varchar(255) NOT NULL,
  `vehicle_model` varchar(255) NOT NULL,
  `vehicle_year` varchar(255) NOT NULL,
  `daily_price` decimal(10,2) NOT NULL,
  `weekly_price` decimal(10,2) NOT NULL,
  `monthly_price` decimal(10,2) NOT NULL,
  `vehicle_description` varchar(255) NOT NULL,
  `main_image` text NOT NULL,
  `vehicle_specKey` text NOT NULL,
  `vehicle_features` varchar(255) NOT NULL,
  `vehicle_type` varchar(255) NOT NULL,
  `vehicle_specValue` text NOT NULL,
  `vehicle_status` varchar(255) NOT NULL,
  `additional_images` text NOT NULL,
  `dealer_longitude` varchar(255) NOT NULL,
  `dealer_latitude` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicle`
--

INSERT INTO `vehicle` (`vehicle_id`, `dealer_id`, `vehicle_make`, `vehicle_model`, `vehicle_year`, `daily_price`, `weekly_price`, `monthly_price`, `vehicle_description`, `main_image`, `vehicle_specKey`, `vehicle_features`, `vehicle_type`, `vehicle_specValue`, `vehicle_status`, `additional_images`, `dealer_longitude`, `dealer_latitude`) VALUES
(17, '12', 'Royal Enfield', 'Hunter 350', '2023', 100.00, 650.00, 2550.00, 'The Royal Enfield Hunter 350 is the most affordable Royal Enfield bike in India. Its roadster design, compact dimensions, adequate power and light weight make it a good choice for newer riders wanting to be a part of the Royal Enfield experience.', '1744051780_re-hunter-350-mn-right-side-view_600x400.avif', 'Engine: 349 CC |Top Speed: 114 kmph|Fuel Capacity: 13 Liters', 'Semi-digital|LCD console |Smartphone connectivity|Tripper navigation pod', 'bike', '', 'unavailable', '', '88.3982336', '22.5247232'),
(19, '12', 'Yamaha', 'MT 15 V2', '2023', 150.00, 950.00, 3850.00, 'The Yamaha MT 15 is a street naked motorcycle based on the Yamaha R15, and gets a potent 155cc engine. The bike’s lightweight nature coupled with the rev-friendly engine makes for a fun experience. It’s also more comfortable than the Yamaha R15, making it', '1744052166_test1.avif', 'Engine: 155 CC |Mileage: 47.94 Kmpl |Top Speed: 122 kmph |Fuel Capacity: 10 Liters ', '  Digital|  Bluetooth|  Self Start Only', 'bike', '', 'available', '', '88.3982336', '22.5247232'),
(20, '12', 'Royal Enfield', 'Classic 350', '2023', 150.00, 1000.00, 3900.00, 'The Classic 350 is the highest-selling motorcycle from Royal Enfield in India. The old-school roadster has a unique design language which attracts a wide range of audience for its looks and the chilled-out riding experience it offers.\r\n\r\n', '1744104611_clasic 350.avif', 'Engine: 349 CC |Mileage: 37.77 Kmpl |Max Power: 20.21 PS @ 6100 rpm | Fuel Capacity: 13 Liters|Top Speed: 120 kmph |Tyre Type: Tubeless ', 'LED lighting|USB charging port|semi-digital instrument |Tripper navigation pod', 'bike', '', 'available', '', '88.3982336', '22.5247232'),
(21, '12', 'KTM ', '250 Duke', '2023', 150.00, 1000.00, 4000.00, 'The KTM 250 Duke is a 250cc streetfighter from the Austrian brand. It looks similar to the 390 Duke but gets a smaller 250cc engine. It’s ideal for those who want a sporty naked that’s more affordable and less aggressive than the 390 Duke in terms of perf', '1744105077_ktm-250-duke.avif', 'Engine: 249 CC |Mileage: 30.08 Kmpl |Max Power: 31 PS @ 9250 rpm | Fuel Capacity: 15 Liters|Top Speed: 148 kmph |Tyre Type: Tubeless ', 'call/SMS alerts ride-by-wire throttle|2 display modes|LED lighting setup', 'bike', '', 'available', '', '88.3982336', '22.5247232'),
(22, '12', 'KTM ', 'RC 200', '2023', 150.00, 1000.00, 4000.00, 'The KTM RC 200 is based on the KTM Duke 200, but comes with a supersport design. It looks similar to the KTM RC 390.\r\n\r\n', '1744105401_ktm-rc-200.avif', 'Engine: 199 CC |Mileage: 35 Kmpl |Max Power: 25.8 PS |Fuel Capacity: 13.7 Liters |Top Speed: 140 kmph |Tyre Type: Tubeless ', 'TFT console|electronic riding aids|switchable ABS via Supermoto mode', 'bike', '', 'unavailable', '', '88.3982336', '22.5247232'),
(23, '14', 'KTM ', '390 Adventure', '2024', 150.00, 1000.00, 4000.00, 'The KTM 390 Adventure is by far one of the most capable adventure bikes in the sub-500cc segment. In its latest avatar, it comes with a completely new design, and looks much sleeker than before.', '1744106009_2025-ktm-390-adventure.avif', 'Engine: 398 CC |Mileage: 30 Kmpl |Max Power: 46 PS @ 8500 rpm |Fuel Capacity: 14.5 Liters |Top Speed: 155 kmph |Tyre Type: Tubeless ', '5-inch TFT display |smartphone connectivity|cornering ABS| cornering traction control|cruise control', 'bike', '', 'available', '', '', ''),
(24, '14', 'KTM ', '1390 Super Duke R', '2024', 150.00, 1000.00, 4000.00, 'The KTM 1390 Super Duke R is the most premium bike under the Duke range. It is also one of the most powerful naked bikes in the 1000+cc segment. The motorcycle comes with a very aggressive design, and is loaded with advanced features.', '1744106385_ktm-1390-super-duke.avif', 'Engine: 1350 CC |Mileage: 16.94 Kmpl |Max Power: 190.34 PS @ 10000 rpm | Fuel Capacity: 17.5 Liters|Top Speed: 250 kmph |Tyre Type: Tubeless ', 'liquid-cooled|5-inch TFT instrument|V-twin engine| Bluetooth connectivity ', 'bike', '', 'available', '', '', ''),
(25, '14', 'Maruti ', 'S-Presso', '2024', 500.00, 3200.00, 13000.00, 'Maruti S-Presso price starts at ₹ 4.26 Lakh and top model price goes upto ₹ 6.12 Lakh. S-Presso is offered in 8 variants - the base model of S-Presso is STD and the top model Maruti S-Presso VXI CNG.', '1744106920_Maruti S-Presso.avif', 'Engine : 998 cc|Torque : 82.1 Nm - 89 Nm|Mileage : 24.12 - 25.3 kmpl|Power : 55.92 - 65.71 bhp|Transmission : Manual / Automatic|Fuel : CNG / Petrol', ' passenger airbag|air-conditioning|power socket. |effortless driving ', 'car', '', 'available', '', '', ''),
(26, '15', 'Mahindra ', 'Thar', '2024', 500.00, 3200.00, 13000.00, 'The Mahindra Thar is a lifestyle off-road SUV. It combines strong road presence with a punchy engine, smooth transmission and some serious off-road capability. While the Thar is offered with rear-wheel drive as standard, the 4x4 version is offered as an o', '1744108401_Mahindra Thar.avif', 'Engine: 1497 cc - 2184 cc|Power : 116.93 - 150.19 bhp|Torque : 300 Nm - 320 Nm|Ground Clearance : 226 mm|Drive Type : 4WD / RWD|Seating Capacity : 4', 'Cruise Control|Parking Sensors|4x4 capabilities|off-roading', 'car', '', 'available', '', '22.5247232', '88.3982336'),
(27, '15', 'Kawasaki ', 'Ninja ZX-10R', '2024', 200.00, 1300.00, 6000.00, 'The Ninja ZX-10R is the most affordable litre-class supersport sold in India. It is loaded to the brim with track-friendly features, and offers excellent performance.\r\n\r\n', '1744108788_ninja-zx-10r.avif', 'Engine: 998 CC |Mileage: 12 Kmpl |Max Power: 203 PS @ 13200 rpm |Fuel Capacity: 17 Liters | Top Speed: 299 kmph|Tyre Type: Tubeless ', 'TFT instrument console|smartphone connectivity | IMU-enhanced electronics package|wheelie control', 'bike', '', 'available', '', '22.5247232', '88.3982336');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `dealer`
--
ALTER TABLE `dealer`
  ADD PRIMARY KEY (`dealer_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `vehicle`
--
ALTER TABLE `vehicle`
  ADD PRIMARY KEY (`vehicle_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `dealer`
--
ALTER TABLE `dealer`
  MODIFY `dealer_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `vehicle`
--
ALTER TABLE `vehicle`
  MODIFY `vehicle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
