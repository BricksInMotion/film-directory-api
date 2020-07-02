SELECT
  link AS url,
  link_desc AS label
FROM films_links
WHERE film_id = :id;
