<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

$input = json_decode(file_get_contents("php://input"), true);

$produto = $input["produto"] ?? "Não informado";
$valor = $input["valor"] ?? "0.00";
$tiktok = $input["tiktok"] ?? "Não informado";
$status = $input["status"] ?? "Indefinido";

$webhook = "https://discord.com/api/webhooks/1392216858912096256/60_MYqbGTRwJMO5H5XMp-3hOijZdq4e3kh8k17KeWOHhc9rEk-_8NLfvznf1Tg8Azs7X";

// Criar mensagem formatada para o Discord
$mensagem = "**📦 Nova Compra Finalizada!**\n\n";
$mensagem .= "**Produto(s):** $produto\n";
$mensagem .= "**Valor:** R$ $valor\n";
$mensagem .= "**TikTok:** $tiktok\n";
$mensagem .= "**Status:** $status\n";
$mensagem .= "**Data:** " . date("d/m/Y H:i:s");

$data = json_encode([
    "content" => $mensagem,
    "embeds" => [
        [
            "title" => "🛍️ PH STORE - Nova Compra",
            "color" => 2664261, // Verde
            "fields" => [
                [
                    "name" => "Produto(s)",
                    "value" => $produto,
                    "inline" => false
                ],
                [
                    "name" => "Valor Total",
                    "value" => "R$ $valor",
                    "inline" => true
                ],
                [
                    "name" => "TikTok do Cliente",
                    "value" => $tiktok,
                    "inline" => true
                ],
                [
                    "name" => "Status",
                    "value" => $status,
                    "inline" => true
                ]
            ],
            "footer" => [
                "text" => "PH STORE - Brainrots Premium"
            ],
            "timestamp" => date("c")
        ]
    ]
]);

$ch = curl_init($webhook);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT, 'PH-STORE-Bot/1.0');

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    echo json_encode([
        "status" => "erro",
        "erro" => curl_error($ch)
    ]);
} else if ($httpCode >= 200 && $httpCode < 300) {
    echo json_encode([
        "status" => "ok",
        "mensagem" => "Compra enviada com sucesso!"
    ]);
} else {
    echo json_encode([
        "status" => "erro",
        "erro" => "Erro HTTP: " . $httpCode,
        "response" => $response
    ]);
}

curl_close($ch);
?>
