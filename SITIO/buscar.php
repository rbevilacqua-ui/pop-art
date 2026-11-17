<?php
// CONEXIÓN CON LA BASE DE DATOS
$conexion = new mysqli("localhost", "root", "", "POP ART");

if ($conexion->connect_error) {
    die("Error de conexión con la base de datos.");
}

$conexion->set_charset("utf8mb4");

function escapar($valor) {
    return htmlspecialchars(
        (string)$valor,
        ENT_QUOTES,
        "UTF-8"
    );
}

// DATOS DE BÚSQUEDA
$texto = trim($_GET["q"] ?? "");
$tipo = $_GET["tipo"] ?? "todos";

if (!in_array($tipo, ["todos", "artista", "obra", "tecnica"], true)) {
    $tipo = "todos";
}

$resultados = [];

// CONSULTA MYSQL
if ($texto !== "") {

    $sql = "SELECT * FROM buscador
            WHERE (
                titulo LIKE ?
                OR tipo LIKE ?
                OR descripcion LIKE ?
                OR palabras_clave LIKE ?
            )";

    if ($tipo !== "todos") {
        if ($tipo === "tecnica") {
            $sql .= " AND (
                tipo LIKE '%tecnica%'
                OR tipo LIKE '%técnica%'
            )";
        } else {
            $sql .= " AND tipo LIKE ?";
        }
    }

    $sql .= " ORDER BY titulo ASC";

    $consulta = $conexion->prepare($sql);
    $busqueda = "%" . $texto . "%";

    if ($tipo !== "todos" && $tipo !== "tecnica") {
        $filtro = "%" . $tipo . "%";

        $consulta->bind_param(
            "sssss",
            $busqueda,
            $busqueda,
            $busqueda,
            $busqueda,
            $filtro
        );
    } else {
        $consulta->bind_param(
            "ssss",
            $busqueda,
            $busqueda,
            $busqueda,
            $busqueda
        );
    }

    $consulta->execute();
    $resultado = $consulta->get_result();

    while ($fila = $resultado->fetch_assoc()) {
        $resultados[] = $fila;
    }

    $consulta->close();
}

// IMÁGENES LOCALES
$imagenes = [
    "andy warhol" => "img/andy.jpg",
    "roy lichtenstein" => "img/roy.jpg",
    "david hockney" => "img/david.jpg",
    "claes oldenburg" => "img/claes.jpg",
    "james rosenquist" => "img/james.jpg",
    "peter blake" => "img/peter.jpg",
    "robert indiana" => "img/robert.jpg",
    "ed ruscha" => "img/ed.jpg",

    "campbell's soup cans" => "img/obra-andy.jpg",
    "whaam!" => "img/obra-roy.jpg",
    "a bigger splash" => "img/obra-david.jpg",
    "floor burger" => "img/obra-claes.jpg",
    "f-111" => "img/obra-james.jpg",
    "on the balcony" => "img/obra-peter.jpg",
    "love" => "img/obra-robert.jpg",
    "standard station" => "img/obra-ed.jpg"
];

function obtenerImagen($titulo) {
    global $imagenes;

    $clave = mb_strtolower(trim($titulo), "UTF-8");

    return $imagenes[$clave] ?? "";
}

// ENLACES INTERNOS
function obtenerLink($link, $tipo) {

    $link = trim((string)$link);

    // Solo permite rutas internas sencillas del sitio
    if (
        $link !== "" &&
        preg_match(
            '~^[a-zA-Z0-9_-]+\.(html|php)(\?[a-zA-Z0-9_=&%.-]*)?(#[a-zA-Z0-9_-]+)?$~',
            $link
        )
    ) {
        return $link;
    }

    $categoria = mb_strtolower($tipo, "UTF-8");

    if (strpos($categoria, "artista") !== false) {
        return "artistas.html";
    }

    if (
        strpos($categoria, "tecnica") !== false ||
        strpos($categoria, "técnica") !== false
    ) {
        return "tecnicas.html";
    }

    return "galeria.html";
}

function claseCategoria($tipo) {
    $categoria = mb_strtolower($tipo, "UTF-8");

    if (strpos($categoria, "artista") !== false) {
        return "artista";
    }

    if (strpos($categoria, "obra") !== false) {
        return "obra";
    }

    return "tecnica";
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Explorá - Buscador Pop Art</title>

<style>

/* ESTILOS GENERALES */

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, Helvetica, sans-serif;
    color: #111;

    background-color: #fff0aa;

    background-image:
        radial-gradient(
            circle,
            rgba(255, 0, 92, 0.10) 3px,
            transparent 4px
        ),
        radial-gradient(
            circle,
            rgba(108, 0, 255, 0.07) 2px,
            transparent 3px
        );

    background-size: 70px 70px, 105px 105px;
    background-position: 0 0, 32px 25px;
}

/* ENCABEZADO */

.header {
    background: linear-gradient(
        90deg,
        #ff005c,
        #ff7a00,
        #ffe600,
        #7300ff,
        #0099ff
    );

    padding: 38px 20px;
    text-align: center;
    border-bottom: 6px solid black;
}

.header h1 {
    font-size: 68px;
    font-weight: 900;
    margin: 0;
    color: black;
    text-shadow: 5px 5px 0 #ff005c;
}

.header p {
    font-size: 19px;
    font-weight: 900;
    color: white;
    text-shadow: 3px 3px 0 black;
}

/* MENÚ */

.menu {
    background: #111;
    padding: 20px;
    display: flex;
    justify-content: center;
    gap: 13px;
    flex-wrap: wrap;
}

.menu a {
    text-decoration: none;
    color: white;
    background: #ff005c;
    padding: 12px 19px;
    font-weight: 900;
    border: 3px solid black;
    box-shadow: 5px 5px 0 #ffe600;
    font-size: 14px;
    transition: 0.2s;
}

.menu a:nth-child(2) {
    background: #ff7a00;
}

.menu a:nth-child(3) {
    background: #ffe600;
    color: black;
}

.menu a:nth-child(4) {
    background: #6c00ff;
}

.menu a:nth-child(5) {
    background: #0099ff;
}

.menu a:nth-child(7) {
    background: #6c00ff;
}

.menu a:hover {
    transform: translateY(-3px);
}

/* PRESENTACIÓN EDITORIAL */

.intro {
    background: #111;
    color: white;
    text-align: center;
    padding: 50px 20px 55px;
    border-top: 5px solid #ffe600;
}

.intro .etiqueta-superior {
    display: inline-block;
    color: #ffe600;
    font-size: 13px;
    letter-spacing: 3px;
    font-weight: 900;
    margin-bottom: 15px;
}

.intro h2 {
    font-size: clamp(38px, 6vw, 72px);
    font-weight: 900;
    line-height: 1;
    margin: 0 0 20px;
    letter-spacing: -2px;
}

.intro h2 span {
    color: #ff005c;
}

.intro p {
    font-size: 17px;
    font-weight: 700;
    color: #f0f0f0;
    margin: 0;
}

/* BUSCADOR */

.buscador-container {
    max-width: 970px;
    width: calc(100% - 40px);
    margin: -20px auto 50px;
    position: relative;
    z-index: 2;

    background: white;
    border: 4px solid #111;
    box-shadow: 9px 9px 0 #ff005c;
    padding: 23px;
}

.buscador-form {
    display: flex;
    gap: 12px;
    align-items: stretch;
}

.buscador-form input {
    flex: 1;
    min-width: 0;
    border: 2px solid #111;
    padding: 16px;
    font-size: 17px;
    font-weight: 700;
    font-family: inherit;
    background: #fff;
}

.buscador-form select {
    border: 2px solid #111;
    background: #ffe600;
    font-family: inherit;
    padding: 12px;
    font-size: 15px;
    font-weight: 900;
    cursor: pointer;
}

.buscador-form button {
    background: #ff005c;
    color: white;
    border: 2px solid #111;
    padding: 15px 25px;
    font-size: 15px;
    font-family: inherit;
    font-weight: 900;
    cursor: pointer;
    transition: 0.2s;
}

.buscador-form button:hover {
    background: #6c00ff;
}

/* CONTENEDOR RESULTADOS */

.resultados {
    max-width: 970px;
    margin: 0 auto 95px;
    padding: 0 20px;
    min-height: 250px;
}

/* CONTADOR */

.contador {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    flex-wrap: wrap;

    border-bottom: 3px solid #111;
    padding: 0 0 15px;
    margin-bottom: 30px;
}

.contador h3 {
    margin: 0;
    font-size: 20px;
    font-weight: 900;
}

.contador span {
    font-size: 13px;
    font-weight: 900;
    color: #555;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* TARJETAS HORIZONTALES */

.lista-resultados {
    display: flex;
    flex-direction: column;
    gap: 26px;
}

.card {
    display: grid;
    grid-template-columns: 230px minmax(0, 1fr);

    background: white;
    border: 4px solid #111;
    box-shadow: 8px 8px 0 #ff005c;

    overflow: hidden;
    transition: 0.2s;
}

.card:nth-child(3n+2) {
    box-shadow: 8px 8px 0 #0099ff;
}

.card:nth-child(3n+3) {
    box-shadow: 8px 8px 0 #6c00ff;
}

.card:hover {
    transform: translateY(-3px);
}

/* IMÁGENES */

.card-imagen {
    height: 240px;
    background: #f5f5f5;
    border-right: 4px solid #111;
    overflow: hidden;
}

.card-imagen img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
}

.card-sin-imagen {
    height: 240px;
    background: #ffe600;
    background-image:
        radial-gradient(
            circle,
            rgba(255,0,92,0.20) 3px,
            transparent 4px
        );
    background-size: 24px 24px;

    border-right: 4px solid #111;

    display: flex;
    align-items: center;
    justify-content: center;
}

.card-sin-imagen span {
    background: #ff005c;
    color: white;
    padding: 12px 18px;
    border: 3px solid #111;
    box-shadow: 5px 5px 0 #111;
    font-size: 26px;
    font-weight: 900;
    transform: rotate(-6deg);
}

/* TEXTO */

.card-contenido {
    padding: 25px 30px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: center;
}

.categoria {
    font-size: 12px;
    font-weight: 900;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    margin-bottom: 12px;
    color: #ff005c;
}

.categoria.obra {
    color: #0099ff;
}

.categoria.tecnica {
    color: #6c00ff;
}

.card h2 {
    font-size: 30px;
    font-weight: 900;
    margin: 0 0 12px;
    line-height: 1.2;
    color: black;
}

.card p {
    font-size: 16px;
    font-weight: 700;
    line-height: 1.6;
    color: #444;
    margin: 0 0 22px;
}

/* BOTÓN EXPLORAR */

.card a {
    display: inline-block;
    background: #ff005c;
    color: white;
    border: 3px solid black;
    box-shadow: 4px 4px 0 #111;
    padding: 11px 21px;
    font-size: 13px;
    font-weight: 900;
    text-decoration: none;
    transition: 0.2s;
}

.card a:hover {
    background: #0099ff;
    transform: translateY(-2px);
}

/* SIN RESULTADOS */

.sin-resultados {
    background: white;
    border: 4px solid #111;
    box-shadow: 8px 8px 0 #ff005c;
    padding: 50px 30px;
    text-align: center;
}

.sin-resultados h2 {
    font-size: 30px;
    font-weight: 900;
    margin: 0 0 15px;
}

.sin-resultados p {
    font-size: 17px;
    font-weight: 700;
    line-height: 1.6;
}

/* PIE DE PÁGINA */

.footer {
    background: #111;
    color: white;
    padding: 45px 20px;
    border-top: 8px solid #ff005c;
    text-align: center;
}

.footer h2 {
    color: #ffe600;
    font-size: 45px;
    font-weight: 900;
    margin: 0 0 12px;
    text-shadow: 4px 4px 0 #ff005c;
}

.footer p {
    font-weight: 700;
    font-size: 14px;
}

/* RESPONSIVE */

@media (max-width: 700px) {

    .header h1 {
        font-size: 48px;
    }

    .intro {
        padding: 45px 20px 65px;
    }

    .buscador-form {
        flex-direction: column;
    }

    .buscador-form input,
    .buscador-form select,
    .buscador-form button {
        width: 100%;
    }

    .card {
        grid-template-columns: 1fr;
    }

    .card-imagen,
    .card-sin-imagen {
        height: 260px;
        border-right: 0;
        border-bottom: 4px solid #111;
    }

    .card-contenido {
        padding: 25px;
    }

    .card h2 {
        font-size: 27px;
    }

    .contador h3 {
        font-size: 17px;
    }
}

</style>
</head>

<body>

<!-- HEADER -->

<header class="header">
    <h1>POP ART</h1>
    <p>La Revolución del Color y la Cultura de Masas</p>
</header>

<!-- MENÚ -->

<nav class="menu">
    <a href="index.html">INICIO</a>
    <a href="definicion.html">DEFINICIÓN</a>
    <a href="artistas.html">ARTISTAS</a>
    <a href="tecnicas.html">TÉCNICAS</a>
    <a href="galeria.html">GALERÍA</a>
    <a href="contacto.php">CREÁ</a>
    <a href="poparthoy.html">POP ART HOY</a>
</nav>

<!-- INTRODUCCIÓN -->

<section class="intro">
    <span class="etiqueta-superior">
        POP ART EXPLORER / ARCHIVO VISUAL
    </span>

    <h2>ENCONTRÁ TU <span>POP.</span></h2>

    <p>
        Artistas, obras y técnicas que transformaron el arte.
    </p>
</section>

<!-- BUSCADOR -->

<section class="buscador-container">

    <form
        class="buscador-form"
        action="buscar.php"
        method="GET"
    >

        <input
            type="search"
            name="q"
            placeholder="¿Qué querés descubrir?"
            value="<?php echo escapar($texto); ?>"
            required
        >

        <select name="tipo">

            <option value="todos"
                <?php if ($tipo === "todos") echo "selected"; ?>>
                Todos
            </option>

            <option value="artista"
                <?php if ($tipo === "artista") echo "selected"; ?>>
                Artistas
            </option>

            <option value="obra"
                <?php if ($tipo === "obra") echo "selected"; ?>>
                Obras
            </option>

            <option value="tecnica"
                <?php if ($tipo === "tecnica") echo "selected"; ?>>
                Técnicas
            </option>

        </select>

        <button type="submit">BUSCAR →</button>

    </form>
</section>

<!-- RESULTADOS -->

<main class="resultados">

<?php if ($texto === "") { ?>

    <div class="sin-resultados">
        <h2>DESCUBRÍ EL UNIVERSO POP.</h2>
        <p>
            Buscá un artista, una obra o una técnica
            y empezá a explorar.
        </p>
    </div>

<?php } else { ?>

    <div class="contador">

        <h3>
            <?php echo count($resultados); ?>
            <?php
                echo count($resultados) === 1
                    ? "RESULTADO"
                    : "RESULTADOS";
            ?>
        </h3>

        <span>
            Búsqueda: "<?php echo escapar($texto); ?>"
        </span>

    </div>

    <?php if (count($resultados) > 0) { ?>

        <div class="lista-resultados">

        <?php
        $numero = 0;

        foreach ($resultados as $fila) {

            $numero++;

            $titulo = $fila["titulo"];
            $categoria = $fila["tipo"];
            $descripcion = $fila["descripcion"];

            $imagen = obtenerImagen($titulo);

            $enlace = obtenerLink(
                $fila["link"] ?? "",
                $categoria
            );

            $clase = claseCategoria($categoria);
        ?>

            <article class="card">

                <?php if ($imagen !== "") { ?>

                    <div class="card-imagen">
                        <img
                            src="<?php echo escapar($imagen); ?>"
                            alt="<?php echo escapar($titulo); ?>"
                            loading="lazy"
                        >
                    </div>

                <?php } else { ?>

                    <div class="card-sin-imagen">
                        <span>POP!</span>
                    </div>

                <?php } ?>

                <div class="card-contenido">

                    <div class="categoria <?php echo escapar($clase); ?>">
                        <?php
                            echo str_pad($numero, 2, "0", STR_PAD_LEFT);
                        ?>
                        / <?php echo escapar($categoria); ?>
                    </div>

                    <h2><?php echo escapar($titulo); ?></h2>

                    <p><?php echo escapar($descripcion); ?></p>

                    <a href="<?php echo escapar($enlace); ?>">
                        EXPLORAR →
                    </a>

                </div>

            </article>

        <?php } ?>

        </div>

    <?php } else { ?>

        <div class="sin-resultados">

            <h2>¡UPS! NO ENCONTRAMOS NADA.</h2>

            <p>
                Probá con otro artista, una obra
                o una técnica del Pop Art.
            </p>

        </div>

    <?php } ?>

<?php } ?>

</main>

<!-- FOOTER -->

<footer class="footer">
    <h2>POP ART</h2>
    <p>
        Explorá el color, la creatividad y la cultura de masas.
    </p>
    <p>© 2026 Pop Art Explorer — Proyecto web</p>
</footer>

</body>
</html>

<?php
$conexion->close();
?>