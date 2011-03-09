begin transaction;

create language 'plpgsql';
CREATE OR REPLACE FUNCTION update_updated()
	RETURNS TRIGGER AS $$
	BEGIN
	   NEW.updated = now(); 
	   RETURN NEW;
	END;
$$ language 'plpgsql';


create table pizza_order (
       id serial primary key,	
       created timestamp default now(),
       updated timestamp default now(),
       
       admin_uuid varchar(42) unique ,
       user_uuid varchar(42) unique ,
       
       driver varchar(100),
       collector varchar(100),
       
       pickup_time timestamp default NULL,
       order_time timestamp default NULL
);


CREATE TRIGGER update_updated BEFORE UPDATE
        ON pizza_order FOR EACH ROW EXECUTE PROCEDURE 
        update_updated();

create table pizza (
       id serial primary key,	-- pizza id
       created timestamp default now(),
       updated timestamp default now(),
       order_id integer references pizza_order(id),
       
       username  varchar(150),	 
       pizzaID  varchar(50),
       comment  varchar(1024),	
       price numeric(5,2) default 60, 	--max prize 999.99

       chili boolean,
       garlic boolean,
       cheese boolean,
       bacon boolean,

       paid boolean
);


CREATE TRIGGER update_updated BEFORE UPDATE
        ON pizza FOR EACH ROW EXECUTE PROCEDURE 
        update_updated();
commit;
