CHAIRBNB PHP + MYSQL PROJECT

CONTENTS
- index.php: Home
- products.php: products fetched from MySQL
- account.php: registration and sign in
- logout.php: sign out
- inquiry.php: inquiry form saving to database
- cart.php: session cart and demo order submission
- db.php: database connection settings
- chairbnb.sql: database structure and sample products
- style.css and script.js: styling and image zoom
- JPG assets: website images

HOW TO RUN (XAMPP)
1. Install/start Apache and MySQL from XAMPP Control Panel.
2. Extract the entire chairbnb_php folder into C:\\xampp\\htdocs\\
   (You can rename the folder chairbnb.)
3. Open http://localhost/phpmyadmin
4. Click Import, select chairbnb.sql, and import it.
5. If your MySQL credentials differ, edit db.php.
6. Visit http://localhost/chairbnb/ (or your folder's name).

NOTES
- This is a school-project demo, not a production store.
- Checkout creates a database order after a user signs in. It does not collect payment.
- Passwords are stored using PHP password_hash().
- Keep all files in the same folder, including the images.
