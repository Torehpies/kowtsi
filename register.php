<?php include('server.php')?>
<!DOCTYPE html>
<html>
<head>
    <link rel= "stylesheet" href="login_register.css">
    <title>Kowtsi | Register</title>
    <link rel="shortcut icon" href="img/kowtsi_logo.ico" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
</head>
<body>
    <section>
        <div class= "form-box">
            <div class="form-value">
                <form action= "register.php" method = "post">

                    <h2><div class="K"> <img src="img/kowtsi_logo.ico" width = "70px" height="70px"></div>Register</h2>

                    <?php include('errors.php'); ?>

                    <div class="input-box">
                        <input type="text" id = "username" name = "username" required>
                        <label for="username">Username</label>
                    </div>
                    <div class="input-box">
                        <input type="email" id = "email" name = "email" required>
                        <label for="email">Email</label>
                    </div>
                    <div class="input-box">
                        <input type="password" minlength = "8" maxlength = "16" id = "password" name = "password_1" required>
                        <label for="password">Password</label>
                    </div>

                    <div class="input-box">
                        <input type="password" minlength = "8" maxlength = "16" id = "password" name = "password_2" required>
                        <label for="password">Confirm Password</label>
                    </div>

                    <button type = "submit" name = "reg_user">Register</button>
                    <p>Already have an account? <a href = "login.php" class = "login">Login</a></p>
                </form>
                
            </div>
        </div>

        
    </section>

    
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>



</body>
</html>
