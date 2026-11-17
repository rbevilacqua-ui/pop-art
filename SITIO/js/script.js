// TEXTO EN VIVO
function actualizarTexto() {
  const texto = document.getElementById("mensaje").value;
  document.getElementById("preview-texto").innerText = texto || "POP ART";
}

// CAMBIO DE COLOR
function cambiarColor(color) {
  const preview = document.getElementById("preview");
  preview.style.background = color;
  preview.style.backgroundImage = ""; // limpia efectos anteriores
}

// TEMAS POP ART (LO IMPORTANTE)
function cambiarTema() {
  const tema = document.getElementById("tema").value;
  const preview = document.getElementById("preview");
  const texto = document.getElementById("preview-texto");

  // RESET
  preview.style = "";
  texto.style = "";
  texto.innerText = "POP ART";

  if (tema === "Retrato Pop") {
    preview.style.background = "#ff005c";
    texto.innerText = "RETRATO POP";
    texto.style.color = "#ffe600";
    texto.style.fontSize = "60px";
    texto.style.textShadow = "5px 5px 0 #111";
  }

  if (tema === "Objeto Icónico") {
    preview.style.background = "#ffe600";
    preview.style.borderRadius = "30px";
    texto.innerText = "LATA POP";
    texto.style.color = "#ff005c";
    texto.style.fontSize = "55px";
    texto.style.textShadow = "4px 4px 0 #111";
  }

  if (tema === "Abstracto Geométrico") {
    preview.style.background = "linear-gradient(45deg, #ff005c 25%, #ffe600 25%, #ffe600 50%, #0099ff 50%, #0099ff 75%, #6a00ff 75%)";
    texto.innerText = "FORMAS";
    texto.style.color = "white";
    texto.style.letterSpacing = "10px";
    texto.style.fontSize = "50px";
    preview.style.transform = "rotate(3deg)";
  }

  if (tema === "Urbano") {
    preview.style.background = "#111";
    preview.style.backgroundImage = "radial-gradient(#ffe600 2px, transparent 2px)";
    preview.style.backgroundSize = "18px 18px";
    texto.innerText = "STREET POP";
    texto.style.color = "#ff005c";
    texto.style.textShadow = "4px 4px 0 #0099ff";
    texto.style.fontSize = "55px";
  }
}

// EFECTOS
function efecto(tipo) {
  const texto = document.getElementById("preview-texto");
  const preview = document.getElementById("preview");

  if (tipo === "warhol") {
    texto.innerText = texto.innerText + " " + texto.innerText;
    texto.style.letterSpacing = "8px";
  }

  if (tipo === "benday") {
    preview.style.backgroundImage = "radial-gradient(#111 2px, transparent 2px)";
    preview.style.backgroundSize = "12px 12px";
  }

  if (tipo === "contorno") {
    texto.style.webkitTextStroke = "2px black";
  }
}

// LIMPIAR TODO
function limpiar() {
  document.getElementById("mensaje").value = "";

  const preview = document.getElementById("preview");
  const texto = document.getElementById("preview-texto");

  preview.removeAttribute("style");
  texto.removeAttribute("style");

  texto.innerText = "POP ART";
}

// DESCARGA (simple por ahora)
function descargar() {
  alert("🎨 Tu obra Pop Art fue creada!");
}