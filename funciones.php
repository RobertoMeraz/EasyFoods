<?php
// funciones.php
require_once 'conexion.php';

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function loginUser($email, $password) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("SELECT id_usuario, nombre, password, rol FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id_usuario'];
            $_SESSION['user_name'] = $user['nombre'];
            $_SESSION['user_role'] = $user['rol'];
            return true;
        }
        return false;
    } catch(PDOException $e) {
        error_log("Error en login: " . $e->getMessage());
        return false;
    }
}

function registerUser($name, $email, $password) {
    global $conn;
    
    try {
        // Verificar si el email ya existe
        $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            return "El correo electrónico ya está registrado";
        }
        
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $hashedPassword]);
        
        return true;
    } catch(PDOException $e) {
        error_log("Error en registro: " . $e->getMessage());
        return "Error al registrar el usuario. Por favor, inténtelo de nuevo.";
    }
}
// funciones.php - Añade estas funciones después de las existentes

// Obtener todas las recetas
function getAllRecetas() {
    global $conn;
    try {
        $stmt = $conn->query("SELECT * FROM recetas_populares");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error al obtener recetas: " . $e->getMessage());
        return [];
    }
}

// Obtener una receta por ID
function getRecetaById($id) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM recetas_populares WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error al obtener receta: " . $e->getMessage());
        return false;
    }
}

// Añadir nueva receta
function addReceta($titulo, $descripcion, $imagen, $categoria_id, $usuario_id, $dificultad, $porciones) {
    global $conn;
    try {
        $stmt = $conn->prepare("INSERT INTO recetas_populares (titulo, descripcion, imagen, categoria_id, usuario_id, dificultad, porciones, fecha_creacion) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
        return $stmt->execute([$titulo, $descripcion, $imagen, $categoria_id, $usuario_id, $dificultad, $porciones]);
    } catch(PDOException $e) {
        error_log("Error al añadir receta: " . $e->getMessage());
        return false;
    }
}

// Actualizar receta
function updateReceta($id, $titulo, $descripcion, $imagen, $categoria_id, $dificultad, $porciones) {
    global $conn;
    try {
        $stmt = $conn->prepare("UPDATE recetas_populares SET titulo = ?, descripcion = ?, imagen = ?, categoria_id = ?, dificultad = ?, porciones = ? WHERE id = ?");
        return $stmt->execute([$titulo, $descripcion, $imagen, $categoria_id, $dificultad, $porciones, $id]);
    } catch(PDOException $e) {
        error_log("Error al actualizar receta: " . $e->getMessage());
        return false;
    }
}

// Eliminar receta
function deleteReceta($id) {
    global $conn;
    try {
        $stmt = $conn->prepare("DELETE FROM recetas_populares WHERE id = ?");
        return $stmt->execute([$id]);
    } catch(PDOException $e) {
        error_log("Error al eliminar receta: " . $e->getMessage());
        return false;
    }
}

// Obtener todas las categorías (asumiendo que tienes una tabla categorías)
function getCategorias() {
    global $conn;
    try {
        $stmt = $conn->query("SELECT * FROM categorias");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error al obtener categorías: " . $e->getMessage());
        return [];
    }
}

// Obtener todas las etiquetas
function getEtiquetas() {
    global $conn;
    try {
        $stmt = $conn->query("SELECT * FROM etiquetas");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error al obtener etiquetas: " . $e->getMessage());
        return [];
    }
}

// Añadir etiqueta a receta
function addEtiquetaToReceta($receta_id, $etiqueta_id) {
    global $conn;
    try {
        $stmt = $conn->prepare("INSERT INTO receta_etiqueta (receta_id, etiqueta_id) VALUES (?, ?)");
        return $stmt->execute([$receta_id, $etiqueta_id]);
    } catch(PDOException $e) {
        error_log("Error al añadir etiqueta a receta: " . $e->getMessage());
        return false;
    }
}

// Eliminar etiqueta de receta
function removeEtiquetaFromReceta($receta_id, $etiqueta_id) {
    global $conn;
    try {
        $stmt = $conn->prepare("DELETE FROM receta_etiqueta WHERE receta_id = ? AND etiqueta_id = ?");
        return $stmt->execute([$receta_id, $etiqueta_id]);
    } catch(PDOException $e) {
        error_log("Error al eliminar etiqueta de receta: " . $e->getMessage());
        return false;
    }
}
// Añade estas funciones al final de funciones.php

// Verificar si el usuario es administrador
function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

// Obtener recetas con información extendida (incluyendo categoría y usuario)
function getRecetasWithDetails() {
    global $conn;
    try {
        $stmt = $conn->query("SELECT r.*, c.nombre as categoria_nombre, u.nombre as usuario_nombre 
                             FROM recetas_populares r
                             LEFT JOIN categorias c ON r.categoria_id = c.id
                             LEFT JOIN usuarios u ON r.usuario_id = u.id_usuario");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error al obtener recetas con detalles: " . $e->getMessage());
        return [];
    }
}

// Obtener etiquetas de una receta específica
function getEtiquetasByReceta($receta_id) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT e.* FROM etiquetas e
                               JOIN receta_etiqueta re ON e.id = re.etiqueta_id
                               WHERE re.receta_id = ?");
        $stmt->execute([$receta_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error al obtener etiquetas de receta: " . $e->getMessage());
        return [];
    }
}

/**
 * Añade una receta a la lista de favoritos de un usuario.
 *
 * @param int $usuario_id ID del usuario.
 * @param int $receta_id ID de la receta.
 * @return bool|string True si se añadió correctamente, "already_exists" si ya era favorita, false en caso de error.
 */
function addRecetaToFavoritos($usuario_id, $receta_id) {
    global $conn; // Asume que $conn es tu objeto PDO de conexión global

    // 0. Validaciones básicas de entrada (robustez adicional)
    if (!$conn) {
        error_log("addRecetaToFavoritos: Conexión a la base de datos no disponible (\$conn es nulo).");
        return false;
    }
    if (!filter_var($usuario_id, FILTER_VALIDATE_INT) || $usuario_id <= 0) {
        error_log("addRecetaToFavoritos: ID de usuario no válido proporcionado: " . htmlspecialchars(print_r($usuario_id, true)));
        return false;
    }
    if (!filter_var($receta_id, FILTER_VALIDATE_INT) || $receta_id <= 0) {
        error_log("addRecetaToFavoritos: ID de receta no válido proporcionado: " . htmlspecialchars(print_r($receta_id, true)));
        return false;
    }

    try {
        // 1. Verificar si la receta ya está en favoritos para este usuario
        // CORRECCIÓN AQUÍ: Seleccionar una columna existente como 'usuario_id' o simplemente '1'
        $stmt_check = $conn->prepare("SELECT usuario_id FROM favoritos WHERE usuario_id = :usuario_id AND receta_id = :receta_id");
        // Alternativamente, podrías usar: "SELECT 1 FROM favoritos WHERE ..."
        
        $stmt_check->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt_check->bindParam(':receta_id', $receta_id, PDO::PARAM_INT);
        $stmt_check->execute();

        if ($stmt_check->fetch()) {
            return "already_exists"; // La receta ya es un favorito
        }

        // 2. Si no existe, intentar insertarla en la tabla 'favoritos'
        $sql_insert = "INSERT INTO favoritos (usuario_id, receta_id, fecha_agregado) VALUES (:usuario_id, :receta_id, NOW())";
        $stmt_insert = $conn->prepare($sql_insert);

        // Vincular parámetros
        $stmt_insert->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt_insert->bindParam(':receta_id', $receta_id, PDO::PARAM_INT);

        if ($stmt_insert->execute()) {
            return true; // Éxito al insertar
        } else {
            $errorInfo = $stmt_insert->errorInfo();
            error_log("Error en addRecetaToFavoritos (execute devolvió false). SQLSTATE: {$errorInfo[0]}, Driver Code: {$errorInfo[1]}, Message: {$errorInfo[2]}. Usuario ID: $usuario_id, Receta ID: $receta_id");
            return false; 
        }

    } catch (PDOException $e) {
        $debug_sql_check = "SELECT usuario_id FROM favoritos WHERE usuario_id = $usuario_id AND receta_id = $receta_id"; // Para el log
        $debug_sql_insert = "INSERT INTO favoritos (usuario_id, receta_id, fecha_agregado) VALUES ($usuario_id, $receta_id, NOW())"; // Para el log
        
        error_log("PDOException en addRecetaToFavoritos: " . $e->getMessage() . " | SQL Check aprox: [$debug_sql_check] | SQL Insert aprox: [$debug_sql_insert] | Usuario ID: $usuario_id | Receta ID: $receta_id");
        return false; 
    }
}

?>

