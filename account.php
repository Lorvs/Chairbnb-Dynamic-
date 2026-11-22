<?php
require 'db.php';
$pageTitle='Account';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $action=$_POST['action'] ?? '';
    if ($action==='signup') {
        $name=trim($_POST['name'] ?? '');
        $email=trim($_POST['email'] ?? '');
        $password=$_POST['password'] ?? '';
        $confirm=$_POST['confirm'] ?? '';
        if (!$name || !$email || !$password || !$confirm) $_SESSION['notice']="Please fill in all fields.";
        elseif (!filter_var($email,FILTER_VALIDATE_EMAIL)) $_SESSION['notice']="Please enter a valid email.";
        elseif (strlen($password)<6) $_SESSION['notice']="Password must be at least 6 characters.";
        elseif ($password!==$confirm) $_SESSION['notice']="Passwords do not match.";
        else {
            try {
                $stmt=$pdo->prepare("INSERT INTO users (name,email,password) VALUES (?,?,?)");
                $stmt->execute([$name,$email,password_hash($password,PASSWORD_DEFAULT)]);
                $_SESSION['user_id']=$pdo->lastInsertId(); $_SESSION['user_name']=$name;
                $_SESSION['notice']="Account created successfully. Welcome!";
                header("Location: index.php"); exit;
            } catch (PDOException $e) { $_SESSION['notice']="That email is already registered."; }
        }
    } elseif ($action==='login') {
        $email=trim($_POST['email'] ?? ''); $password=$_POST['password'] ?? '';
        $stmt=$pdo->prepare("SELECT * FROM users WHERE email=?"); $stmt->execute([$email]); $user=$stmt->fetch();
        if ($user && password_verify($password,$user['password'])) {
            session_regenerate_id(true); $_SESSION['user_id']=$user['id']; $_SESSION['user_name']=$user['name'];
            $_SESSION['notice']="Sign in successful. Welcome back!";
            header("Location: index.php"); exit;
        } else $_SESSION['notice']="Incorrect email or password.";
    }
    header("Location: account.php"); exit;
}
if (!empty($_GET['message'])) $_SESSION['notice']=$_GET['message'];
require 'header.php';
?>
<section class="account">
<div class="form-box">
<h2>Sign In</h2>
<form method="post">
<input type="hidden" name="action" value="login">
<label>Email</label><input type="email" name="email" placeholder="Enter your email" required>
<label>Password</label><input type="password" name="password" placeholder="Enter your password" required>
<button type="submit">Sign In</button>
</form>
</div>
<div class="form-box">
<h2>Create Account</h2>
<form method="post">
<input type="hidden" name="action" value="signup">
<label>Full Name</label><input type="text" name="name" placeholder="Enter your name" required>
<label>Email</label><input type="email" name="email" placeholder="Enter your email" required>
<label>Password</label><input type="password" name="password" minlength="6" placeholder="Create a password" required>
<label>Confirm Password</label><input type="password" name="confirm" minlength="6" placeholder="Confirm your password" required>
<button type="submit">Sign Up</button>
</form>
</div>
</section>
<?php require 'footer.php'; ?>
