CREATE FUNCTION `archive_orders`() RETURNS int(11)
BEGIN
	declare valid_limit int;
    
	set @months_interval = 3;
    set @procentual_limit = 30;
    
    select ((@procentual_limit / 100) * count(*)) into valid_limit from order_header;
    
    call handle_archive_orders(@months_interval, valid_limit);
RETURN 1;
END$$


CREATE FUNCTION `archive_shipments`() RETURNS int(11)
BEGIN
	declare valid_limit int;
    
	set @months_interval = 3;
    set @procentual_limit = 30;
    
    select ((@procentual_limit / 100) * count(*)) into valid_limit from shipment;
    
    call handle_archive_shipments(@months_interval, valid_limit);
RETURN 1;
END$$


CREATE FUNCTION `count_shipments_under_way`() RETURNS int(11)
BEGIN
DECLARE result int;
	select count(*) into result from shipment where status = 1;
RETURN result;
END$$


CREATE FUNCTION `count_shipping_orders`() RETURNS int(11)
BEGIN
DECLARE result int;
	select count(*) into result from order_header oh inner join shipment s on oh.shipment_id = s.id where s.status = 1;
RETURN result;
END$$


CREATE FUNCTION `get_total_stock_sold`() RETURNS int(11)
BEGIN
DECLARE result bigint;
	select sum(order_stock) into result from order_line ol inner join order_header oh on ol.order_header_id = oh.id where oh.status = 2;
RETURN result;
END$$