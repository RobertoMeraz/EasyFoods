<?php
session_start();
require_once 'funciones.php'; // Contiene isLoggedIn(), isAdmin(), etc.
// Asegúrate que funciones.php también incluya conexion.php para que $conn esté disponible.

// Para la conexión, asumimos que $conn se establece en conexion.php,
// que es incluido por funciones.php
global $conn; 

$action = $_GET['action'] ?? 'list'; // Acción por defecto: listar
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0; // ID para editar/eliminar
$error = '';
$success = '';

// --- PROCESAR FORMULARIOS (AGREGAR/EDITAR) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');

    if (empty($nombre)) {
        $error = "El nombre del ingrediente es requerido.";
    } else {
        try {
            if (isset($_POST['add_ingrediente'])) { // Acción de agregar
                // Verificar si ya existe (debido a la restricción UNIQUE en la BD)
                $stmt_check = $conn->prepare("SELECT id_ingrediente FROM ingredientes WHERE nombre = ?");
                $stmt_check->execute([$nombre]);
                if ($stmt_check->fetch()) {
                    $error = "Un ingrediente con este nombre ya existe.";
                } else {
                    $stmt = $conn->prepare("INSERT INTO ingredientes (nombre) VALUES (?)");
                    $stmt->execute([$nombre]);
                    $success = "Ingrediente agregado correctamente.";
                    header("Location: admin_ingredientes.php?success=" . urlencode($success));
                    exit;
                }
            } elseif (isset($_POST['edit_ingrediente']) && $id > 0) { // Acción de editar
                 // Verificar si el nuevo nombre ya existe para OTRO ingrediente
                $stmt_check = $conn->prepare("SELECT id_ingrediente FROM ingredientes WHERE nombre = ? AND id_ingrediente != ?");
                $stmt_check->execute([$nombre, $id]);
                if ($stmt_check->fetch()) {
                    $error = "Otro ingrediente ya tiene este nombre.";
                } else {
                    $stmt = $conn->prepare("UPDATE ingredientes SET nombre = ? WHERE id_ingrediente = ?");
                    $stmt->execute([$nombre, $id]);
                    $success = "Ingrediente actualizado correctamente.";
                    header("Location: admin_ingredientes.php?success=" . urlencode($success)); 
                    exit;
                }
            }
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') { 
                 $error = "Error: El nombre del ingrediente ya existe o hubo otro problema de integridad.";
            } else {
                $error = "Error al guardar el ingrediente: " . $e->getMessage();
            }
            error_log("Error DB Ingredientes: " . $e->getMessage()); 
        }
    }
}

// --- PROCESAR ELIMINACIÓN ---
if ($action === 'delete' && $id > 0) {
    try {
        // Opcional: Verificar si el ingrediente está en uso
        $stmt_check_uso = $conn->prepare("SELECT COUNT(*) as total FROM receta_ingrediente WHERE ingrediente_id = ?");
        $stmt_check_uso->execute([$id]);
        $en_uso = $stmt_check_uso->fetchColumn();

        if ($en_uso > 0) {
            $_SESSION['mensaje_error_ingrediente'] = "Error: No se puede eliminar el ingrediente 'ID: $id' porque está siendo utilizado en {$en_uso} receta(s).";
        } else {
            $stmt = $conn->prepare("DELETE FROM ingredientes WHERE id_ingrediente = ?");
            $stmt->execute([$id]);
            $_SESSION['mensaje_exito_ingrediente'] = "Ingrediente eliminado correctamente.";
        }
    } catch (PDOException $e) {
        if ($e->getCode() == '23000') { 
            $_SESSION['mensaje_error_ingrediente'] = "Error: No se puede eliminar el ingrediente porque está referenciado en alguna receta.";
        } else {
            $_SESSION['mensaje_error_ingrediente'] = "Error al eliminar el ingrediente: " . $e->getMessage();
        }
        error_log("Error DB Eliminando Ingrediente: " . $e->getMessage());
    }
    header("Location: admin_ingredientes.php"); // Redirigir para mostrar mensajes de sesión y limpiar URL
    exit;
}

// --- OBTENER DATOS PARA EDICIÓN O LISTA ---
$ingrediente_actual = []; 
$ingredientes = [];      

if ($action === 'edit' && $id > 0) {
    try {
        $stmt = $conn->prepare("SELECT * FROM ingredientes WHERE id_ingrediente = ?");
        $stmt->execute([$id]);
        $ingrediente_actual = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$ingrediente_actual) {
            $_SESSION['mensaje_error_ingrediente'] = "Ingrediente no encontrado para editar.";
            header("Location: admin_ingredientes.php");
            exit;
        }
    } catch (PDOException $e) {
        $error = "Error al obtener ingrediente para editar: " . $e->getMessage(); // Error para mostrar en la vista de edición si falla
        error_log("Error DB Obteniendo Ingrediente para Editar: " . $e->getMessage());
        // No redirigir aquí, permitir que el formulario de edición muestre el error
    }
}


// Siempre obtener todos los ingredientes para la lista
// (incluso si una acción de edición falla, para mostrar la lista debajo o si se cancela)
try {
    $stmt_list = $conn->query("SELECT id_ingrediente, nombre FROM ingredientes ORDER BY nombre ASC");
    $ingredientes = $stmt_list->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Este error se añadiría a cualquier error existente si 'edit' falló
    $error .= ($error ? "<br>" : "") . "Error al obtener la lista de ingredientes: " . $e->getMessage();
    error_log("Error DB Listando Ingredientes: " . $e->getMessage());
    $ingredientes = [];
}

// Capturar mensajes de la URL (si se redirigió desde POST) o de la sesión (si se redirigió desde DELETE)
if (isset($_GET['success'])) {
    $success = htmlspecialchars($_GET['success']);
}
if (isset($_SESSION['mensaje_exito_ingrediente'])) {
    $success = htmlspecialchars($_SESSION['mensaje_exito_ingrediente']);
    unset($_SESSION['mensaje_exito_ingrediente']);
}
if (isset($_SESSION['mensaje_error_ingrediente'])) {
    $error = htmlspecialchars($_SESSION['mensaje_error_ingrediente']);
    unset($_SESSION['mensaje_error_ingrediente']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Ingredientes - Easy Foods Admin</title>
    <link rel="stylesheet" href="estilos/estilo_admin.css"> 
    <style>
        /* Estilos para mensajes de alerta (puedes moverlos a un CSS global si no están ya) */
        .alert { padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px; text-align: center; }
        .alert-success { color: #155724; background-color: #d4edda; border-color: #c3e6cb; }
        .alert-danger { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
        /* Añade más estilos si son necesarios para admin-form, form-group, etc. */
    </style>
</head>
<body>
    <div class="admin-container">
        <?php include 'header.php'; ?> 
        
        <?php include 'admin_sidebar.php'; ?> 

        <main class="admin-main">
            <h1>Gestión de Ingredientes</h1>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>
            
            <?php if ($action === 'add' || ($action === 'edit' && $ingrediente_actual)): ?>
                <h2><?= $action === 'add' ? 'Agregar Nuevo Ingrediente' : 'Editar Ingrediente (ID: '.htmlspecialchars($ingrediente_actual['id_ingrediente']).')' ?></h2>
                <form method="POST" 
                      action="admin_ingredientes.php<?= $action === 'edit' ? '?action=edit&id='.htmlspecialchars($id) : '?action=add' ?>" 
                      class="admin-form">
                    <div class="form-group">
                        <label for="nombre">Nombre del Ingrediente:</label>
                        <input type="text" id="nombre" name="nombre" required 
                               value="<?= htmlspecialchars($ingrediente_actual['nombre'] ?? ($_POST['nombre'] ?? '')) ?>">
                    </div>
                    
                    <div class="form-actions">
                        <?php if ($action === 'add'): ?>
                            <button type="submit" name="add_ingrediente" class="btn-save">Agregar Ingrediente</button>
                        <?php else: ?>
                            <button type="submit" name="edit_ingrediente" class="btn-save">Actualizar Ingrediente</button>
                        <?php endif; ?>
                        <a href="admin_ingredientes.php" class="btn-cancel">Cancelar</a>
                    </div>
                </form>
                <hr style="margin: 30px 0;"> 
            <?php endif; ?>

            <?php // La lista siempre se muestra, o al menos el encabezado y el botón de agregar si 'add' o 'edit' están activos ?>
            <div class="admin-actions">
                 <a href="admin_ingredientes.php?action=add" class="btn-add">Agregar Nuevo Ingrediente</a>
            </div>
            
            <h2>Lista de Ingredientes</h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($ingredientes)): ?>
                        <tr>
                            <td colspan="3" style="text-align:center;">No hay ingredientes registrados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($ingredientes as $ing): ?>
                        <tr>
                            <td><?= htmlspecialchars($ing['id_ingrediente']) ?></td>
                            <td><?= htmlspecialchars($ing['nombre']) ?></td>
                            <td>
                                <a href="admin_ingredientes.php?action=edit&id=<?= $ing['id_ingrediente'] ?>" class="btn-edit">Editar</a>
                                <a href="admin_ingredientes.php?action=delete&id=<?= $ing['id_ingrediente'] ?>" class="btn-delete" 
                                   onclick="return confirm('¿Estás seguro de que quieres eliminar este ingrediente? Si está siendo utilizado en recetas, es posible que no se pueda eliminar o que afecte esas recetas.');">Eliminar</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>