<?php

    session_start();
    if (!array_key_exists("username", $_SESSION))
    {
?>
<form method="post" action="login.php">
    <label>Username: </label><input type="text" name="username"/>
    <label>Password: </label><input type="text" name="password"/>
    <input type="submit" />
</form>
<?php
    }
    else
    { 
?>
    Welcome, <?php echo $_SESSION['username']; ?> <a href="logout.php" >logout</a>
<?php
    }
?>

