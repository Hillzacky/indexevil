$t = @"
 _           _     __  __          _ _ 
(_)_ __   __| | ___\ \/ /_____   _(_) |
| | '_ \ / _' |/ _ \\  // _ \ \ / / | |
| | | | | (_| |  __//  \  __/\ V /| | |
|_|_| |_|\__,_|\___/_/\_\___| \_/ |_|_|
        Copyright by Hillzacky`n`n
"@
for ($i=0;$i -lt $t.length;$i++) {
if ($i%2) {
 $c = "red"
}
elseif ($i%5) {
 $c = "yellow"
}
elseif ($i%7) {
 $c = "green"
}
else {
   $c = "white"
}
write-host $t[$i] -NoNewline -ForegroundColor $c
}

[string[]]$arrayFromFile = Get-Content -Path 'C:\server\htdocs\indeXevil\src\500.txt'
$domain =  Read-Host 'Masukan domain tanpa http'

foreach ($alamat in $arrayFromFile){
  Write-Host '>>> Antosan. . .' -fore yellow
  $uri = $alamat -f $domain
  try{
   $submit = Invoke-WebRequest -Uri $uri -Headers @{
    "Referer"="https://www.google.com/"
    "User-Agent"="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.192 Safari/537.36"
   }
   Write-Host $submit.StatusCode 'Submit Sukses :' $uri -fore green
   $pinguri = "http://pingomatic.com/ping/?title=Pingtime&blogurl="+$uri+"&rssurl=http%3A%2F%2F&chk_weblogscom=on&chk_blogs=on&chk_technorati=on&chk_feedburner=on&chk_syndic8=on&chk_newsgator=on&chk_myyahoo=on&chk_pubsubcom=on&chk_blogdigger=on&chk_blogrolling=on&chk_blogstreet=on&chk_moreover=on&chk_weblogalot=on&chk_icerocket=on&chk_newsisfree=on&chk_topicexchange=on&chk_google=on&chk_tailrank=on&chk_bloglines=on&chk_postrank=on&chk_skygrid=on&chk_bitacoras=on&chk_collecta=on"
   $ping = Invoke-WebRequest -Uri $pinguri -Headers @{
    "Referer"="https://www.google.com/"
    "User-Agent"="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.192 Safari/537.36"
   }
   Write-Host $ping.StatusCode 'Ping Sukses :' $pinguri -fore green
  }catch [System.Net.WebException]{
   if($Error[0].Exception.Response.StatusDescription -ne $null){
    Write-Host '>>>' $Error[0].Exception.Response.StatusDescription -fore red
   }else{
    Write-Host '>>> Sukses :' $uri -fore green
   }
  }
}