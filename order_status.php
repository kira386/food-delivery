<?php
require_once __DIR__.'/../../app/db.php'; require_once __DIR__.'/../../app/http.php';
$user=bearer_user(); if($_SERVER['REQUEST_METHOD']!=='PATCH')json_response(['error'=>'Use PATCH'],405);
$d=json_input();$id=(int)($d['order_id']??0);$status=$d['status']??'';
$allowed=['accepted','preparing','ready','picked_up','delivered','cancelled'];
if(!in_array($status,$allowed,true))json_response(['error'=>'Invalid status'],422);
if($user['role']==='courier')$s=db()->prepare("UPDATE orders SET status=? WHERE id=? AND courier_id=? AND status IN ('ready','picked_up')");
elseif($user['role']==='restaurant')$s=db()->prepare("UPDATE orders o JOIN restaurants r ON r.id=o.restaurant_id SET o.status=? WHERE o.id=? AND r.manager_id=? AND o.status IN ('pending','accepted','preparing','ready')");
else json_response(['error'=>'Forbidden'],403);
$s->execute([$status,$id,$user['id']]);json_response(['success'=>$s->rowCount()>0]);
