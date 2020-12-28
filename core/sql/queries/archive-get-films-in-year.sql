SELECT id, title, date_create AS `date`
FROM films
WHERE YEAR(date_create) = :year
ORDER BY YEAR(date_create) ASC;
