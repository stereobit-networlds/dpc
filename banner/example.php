<?include("Banner.php");

// Drop some 480x80 gifs or jpegs in banners/ dir and use this:
// press "Refresh" button in your browser several times to see
// your banners rotate.
// You should refresh only after TIMEOUT interval has passed, or you
// will see the same counter value everytime.
// For testing, you could just decrease TIMEOUT in Banner.php

?>
<table>
   <tr><td><!-- BANNER --><img src="<?=BannerR::rotate()?>" width="480" height="80" alt="BANNER" border="0"><!-- /BANNER --></td></tr>
</table>