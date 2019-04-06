CREATE trigger handle_stocks
	after update on order_header
	for each row
begin
    DECLARE id_tmp INT;
    DECLARE new_stock_tmp INT;
    
    DECLARE bDone INT;
    
    DECLARE curs CURSOR FOR  
    SELECT wp.id, (wp.stock - ol.order_stock) as new_stock 
    FROM order_line ol
    inner join warehouse_product wp on ol.warehouse_product_id = wp.id
    where ol.order_header_id = OLD.id;
    
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET bDone = 1;
    
	if NEW.`status` <> OLD.`status` and NEW.`status` = 2 then
		OPEN curs;
		SET bDone = 0;
		REPEAT
			FETCH curs INTO id_tmp, new_stock_tmp;
			update warehouse_product set stock = new_stock_tmp where id = id_tmp;
		UNTIL bDone END REPEAT;
		CLOSE curs;
    end if;
end$$