<?php 
require __DIR__.'/vendor/autoload.php';

echo PHP_EOL.<<<S4IB0T
 _           _     __  __          _ _ 
(_)_ __   __| | ___\ \/ /_____   _(_) |
| | '_ \ / _` |/ _ \\\\  // _ \ \ / / | |
| | | | | (_| |  __//  \  __/\ V /| | |
|_|_| |_|\__,_|\___/_/\_\___| \_/ |_|_|
        Copyright by Hillzacky
S4IB0T.PHP_EOL;

echo PHP_EOL.<<<MENU
=========================================

1. RSS
2. RPC
3. Feed
4. Ping
5. Extract URL
6. Clear

=========================================
MENU.PHP_EOL;

$input = trim( fgets( STDIN ) );

switch ( $input )
{
  case 1:
    echo "rss";
    break;
  case 2:
    echo "rpc";
    break;
  case 3:
    echo "feed";
    break;
  case 4:
    echo "ping";
    break;
  case 5:
    echo "extract-xml";
    $sitemap = trim( fgets( STDIN ) );
    $sitemaps = extractXML($sitemap);

    break;
  case 6:
    echo "clear";
    break;
  default:
    echo "Menu";
}

?>