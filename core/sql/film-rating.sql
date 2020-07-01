SELECT
  total_votes,
  ROUND(total_value / total_votes, 2) AS raw_rating,
  (SELECT IFNULL(raw_rating, FORMAT(0, 0))) AS rating
FROM films_user_rate
WHERE films_user_rate.id = CONCAT('film', :id);
