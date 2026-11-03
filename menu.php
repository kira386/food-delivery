<?php
require_once __DIR__.'/../../app/db.php'; require_once __DIR__.'/../../app/http.php';
$id=(int)($_GET['restaurant_id']??0);
$s=db()->prepare("SELECT id,restaurant_id,name,description,price,image_url FROM menu_items WHERE restaurant_id=? AND is_available=1 ORDER BY name");
$s->execute([$id]); json_response(['items'=>$s->fetchAll()]);
