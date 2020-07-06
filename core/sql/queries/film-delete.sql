DELETE FROM films_user_rate WHERE id = CONCAT('film', :id);
DELETE FROM films_user_rate WHERE id REGEXP CONCAT('^rev..', :id, '$');

DELETE FROM films_user_rate_votes WHERE rating_id = CONCAT('film', :id);
DELETE FROM films_user_rate_votes WHERE rating_id REGEXP CONCAT('^rev..', :id, '$');

DELETE FROM films_reviews WHERE filmid = :id;
DELETE FROM films_links WHERE film_id = :id;
DELETE FROM films_castcrew WHERE film_id = :id;
DELETE FROM films_genre WHERE film_id = :id;

DELETE FROM films WHERE id = :id;
