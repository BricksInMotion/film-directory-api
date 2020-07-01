SELECT
  warn_vio AS violence,
  warn_lang AS language,
  warn_sex AS sex
FROM films
WHERE id = :id;
