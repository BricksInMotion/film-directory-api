SELECT
`films`.`id`,
`films`.`title`
FROM `films`
WHERE `films`.`title` LIKE CONCAT("%", :title, "%")
ORDER BY `films`.`title` ASC;
