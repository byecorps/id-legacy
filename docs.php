<?php

$Parsedown = new ParsedownExtra();

// Loads the relevant file from /docs

$file_path = __DIR__ . $path;

/* If it's a dir, check for index.md
If it's a file.md, load it. */

// First check if the file exists as a directory
if (file_exists($file_path)) {
	// Check if the index.md exists
	if (file_exists($file_path . '/index.md')) {
		// And if so, make that the file path.
		$file_path .= "/index.md";
	} else { // If not, 404.
		http_response_code(404);
		include("404.html");
		include("footer.php");
		die($file_path);
	}
} elseif (file_exists($file_path . '.md')) { // Check if $file.md exists.
	// And if so, make that the file path.
	$file_path .= '.md';
}  else { // now if not, just show the standard 404 page.
	http_response_code(404);
	include("404.html");
	include("footer.php");
	die($file_path);
}

echo $file_path;
echo "<br>";

$file = fopen($file_path, "r");
$file_contents = fread($file, filesize($file_path));
fclose($file);

echo $Parsedown->parse($file_contents);
