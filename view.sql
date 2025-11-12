CREATE OR REPLACE  VIEW itemsview AS
SELECT items.* , categories.* FROM items 
INNER JOIN  categories on  items.items_cat = categories.categories_id ;

CREATE OR REPLACE VIEW myfavorite AS
SELECT favorite.* , items.* , users.users_id FROM favorite 
INNER JOIN users ON users.users_id  = favorite.favorite_users_id
INNER JOIN items ON items.items_id  = favorite.favorite_items_id ;

CREATE or REPLACE VIEW cartview AS 
SELECT SUM(items.items_price - items.items_price * items_discount / 100) as ask_price  , COUNT(cart.cart_items_id) as number_of_pieces, cart.* , items.* FROM cart 
INNER JOIN items ON items.items_id = cart.cart_items_id
WHERE cart_orders = 0 
GROUP BY cart.cart_items_id , cart.cart_users_id ,cart.cart_orders ;

CREATE  OR REPLACE VIEW ordersview AS 
SELECT orders.* , address.* FROM orders 
LEFT JOIN address ON address.address_id = orders.orders_address ; 

CREATE OR REPLACE VIEW ordersdetailsview  AS 
SELECT SUM(items.items_price - items.items_price * items_discount / 100) as itemsprice  , COUNT(cart.cart_items_id) as countitems , cart.* , items.*   FROM cart 
INNER JOIN items ON items.items_id = cart.cart_items_id 
WHERE cart_orders != 0 
GROUP BY cart.cart_items_id , cart.cart_users_id , cart.cart_orders ;

CREATE OR REPLACE VIEW itemstopselling AS 
SELECT COUNT(cart_id) as countitems , cart.* , items.*  , (items_price - (items_price * items_discount / 100 ))  as itemspricedisount  FROM cart 
INNER JOIN items ON items.items_id = cart.cart_items_id
WHERE cart_orders != 0 
GROUP by cart_items_id ; 



 