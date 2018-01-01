<?php

namespace Asbestos;

final class AsbestosInstaller {

	private static function recursiveDelete($path) {
		$cwd = getcwd();
		$path = realpath($path);
		if ($path !== FALSE && is_dir($path) && substr($path, 0, strlen($cwd)) == $cwd) {
			$handle = opendir($path);
			while (($entry = readdir($handle)) !== FALSE) {
				if ($entry != '.' && $entry != '..') {
					$entry_path = $path . DIRECTORY_SEPARATOR . $entry;
					if (is_dir($entry_path)) {
						self::recursiveDelete($entry_path);
					} else {
						unlink($entry_path);
					}
				}
			}
			closedir($handle);
			rmdir($path);
		}
	}

	private static function recursiveCopy($path_s, $path_d) {
		$handle = opendir($path_s);
		$stat_dir = stat($path_s);
		mkdir($path_d, 0777, true);
		while (($entry = readdir($handle)) !== FALSE) {
			if ($entry != '.' && $entry != '..') {
				$entry_path_s = $path_s . DIRECTORY_SEPARATOR . $entry;
				$entry_path_d = $path_d . DIRECTORY_SEPARATOR . $entry;
				if (is_dir($entry_path_s)) {
					self::recursiveCopy($entry_path_s, $entry_path_d);
				} else {
					$stat = stat($entry_path_s);
					copy($entry_path_s, $entry_path_d);
					chmod($entry_path_d, $stat['mode']);
					touch($entry_path_d, $stat['mtime'], $stat['atime']);
				}
			}
		}
		chmod($path_d, $stat_dir['mode']);
		touch($path_d, $stat_dir['mtime'], $stat['atime']);
		closedir($handle);
	}

	private static function gitTouch($path) {
		exec('git -C ' . escapeshellarg($path) . ' ls-tree -r -t HEAD --name-only', $output);
		foreach ($output as $file) {
			$mtime = (int) exec('git -C ' . escapeshellarg($path) . ' log -n 1 --format="%ct" -- ' . escapeshellarg($file));
			if ($mtime) {
				touch($path . DIRECTORY_SEPARATOR . $file, $mtime);
			}
		}
	}

	public static function copyAsbestosToWWW($event) {
		$dir_www_asbestos = getcwd() . DIRECTORY_SEPARATOR . 'www' . DIRECTORY_SEPARATOR . 'asbestos';
		$dir_vendor_asbestos = dirname(__DIR__);
		self::gitTouch($dir_vendor_asbestos);
		self::recursiveDelete($dir_www_asbestos);
		echo "Copying asbestos to {$dir_www_asbestos}\n";
		self::recursiveCopy($dir_vendor_asbestos, $dir_www_asbestos);
	}

	private function __construct() { }

}

?>
