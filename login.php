<?php
require_once __DIR__.'/../../app/db.php'; require_once __DIR__.'/../../app/http.php';
$d=json_input(); $s=db()->prepare("SELECT id,name,password_hash,role FROM users WHERE email=? AND is_active=1");
$s->execute([strtolower(trim($d['email']??''))]); $u=$s->fetch();
if(!$u || !password_verify($d['password']??'', $u['password_hash'])) json_response(['error'=>'Invalid credentials'],401);
$token=bin2hex(random_bytes(32)); $hash=hash('sha256',$token);
db()->prepare("INSERT INTO api_tokens(user_id,token_hash,expires_at) VALUES(?,?,DATE_ADD(NOW(),INTERVAL 7 DAY))")->execute([$u['id'],$hash]);
json_response(['token'=>$token,'user'=>['id'=>(int)$u['id'],'name'=>$u['name'],'role'=>$u['role']]]);
