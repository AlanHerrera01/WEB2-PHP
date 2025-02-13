<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>Colores de Vehículos</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6.0.0 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php
        require_once("constantes.php");
        include_once("class/class.color.php");
        
        $cn = conectar();
        $v = new COLOR($cn);
        
        if (isset($_GET['d'])) {
            $dato = base64_decode($_GET['d']);
            $tmp = explode("/", $dato);
            $op = $tmp[0];
            $id = $tmp[1];
            
            if ($op == "del") {
                echo $v->delete_color($id);
            } elseif ($op == "det") {
                echo $v->get_detail_color($id); // Si necesitas este detalle, deberías implementarlo en la clase
            } elseif ($op == "new") {
                echo $v->get_form();  // Muestra el formulario para agregar un nuevo color
            } elseif ($op == "act") {
                echo $v->get_form($id);  // Muestra el formulario para actualizar el color seleccionado
            }
            
        } else {
            // Verifica si se ha enviado el formulario
            if (isset($_POST['Guardar']) && $_POST['op'] == "new") {
                $v->save_color(); // Guarda un nuevo color
            } elseif (isset($_POST['Guardar']) && $_POST['op'] == "update") {
                $v->update_color(); // Actualiza un color existente
            } else {
                echo $v->get_list(); // Muestra la lista de colores
            }
        }

        // Función de conexión a la base de datos
        function conectar() {
            $c = new mysqli(SERVER, USER, PASS, BD);
            
            if ($c->connect_errno) {
                die("Error de conexión: " . $c->connect_errno . ", " . $c->connect_error);
            }
            
            $c->set_charset("utf8");
            return $c;
        }
    ?>
</body>
</html>
