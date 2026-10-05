<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to LightWork</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/stylesfusion.css"
    
</head>
<body>
    

    <header>
        <div class="logo">
        <a href="index.php">
            <img src="assets/images/logo lightwork v0.2.png" alt="LightWork Logo">
        </a>
</div>
    </header>



    <main>

            <div class="contenedor__todo">
                <div class="caja__trasera">
                    <div class="caja__trasera-sign-in">
                        <h3>Welcome back!</h3>
                        <p>Login to keep connected with us</p>
                        <button id="btn__sign-in">Sign in</button>
                    </div>
                    <div class="caja__trasera-sign-up">
                        <h3>Hello, friend!</h3>
                        <p>Start your journey with us</p>
                        <button id="btn__sign-up">Sign up</button>
                    </div>
                </div>

                <!--formulario login register-->
                <div class="contenedor__sign-in-sign-up">

                    <form action="php/SI.php" method="POST" class="formulario__sign-in">

                        <h2>Sign in</h2>
                        <input type="text" placeholder="Username/Email" name="username">
                        <input type="password" placeholder="Password" name="password">
                        <button type="submit" name="submit_si">Sign in</button>

                    </form>

                    <form action="php/SU.php" method="POST" class="formulario__sign-up">

                        <h2>Sign up</h2>
                        <input type="text" placeholder="Email" name="email">
                        <input type="text" placeholder="Username" name="usuario">
                        <input type="password" placeholder="Password" name="contrasena">
                        <button type="submit" name="submit_su">Sign up</button>

                    </form>

                </div>

                <?php
                    if (isset($_GET["error"])){
                        if ($_GET["error"] == "emptyinput"){
                            echo "<p>Fill in all fields!</p>";
                        }
                        else if($_GET["error"] == "invalidusername"){
                            echo "<p>Choose a proper username</p>";
                        }
                        else if($_GET["error"] == "invalidemail"){
                            echo "<p>Choose a proper email</p>";
                        }
                        else if($_GET["error"] == "usernametaken"){
                            echo "<p>Username taken</p>";
                        }
                        else if($_GET["error"] == "none"){
                            echo "<p>You have signed up!</p>";
                        }
                        else if($_GET["error"] == "wronglogin"){
                            echo "<p>Wrong login information!</p>";
                        }
                    }
                ?>

            </div>

    </main>



<script src="assets/js/script.js"></script>

</body>







</html>