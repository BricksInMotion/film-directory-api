SELECT films.id,
  films.title,
  films.img_thumb AS thumbnail,
  films.date_create AS `date`,
  films.user_desc AS `description`,
  films.user_id,
  forums_users.username AS `user_name`
FROM films
JOIN forums_users ON forums_users.id = films.user_id
ORDER BY films.id DESC LIMIT :total;
