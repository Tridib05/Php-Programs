# E-Commerce Catalog - Getting Started Guide

Follow this step-by-step guide to set up and use the e-commerce system.

## ✅ Step 1: Import Database (MUST DO FIRST)

### Option A: phpMyAdmin (Easiest)
1. Open http://localhost/phpmyadmin
2. Click **Import** tab at top
3. Click **Choose File**
4. Navigate to: `c:\xampp\htdocs\Tridib\Ecommerce-Catalog\setup.sql`
5. Click **Go** at bottom
6. Wait for success message ✓

### Option B: MySQL Command Line
1. Open PowerShell
2. Run this command:
   ```powershell
   mysql -u root < 'c:\xampp\htdocs\Tridib\Ecommerce-Catalog\setup.sql'
   ```
3. Should see no errors

---

## ✅ Step 2: Verify Setup

Visit: **http://localhost/Tridib/Ecommerce-Catalog/setup_test.php**

You should see:
- ✓ Database connected successfully
- ✓ categories table exists
- ✓ products table exists
- ✓ orders table exists
- ✓ order_items table exists
- ✓ images/products folder is writable
- ✓ Sample data (5 categories, 8 products)

If anything shows ✗ FAIL, re-run setup.sql

---

## ✅ Step 3: Test Public Store

### Home Page
Visit: **http://localhost/Tridib/Ecommerce-Catalog/**

You should see:
- Hero banner
- Featured products section
- Category links in header

### Products Page
Click **All Products** in navigation

You should see:
- Product grid with images
- Product names and prices
- Pagination (if many products)
- Filter by category

### Search
Enter a product name in search box (e.g., "Headphones")

You should see:
- Matching products appear
- Filters work correctly

### Product Detail
Click any product

You should see:
- Full product information
- Product image
- Price and stock status
- **Add to Cart** button

---

## ✅ Step 4: Test Shopping Cart

### Add Item
1. On any product page, enter quantity
2. Click **Add to Cart**
3. You'll be redirected to cart page

### View Cart
You should see:
- All items added
- Quantity columns
- Price calculation
- **Proceed to Checkout** button

### Update Cart
1. Change quantities
2. Click **Update Cart**
3. Totals recalculate

### Remove Item
Click **Remove** next to any item

---

## ✅ Step 5: Test Checkout

### Complete Checkout
1. Click **Proceed to Checkout**
2. Fill in shipping form:
   - Full Name: (enter your name)
   - Email: (enter valid email)
   - Phone: (optional)
   - Address: (enter address)
3. Click **Place Order**

### Success
You should see:
- Success message
- Order confirmation
- Link back to store

### Verify Order
1. Go to Admin Panel
2. Check Orders page
3. Your order should appear

---

## ✅ Step 6: Admin Panel Access

### Admin Login
Visit: **http://localhost/Tridib/Ecommerce-Catalog/admin/login.php**

Enter:
- Username: **admin**
- Password: **admin123**

You should see the Admin Dashboard

---

## ✅ Step 7: Test Admin Features

### Dashboard
You should see:
- Total Products count
- Total Categories count
- Total Orders count
- Total Revenue
- Recent Orders list
- Low Stock alerts

### Add New Product
1. Click **Products** in sidebar
2. Click **Add Product** button
3. Fill form:
   - Name: "Test Product"
   - Price: 99.99
   - Stock: 50
   - Category: Select one
   - Image: (upload JPG/PNG, optional)
   - Check "Active" box
4. Click **Add Product**

You should see success message

### Edit Product
1. From Products page, click **Edit** on any product
2. Modify fields (e.g., price, stock)
3. Click **Update Product**

You should see updated product in store

### Delete Product
1. From Products page, click **Delete** on any product
2. Confirm deletion

Product should disappear from store

### Manage Categories
1. Click **Categories** in sidebar
2. Add new category:
   - Name: "New Category"
   - Click **Add Category**
3. View existing categories
4. Delete unused categories

### View Orders
1. Click **Orders** in sidebar
2. See all customer orders
3. View customer info and totals

### Logout
1. Click **Logout** in sidebar
2. You'll return to login page

---

## 🧪 Test Cases to Try

### Test 1: Purchase Flow
- [ ] Browse products
- [ ] Add 2-3 items to cart
- [ ] Go to checkout
- [ ] Enter shipping info
- [ ] Place order
- [ ] See order in admin

### Test 2: Product Management
- [ ] Create new product with image
- [ ] Edit product (change price)
- [ ] Delete unused product
- [ ] Verify changes in store

### Test 3: Category Management
- [ ] Add new category
- [ ] Create product in category
- [ ] Filter by category in store
- [ ] Delete category

### Test 4: Search & Filter
- [ ] Search for product by name
- [ ] Filter by category
- [ ] Sort by price
- [ ] Pagination between pages

### Test 5: Cart Operations
- [ ] Add same product twice
- [ ] Remove item from cart
- [ ] Update quantities
- [ ] Clear cart by checkout

### Test 6: Stock Management
- [ ] Check low stock alert in admin
- [ ] Edit product stock
- [ ] Monitor stock levels

---

## 🔧 Configuration

### Change Admin Password
Edit `admin/login.php` (line ~10):
```php
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'admin123');  // ← Change this
```

### Change Database Credentials
Edit `config/database.php`:
```php
private $host = "127.0.0.1";     // MySQL host
private $username = "root";       // MySQL user
private $password = "";           // MySQL password (add if needed)
private $database = "ecommerce_catalog";
```

### Change Store Name
Edit `includes/header.php` (line ~20):
```html
<a class="navbar-brand" href="index.php">
    <i class="fas fa-store"></i> E-Shop  <!-- ← Change "E-Shop" -->
</a>
```

---

## 📋 Important Notes

1. **Shopping Cart**: Stored in browser session (not persistent across browser close)
2. **Images**: Stored in `images/products/` folder
3. **Orders**: Saved to database permanently
4. **Admin**: Demo credentials should be changed before production
5. **Database**: Use strong password before deploying online
6. **HTTPS**: Always use HTTPS in production (not just HTTP)

---

## ❓ Troubleshooting

### Issue: "Database connection failed"
**Solution**: 
- Start MySQL from XAMPP Control Panel
- Check credentials in `config/database.php`
- Verify database name is `ecommerce_catalog`

### Issue: Images not uploading
**Solution**:
- Check `images/products/` folder exists
- Make folder writable (right-click → Properties → Security)
- Image must be JPG, PNG, or GIF
- File size must be under 5MB

### Issue: Admin login doesn't work
**Solution**:
- Use exact credentials: admin / admin123
- Clear browser cache (Ctrl + Shift + Delete)
- Check PHP sessions are enabled
- Try different browser

### Issue: Products not showing in store
**Solution**:
- Check products have `is_active = 1` in database
- Verify product category exists
- Reload page (Ctrl + F5)
- Check browser console for errors

### Issue: Cart not working
**Solution**:
- Enable browser cookies and JavaScript
- Check browser console (F12) for errors
- Try different browser
- Clear browser cache

---

## 📞 Quick Support

**Setup Check**: http://localhost/Tridib/Ecommerce-Catalog/setup_test.php

**Documentation**: See README.md and COMPLETION_REPORT.md

**Database**: Use phpMyAdmin to view/manage data

**Browser Console**: Press F12 to debug JavaScript issues

---

## 🎉 You're Ready!

Your e-commerce system is fully functional. 

**Start with:**
1. ✅ Browse the store at **index.php**
2. ✅ Add products via admin dashboard
3. ✅ Test checkout process
4. ✅ Manage orders

Enjoy your e-commerce platform! 🚀

---

**Next Steps (Optional):**
- Customize colors and branding
- Add more product categories
- Upload product images
- Monitor orders and revenue
- Regular database backups

For advanced features like payment processing, email notifications, or customer accounts, see README.md section "Next Steps (Optional Enhancements)"
