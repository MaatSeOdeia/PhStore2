<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$input = json_decode(file_get_contents("php://input"), true);

$produto = $input["produto"] ?? "Não informado";
$valor = $input["valor"] ?? "0.00";
$status = $input["status"] ?? "Indefinido";
$tiktok = $input["tiktok"] ?? "TikTok não informado";

$webhook = "https://discord.com/api/webhooks/1392216858912096256/60_MYqbGTRwJMO5H5XMp-3hOijZdq4e3kh8k17KeWOHhc9rEk-_8NLfvznf1Tg8Azs7X";
$mensagem = "**📦 Nova Compra Finalizada!**\n\nProduto(s): $produto\nValor: R$ $valor\nStatus: $status\nTikTok: $tiktok";

$data = json_encode(["content" => $mensagem]);

$ch = curl_init($webhook);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo json_encode(["erro" => curl_error($ch)]);
} else {
    echo json_encode(["status" => "ok"]);
}
curl_close($ch);
?>
