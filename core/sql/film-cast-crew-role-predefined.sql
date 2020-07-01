SELECT
  `films_crewtype`.`id` AS `role_id`,
  `films_crewtype`.`crewname` AS `role`,
  `films_crewtype`.`crewdesc` AS `description`,
  `name` AS `raw_db_name`,
  IFNULL(`user_id`, 0) AS `user_id`,
  (SELECT `username` FROM `forums_users` WHERE `user_id` = `forums_users`.`id`) AS `raw_user_name`,
  (SELECT IFNULL(`raw_db_name`, `raw_user_name`)) AS `raw_name`,
  (SELECT IFNULL(`raw_name`, "Unknown")) AS `user_name`
FROM `films_castcrew`
  INNER JOIN `films_crewtype` ON `films_castcrew`.`job` = `films_crewtype`.`id`
WHERE `job` < 8 AND `film_id` = :id
ORDER BY `job` ASC;
