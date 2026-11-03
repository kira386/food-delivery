<?php
function json_input(): array {
    $data = json_decode(file_get_contents('php://input'), true);
    return is_array($data) ? $data : [];
}
function json_response(array $data, int $status=200): never {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}
function bearer_user(): array {
    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    if (!preg_match('/Bearer\s+(.+)/i', $header, $m)) json_response(['error'=>'Unauthorized'], 401);
    $tokenHash = hash('sha256', trim($m[1]));
    $q = db()->prepare("SELECT u.id,u.name,u.role FROM api_tokens t JOIN users u ON u.id=t.user_id WHERE t.token_hash=? AND t.expires_at>NOW() AND u.is_active=1");
    $q->execute([$tokenHash]); $user = $q->fetch();
    if (!$user) json_response(['error'=>'Unauthorized'], 401);
    return $user;
}
