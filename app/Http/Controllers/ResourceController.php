<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\Resource;

class ResourceController extends Controller
{
    public function test(){
        $links = mysqli_connect("10.1.12.11", "sd", "1111", "db");
        if (!$link) {
            echo "Error: Unable to connect to MySQL." . PHP_EOL;
            echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
            exit;
        }
        
        echo "Success: A proper connection to MySQL was made! The my_db database is great." . PHP_EOL;
        echo "Host information: " . mysqli_get_host_info($link) . PHP_EOL;
        
        mysqli_close($link);
    }
}
