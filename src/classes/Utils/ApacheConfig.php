<?php

namespace Asbestos\Utils;

final class ApacheConfig {

    private $_config = [];

    public function addRootDirectory($dir, $indexes=false, $allow_override=false) {
        $indexes = ($indexes ? '+' : '-');
        $allow_override = ($allow_override ? 'All' : 'None');
        $this->_config[] = <<<EOF
<Directory ${dir}>
    ServerSignature Off
    DirectoryIndex index.php
    DirectorySlash On
    AcceptPathInfo Off
    RewriteEngine On
    Options -MultiViews {$indexes}Indexes -Includes -ExecCGI +FollowSymLinks

    ErrorDocument 400 /
    ErrorDocument 403 /
    ErrorDocument 404 /
    ErrorDocument 500 /
    ErrorDocument 503 /

    AllowOverride {$allow_override}
    Require all granted

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule .? index.php [L]
</Directory>
EOF;
    }

    public function addDenyDirectory($dir) {
        $this->_config[] = <<<EOF
<Directory {$dir}>
    Require all denied
</Directory>
EOF;
    }

    public function addVirtualHost($ip, $port, $www_dir, $log_name) {
        if ($ip) {
            $ip .= ':';
        }
        $this->_config[] = <<<EOF
Listen {$ip}{$port}

<VirtualHost *:{$port}>
    DocumentRoot {$www_dir}
    ErrorLog \${APACHE_LOG_DIR}/error-{$log_name}.log
    CustomLog \${APACHE_LOG_DIR}/access-{$log_name}.log combined
</VirtualHost>
EOF;
    }

    public function get() {
        return implode("\n\n", $this->_config) . "\n";
    }

}

?>
