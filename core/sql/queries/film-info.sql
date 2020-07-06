SELECT
  `id`,
  `title`,
  `user_desc` AS `description`,
  `img_thumb` AS `thumbnail`,
  `lenth` AS `runtime`,
  `date_create` AS `release_date`,
  `user_id`
FROM `films`
WHERE `films`.`id`= :id
LIMIT 1;
