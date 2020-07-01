SELECT
  warn_lang AS language,
  warn_sex AS sex,
  warn_vio AS violence
FROM films
WHERE id = :id;
