<?php

require_once('river_includes.php');

$title='R.I.V.E.R. System v0.1.2';

print_header($title);

//test to see if a choice has been made
if (isset($choice))
{
    include($choice.".php");
}
else
{
    print "<p>Welcome to the new volunteer management system here at CYCO--R.I.V.E.R. (<b>R</b>ecruit & <b>I</b>nspire with <b>V</b>olunteer <b>E</b>ngagement <b>R</b>esources).</p>";
    print '<p align="center">Information about the status of R.I.V.E.R. can be found at its <a href="http://sourceforge.net/projects/r-i-v-e-r/">Sourceforge Listing</a>.  Current information about the development roadmap, bugs, and support issues can be found at the Sourceforge site.  Please use this method to contact the developers of this software.</p>';

}//if-end else

print_footer();

?>
