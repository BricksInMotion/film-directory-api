SELECT
  `forums_users`.`username` AS `user_name`
  `comments`
FROM `films_reviews`
INNER JOIN `forums_users` ON `films_reviews`.`userid` = `forums_users`.`id`
WHERE `films_reviews`.`filmid` = :id;
