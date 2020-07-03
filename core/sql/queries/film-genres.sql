SELECT genre
FROM genres
INNER JOIN films_genre ON genres.id = films_genre.genres_id
WHERE films_genre.film_id = :id;
