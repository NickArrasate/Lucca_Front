ALTER TABLE `inventory_locations` ADD `display_name` VARCHAR(30) AFTER `short`;

UPDATE `inventory_locations` SET `display_name` = 'Los Angeles' WHERE `short` = 'LA';
UPDATE `inventory_locations` SET `display_name` = 'New York' WHERE `short` = 'NY';
UPDATE `inventory_locations` SET `display_name` = 'Warehouse' WHERE `short` = 'WH';
