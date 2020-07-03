-- Ideally there would be FK constains that would cascade down on deletion,
-- as well as the *user_rate* tables not use this weird PK format
DELETE FROM films_user_rate WHERE id = CONCAT('film', :id);
DELETE FROM films_user_rate id REGEXP CONCAT('^rev..', :id, '$');

DELETE FROM films_user_rate_votes WHERE id = CONCAT('film', :id);
DELETE FROM films_user_rate_votes id REGEXP CONCAT('^rev..', :id, '$');

DELETE FROM films_reviews WHERE filmid = :id;
DELETE FROM films_links WHERE film_id = :id;
DELETE FROM films_castcrew WHERE film_id = :id;
DELETE FROM films_genre WHERE film_id = :id;

DELETE FROM films WHERE id = :id;
