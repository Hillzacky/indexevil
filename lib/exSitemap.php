<?php
function extractXML($site, $key = null) {
  $xml=simplexml_load_file($site);
  $counter=1;
  $countMap = count($xml->sitemap);
  $countUrl = count($xml->url);
  if( count($xml->sitemap) ){
    foreach( $xml->sitemap as $map ) {
      echo "Pengambilan lebih dalam... ({$counter}/{$countMap})".PHP_EOL;
      $otherSite[] = extractXML($map->loc);$counter++;
    }
    $child_xmls[] = call_user_func_array('array_merge', $otherSite);
  }else{
    foreach( $xml->url as $map ) {
      echo "Pengambilan Url... ({$counter}/{$countUrl})".PHP_EOL;
      if ( filter_var($map->loc, FILTER_VALIDATE_URL) )
        $child_xmls[] = (string) $map->loc;$counter++;
    }
  }
  return $child_xmls;
}

// $sitemaps = extractXML('sitemap_index.xml');
// print_r($sitemaps);

?>