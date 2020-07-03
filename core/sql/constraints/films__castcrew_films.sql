ALTER TABLE `films_castcrew`
  ADD CONSTRAINT `FK_films__castcrew_films`
  FOREIGN KEY (`film_id`)
  REFERENCES `films` (`id`)
  ON UPDATE CASCADE
  ON DELETE CASCADE;
