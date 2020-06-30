SELECT
  `title`,
  `user_desc` AS `desc`,
  `img_thumb` AS `thumbnail`,
  `lenth` AS `length`,
  `date_create` AS `release_date`
FROM `films`
WHERE `films`.`id`= ?
LIMIT 1;
