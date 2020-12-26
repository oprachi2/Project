<?php
include "db.php";
require_once("db.php");
if (!$_POST) 
{
$display_block = <<<END_OF_BLOCK
<form method="POST" action="$_SERVER[PHP_SELF]">
<p><label for="email">Your E-Mail Address:</label><br/>
<input type="email" id="email" name="email" size="40" maxlength="150" /></p>
<fieldset>
<legend>Action:</legend><br/>
<input type="radio" id="action_sub" name="action" value="sub" checked />
<label for="action_sub">subscribe</label><br/>
<input type="radio" id="action_unsub" name="action" value="unsub" />
<label for="action_unsub">unsubscribe</label>
</fieldset>
<button type="submit" name="submit" value="submit">Submit</button>
</form>
END_OF_BLOCK;
} 
else if (($_POST) && ($_POST['action'] == "sub")) 
{
if ($_POST['email'] == "") {
header("Location: manage.php");
exit;
} 
else {
doDB();
emailChecker($_POST['email']);
if (mysqli_num_rows($check_res) < 1) 
{
mysqli_free_result($check_res);
$add_sql = "INSERT INTO subscribers (email) VALUES('".$safe_email."')";
$add_res = mysqli_query($mysqli, $add_sql) or die(mysqli_error($mysqli));
$display_block = "<p>Thanks for signing up!</p>";
mysqli_close($mysqli);
} 
else {
$display_block = "<p>You’re already subscribed!</p>";
}
}
}
else if (($_POST) && ($_POST['action'] == "unsub")) {
if ($_POST['email'] == "") {
header("Location: manage.php");
exit;
} else {
doDB();
emailChecker($_POST['email']);
if (mysqli_num_rows($check_res) < 1) {
mysqli_free_result($check_res);
$display_block = "<p>Couldn’t find your address!</p>
<p>No action was taken.</p>";
} else {
 while ($row = mysqli_fetch_array($check_res)) {
 $id = $row['id'];
 }
 $del_sql = "DELETE FROM subscribers WHERE id = ".$id;
 $del_res = mysqli_query($mysqli, $del_sql)
 or die(mysqli_error($mysqli));
 $display_block = "<p>You’re unsubscribed!</p>";
 }
 mysqli_close($mysqli);
 }
 }
 ?>
 <!DOCTYPE html>
 <html>
 <head>
 <title>Subscribe/Unsubscribe to a Mailing List</title>
 <style>
body {
  background-image: url('bg.jpg');
}
</style>
 </head>
 <body>
 <h1>Subscribe/Unsubscribe to a Mailing List</h1>
<?php echo "$display_block"; ?>
 </body>
 </html>