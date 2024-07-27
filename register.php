<?php
// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ambicontrol";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Usuarios
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tipo_formulario']) && $_POST['tipo_formulario'] == "usuarios") {
    $nombre_completo = $_POST['nombre_completo'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encriptar la contraseña
    $tipo_documento = $_POST['tipo_documento'];
    $numero_documento = $_POST['numero_documento'];

    // Verificar si el usuario ya está registrado
    $sql_check = $conn->prepare("SELECT * FROM usuarios WHERE numero_documento = ? OR email = ?");
    $sql_check->bind_param("ss", $numero_documento, $email);
    $sql_check->execute();
    $result_check = $sql_check->get_result();

    if ($result_check->num_rows > 0) {
        echo "El usuario ya está registrado.";
        // Redirigir de nuevo al formulario de registro después de 3 segundos
        header("refresh:3;url=registro.php");
        exit();
    } else {
        // Insertar el nuevo usuario en la base de datos
        $sql = $conn->prepare("INSERT INTO usuarios (nombre_completo, email, password, tipo_documento, numero_documento) VALUES (?, ?, ?, ?, ?)");
        $sql->bind_param("sssss", $nombre_completo, $email, $password, $tipo_documento, $numero_documento);

        if ($sql->execute() === TRUE) {
            echo "Registro exitoso.";
            // Redirigir al inicio de sesión después de 3 segundos
            header("refresh:3;url=../index.php");
            exit();
        } else {
            echo "Error: " . $sql->error;
        }
    }
    $sql_check->close();
    $sql->close();
}

// Registración de ambiente
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tipo_formulario']) && $_POST['tipo_formulario'] == "asignaciones") {
    $numero_documento = $_POST['numero_documento'];
    $tipo_asignacion = $_POST['tipo_asignacion'];
    $numero_ambiente = $_POST['numero_ambiente'];

    $sql = $conn->prepare("INSERT INTO asignaciones (numero_documento, tipo_asignacion, numero_ambiente) VALUES (?, ?, ?)");
    $sql->bind_param("sss", $numero_documento, $tipo_asignacion, $numero_ambiente);

    if ($sql->execute() === TRUE) {
        echo "Asignación exitosa.";
        // Redirigir después de 3 segundos
        header("refresh:3;url=asignar_ambiente.php");
        exit();
    } else {
        echo "Error: " . $sql->error;
    }
    $sql->close();
}

// Observaciones
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tipo_formulario']) && $_POST['tipo_formulario'] == "observaciones") {
    $numero_documento = $_POST['numero_documento'];
    $observacion = $_POST['observacion'];

    $sql = $conn->prepare("INSERT INTO observaciones (numero_documento, observacion) VALUES (?, ?)");
    $sql->bind_param("ss", $numero_documento, $observacion);

    if ($sql->execute() === TRUE) {
        echo "Observación registrada exitosamente.";
        // Redirigir después de 3 segundos
        header("refresh:3;url=registrar_observacion.php");
        exit();
    } else {
        echo "Error: " . $sql->error;
    }
    $sql->close();
}

// Inventario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tipo_formulario']) && $_POST['tipo_formulario'] == "inventario") {
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $elemento = $_POST['elemento'];
    $disponibilidad = $_POST['disponibilidad'];
    $cantidad = $_POST['cantidad'];
    $descripcion = $_POST['descripcion'];

    $sql = $conn->prepare("INSERT INTO inventario (fecha, hora, elemento, disponibilidad, cantidad, descripcion) VALUES (?, ?, ?, ?, ?, ?)");
    $sql->bind_param("ssssss", $fecha, $hora, $elemento, $disponibilidad, $cantidad, $descripcion);

    if ($sql->execute() === TRUE) {
        echo "Inventario registrado exitosamente.";
        // Redirigir después de 3 segundos
        header("refresh:3;url=registrar_inventario.php");
        exit();
    } else {
        echo "Error: " . $sql->error;
    }
    $sql->close();
}

$conn->close();
?>
