SELECT 
    hs.name AS section_name,
    hq.question,
    ha.assessment_date,
    MAX(CASE WHEN c.name = 'Nairobi' THEN ha2.answer END) AS Nairobi,
    MAX(CASE WHEN c.name = 'Homa Bay' THEN ha2.answer END) AS `Homa Bay`,
    MAX(CASE WHEN c.name = 'Kiambu' THEN ha2.answer END) AS Kiambu,
    MAX(CASE WHEN c.name = 'Kirinyaga' THEN ha2.answer END) AS Kirinyaga,
    MAX(CASE WHEN c.name = 'Laikipia' THEN ha2.answer END) AS Laikipia,
    MAX(CASE WHEN c.name = 'Machakos' THEN ha2.answer END) AS Machakos,
    MAX(CASE WHEN c.name = 'Makueni' THEN ha2.answer END) AS Makueni,
    MAX(CASE WHEN c.name = 'Kitui' THEN ha2.answer END) AS Kitui,
    MAX(CASE WHEN c.name = 'Kisii' THEN ha2.answer END) AS Kisii,
    MAX(CASE WHEN c.name = 'Kisumu' THEN ha2.answer END) AS Kisumu,
    MAX(CASE WHEN c.name = 'Migori' THEN ha2.answer END) AS Migori,
    CONCAT(u.first_name, ' ', u.middle_name, ' ', u.last_name) AS user_name
FROM 
    hss_sections hs
JOIN 
    hss_questions hq ON hs.id = hq.section_id
JOIN 
    hss_assessments ha ON hq.section_id = ha.section_id
JOIN 
    hss_answers ha2 ON ha.id = ha2.assessment_id AND hq.id = ha2.question_id
JOIN 
    users u ON ha2.user_id = u.id
JOIN 
    counties c ON ha.county_code = c.code
GROUP BY 
    hs.name, hq.question, ha.assessment_date, u.first_name, u.middle_name, u.last_name
ORDER BY 
    hs.name, hq.question, ha.assessment_date;