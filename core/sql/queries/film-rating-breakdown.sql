SELECT
  user_id,
  IFNULL(username, '(N/A)') AS user_name,
  rating,
  rating_date
FROM films_user_rate_votes
LEFT JOIN forums_users ON forums_users.id = user_id
WHERE rating_id = CONCAT('film', :id)
ORDER BY rating_date;
