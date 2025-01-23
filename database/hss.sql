create table hss_sections(
    id serial primary key,
    name varchar(255) not null,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp
);

create table hss_questions(
    id serial primary key,
    section_id int not null,
    question text not null,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp
);
create table hss_assessments(
    id serial primary key,
    user_id int not null,
    county_code int not null,
    section_id int not null,
    assessment_date date not null,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp,
    constraint fk_hss_assessments_user_id foreign key(user_id) references users(id) on update cascade on delete RESTRICT,
    constraint fk_hss_assessments_county_code foreign key(county_code) references counties(code) on update cascade on delete RESTRICT
);

create table hss_answers(
    id serial primary key,
    assessment_id BIGINT UNSIGNED not null,
    question_id BIGINT UNSIGNED not null,
    answer text not null,
    user_id int not null,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp,
    constraint fk_hss_answers_question_id foreign key(question_id) references hss_questions(id) on update cascade on delete RESTRICT,
    constraint fk_hss_answers_assessment_id foreign key(assessment_id) references hss_assessments(id) on update cascade on delete RESTRICT,
    constraint fk_hss_answers_user_id foreign key(user_id) references users(id) on update cascade on delete RESTRICT
);