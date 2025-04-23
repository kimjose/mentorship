SELECT ap.title, ap.description, ap.status, DATE_FORMAT(ap.created_at, '%d/%m/%Y'), ap.due_date, f.mfl_code, f.name facility_name,
p.name program FROM action_points ap
LEFT JOIN facilities f ON f.id = ap.facility_id
LEFT JOIN programs p ON p.id = f.program_id WHERE ap.facility_id IS NOT NULL
