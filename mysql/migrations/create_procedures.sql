DELIMITER $$
CREATE PROCEDURE `count_all` (in entity_name varchar(45))
BEGIN
    set @s := concat('select count(*) from ', entity_name);
    prepare query from @s;
    execute query;
    deallocate prepare query;
END$$


CREATE PROCEDURE `count_orders_by_state`(out archived int,  out under_way int, out done int)
BEGIN

SELECT count(*) into archived from order_header_archived;
select count(*) into under_way from order_header oh inner join shipment s on oh.shipment_id = s.id where s.status = 1;
select count(*) into done from order_header where status = 2;

END$$


CREATE PROCEDURE `count_shipments_by_state`(out archived int,  out under_way int, out done int)
BEGIN

SELECT count(*) into archived from shipment_archived;
select count(*) into under_way from shipment where status = 1;
select count(*) into done from shipment where status = 2;

END$$



CREATE PROCEDURE `delete_one_by_id`(in entity varchar(45), in id varchar(5))
BEGIN
	set @s := concat('delete from ', entity, ' where id = ', id);

    if entity = "order_header" then
		set @prior := concat('delete from order_line where order_header_id = ', id);
        prepare query from @prior;
		execute query;
		deallocate prepare query;
    end if;

    prepare query from @s;
    execute query;
    deallocate prepare query;
END$$



CREATE PROCEDURE `fetch_all`(in entity_name varchar(45))
BEGIN
    set @s := concat('select * from ', entity_name);
    prepare query from @s;
    execute query;
    deallocate prepare query;
END$$



CREATE PROCEDURE `get_orders_by_warehouse`(in warehouse varchar(25))
BEGIN
	select id, shipment_id, `status`, address, (
		case
			when is_return = 0 then 'delivery'
            when is_return = 1 then 'return'
            else 'undefined'
		end
    ) as type
    from order_header where warehouse_id = (select id from warehouse where name like warehouse);
END$$


CREATE PROCEDURE `get_shipments_by_warehouse`(in warehouse varchar(25))
BEGIN
	select * from shipment where warehouse_id = (select id from warehouse where name like warehouse);
END$$


CREATE PROCEDURE `handle_archive_orders`(in months_interval int, in valid_limit int)
BEGIN
  DECLARE bDone INT DEFAULT 0;

  DECLARE id_tmp int;
  DECLARE shipment_id_tmp INT;
  DECLARE warehouse_id_tmp INT;

  DECLARE curs CURSOR FOR SELECT id, shipment_id, warehouse_id FROM order_header oh
  LEFT JOIN shipment s on s.id = oh.shipment_id
  WHERE s.id is not null
  AND s.date > date_sub(curdate(), INTERVAL months_interval MONTH)
  AND oh.`status` = 2
  order by `date` desc limit valid_limit;

  DECLARE CONTINUE HANDLER FOR NOT FOUND SET bDone = 1;

  OPEN curs;

  archive: LOOP
	FETCH curs INTO id_tmp, shipment_id_tmp, warehouse_id_tmp;

    IF bDone = 1 THEN
		LEAVE archive;
	END IF;

	insert into log(msg, timestamp) values (concat('Archiving order ', id_tmp), now());

	INSERT INTO order_header_archived VALUES (id_tmp, shipment_id_tmp, warehouse_id_tmp);
    DELETE from order_header where id = id_tmp;
  end loop archive;

  CLOSE curs;
END$$


CREATE PROCEDURE `handle_archive_shipments`(in months_interval int, in valid_limit int)
BEGIN
  DECLARE bDone INT DEFAULT 0;

  DECLARE id_tmp int;
  DECLARE warehouse_id_tmp INT;

  DECLARE curs CURSOR FOR  SELECT id, warehouse_id FROM shipment
  WHERE date > date_sub(curdate(), INTERVAL months_interval MONTH)
  AND `status` = 2
  order by `date` desc limit valid_limit;

  DECLARE CONTINUE HANDLER FOR NOT FOUND SET bDone = 1;

  OPEN curs;

  archive: LOOP
	FETCH curs INTO id_tmp, warehouse_id_tmp;

    IF bDone = 1 THEN
		LEAVE archive;
	END IF;

	insert into log(msg, timestamp) values (concat('Archiving ', id_tmp), now());

	INSERT INTO shipment_archived VALUES (id_tmp, warehouse_id_tmp);
    DELETE from shipment where id = id_tmp;
  end loop archive;

  CLOSE curs;
END$$


CREATE PROCEDURE `mark_one_as`(in entity varchar(45), in id varchar(5), in entity_status varchar(5))
BEGIN
	set @s := concat('update ', entity, ' set status = ', entity_status, ' where id = ', id);

    prepare query from @s;
    execute query;
    deallocate prepare query;
END$$


CREATE PROCEDURE `sync_shipments`()
BEGIN
	set SQL_SAFE_UPDATES = 0;
	update shipment s set s.no_of_orders = (select count(*) from order_header where shipment_id = s.id) where 1 = 1;
    set SQL_SAFE_UPDATES = 1;
END$$
