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
?>

