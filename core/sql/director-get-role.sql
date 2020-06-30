SELECT
  `films`.`id`,
  `films`.`title`,
  YEAR(`films`.`date_create`) AS `year_released`,
  IF(`cast` = "", "", `cast`) AS `role`
FROM `films_castcrew`
INNER JOIN `films` ON `films`.`id` = `film_id`
WHERE `films_castcrew`.`user_id`= :id AND `job` = :role
ORDER BY `films`.`date_create` DESC;
