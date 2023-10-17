create table password_resets(
    id int not null AUTO_INCREMENT PRIMARY KEY,
    user_id not null,
    token VARCHAR(200) not null,
    is_used TINYINT not null default = 0,
    expires_at TIMESTAMP not null,
    created_at TIMESTAMP null DEFAULT CURRENT_TIMESTAMP(),
    updated_at TIMESTAMP null DEFAULT CURRENT_TIMESTAMP,
    constraint fk_reset_user FOREIGN KEY(user_id) REFERENCES users(id) on delete cascade on update cascade
);