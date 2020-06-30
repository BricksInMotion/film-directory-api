SELECT
`films`.`id`,
`films`.`title`
FROM `films`
WHERE `films`.`title` LIKE CONCAT("%", ?, "%")
ORDER BY `films`.`title` ASC;
