SELECT `films_users`.`real_name`, `comments`
FROM `films_reviews`
INNER JOIN `films_users` ON `films_reviews`.`userid` = `films_users`.`user_id`
WHERE `films_reviews`.`filmid` = :id;
