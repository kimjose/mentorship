-- Active: 1676467061241@@127.0.0.1@3307@ess
create table password_resets(
    id int not null AUTO_INCREMENT PRIMARY KEY,
    user_id int not null,
    token VARCHAR(200) not null,
    is_used TINYINT not null default 0,
    expires_at TIMESTAMP not null,
    created_at TIMESTAMP null DEFAULT CURRENT_TIMESTAMP(),
    updated_at TIMESTAMP null DEFAULT CURRENT_TIMESTAMP,
    constraint fk_reset_user FOREIGN KEY(user_id) REFERENCES users(id) on delete cascade on update cascade
);

alter table facility_visits add column approved tinyint not null default 0 after created_by,
    add column approved_by int null after approved,
    add constraint visit_approver foreign key(approved_by) references users(id) on delete restrict on update cascade;


alter table facility_visits drop column approved,
    drop constraint visit_approver,
    drop column  approved_by,
    add column closed tinyint not null default 0 after created_by;

CREATE DEFINER=`root`@`localhost` EVENT `ess_midnight_tasks`
	ON SCHEDULE
		EVERY 1 DAY STARTS '2024-01-14 00:01:10'
	ON COMPLETION PRESERVE
	ENABLE
	COMMENT ''
	DO BEGIN
 
    UPDATE facility_visits SET closed = 0 WHERE closed = 1;
END

CREATE TABLE `programs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_program_name` (`name`) USING BTREE,
  KEY `FK_program_creator` (`created_by`),
  CONSTRAINT `FK_program_creator` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

insert into programs(name, created_by) VALUES('CONNECT', 1);
alter table facilities add column program_id int not null default 1 after id;
alter table facilities add constraint fk_facility_program FOREIGN KEY(program_id) REFERENCES programs(id) on delete restrict on update cascade;
alter table users add column program_ids text null after id;
alter table teams add column program_id int not null default 1 after id,
    add constraint fk_team_program FOREIGN KEY(program_id) REFERENCES programs(id) on update cascade on delete restrict;

create table team_members(
    user_id int not null,
    team_id int not null,
    created_at TIMESTAMP null DEFAULT CURRENT_TIMESTAMP(),
    updated_at TIMESTAMP null DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP(),
    PRIMARY KEY(user_id, team_id),
    constraint fk_team_member_team FOREIGN KEY(team_id) REFERENCES teams(id) on delete cascade on update cascade,
    constraint fk_team_members_user FOREIGN KEY(user_id) REFERENCES users(id) on delete cascade on update cascade
);
