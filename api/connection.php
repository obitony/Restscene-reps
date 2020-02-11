<?php
error_reporting(0);
ini_set("display_errors",0);

public function connect(){
        return new mysqli("localhost","root","","reps");
    }



?>