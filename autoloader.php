<?php
class Autoloader {
    static public function loader($className) {
        $filename = "class/" . str_replace('\\', '/', $className) . ".php";
            include($filename);
            if (class_exists($className)) {
                return TRUE;
            }
    }
}
