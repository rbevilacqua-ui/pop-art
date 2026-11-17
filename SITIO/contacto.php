<?php
session_start();

$conexion = new mysqli("localhost", "root", "", "POP ART");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$opiniones = $conexion->query("SELECT * FROM opiniones ORDER BY id DESC");

$sesionIniciada = isset($_SESSION["usuario"]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contacto - Pop Art</title>

<style>
body {
  margin: 0;
  font-family: Arial, Helvetica, sans-serif;
  background:
    radial-gradient(circle at top left, rgba(255,0,92,0.16) 0%, transparent 22%),
    radial-gradient(circle at bottom right, rgba(0,153,255,0.16) 0%, transparent 22%),
    #ffe600;
}

.header {
  background: linear-gradient(90deg,#ff005c,#ff7a00,#ffe600,#7300ff,#0099ff);
  padding: 45px 20px;
  text-align: center;
  border-bottom: 6px solid black;
}
.header h1 {
  font-size: 70px;
  margin: 0;
  font-weight: 900;
  color: black;
  text-shadow: 6px 6px 0 #ff005c;
}
.header p {
  font-size: 20px;
  color: white;
  font-weight: 900;
  text-shadow: 3px 3px 0 black;
}

.menu {
  background: black;
  padding: 20px;
  display: flex;
  justify-content: center;
  gap: 15px;
  flex-wrap: wrap;
}
.menu a {
  text-decoration: none;
  color: white;
  background: #ff0066;
  padding: 13px 22px;
  font-weight: 900;
  border: 4px solid black;
  box-shadow: 5px 5px 0 #ffe600;
  font-size: 15px;
}
.menu a:nth-child(2) { background: #ff7a00; }
.menu a:nth-child(3) { background: #ffe600; color: black; }
.menu a:nth-child(4) { background: #6c00ff; }
.menu a:nth-child(5) { background: #0099ff; }
.menu a:nth-child(6) { background: #ff0066; }

.mini-login {
  background: white !important;
  color: black !important;
  font-size: 13px !important;
}

/* NUEVO BOTÓN CERRAR SESIÓN */
.menu a.cerrar-sesion {
  background: #ff005c;
  color: white;
  font-size: 13px;
}
.menu a.cerrar-sesion:hover {
  background: #6c00ff;
}

.contacto {
  padding: 45px 6%;
}
h2 {
  font-size: 34px;
  font-weight: 900;
  border-bottom: 5px solid black;
  padding-bottom: 10px;
  margin: 0 0 28px;
}

.login-aviso,
.form-opinion,
.preview-box {
  border: 5px solid #111;
  box-shadow: 9px 9px 0 #111;
  padding: 28px;
  margin: 40px auto;
  max-width: 850px;
  position: relative;
  box-sizing: border-box;
}

.login-aviso {
  background: white;
  text-align: center;
  max-width: 760px;
}
.login-aviso h2 {
  border: 0;
  font-size: 30px;
  margin-bottom: 18px;
}
.login-aviso p {
  font-size: 18px;
  font-weight: 800;
  line-height: 1.5;
}
.botones-login a {
  background: #ff005c;
  color: white;
  font-weight: 900;
  border: 4px solid #111;
  box-shadow: 5px 5px 0 #111;
  padding: 13px 24px;
  text-decoration: none;
  display: inline-block;
  margin: 10px;
  cursor: pointer;
}
.botones-login a:nth-child(2) {
  background: #0099ff;
}

.preview-box {
  background:
    radial-gradient(circle, rgba(255,255,255,0.18) 2px, transparent 3px),
    #0099ff;
  background-size: 20px 20px;
}
.preview-box h3,
.form-opinion h2 {
  color: white;
  text-shadow: 3px 3px 0 black;
}

#preview {
  height: 320px;
  background: white;
  border: 5px solid #111;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 28px;
  position: relative;
  overflow: hidden;
}
#preview::before {
  content: "POP!";
  position: absolute;
  top: 15px;
  left: 18px;
  background: #ff005c;
  color: white;
  border: 3px solid #111;
  box-shadow: 4px 4px 0 #111;
  padding: 6px 14px;
  font-weight: 900;
}
#preview::after {
  content: "WOW!";
  position: absolute;
  right: 18px;
  bottom: 18px;
  background: #ffe600;
  color: black;
  border: 3px solid #111;
  box-shadow: 4px 4px 0 #111;
  padding: 6px 14px;
  font-weight: 900;
  transform: rotate(-8deg);
}
#preview-texto {
  font-size: 58px;
  font-weight: 900;
  text-align: center;
  color: black;
  padding: 20px;
}
.preview-box input {
  width: 100%;
  padding: 15px;
  border: 4px solid #111;
  margin-bottom: 22px;
  font-size: 16px;
  box-sizing: border-box;
  font-weight: 700;
}

.paleta,
.efectos {
  margin-top: 20px;
}
.paleta h3,
.efectos h3 {
  font-size: 22px;
  margin: 0 0 12px;
  color: white;
  text-shadow: 2px 2px 0 #111;
}
.paleta button,
.efectos button {
  background: #ff005c;
  color: white;
  font-weight: 900;
  border: 3px solid #111;
  box-shadow: 5px 5px 0 #111;
  padding: 12px 20px;
  display: inline-block;
  margin: 8px;
  cursor: pointer;
}
.paleta button:nth-child(3) { background: #ff7a00; }
.paleta button:nth-child(4) { background: #ffe600; color: black; }
.paleta button:nth-child(5) { background: #6a00ff; }
.paleta button:nth-child(6) { background: #0099ff; }

.form-opinion {
  background:
    repeating-linear-gradient(
      45deg,
      rgba(255,255,255,0.10) 0px,
      rgba(255,255,255,0.10) 10px,
      transparent 10px,
      transparent 20px
    ),
    #ff7a00;
}
.form-opinion p {
  font-size: 17px;
  font-weight: 800;
  color: white;
  text-shadow: 2px 2px 0 black;
}
.form-opinion textarea {
  width: 100%;
  height: 120px;
  padding: 15px;
  border: 4px solid #111;
  resize: none;
  font-size: 16px;
  box-sizing: border-box;
  font-weight: 700;
}
.form-opinion button {
  background: #ff005c;
  color: white;
  font-weight: 900;
  border: 4px solid #111;
  box-shadow: 5px 5px 0 #111;
  padding: 13px 24px;
  margin-top: 15px;
  cursor: pointer;
}
.botones-login a:hover,
.form-opinion button:hover,
.paleta button:hover,
.efectos button:hover {
  transform: translateY(-3px);
}

.resenas-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 32px;
  margin-top: 35px;
}
.resena-card {
  background: white;
  border: 5px solid #111;
  box-shadow: 8px 8px 0 #111;
  padding: 24px;
  position: relative;
  box-sizing: border-box;
  min-height: 190px;
}
.resena-card::after {
  content: "";
  position: absolute;
  bottom: -22px;
  left: 40px;
  width: 30px;
  height: 30px;
  background: inherit;
  border-left: 5px solid #111;
  border-bottom: 5px solid #111;
  transform: rotate(-45deg);
}
.resena-card:nth-child(5n+1) { background:#ff0066; color:white; }
.resena-card:nth-child(5n+2) { background:#ff7a00; color:black; }
.resena-card:nth-child(5n+3) { background:#ffe600; color:black; }
.resena-card:nth-child(5n+4) { background:#6a00ff; color:white; }
.resena-card:nth-child(5n+5) { background:#0099ff; color:white; }

.resena-header {
  display: flex;
  align-items: center;
  gap: 15px;
  margin-bottom: 15px;
}
.avatar {
  width: 52px;
  height: 52px;
  background: #111;
  border: 3px solid white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: bold;
  font-size: 22px;
  flex-shrink: 0;
}
.resena-card h4 {
  margin: 0;
  font-size: 18px;
  font-weight: 900;
}
.resena-card span { font-size: 17px; }
.resena-card p {
  font-size: 16px;
  line-height: 1.45;
  font-weight: 800;
  margin: 0;
}

/* VENTANAS LOGIN Y REGISTRO */

.modal-login {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.45);
  backdrop-filter: blur(8px);
  justify-content: center;
  align-items: center;
  z-index: 9999;
}
.login-card {
  background: white;
  border: 6px solid #111;
  box-shadow: 12px 12px 0 #ff005c;
  padding: 35px;
  width: 370px;
  max-width: calc(100vw - 70px);
  text-align: center;
  position: relative;
}
.login-card h2 {
  border: none;
  margin-bottom: 20px;
}
.login-card input {
  width: 100%;
  padding: 13px;
  margin: 10px 0;
  border: 4px solid #111;
  box-sizing: border-box;
  font-weight: 800;
  font-size: 15px;
}
.login-card button {
  width: 100%;
  background: #ff005c;
  color: white;
  border: 4px solid #111;
  box-shadow: 5px 5px 0 #111;
  padding: 13px;
  font-weight: 900;
  cursor: pointer;
  margin-top: 10px;
  font-size: 15px;
}
.login-card p { font-weight: 800; }
.login-card a {
  color: #ff005c;
  font-weight: 900;
}
.cerrar-login {
  position: absolute;
  top: 10px;
  right: 15px;
  font-size: 24px;
  font-weight: 900;
  cursor: pointer;
}
.msg-error {
  color: red;
  font-weight: 900;
  margin-top: 10px;
}
.msg-ok {
  background: #ffe600;
  border: 3px solid #111;
  padding: 10px;
  font-weight: 900;
  margin-bottom: 10px;
}

/* FOOTER ORIGINAL */

.footer-retro {
  background:
    radial-gradient(circle, rgba(255,255,255,0.18) 2px, transparent 3px),
    linear-gradient(135deg,#111 0%,#111 45%,#ff005c 45%,#ff005c 55%,#111 55%);
  background-size: 18px 18px,100% 100%;
  color: white;
  padding: 50px 45px 25px;
  border-top: 10px solid black;
  box-shadow: 0 -10px 0 #ffe600;
}
.footer-top {
  display: grid;
  grid-template-columns: 1.5fr 1fr 1fr 1.2fr;
  gap: 35px;
  align-items: start;
}
.footer-brand h2 {
  font-size: 58px;
  margin: 10px 0;
  color: #ffe600;
  font-weight: 900;
  text-shadow:
    4px 4px 0 #ff005c,
    8px 8px 0 #0099ff,
    12px 12px 0 black;
}
.footer-brand p,
.footer-info p {
  font-weight: 800;
  line-height: 1.5;
}
.footer-sticker {
  display: inline-block;
  background: #ffe600;
  color: black;
  padding: 10px 18px;
  border: 4px solid white;
  box-shadow: 6px 6px 0 #0099ff;
  font-weight: 900;
  transform: rotate(-6deg);
}
.footer-info h3 {
  color: #ffe600;
  border-bottom: 4px solid #ff005c;
  display: inline-block;
}
.footer-info a {
  display: block;
  text-decoration: none;
  color: white;
  font-weight: 900;
  margin: 10px 0;
}
.footer-info a:hover { color: #ffe600; }
.footer-frase-retro {
  background: #0099ff;
  border: 5px solid white;
  box-shadow: 10px 10px 0 #ffe600;
  padding: 22px;
  transform: rotate(2deg);
}
.footer-frase-retro p {
  margin: 0;
  font-size: 20px;
  font-weight: 900;
}
.footer-bottom {
  margin-top: 40px;
  border-top: 4px dashed #ffe600;
  padding-top: 18px;
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;
  font-weight: 900;
}
</style>
</head>

<body>

<header class="header">
  <h1>POP ART</h1>
  <p>La Revolución del Color y la Cultura de Masas</p>
</header>

<nav class="menu">
  <a href="index.html">INICIO</a>
  <a href="definicion.html">DEFINICIÓN</a>
  <a href="artistas.html">ARTISTAS</a>
  <a href="tecnicas.html">TÉCNICAS</a>
  <a href="galeria.html">GALERÍA</a>
  <a href="contacto.php">CREÁ</a>

  <?php if ($sesionIniciada) { ?>
    <a href="logout.php" class="cerrar-sesion">
      CERRAR SESIÓN
    </a>
  <?php } else { ?>
    <a href="#" onclick="abrirLogin(); return false;" class="mini-login">
      Iniciar sesión / Registrarse
    </a>
  <?php } ?>
</nav>

<section class="contacto">

<?php if (!$sesionIniciada) { ?>

  <div class="login-aviso">

    <h2>Para acceder a Creá tenés que iniciar sesión o registrarte</h2>

    <p>
      Solo los usuarios registrados pueden crear una obra Pop Art
      y publicar reseñas dentro del sitio.
    </p>

    <p>
      🎨 Creá tu afiche personalizado<br>
      ⭐ Compartí tu reseña como usuario registrado
    </p>

    <div class="botones-login">
      <a href="#" onclick="abrirLogin(); return false;">
        Iniciar sesión
      </a>
      <a href="#" onclick="abrirRegistro(); return false;">
        Registrarse
      </a>
    </div>

  </div>

<?php } else { ?>

  <h2>ESTUDIO POP ART</h2>

  <div class="preview-box">

    <div id="preview">
      <h3 id="preview-texto">POP ART</h3>
    </div>

    <input
      type="text"
      id="mensaje"
      placeholder="Escribí una frase para tu afiche..."
      oninput="actualizarTexto()"
    >

    <div class="paleta">
      <h3>PALETA DE COLOR</h3>
      <button onclick="cambiarColor('#ff005c')">Magenta</button>
      <button onclick="cambiarColor('#ff7a00')">Naranja</button>
      <button onclick="cambiarColor('#ffe600')">Amarillo</button>
      <button onclick="cambiarColor('#6a00ff')">Púrpura</button>
      <button onclick="cambiarColor('#0099ff')">Azul</button>
    </div>

    <div class="efectos">
      <h3>EFECTOS VISUALES</h3>
      <button onclick="efecto('warhol')">Repetición Warhol</button>
      <button onclick="efecto('benday')">Puntos Ben Day</button>
      <button onclick="efecto('contorno')">Contorno cómic</button>
      <button onclick="limpiarObra()">Limpiar</button>
      <button onclick="descargarObra()">Descargar obra</button>
    </div>

  </div>

  <div class="form-opinion">

    <h2>DEJÁ TU RESEÑA</h2>

    <p>
      Hola <?php echo htmlspecialchars($_SESSION["usuario"], ENT_QUOTES, "UTF-8"); ?> 👋
    </p>

    <form action="enviar.php" method="POST">
      <textarea
        name="opinion"
        required
        placeholder="Escribí tu opinión sobre el sitio o el Pop Art..."
      ></textarea>
      <button type="submit">Publicar reseña</button>
    </form>

  </div>

<?php } ?>

<h2>RESEÑAS DE USUARIOS</h2>

<div class="resenas-grid">

<?php
if ($opiniones && $opiniones->num_rows > 0) {
    while ($fila = $opiniones->fetch_assoc()) {
        echo "<div class='resena-card'>";
        echo "<div class='resena-header'>";
        echo "<div class='avatar'>" .
             strtoupper(substr(htmlspecialchars($fila["usuario"], ENT_QUOTES, "UTF-8"), 0, 1)) .
             "</div>";
        echo "<div>";
        echo "<h4>" . htmlspecialchars($fila["usuario"], ENT_QUOTES, "UTF-8") . "</h4>";
        echo "<span>★★★★★</span>";
        echo "</div>";
        echo "</div>";
        echo "<p>" . htmlspecialchars($fila["opinion"], ENT_QUOTES, "UTF-8") . "</p>";
        echo "</div>";
    }
} else {
    echo "<p>Todavía no hay reseñas cargadas.</p>";
}
?>

</div>
</section>

<!-- VENTANA INICIAR SESIÓN -->

<div id="modalLogin" class="modal-login">
  <div class="login-card">
    <span class="cerrar-login" onclick="cerrarLogin()">✕</span>

    <h2>Iniciar sesión</h2>

    <?php
    if (
      isset($_GET["modal"]) &&
      $_GET["modal"] == "login" &&
      isset($_GET["error"])
    ):
    ?>
      <p class="msg-error">Usuario o contraseña incorrectos</p>
    <?php endif; ?>

    <form action="login.php" method="POST">
      <input
        type="text"
        name="usuario"
        placeholder="Usuario"
        required
      >
      <input
        type="password"
        name="password"
        placeholder="Contraseña"
        required
      >
      <button type="submit">Ingresar</button>
    </form>

    <p>
      ¿No tenés cuenta?
      <a href="#" onclick="cerrarLogin(); abrirRegistro(); return false;">
        Registrate
      </a>
    </p>
  </div>
</div>

<!-- VENTANA REGISTRO -->

<div id="modalRegistro" class="modal-login">
  <div class="login-card">
    <span class="cerrar-login" onclick="cerrarRegistro()">✕</span>

    <h2>Registrarse</h2>

    <?php
    if (
      isset($_GET["modal"]) &&
      $_GET["modal"] == "registro" &&
      isset($_GET["error"])
    ):
    ?>
      <p class="msg-error">Error al registrarse. Intentá de nuevo.</p>
    <?php endif; ?>

    <form action="registrarse.php" method="POST">
      <input type="text" name="nombre" placeholder="Nombre" required>
      <input type="text" name="apellido" placeholder="Apellido" required>
      <input type="email" name="mail" placeholder="Mail" required>
      <input type="text" name="usuario" placeholder="Usuario" required>
      <input type="password" name="password" placeholder="Contraseña" required>
      <button type="submit">Registrarme</button>
    </form>

    <p>
      ¿Ya tenés cuenta?
      <a href="#" onclick="cerrarRegistro(); abrirLogin(); return false;">
        Iniciar sesión
      </a>
    </p>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

<script>
// MODALES
function abrirLogin() {
  document.getElementById("modalLogin").style.display = "flex";
}

function cerrarLogin() {
  document.getElementById("modalLogin").style.display = "none";
}

function abrirRegistro() {
  document.getElementById("modalRegistro").style.display = "flex";
}

function cerrarRegistro() {
  document.getElementById("modalRegistro").style.display = "none";
}

// ABRIR MODAL SEGÚN URL
(function() {
  const params = new URLSearchParams(window.location.search);
  const modal = params.get("modal");

  if (modal === "login") abrirLogin();
  if (modal === "registro") abrirRegistro();
})();

// ESTUDIO POP ART
function actualizarTexto() {
  let texto = document.getElementById("mensaje").value;
  document.getElementById("preview-texto").innerText = texto || "POP ART";
}

function cambiarColor(color) {
  document.getElementById("preview").style.background = color;
}

function efecto(tipo) {
  let texto = document.getElementById("preview-texto");
  let preview = document.getElementById("preview");

  texto.style.letterSpacing = "0px";
  texto.style.textShadow = "none";
  texto.style.webkitTextStroke = "0px";
  preview.style.backgroundImage = "none";

  if (tipo === "warhol") {
    texto.style.letterSpacing = "8px";
    texto.style.textShadow = "6px 6px #ff005c, -6px -6px #0099ff";

  } else if (tipo === "benday") {
    preview.style.backgroundImage =
      "radial-gradient(circle, rgba(0,0,0,0.25) 2px, transparent 3px)";
    preview.style.backgroundSize = "18px 18px";

  } else if (tipo === "contorno") {
    texto.style.webkitTextStroke = "2px black";
    texto.style.color = "#ffe600";
    texto.style.textShadow = "5px 5px #ff005c";
  }
}

function limpiarObra() {
  document.getElementById("mensaje").value = "";
  document.getElementById("preview-texto").innerText = "POP ART";
  document.getElementById("preview").style.background = "white";
  document.getElementById("preview").style.backgroundImage = "none";
  document.getElementById("preview-texto").style.letterSpacing = "0px";
  document.getElementById("preview-texto").style.textShadow = "none";
  document.getElementById("preview-texto").style.webkitTextStroke = "0px";
  document.getElementById("preview-texto").style.color = "black";
}

function descargarObra() {
  const obra = document.getElementById("preview");

  html2canvas(obra).then(function(canvas) {
    const link = document.createElement("a");
    link.download = "mi-obra-pop-art.png";
    link.href = canvas.toDataURL("image/png");
    link.click();
  });
}
</script>

<footer class="footer-retro">
  <div class="footer-top">

    <div class="footer-brand">
      <span class="footer-sticker">WOW!</span>
      <h2>POP ART</h2>
      <p>
        Explorá el universo Pop Art, creá tu propia obra,
        compartí reseñas y descubrí el impacto de la cultura visual.
      </p>
    </div>

    <div class="footer-info">
      <h3>EXPLORÁ</h3>
      <a href="index.html">Inicio</a>
      <a href="definicion.html">Definición</a>
      <a href="artistas.html">Artistas</a>
      <a href="tecnicas.html">Técnicas</a>
      <a href="galeria.html">Galería</a>
      <a href="contacto.php">Creá</a>
    </div>

    <div class="footer-info">
      <h3>INTERACTUÁ</h3>
      <p>🎨 Diseñá tu obra</p>
      <p>⭐ Publicá reseñas</p>
      <p>💥 Explorá artistas</p>
      <p>🖼️ Inspirate visualmente</p>
    </div>

    <div class="footer-frase-retro">
      <p>"El arte puede estar en una lata, una pantalla o un cómic."</p>
    </div>

  </div>

  <div class="footer-bottom">
    <p>🎨 Pop Art Explorer — Sitio interactivo de arte y cultura visual</p>
    <p>© 2024 | Proyecto web</p>
  </div>
</footer>

</body>
</html>