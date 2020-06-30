SELECT `id`, `username`, `realname`
FROM `forums_users`
WHERE `forums_users`.`id`= :id
LIMIT 1;
