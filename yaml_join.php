#!/usr/bin/env php

<?php
if (isset($argv[1])) {
  if ($argv[1] == "--help") {
    $help = "\nIf run without an argument, it joins all the yaml files\n" .
      "in the directory of the script.\n" .
      "If the name of the file that contains the joined yaml files\n" .
      "is passed as an argument, it will split it up into the original\n" .
      "files and adds a .new extension.\n\n";
    echo($help);
    exit();
  }
  else {
    if ($joined_file = file_get_contents($argv[1])) {
      $kaboom = explode("# &Ł@", $joined_file);
      foreach($kaboom as $file_content) {
        if (!empty($file_content)) {
          $filename = substr($file_content, 0, strpos($file_content, "\n"));
          file_put_contents($filename . ".new", substr($file_content, strpos($file_content, "\n") + 1));
        }
      }
    }
  }
}
else {
  $dir_list = scandir('./');
  $file_list = array_filter($dir_list, "is_yaml_file");
  $joined_file = "";
  foreach($file_list as $file) {
    $joined_file .= "# &Ł@" . $file . "\n";
    $joined_file .= file_get_contents($file);
  }
  if (!empty($joined_file)) {
    file_put_contents("joined_yaml.yml", $joined_file);
  }
}

function is_yaml_file($string) {
  if (is_file($string)) {
    $kaboom = explode(".", $string);
    $extension = $kaboom[count($kaboom) - 1];
    if ($extension == "yaml" || $extension == "yml") {
      return TRUE;
    }
    else {
      echo("Not a yaml file: " . $string . "\n");
      return FALSE;
    }
  }
  else {
    echo("Not a file: " . $string . "\n");
    return FALSE;
  }
}