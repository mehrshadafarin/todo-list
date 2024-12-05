<?php
function loadEnv($file = './private/.ENV') {
    if (!file_exists($file)) {
        throw new Exception("Environment file not found.");
    }
    return parse_ini_file($file);
    
}
?>
