USE dev;

-- ALTER TABLE `warehouse_product` ADD UNIQUE `prod_loc`(`warehouse_id`, `product_id`);

ALTER TABLE order_header
ADD FOREIGN KEY (shipment_id) REFERENCES shipment(id);

ALTER TABLE order_header
ADD FOREIGN KEY (warehouse_id) REFERENCES warehouse(id);

ALTER TABLE order_line
ADD FOREIGN KEY (warehouse_product_id) REFERENCES warehouse_product(id);

ALTER TABLE order_line
ADD FOREIGN KEY (order_header_id) REFERENCES order_header(id);

ALTER TABLE warehouse_product
ADD FOREIGN KEY (product_id) REFERENCES product(id);

ALTER TABLE warehouse_product
ADD FOREIGN KEY (warehouse_id) REFERENCES warehouse(id);

-- //BACKUP: PROCEDURES: FUNCTIONS:
--
-- CREATE DEFINER=`andi`@`localhost` PROCEDURE `count_all`(in entity_name varchar(45))
-- BEGIN
--     set @s := concat('select count(*) from ', entity_name);
--     prepare query from @s;
--     execute query;
--     deallocate prepare query;
-- END
--
-- CREATE DEFINER=`andi`@`localhost` PROCEDURE `count_orders_by_state`(out archived int,  out under_way int, out done int)
-- BEGIN
--
-- SELECT count(*) into archived from order_header_archived;
-- select count(*) into under_way from order_header oh inner join shipment s on oh.shipment_id = s.id where s.status = 1;
-- select count(*) into done from order_header where status = 2;
--
-- END
--
-- CREATE DEFINER=`andi`@`localhost` PROCEDURE `count_shipments_by_state`(out archived int,  out under_way int, out done int)
-- BEGIN
--
-- SELECT count(*) into archived from shipment_archived;
-- select count(*) into under_way from shipment where status = 1;
-- select count(*) into done from shipment where status = 2;
--
-- END
--
-- CREATE DEFINER=`andi`@`localhost` PROCEDURE `delete_one_by_id`(in entity varchar(45), in id varchar(5))
-- BEGIN
-- 	set @s := concat('delete from ', entity, ' where id = ', id);
--
--     prepare query from @s;
--     execute query;
--     deallocate prepare query;
-- END
--
-- CREATE DEFINER=`andi`@`localhost` PROCEDURE `fetch_all`(in entity_name varchar(45))
-- BEGIN
--     set @s := concat('select * from ', entity_name);
--     prepare query from @s;
--     execute query;
--     deallocate prepare query;
-- END
--
-- CREATE DEFINER=`andi`@`localhost` PROCEDURE `get_orders_by_warehouse`(in warehouse varchar(25))
-- BEGIN
-- 	select id, shipment_id, `status`, address, (
-- 		case
-- 			when is_return = 0 then 'delivery'
--             when is_return = 1 then 'return'
--             else 'undefined'
-- 		end
--     ) as type
--     from order_header where warehouse_id = (select id from warehouse where name like warehouse);
-- END
--
-- CREATE DEFINER=`andi`@`localhost` PROCEDURE `get_shipments_by_warehouse`(in warehouse varchar(25))
-- BEGIN
-- 	select * from shipment where warehouse_id = (select id from warehouse where name like warehouse);
-- END
--
-- CREATE DEFINER=`andi`@`localhost` PROCEDURE `handle_archive_shipments`(in months_interval int, in valid_limit int)
-- BEGIN
--   DECLARE bDone INT;
--
--   DECLARE id_tmp int;
--   DECLARE warehouse_id_tmp INT;
--
--   DECLARE curs CURSOR FOR  SELECT something FROM shipment
--   WHERE date > date_sub(curdate(), INTERVAL months_interval MONTH)
--   order by `date` desc limit valid_limit;
--
--   DECLARE CONTINUE HANDLER FOR NOT FOUND SET bDone = 1;
--
--   OPEN curs;
--
--   SET bDone = 0;
--   REPEAT
--     FETCH curs INTO id_tmp, warehouse_id_tmp;
-- 	INSERT INTO shipment_archived VALUES (id_tmp, warehouse_id_tmp);
--     DELETE from shipment where id = id_tmp;
--   UNTIL bDone END REPEAT;
--
--   CLOSE curs;
-- END
--
-- CREATE DEFINER=`andi`@`localhost` PROCEDURE `mark_one_as`(in entity varchar(45), in id varchar(5), in entity_status varchar(5))
-- BEGIN
-- 	set @s := concat('update ', entity, ' set status = ', entity_status, ' where id = ', id);
--
--     prepare query from @s;
--     execute query;
--     deallocate prepare query;
-- END
--
-- CREATE DEFINER=`andi`@`localhost` PROCEDURE `sync_shipments`()
-- BEGIN
-- 	set SQL_SAFE_UPDATES = 0;
-- 	update shipment s set s.no_of_orders = (select count(*) from order_header where shipment_id = s.id) where 1 = 1;
--     set SQL_SAFE_UPDATES = 1;
-- END
--
-- CREATE DEFINER=`andi`@`localhost` FUNCTION `archive_shipments`() RETURNS int(11)
-- BEGIN
-- 	declare valid_limit int;
--
-- 	set @months_interval = 3;
--     set @procentual_limit = 30;
--
--     select ((procentual_limit / 100) * count(*)) into valid_limit from shipment;
--
--     call handle_archive_shipments(months_interval, valid_limit);
-- RETURN 1;
-- END
--
-- CREATE DEFINER=`andi`@`localhost` FUNCTION `count_shipments_under_way`() RETURNS int(11)
-- BEGIN
-- DECLARE result int;
-- 	select count(*) into result from shipment where status = 1;
-- RETURN result;
-- END
--
-- CREATE DEFINER=`andi`@`localhost` FUNCTION `count_shipping_orders`() RETURNS int(11)
-- BEGIN
-- DECLARE result int;
-- 	select count(*) into result from order_header oh inner join shipment s on oh.shipment_id = s.id where s.status = 1;
-- RETURN result;
-- END
--
-- CREATE DEFINER=`andi`@`localhost` FUNCTION `new_function`() RETURNS int(11)
-- BEGIN
-- DECLARE result bigint;
-- 	select sum(order_stock) into result from order_line ol inner join order_header oh on ol.order_header_id = oh.id where oh.status = 3;
-- RETURN result;
-- END
--
-- CREATE DEFINER=`andi`@`localhost` FUNCTION `archive_orders`() RETURNS int(11)
-- BEGIN
-- 	declare valid_limit int;
--
-- 	set @months_interval = 3;
--     set @procentual_limit = 30;
--
--     select ((procentual_limit / 100) * count(*)) into valid_limit from order_header;
--
--     call handle_archive_orders(months_interval, valid_limit);
-- RETURN 1;
-- END
--
--
-- DELIMITER //
-- create trigger handle_stocks
-- 	after update on order_header
-- 	for each row
-- begin
--     DECLARE id_tmp INT;
--     DECLARE new_stock_tmp INT;
--
--     DECLARE bDone INT;
--
--     DECLARE curs CURSOR FOR
--     SELECT wp.id, (wp.stock - ol.order_stock) as new_stock
--     FROM order_line ol
--     inner join warehouse_product wp on ol.warehouse_product_id = wp.id
--     where ol.order_header_id = OLD.id;
--
-- 	DECLARE CONTINUE HANDLER FOR NOT FOUND SET bDone = 1;
--
-- 	if NEW.`status` <> OLD.`status` and NEW.`status` = 2 then
-- 		OPEN curs;
-- 		SET bDone = 0;
-- 		REPEAT
-- 			FETCH curs INTO id_tmp, new_stock_tmp;
-- 			update warehouse_product set stock = new_stock_tmp where id = id_tmp;
-- 		UNTIL bDone END REPEAT;
-- 		CLOSE curs;
--     end if;
-- end //
-- DELIMITER ;
