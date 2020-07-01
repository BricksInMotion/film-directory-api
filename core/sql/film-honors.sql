SELECT CAST(`review_stat` AS CHAR) AS `review_stat`
FROM `films`
WHERE `id` = :id;
