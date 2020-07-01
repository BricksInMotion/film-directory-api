SELECT
`id`,
ROUND(`total_value` / `total_votes`, 1) AS `raw_rating`,
(SELECT IFNULL(`raw_rating`, "N/A")) AS `rating`
FROM films_user_rate
WHERE id REGEXP CONCAT("^rev..", :id, "$")';
