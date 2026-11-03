<?php
require_once __DIR__.'/../../app/db.php'; require_once __DIR__.'/../../app/http.php';
$d=json_input(); $name=trim($d['name']??''); $email=strtolower(trim($d['email']??'')); $password=$d['password']??'';
$role=$d['role']??'customer';
if($name==='' || !filter_var($email,FILTER_VALIDATE_EMAIL) || strlen($password)<10 || !in_array($role,['customer','restaurant','courier'],true)) json_response(['error'=>'Invalid registration data. Password must be at least 10 characters.'],422);
try {
 $s=db()->prepare("INSERT INTO users(name,email,password_hash,role) VALUES(?,?,?,?)");
 $s->execute([$name,$email,password_hash($password,PASSWORD_DEFAULT),$role]);
 json_response(['success'=>true,'user_id'=>(int)db()->lastInsertId()],201);
} catch(PDOException $e) { json_response(['error'=>'Email may already be registered.'],409); }
