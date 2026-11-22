<?php
require 'db.php'; $pageTitle='Inquiry';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $name=trim($_POST['name']??''); $email=trim($_POST['email']??'');
    $product=trim($_POST['product']??''); $message=trim($_POST['message']??'');
    if (!$name||!filter_var($email,FILTER_VALIDATE_EMAIL)||!$product||!$message) $_SESSION['notice']="Please complete all fields with a valid email.";
    else {
        $stmt=$pdo->prepare("INSERT INTO inquiries (user_id,name,email,product,message) VALUES (?,?,?,?,?)");
        $stmt->execute([$_SESSION['user_id']??null,$name,$email,$product,$message]);
        $_SESSION['notice']="Your inquiry was sent successfully!";
    }
    header("Location: inquiry.php"); exit;
}
$items=$pdo->query("SELECT name FROM products ORDER BY name")->fetchAll(PDO::FETCH_COLUMN);
$selected=$_GET['product']??'';
require 'header.php';
?>
<section class="inquiry"><div class="form-box">
<h2>Send an Inquiry</h2><p>Interested in our furniture? Send us a message.</p>
<form method="post">
<label>Name</label><input name="name" value="<?= e($_SESSION['user_name']??'') ?>" required>
<label>Email</label><input type="email" name="email" required>
<label>Furniture</label><select name="product" required><option value="">Choose furniture</option>
<?php foreach($items as $item): ?><option value="<?= e($item) ?>" <?= $selected===$item?'selected':'' ?>><?= e($item) ?></option><?php endforeach; ?>
</select>
<label>Message</label><textarea name="message" placeholder="Write your inquiry" required></textarea>
<button type="submit">Send Inquiry</button>
</form></div></section>
<?php require 'footer.php'; ?>
