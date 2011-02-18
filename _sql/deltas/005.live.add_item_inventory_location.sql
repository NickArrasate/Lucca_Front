CREATE TABLE `item_inventory_location` (
  `item_id` int(11) NOT NULL,
  `inventory_location_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  UNIQUE KEY `item_location` (`item_id`,`inventory_location_id`)
);
