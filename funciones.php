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
?>