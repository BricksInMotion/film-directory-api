INSERT INTO films (
  title, `user_id`, user_desc,
  warn_sex, warn_lang, warn_vio,
  review_stat, date_create, lenth
) VALUES (
  :title, :user_id, :description,
  :sex, :language, :violence,
  1, :release_date, :length
);
