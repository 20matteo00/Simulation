<?php
session_destroy();

// Reindirizza PRIMA di qualsiasi echo o HTML
header("Location: index.php");
exit;
