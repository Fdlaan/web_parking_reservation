<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    
    <header>
        <a class="brand" href="../home.php">
        <span class="brand-mark">P</span>
        <span class="brand-name">Parking</span>
        </a>
        <nav>
        <a href="../index.php">Home</a>
        <a href="login.html" class="active">Login</a>
        <a href="register.html">Register</a>
        </nav>
    </header>
    
    <main>
        <div class="photo" role="img" aria-label="Parking garage entrance"></div>
    
        <section class="form-side">
            <div class="form-wrap">
                <h1>Login</h1>
                <form action="../backend/login.php" method="POST">
                <label for="identity">Email/Phone Number</label>
                <input type="text" id="identity" name="identity" placeholder="ex. user123@gmail.com/08123456789" required>
        
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
        
                <button type="submit">Login</button>
                </form>
        
                <div class="links">
                <a class="forgot" href="forgot-password.html">Forgot Password?</a><br>
                Don't have an account? <a href="register.html">Register here.</a>
                </div>
            </div>
        </section>
    </main>
    
    </body>
</html>