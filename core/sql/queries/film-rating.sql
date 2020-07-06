SELECT
  COUNT(rating) AS total_votes,
  IFNULL(ROUND(SUM(rating) / COUNT(rating), 2), 0) AS rating
FROM films_user_rate_votes
WHERE rating_id = CONCAT('film', :id);
