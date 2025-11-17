# E-Commerce Catalog System

A complete, production-ready PHP + MySQL e-commerce platform with product catalog, shopping cart, checkout, and admin dashboard.

## Features

✅ **Product Catalog**
- Browse products by category
- Search functionality
- Product filters and sorting
- Product detail pages with descriptions

✅ **Shopping Cart**
- Add/remove items
- Update quantities
- Real-time total calculation

✅ **Checkout System**
- Secure order placement
- Customer information collection
- Order confirmation

✅ **Admin Dashboard**
- Login system with demo credentials
- Product management (add, edit, delete)
- Category management
- Order tracking
- Dashboard statistics
- Stock monitoring

✅ **Security**
- Prepared statements (SQL injection prevention)
- Output escaping (XSS prevention)
- Input validation
- Password protected admin

✅ **Responsive Design**
- Bootstrap 5 framework
- Mobile-friendly interface
- Professional UI/UX

## Installation

### Step 1: Database Setup

**Using phpMyAdmin (Recommended):**
1. Open http://localhost/phpmyadmin
2. Click "Import" tab
3. Select `setup.sql` from the Ecommerce-Catalog folder
4. Click "Go"

**Using MySQL CLI:**
```powershell
cd 'c:\xampp\htdocs\Tridib\Ecommerce-Catalog'
mysql -u root < setup.sql
```

### Step 2: Verify Database Credentials

Edit `config/database.php` if needed:
```php
private $host = "127.0.0.1";      // MySQL host
private $username = "root";        // MySQL user
private $password = "";            // MySQL password
private $database = "ecommerce_catalog";
```

### Step 3: Check Setup

Visit: http://localhost/Tridib/Ecommerce-Catalog/setup_test.php

All checks should show **✓ OK**

### Step 4: Start Using

**Public Store:** http://localhost/Tridib/Ecommerce-Catalog/

**Admin Panel:** http://localhost/Tridib/Ecommerce-Catalog/admin/login.php
- Username: `admin`
- Password: `admin123`

## Folder Structure

```
Ecommerce-Catalog/
├── admin/                    # Admin pages
│   ├── login.php            # Admin login
│   ├── dashboard.php        # Dashboard with stats
│   ├── products.php         # Product list
│   ├── add_product.php      # Add new product
│   ├── edit_product.php     # Edit product
│   ├── delete_product.php   # Delete product
│   ├── categories.php       # Manage categories
│   ├── orders.php           # View orders
│   └── logout.php           # Admin logout
├── config/
│   └── database.php         # Database connection (edit credentials here)
├── includes/
│   ├── header.php           # Page header template
│   └── footer.php           # Page footer template
├── css/
│   └── style.css            # Custom styles
├── js/                      # JavaScript files
├── images/
│   └── products/            # Product images upload directory
├── index.php                # Home page
├── products.php             # All products page
├── product-detail.php       # Product detail page
├── search.php               # Search results page
├── cart.php                 # Shopping cart
├── checkout.php             # Checkout page
├── setup.sql                # Database schema
├── setup_test.php           # Setup verification
└── README.md                # This file
```

## Database Schema

### Categories Table
- id: Unique identifier
- name: Category name (unique)
- description: Category description
- created_at: Timestamp

### Products Table
- id: Unique identifier
- name: Product name
- description: Product description
- price: Product price (decimal)
- stock: Available quantity
- image: Image filename
- category_id: Foreign key to categories
- is_active: Visibility flag
- created_at, updated_at: Timestamps

### Orders Table
- id: Order number
- customer_name: Customer full name
- customer_email: Customer email
- customer_phone: Customer phone number
- shipping_address: Delivery address
- total_items: Number of items
- total_price: Order total
- status: pending, processing, completed, cancelled
- created_at, updated_at: Timestamps

### Order Items Table
- id: Line item ID
- order_id: Foreign key to orders
- product_id: Foreign key to products
- quantity: Item quantity
- price: Price at time of order

## Usage Guide

### For Customers

1. **Browse Products**
   - Visit http://localhost/Tridib/Ecommerce-Catalog/
   - Click "All Products" or browse by category
   - Use search to find specific items

2. **Add to Cart**
   - Select product and click "Add to Cart"
   - Adjust quantity as needed
   - View cart via shopping cart icon

3. **Checkout**
   - Review items in cart
   - Click "Proceed to Checkout"
   - Enter shipping information
   - Confirm order

### For Administrators

1. **Login**
   - Go to admin/login.php
   - Use: admin / admin123

2. **Manage Products**
   - Dashboard → Products
   - Add new products with images
   - Edit existing products
   - Delete obsolete products
   - Monitor stock levels

3. **Manage Categories**
   - Dashboard → Categories
   - Create new categories
   - Delete unused categories

4. **View Orders**
   - Dashboard → Orders
   - See all customer orders
   - Track order status

5. **Dashboard Stats**
   - Total products, categories, orders
   - Revenue tracking
   - Low stock alerts

## Security Notes

⚠️ **Before Production Deployment:**

1. **Admin Credentials**
   - Change demo password in `admin/login.php`
   - Or implement admin user database
   - Use `password_hash()` and `password_verify()`

2. **HTTPS**
   - Use SSL certificates
   - Never transmit sensitive data over HTTP

3. **Database**
   - Use strong database password
   - Limit database user permissions
   - Regular backups

4. **File Upload**
   - Already validates image types and sizes
   - Consider virus/malware scanning
   - Store uploads outside web root in production

5. **Validation**
   - Server-side input validation (already implemented)
   - Additional field-specific regex validation
   - CSRF token protection (recommended)

6. **Error Handling**
   - Don't expose database errors to users
   - Log errors securely
   - Use generic error messages

## Troubleshooting

### "Database connection failed"
- ✓ Check MySQL is running (XAMPP Control Panel)
- ✓ Verify credentials in `config/database.php`
- ✓ Ensure `ecommerce_catalog` database exists

### "Table not found" errors
- ✓ Run `setup.sql` again via phpMyAdmin
- ✓ Delete existing database and reimport

### Images not uploading
- ✓ Check `images/products/` folder exists and is writable
- ✓ Verify file size is under 5MB
- ✓ Check file type is JPG, PNG, or GIF

### Admin login issues
- ✓ Default: username `admin`, password `admin123`
- ✓ Clear browser cache/cookies
- ✓ Check PHP session support

### Products not showing
- ✓ Ensure `is_active = 1` in database
- ✓ Check category is linked correctly
- ✓ Verify product price > 0

## API Reference

### Database Class Methods

```php
// Query with prepared statements
$db->query($sql, $params);

// Fetch all results
$results = $db->fetchAll($sql, $params);

// Fetch single result
$result = $db->fetchOne($sql, $params);

// Insert record
$id = $db->insert('table_name', $data_array);

// Update record
$db->update('table_name', $data_array, 'where_column', $where_value);

// Delete record
$db->delete('table_name', 'where_column', $where_value);
```

## Sample Data

The system comes with sample data:
- **5 Categories**: Electronics, Clothing, Home & Garden, Sports, Books
- **8 Products**: Headphones, T-Shirt, Desk Lamp, Shoes, Book, Cable, Chair, Yoga Mat

Use this for testing before adding your own products.

## Performance Tips

1. Add product images in recommended sizes (500x500px)
2. Use descriptive product names and descriptions
3. Organize products into categories for better UX
4. Monitor low stock items from dashboard
5. Archive old orders periodically

## Customization

### Change Admin Colors
Edit `admin/login.php`, `admin/dashboard.php` - modify the `style` tags

### Add More Product Fields
1. Add column to `products` table in SQL
2. Update add/edit product forms
3. Modify product display pages

### Customize Checkout
Edit `checkout.php` to add/remove fields or payment methods

### Modify Store Logo/Name
Edit `includes/header.php` - change "E-Shop" brand

## Support & Maintenance

- Run `setup_test.php` to check system status
- Check browser console (F12) for JavaScript errors
- Monitor MySQL error logs
- Keep database backups

## Version & Updates

- **Version**: 1.0.0
- **Last Updated**: November 2025
- **MySQL**: 5.7+
- **PHP**: 7.0+

---

**Ready to use!** Follow the Installation steps above, then visit the store or admin panel.

For issues, run `setup_test.php` and check all items are green ✓
