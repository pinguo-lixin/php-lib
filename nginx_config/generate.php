#!/usr/bin/env php
<?php

/**
 * @var string $host host
 */
$host = '';
/**
 * @var string $projectName 项目文件目录名
 */
$projectName = '';

$greenColor = "\033[1;32m";
$redColor = "\033[1;31m";
$colorClose = "\033[0m";

echo "{$greenColor}请输入 host:  {$colorClose}";
$host = $projectName = trim(fgets(STDIN));

echo "{$greenColor}请输入项目目录名：(default: {$projectName})  {$colorClose}";
$projectName = trim(fgets(STDIN));


$template = <<<TEMP
server {
    listen       80; 
    server_name $host;

    access_log  /home/worker/data/nginx/logs/$host.access.log  main;
    error_log   /home/worker/data/nginx/logs/$host.error.log;

    root   /home/worker/data/www/$projectName/public;
    index  index.html index.htm index.php;

    location ~ /\.git/ {
        return 404;
    }
	
    location ~ /\.svn/ {
        return 404;
    }

    location / {
        try_files \$uri \$uri/ /index.php?\$args;
    }

    location = /favicon.ico {
        log_not_found off;
        access_log off;
    }

    location = /robots.txt {
        allow all;
        log_not_found off;
        access_log off;
    }

    location ~ \.php$ {
        fastcgi_pass  php-fpm;
        fastcgi_index  index.php;
        fastcgi_param  SCRIPT_FILENAME  \$document_root\$fastcgi_script_name;
        include        fastcgi_params;
        proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
        proxy_next_upstream http_502 http_503 http_504 http_500 error timeout invalid_header;
    }
	
    location ~* \.(js|css|png|jpg|jpeg|gif|ico)$ {
        expires max;
        log_not_found off;
        access_log off;
    }
}
TEMP;

$confName = "$host.conf";

if (file_exists($confName)) {
    echo "{$redColor}文件已存在，要覆盖吗？(y|n){$colorClose} ";
    $overWrite = strtolower(trim(fgets(STDIN)));
    if ($overWrite === 'n') {
        exit(0);
    }
}

if ($host && file_put_contents($confName, $template)) {
    echo "{$greenColor}Success!{$colorClose}";
} else {
    die("{$redColor}Error!{$colorClose}");
}