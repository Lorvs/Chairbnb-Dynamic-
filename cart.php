<?php
require 'db.php';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $id=(int)($_POST['product_id']??0); $action=$_POST['action']??'';
    if ($action==='add'||$action==='buy') {
        $check=$pdo->prepare("SELECT id FROM products WHERE id=?"); $check->execute([$id]);
        if ($check->fetch()) {
            $_SESSION['cart'][$id]=($_SESSION['cart'][$id]??0)+1;
            $_SESSION['notice']=$action==='buy'?"Item added. Review your cart below.":"Item added to your cart.";
        }
    } elseif ($action==='remove') { unset($_SESSION['cart'][$id]); $_SESSION['notice']="Item removed."; }
    elseif ($action==='update') {
        foreach($_POST['quantity']??[] as $pid=>$qty) {
            $pid=(int)$pid; $qty=max(0,min(99,(int)$qty));
            if ($qty===0) unset($_SESSION['cart'][$pid]); else $_SESSION['cart'][$pid]=$qty;
        }
        $_SESSION['notice']="Cart updated.";
    } elseif ($action==='checkout') {
        requireLogin();
        if (!empty($_SESSION['cart'])) {
            $pdo->beginTransaction();
            try {
                $stmt=$pdo->prepare("INSERT INTO orders (user_id,total) VALUES (?,0)");
                $stmt->execute([$_SESSION['user_id']]); $orderId=$pdo->lastInsertId(); $total=0;
                $productStmt=$pdo->prepare("SELECT * FROM products WHERE id=?");
                $itemStmt=$pdo->prepare("INSERT INTO order_items (order_id,product_id,quantity,unit_price) VALUES (?,?,?,?)");
                foreach($_SESSION['cart'] as $pid=>$qty) {
                    $productStmt->execute([$pid]); $p=$productStmt->fetch();
                    if($p){$itemStmt->execute([$orderId,$pid,$qty,$p['price']]);$total+=$p['price']*$qty;}
                }
                $update=$pdo->prepare("UPDATE orders SET total=? WHERE id=?");$update->execute([$total,$orderId]);
                $pdo->commit();unset($_SESSION['cart']);
                $_SESSION['notice']="Order #$orderId placed! (Demo checkout; no payment collected.)";
            } catch(Exception $e) { $pdo->rollBack(); $_SESSION['notice']="Unable to place order."; }
        }
    }
    header("Location: cart.php"); exit;
}
$pageTitle='Cart'; require 'header.php';
$cart=$_SESSION['cart']??[]; $total=0;
?>
<section class="products"><h2>Your Shopping Cart</h2>
<?php if(!$cart): ?><p>Your cart is empty. <a href="products.php">Browse furniture</a>.</p>
<?php else: ?>
<form method="post"><input type="hidden" name="action" value="update">
<table class="cart-table"><tr><th>Item</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th></th></tr>
<?php foreach($cart as $id=>$qty):
$stmt=$pdo->prepare("SELECT * FROM products WHERE id=?");$stmt->execute([$id]);$p=$stmt->fetch();if(!$p)continue;
$sub=$p['price']*$qty;$total+=$sub; ?>
<tr><td><?= e($p['name']) ?></td><td>₱<?= number_format($p['price'],2) ?></td>
<td><input class="qty" type="number" min="0" max="99" name="quantity[<?= (int)$id ?>]" value="<?= (int)$qty ?>"></td>
<td>₱<?= number_format($sub,2) ?></td><td><form method="post" action="cart.php"><input type="hidden" name="action" value="remove"><input type="hidden" name="product_id" value="<?= (int)$id ?>"><button class="danger" type="submit">Remove</button></form></td></tr>
<?php endforeach; ?>
</table><button type="submit">Update Cart</button></form>
<h3>Total: ₱<?= number_format($total,2) ?></h3>
<form method="post"><input type="hidden" name="action" value="checkout"><button type="submit">Place Demo Order</button></form>
<p class="small-note">Checkout is a class-project demo. It records an order but does not process payment.</p>
<?php endif; ?>
</section>
<?php require 'footer.php'; ?>
