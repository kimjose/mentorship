-- This SQL script creates the necessary tables for the Health Performance Tracking (HPT) system.
-- hpt_sections: Contains the sections of the HPT assessment.
create table if not exists hpt_sections (
    id int primary key AUTO_INCREMENT,
    name VARCHAR(100) not null,
    linked_to_commodity boolean default false,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp
);
-- hpt_questions: Contains the questions for each section of the HPT assessment.
create table if not exists hpt_questions (
    id int primary key AUTO_INCREMENT,
    section_id int not null,
    question_text VARCHAR(255) not null,
    question_type enum('check_opt', 'textfield_s', 'radio_opt', 'number_opt') not null,
    options text, -- JSON or comma-separated values for multiple choice options
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp,
    foreign key (section_id) references hpt_sections(id)
);
-- hpt_assessments: Contains the details of each HPT assessment.
create table if not exists hpt_assessments (
    id int primary key AUTO_INCREMENT,
    date_of_interview date not null,
    interviewer_id int not null,
    facility_id int not null,
    sub_county VARCHAR(100),
    ward VARCHAR(100),
    facility_ownership enum('Ministry of Health', 'Private', 'Faith-based Organization', 'Non-Governmental Organization', 'Other') not null,
    facility_ownership_other VARCHAR(100),
    keph_level enum('Level 1', 'Level 2', 'Level 3', 'Level 4', 'Level 5', 'Level 6') not null,
    review_period_start date not null,
    review_period_end date not null,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp,
    foreign key (interviewer_id) references users(id),
    foreign key (facility_id) references facilities(id)
);

-- hpt_assessment_commodities: Contains the commodities linked to each HPT assessment.
create table if not exists hpt_assessment_commodities (
    id int primary key AUTO_INCREMENT,
    assessment_id int not null,
    commodity_name VARCHAR(100) not null,
    able_to_verify boolean default false,
    reason_for_not_verifying VARCHAR(255),
    reason_for_not_verifying_other VARCHAR(255),
    created_by int not null, -- User ID of the person who created the commodity entry
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp,
    foreign key (assessment_id) references hpt_assessments(id),
    foreign key (created_by) references users(id)
);

-- hpt_assessment_responses: Contains the responses to each question in the HPT assessment.
create table if not exists hpt_assessment_responses (
    id int primary key AUTO_INCREMENT,
    assessment_id int not null,
    assessment_commodity_id int, -- Nullable if the question is not related to a commodity
    question_id int not null,
    answer text,
    created_by int not null, -- User ID of the person who created the response
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp,
    foreign key (assessment_id) references hpt_assessments(id),
    foreign key (question_id) references hpt_questions(id),
    foreign key (created_by) references users(id) 
);
