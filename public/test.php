<?php

$myobj = json_decode('{"age":"aa", "date":"vvvv"}');
$bolvalue = property_exists($myobj, 'age');
print ($bolvalue);
$mybol=True;
if (!$mybol)print ('False case');
else print ('True case');
