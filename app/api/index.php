  <?php
 header("Content-Type: application/json");

$route = $_GET['route'] ?? '';

switch($route){

    case 'auth':
        include 'auth.php';
        break;

    default:
        echo json_encode([
            "status" => "error",
            "message" => "Invalid route"
        ]);                                
        }

    ?>
