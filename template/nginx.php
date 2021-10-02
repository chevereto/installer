<?php

header('Content-Type: text/plain');
printf('# Chevereto nginx generated rules for ' . $runtime->rootUrl . '

location ~* ' . $runtime->relPath . '(importing|app|content|lib)/.*\.(po|php|lock|sql)$ {
  deny all;
}

location ~ \.(jpe?g|png|gif|webp)$ {
    log_not_found off;
    error_page 404 ' . $runtime->relPath . 'content/images/system/default/404.gif;
}

location ~* ' . $runtime->relPath . '.*\.(ttf|ttc|otf|eot|woff|woff2|font.css|css|js)$ {
  add_header Access-Control-Allow-Origin "*";
}

location ' . $runtime->relPath . ' {
  index index.php;
  try_files $uri $uri/ /index.php$is_args$query_string;
}

# END Chevereto nginx rules
');
