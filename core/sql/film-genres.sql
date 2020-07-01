SELECT `genre`
FROM `films_genre_categories`
INNER JOIN `films_genre` ON `films_genre_categories`.`id` = `films_genre`.`genres_id`
WHERE `films_genre`.`film_id` = :id;
