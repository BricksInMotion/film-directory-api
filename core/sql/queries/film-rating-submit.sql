INSERT INTO films_user_rate_votes (
  rating_id, user_id, rating
) VALUES (
  CONCAT('film', :id), :user_id, :rating_value
);
