<?php
$error = "couldnt connect";

// @ m4 bt4t8al m3 ay 7aga 8eer die

if(!@mysql_connect('localhost','root','') || !@mysql_select_db('articales'))
{
 
    die ($error);

}





?>