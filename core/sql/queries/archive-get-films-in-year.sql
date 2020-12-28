SELECT
  id,
  title,
  date_create AS `date`,
  img_thumb AS thumbnail
FROM films
WHERE YEAR(date_create) = :year
ORDER BY YEAR(date_create) ASC;
