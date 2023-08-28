<?php
set_time_limit(0);
$env = parse_ini_file('.env');
// Specify the repository directory path
$destination_dir = __DIR__;

// Specify the repository branch
$branch = "master";

// the repository URL to pull from
$repository_url = $env["git_repository"];
// git_repository = https://username:password@reepositoryUrl

chdir($destination_dir);
echo "<pre>";
if (!file_exists('.git')) {
  unlink("." . $_SERVER['SCRIPT_NAME']);
  echo shell_exec("git init");
  echo shell_exec("git remote add origin {$repository_url} 2>&1");
}
echo "<h1>Pulling from git repository.</h1>";
echo shell_exec("git pull --ff-only --force {$repository_url} {$branch} 2>&1");

echo "</pre>";
echo "Finished";
echo "<hr>";
