ALTER TABLE `films_castcrew`
ADD CONSTRAINT `FK_films_crewtype__films_castcrew`
  FOREIGN KEY (`job`)
  REFERENCES `films_crewtype` (`id`)
  ON UPDATE CASCADE
  ON DELETE RESTRICT;
