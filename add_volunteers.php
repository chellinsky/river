<?php
if(!isset($_POST['Submit'])) //see if the form was attempted
{ //if not, display the form
/*<p>Please complete this form and hit submit to add a volunteer into the database.  You can always update the information later by clicking on the "Edit Volunteer" link to the left.  Items marked with an * are required.</p>

<form name="add_volunteer" method="post" action="index.php?choice=add_volunteers">

<p>First Name*: <input type="text" name="first_name" /> Middle Name: <input type="text" name="middle_name" /> Last Name*: <input type="text" name="last_name" /></p>
<p>Company: <input type="text" name="company" /></p>
<p>Address: <input type="text" name="address" /></p>
<p>City: <input type="text" name="city" /> State: <input type="text" name="state" /> Zip Code: <input type="text" name="zip" /></p>
<p>Phone One (with area code and only numerics)*: <input type="text" name="phone1" size="10" maxlength="10" /></p>
<p>Phone Two (with area code and only numerics): <input type="text" name="phone2" size="10" maxlength="10" /></p>
<p>Email: <input type="text" name="email" /></p>
<p>Birthdate: <input type="text" name="birthdate" /> (mm-dd-yyyy)</p>
<p>Consent Form? <input type="checkbox" name="consent_form" value="1" /> Photo Form? <input type="checkbox" name="photo_form" value="1" /></p>

<p><input type="radio" name="emerg_form" value="yes_emerg" />Enter Emergency Information

<input type="radio" name="emerg_form" value="no_emerg" />Enter Emergency Information Later </p>
<p align="center"><input type="submit" name="Submit" value="Send Form" /></p>
</form>
*/

	add_data_form(TAB_PRE.'_volunteers');

}// end the if
elseif (is_string($_POST['first_name']) && is_string($_POST['last_name']) && is_numeric($_POST['phone1']) && strlen($_POST['phone1']) == 10)
{
    //test the values for valid input and then insert into the database
    $inputarray = $_POST;
    $table = TAB_PRE."_volunteers";
    
    add_data($inputarray, $table);

}// end the elseif
else
{
    //reprint form with user input intact
    echo '<h3>Not all required fields were completed, please try again!</h3>';
    echo '<p>Please complete this form and hit submit to add a volunteer into the database.  You can always update the information later by clicking on the "Edit Volunteer" link to the left.  Items marked with an * are required.</p>';
    echo '<form name="add_volunteer" method="post" action="index.php?choice=add_volunteers">';
    echo '<p>First Name*: <input type="text" name="first_name" value="'.$_POST['first_name'].'" /> Middle Name: <input type="text" name="middle_name" value="'.$_POST['middle_name'].'" /> Last Name*: <input type="text" name="last_name" value="'.$_POST['last_name'].'" /></p>';
		echo '<p>Company: <input type="text" name="company" value="'.$_POST['company'].'" /></p>';
    echo '<p>Address: <input type="text" name="address" value="'.$_POST['address'].'" /></p>';
    echo '<p>City: <input type="text" name="city" value="'.$_POST['city'].'" /> State: <input type="text" name="state" value="'.$_POST['state'].'" /> Zip Code: <input type="text" name="zip" value="'.$_POST['zip_code'].'" /></p>';
    echo '<p>Phone One (with area code and only numerics)*: <input type="text" name="phone1" size="10" maxlength="10" value="'.$_POST['phone1'].'" /></p>';
    echo '<p>Phone Two (with area code and only numerics): <input type="text" name="phone2" size="10" maxlength="10" value="'.$_POST['phone2'].'" /></p>';
    echo '<p>Email: <input type="text" name="email" value="'.$_POST['email'].'" /></p>';
    echo '<p>Birthdate: <input type="text" name="birthdate" value="'.$_POST['birthdate'].'" /> (mm-dd-yyyy)</p>';
    echo '<p>Consent Form? <input type="checkbox" name="consent_form" value="1" /> Photo Form? <input type="checkbox" name="photo_form" value="1" /></p>';
    echo '<p><input type="radio" name="emerg_form" value="yes_emerg" />Enter Emergency Information';

    echo '<input type="radio" name="emerg_form" value="no_emerg" />Enter Emergency Information Later</p>';

    echo '<p align="center"><input type="submit" name="Submit" value="Send Form" /></p>';
    echo '</form>';
    

}// end the else
