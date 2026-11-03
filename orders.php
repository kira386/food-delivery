<?php
require_once __DIR__.'/../../app/db.php'; require_once __DIR__.'/../../app/http.php';
$user=bearer_user();
if($_SERVER['REQUEST_METHOD']==='GET') {
 if($user['role']==='customer') {$s=db()->prepare("SELECT * FROM orders WHERE customer_id=? ORDER BY id DESC");$s->execute([$user['id']]);}
 elseif($user['role']==='courier') {$s=db()->prepare("SELECT * FROM orders WHERE courier_id=? ORDER BY id DESC");$s->execute([$user['id']]);}
 elseif($user['role']==='restaurant') {$s=db()->prepare("SELECT DISTINCT o.* FROM orders o JOIN order_items i ON i.order_id=o.id JOIN restaurants r ON r.id=i.restaurant_id WHERE r.manager_id=? ORDER BY o.id DESC");$s->execute([$user['id']]);}
 else json_response(['error'=>'Role not supported'],403);
 json_response(['orders'=>$s->fetchAll()]);
}
if($_SERVER['REQUEST_METHOD']==='POST' && $user['role']==='customer') {
 $d=json_input(); $restaurant=(int)($d['restaurant_id']??0); $items=$d['items']??[];
 if(!$restaurant || !is_array($items) || !$items) json_response(['error'=>'restaurant_id and items are required'],422);
 $pdo=db(); $pdo->beginTransaction();
 try {
  $r=$pdo->prepare("SELECT id FROM restaurants WHERE id=? AND is_active=1");$r->execute([$restaurant]);if(!$r->fetch())throw new RuntimeException('Restaurant unavailable');
  $total=0;$lines=[];
  foreach($items as $item){$mid=(int)($item['menu_item_id']??0);$qty=max(1,min(30,(int)($item['quantity']??1)));
   $m=$pdo->prepare("SELECT id,price FROM menu_items WHERE id=? AND restaurant_id=? AND is_available=1");$m->execute([$mid,$restaurant]);$row=$m->fetch();if(!$row)throw new RuntimeException('Menu item unavailable');
   $total+=(float)$row['price']*$qty;$lines[]=[$mid,$qty,(float)$row['price']];
  }
  $pdo->prepare("INSERT INTO orders(customer_id,restaurant_id,total_amount,status) VALUES(?,?,?,'pending')")->execute([$user['id'],$restaurant,$total]);$oid=(int)$pdo->lastInsertId();
  $ins=$pdo->prepare("INSERT INTO order_items(order_id,restaurant_id,menu_item_id,quantity,unit_price) VALUES(?,?,?,?,?)");
  foreach($lines as $l)$ins->execute([$oid,$restaurant,$l[0],$l[1],$l[2]]);
  $pdo->commit();json_response(['success'=>true,'order_id'=>$oid,'total'=>$total],201);
 }catch(Throwable $e){$pdo->rollBack();json_response(['error'=>$e->getMessage()],422);}
}
json_response(['error'=>'Method or role not allowed'],405);
