<?php
if(isset($_POST['emerg_submit']))
{
    add_data($_POST, (TAB_PRE.'_emergency'));
}
else {echo 'Something is not right.  Please try whatever request you made again.  This page should not appear like this.';}
?>