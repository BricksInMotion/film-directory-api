CREATE TABLE `api_keys` (
  `id` TINYINT(4) UNSIGNED NOT NULL AUTO_INCREMENT,
  `token` VARCHAR(64) NOT NULL COLLATE 'utf8mb4_unicode_ci',
  `date_created` DATETIME NOT NULL DEFAULT current_timestamp(),
  `desc` VARCHAR(256) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `token` (`token`)
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB;
