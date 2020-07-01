SELECT
  IF(`cast` = "", `crewdesc`, `cast`) AS `crewname`,
  `name` AS `raw_db_name`,
  IFNULL(`user_id`, 0) AS `cc_user_id`,
  (SELECT `real_name` FROM `films_users` WHERE `cc_user_id` = `films_users`.`user_id`) AS `raw_user_name`,
  (SELECT IFNULL(`raw_db_name`, `raw_user_name`)) AS `raw_name`,
  (SELECT IFNULL(`raw_name`, "Unknown")) AS `name`
FROM `films_castcrew`
  INNER JOIN `films_crewtype` ON `films_castcrew`.`job` = `films_crewtype`.`id`
WHERE `job` >= 8 AND `film_id` = :id
ORDER BY `job` ASC;
