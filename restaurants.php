<?php
require_once __DIR__.'/../../app/db.php'; require_once __DIR__.'/../../app/http.php';
$q=db()->query("SELECT r.id,r.name,r.description,r.image_url,u.name manager_name FROM restaurants r JOIN users u ON u.id=r.manager_id WHERE r.is_active=1 ORDER BY r.name");
json_response(['restaurants'=>$q->fetchAll()]);
