<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ReviewHub Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="main-header">
        <h1>Welcome to ReviewHub</h1>
        <p>Your personal space to rate and review movies.</p>
    </header>
    <main class="container">
        <div class="form-container">
            <h2>Login</h2>
            <form id="loginForm" action="dashboard.php" method="POST">
                <div class="form-group">
                    <label>Username:</label>
                    <input type="text" id="login_username" name="login_username">
                </div>
                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" id="login_password" name="login_password">
                </div>
                <button type="submit" class="btn">Login</button>
                <p id="error-msg" class="error-msg" style="color:red; display:none; text-align:center; margin-top:10px;"></p> 
            </form>
        </div>
    </main>
    <footer class="main-footer"><p>&copy; 2025 ReviewHub.</p></footer>

    <script>
        document.getElementById("loginForm").addEventListener("submit", function(e) {
            e.preventDefault(); // Stop initially
            
            var user = document.getElementById("login_username").value.trim();
            var pass = document.getElementById("login_password").value.trim();
            var err = document.getElementById("error-msg");

            if (user === "") {
                err.innerText = "Username is required"; err.style.display = "block";
            } else if (pass === "") {
                err.innerText = "Password is required"; err.style.display = "block";
            } else if (pass.length < 8) {
                err.innerText = "Password must be 8+ chars"; err.style.display = "block";
            } else {
                // Valid! Submit the form manually
                this.submit();
            }
        });
    </script>
</body>
</html>