SELECT
  id,
  username AS user_name,
  realname AS real_name
FROM forums_users
WHERE
  group_id <> 0
  AND (
    username LIKE CONCAT('%', :name, '%')
    OR realname LIKE CONCAT('%', :name, '%')
  );
