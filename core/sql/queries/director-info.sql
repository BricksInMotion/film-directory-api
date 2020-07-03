SELECT
  `id`,
  `username` AS` user_name`,
  `realname` AS `real_name`
FROM `forums_users`
WHERE `forums_users`.`id`= :id
LIMIT 1;
