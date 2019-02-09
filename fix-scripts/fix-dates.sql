update game g
set date_played = '2019-01-30'
where is_walkover = 1;
update game g
set date_played = date_of_match
where is_walkover != 1;