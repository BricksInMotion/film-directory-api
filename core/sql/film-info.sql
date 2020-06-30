SELECT
  `id`,
  `title`,
  `user_desc` AS `desc`,
  `img_thumb` AS `thumbnail`,
  `lenth` AS `length`,
  `date_create` AS `release_date`,
  `user_id` AS `director_id`
FROM `films`
WHERE `films`.`id`= ?
LIMIT 1;
