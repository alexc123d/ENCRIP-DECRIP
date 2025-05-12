<?php
// caesar_cipher.php
// Programa en PHP para cifrado y descifrado César con Bootstrap, MySQL y animación de mapeo

/*
SQL para crear la base de datos y la tabla:

CREATE DATABASE cifrado_db;
USE cifrado_db;

CREATE TABLE mensajes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mensaje VARCHAR(35) NOT NULL,
    estado TINYINT NOT NULL,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
*/

// Conexión a MySQL (phpMyAdmin en localhost)
$mysqli = new mysqli('localhost', 'root', '2805', 'cifrado_db');
if ($mysqli->connect_error) {
    die('Error de conexión (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);
}

function caesar_encrypt($text, $shift) {
    $result = '';
    $shift %= 26;
    for ($i = 0; $i < strlen($text); $i++) {
        $char = $text[$i];
        if (ctype_upper($char)) {
            $result .= chr((ord($char) - 65 + $shift + 26) % 26 + 65);
        } elseif (ctype_lower($char)) {
            $result .= chr((ord($char) - 97 + $shift + 26) % 26 + 97);
        } else {
            $result .= $char;
        }
    }
    return $result;
}

function caesar_decrypt($text, $shift) {
    return caesar_encrypt($text, -$shift);
}

$decryptedContent = '';
$encryptedText   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shift = isset($_POST['shift']) ? (int)$_POST['shift'] : 3;
    if (!empty($_FILES['txtfile']['tmp_name']) && is_uploaded_file($_FILES['txtfile']['tmp_name'])) {
        $fileContent = file_get_contents($_FILES['txtfile']['tmp_name']);
        $decryptedContent = caesar_decrypt($fileContent, $shift);
        $insertMsg = substr($decryptedContent, 0, 35);
        $estado = 2;
        $stmt = $mysqli->prepare("INSERT INTO mensajes (mensaje, estado) VALUES (?, ?)");
        $stmt->bind_param('si', $insertMsg, $estado);
        $stmt->execute(); $stmt->close();
        header('Content-Description: File Transfer');
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="decrypted.txt"');
        echo $decryptedContent; exit;
    }
    if (!empty($_POST['plaintext'])) {
        $plaintext = substr($_POST['plaintext'], 0, 35);
        $encryptedText = caesar_encrypt($plaintext, $shift);
        $estado = 1;
        $stmt = $mysqli->prepare("INSERT INTO mensajes (mensaje, estado) VALUES (?, ?)");
        $stmt->bind_param('si', $encryptedText, $estado);
        $stmt->execute(); $stmt->close();
    }
}
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Cifrado César con Animación Detallada</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  <style>
    body { background: #001f3f; color: #fff; }
    @keyframes bg-shift { 0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%} }
    .bg-light { background: linear-gradient(270deg,#001f3f,#004080,#001f3f); background-size:600% 600%; animation:bg-shift 10s ease infinite; }
    #cipherOverlay { position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.85); display:none; z-index:1050; }
    #cipherContent { background:#fff; color:#000; padding:20px; border-radius:8px; max-width:90%; max-height:80%; overflow:auto; margin:auto; }
    .mapping-line { font-family:monospace; margin:4px 0; }
  </style>
</head>
<body class="bg-light">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark animate__animated animate__fadeInDown">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <i class="bi bi-shield-lock-fill animate__animated animate__pulse animate__infinite"></i> César PHP
      </a>
    </div>
  </nav>
  <div class="container py-5">
    <h1 class="mb-5 text-center animate__animated animate__zoomInUp">Cifrado y Descifrado César</h1>
    <div class="row gy-4">
      <div class="col-md-6">
        <div class="card bg-dark text-white shadow animate__fadeInLeft">
          <div class="card-body">
            <h5 class="card-title"><i class="bi bi-file-earmark-arrow-up-fill"></i> Cifrar Texto</h5>
            <form id="cipherForm" method="post" onsubmit="return animateCipher();">
              <div class="mb-3"><label class="form-label">Desplazamiento</label><input type="number" class="form-control" name="shift" value="3" required></div>
              <div class="mb-3"><label class="form-label">Texto a cifrar (máx.35 caracteres)</label><textarea id="plain" name="plaintext" class="form-control" rows="2" maxlength="35" required></textarea></div>
              <button type="submit" class="btn btn-success w-100 animate__heartBeat">Cifrar Texto</button>
            </form>
          </div>
        </div>
        <?php if (!empty($encryptedText)): ?>
        <div class="card bg-success text-dark mt-4 shadow animate__fadeInUp">
          <div class="card-body">
            <h5 class="card-title"><i class="bi bi-check-circle-fill"></i> Resultado</h5>
            <textarea class="form-control" rows="2" readonly><?php echo htmlspecialchars($encryptedText); ?></textarea>
          </div>
        </div>
        <?php endif; ?>
      </div>
      <div class="col-md-6">
        <div class="card bg-dark text-white shadow animate__fadeInRight">
          <div class="card-body">
            <h5 class="card-title"><i class="bi bi-file-earmark-lock2"></i> Descifrar Archivo TXT</h5>
            <form method="post" enctype="multipart/form-data">
              <div class="mb-3"><label class="form-label">Desplazamiento</label><input type="number" class="form-control" name="shift" value="3" required></div>
              <div class="mb-3"><label class="form-label">Archivo cifrado</label><input type="file" class="form-control" name="txtfile" accept=".txt" required></div>
              <button type="submit" class="btn btn-warning w-100 animate__rubberBand">Descifrar y Descargar</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Overlay -->
  <div id="cipherOverlay" class="d-none align-items-center justify-content-center">
    <div id="cipherContent"></div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function animateCipher() {
      const text = document.getElementById('plain').value;
      const shift = parseInt(document.querySelector('#cipherForm input[name="shift"]').value);
      const overlay = document.getElementById('cipherOverlay');
      const content = document.getElementById('cipherContent');
      content.innerHTML = '<h5>Animando cifrado...</h5>';
      overlay.classList.remove('d-none');
      overlay.classList.add('d-flex');
      let i = 0;
      function step() {
        if (i < text.length) {
          const ch = text[i];
          let enc = ch;
          if (/[A-Za-z]/.test(ch)) {
            const base = ch === ch.toUpperCase() ? 65 : 97;
            enc = String.fromCharCode((ch.charCodeAt(0) - base + shift + 26) % 26 + base);
          }
          const line = document.createElement('div');
          line.className = 'mapping-line';
          line.textContent = `"${ch}" → "${enc}"`;
          content.appendChild(line);
          i++;
          setTimeout(step, 300);
        } else {
          setTimeout(() => { overlay.classList.remove('d-flex'); overlay.classList.add('d-none'); document.getElementById('cipherForm').submit(); }, 500);
        }
      }
      step();
      return false;
    }
  </script>
</body>
</html>
