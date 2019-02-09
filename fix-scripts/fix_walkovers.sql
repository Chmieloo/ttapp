update game gg set gg.is_walkover = 1 where gg.id in (
select s.game_id 
from scores s
group by s.game_id
having (sum(home_points) = 33 and sum(away_points) = 0) or (sum(home_points) = 0 and sum(away_points) = 33)
)