<?php

use \Asbestos\Asbestos;
use \Asbestos\Config;

Asbestos::startThemedPage('debug');

?>
<pre style="text-align: left;">
<?php echo date('c'), "\n"; ?>

<strong>$req->isSecure()</strong> = "<?= $req->isSecure(); ?>"
<strong>$req->getScheme()</strong> = "<?= $req->getScheme(); ?>"
<strong>$req->getMethod()</strong> = "<?= $req->getMethod(); ?>"
<strong>$req->getUri()</strong> = "<?= $req->getUri(); ?>"
<strong>$req->getHost()</strong> = "<?= $req->getHost(); ?>"
<strong>$req->getPath()</strong> = "<?= $req->getPath(); ?>"
<strong>$req->getQuery()</strong> = "<?= $req->getQuery(); ?>"
<strong>$req->getUrl()</strong> = "<?= $req->getUrl(); ?>"

<strong>Config::get(null)</strong> = <?php var_dump(Config::get(null)); ?>

<strong>array_keys($GLOBALS)</strong> = <?php var_dump(array_keys($GLOBALS)); ?>
</pre>
