SELECT  p.name program, f.mfl_code, f.name facility_name, fv.id visit_id, fv.visit_date, fv.facility_id,
 c.title checklist, s.title section
 FROM facility_visits fv LEFT JOIN responses r ON r.visit_id = fv.id
LEFT JOIN questions q ON q.id = r.question_id
LEFT JOIN sections s ON s.id = q.section_id
LEFT JOIN checklists c ON c.id = s.checklist_id
LEFT JOIN facilities f ON f.id = fv.facility_id
LEFT JOIN programs p ON p.id = f.program_id
GROUP BY fv.id, s.id