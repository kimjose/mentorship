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
