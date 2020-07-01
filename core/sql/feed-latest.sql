SELECT films.id,
  films.title,
  films.user_id,
  forums_users.username AS `user_name`,
  films.img_thumb AS thumbnail
FROM films
JOIN forums_users ON forums_users.id = films.user_id
ORDER BY films.id DESC LIMIT 8;
