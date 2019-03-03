update tournament_group tg set tg.color_template = '1.2.2.3.3.4.4.5.5.5' where tg.id = 1;
update tournament_group tg set tg.color_template = '1.2.2.3.3.4.4.5.5.5' where tg.id = 2;
update tournament_group tg set tg.color_template = '1.2.2.3.3.4.4.5.6.6' where tg.id = 3; 
update tournament_group tg set tg.color_template = '1.2.2.3.3.4.4.5.6' where tg.id = 4; 
update tournament_group tg set tg.color_template = '1.2.2.3.3.4.4.5.6.6' where tg.id = 5; 
update tournament_group tg set tg.color_template = '1.2.2.3.3.4.4.5.6' where tg.id = 6; 

update game g set g.current_set = 1 where g.is_finished = 0;