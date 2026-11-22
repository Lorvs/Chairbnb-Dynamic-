<?php
require 'db.php';
$pageTitle='Furniture';
$stmt=$pdo->query("SELECT * FROM products ORDER BY id");
$products=$stmt->fetchAll(PDO::FETCH_ASSOC);
require 'header.php';
?>
<section class="products">
<h2>Our Furniture</h2><p>Click a furniture image to view it larger.</p>
<div class="product-container">
<?php foreach($products as $product): ?>
<div class="product-card">
<img src="<?= e($product['image']) ?>" alt="<?= e($product['name']) ?>" onclick="zoomImage(this)">
<h3><?= e($product['name']) ?></h3><p><?= e($product['description']) ?></p>
<p class="price">₱<?= number_format($product['price'],2) ?></p>
<form action="cart.php" method="post">
<input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
<input type="hidden" name="action" value="add">
<button type="submit">Add to Cart</button>
</form>
<form action="cart.php" method="post">
<input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
<input type="hidden" name="action" value="buy">
<button type="submit" class="secondary">Buy Now</button>
</form>
<a class="inquire-link" href="inquiry.php?product=<?= urlencode($product['name']) ?>">Ask about this item</a>
</div>
<?php endforeach; ?>
</div>
</section>
<div id="imageModal" class="modal" onclick="closeImage()"><span>&times;</span><img id="zoomedImage" src="" alt="Zoomed furniture"></div>
<script src="script.js"></script>
<?php require 'footer.php'; ?>
