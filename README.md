# E-Commerce Website

A complete PHP-based e-commerce platform with user authentication, shopping cart, order management, and admin dashboard.

## Database Setup

1. Import the `ecommerce_db.sql` file into phpMyAdmin:
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create a new database called `ecommerce_db`
   - Import the SQL file

2. Database credentials (in `config/db.php`):
   - Host: localhost
   - User: root
   - Password: (empty)
   - Database: ecommerce_db

## Project Structure

```
ecommerce/
├── config/
│   └── db.php                 # Database connection
├── includes/
│   ├── header.php             # Navigation bar & page header
│   └── footer.php             # Page footer
├── admin/
│   ├── dashboard.php          # Admin dashboard
│   ├── manage-products.php    # Product management
│   ├── manage-orders.php      # Order management
│   ├── manage-customers.php   # Customer management
│   ├── add-product.php        # Add new product
│   ├── edit-product.php       # Edit product
│   ├── order-details.php      # Order details view
│   └── logout.php             # Admin logout
├── images/                    # Product images folder
├── index.php                  # Homepage with product listing
├── product-detail.php         # Product detail page
├── cart.php                   # Shopping cart
├── checkout.php               # Checkout page
├── order-confirmation.php     # Order success page
├── login.php                  # User login
├── register.php               # User registration
├── logout.php                 # User logout
├── user-dashboard.php         # User profile & orders
├── order-details.php          # Order details (user view)
├── edit-profile.php           # Edit user profile
└── README.md                  # This file
```

## Features

### Customer Features
- Browse products by category
- View product details
- Add products to cart
- Checkout and place orders
- User registration and login
- View order history
- Edit profile
- Track order shipments

### Admin Features
- Dashboard with statistics
- Manage products (add, edit, delete)
- Manage orders (view, update status)
- Manage customers
- View low stock alerts

## How to Use

### First Time Setup

1. **Initialize Database**: Visit `http://localhost/ecommerce/setup.php`
   - This adds password and admin columns to database
   - Sets password "12345678" for all users
   - Makes User ID 1 an admin

2. **Delete Setup Files**: Remove `setup.php`, `update-images.php`, and `migrate-auth.php`

### Access the Website

1. **Homepage**: http://localhost/ecommerce/
2. **Login**: http://localhost/ecommerce/login.php
   - Both customers and admins use same login page
   - Customers → redirected to homepage
   - Admins → redirected to admin dashboard

### User Registration & Login

1. **Register**: Click "Register" on homepage
2. **Credentials**:
   - Demo Customer: `jean.dupont@email.com` / `12345678`
   - Demo Admin: `jean.dupont@email.com` / `12345678`
3. **New Users**: Register with any password (6+ characters)
   - Automatically registered as customer
   - Can be promoted to admin by database query

### Shopping

1. Browse products or use category filter
2. Click "View Details" on a product
3. Add items to cart
4. Go to cart and review
5. Checkout (login required)
6. Enter delivery address
7. Place order
8. View order confirmation

### Admin Operations

1. Login to admin panel
2. View dashboard statistics
3. Manage products:
   - Add new products
   - Edit product details
   - Delete products
   - Monitor stock levels
4. Manage orders:
   - View all orders
   - Update order status
5. Manage customers:
   - View customer list
   - Delete customer accounts

## Database Tables

### clients
- `id`: Primary key
- `name`: Customer name
- `email`: Email address (unique)
- `adress`: Delivery address

### products
- `id`: Primary key
- `name`: Product name
- `description`: Product description
- `price`: Price
- `category_id`: Foreign key to categories
- `image`: Image filename
- `qtstock`: Stock quantity

### categories
- `id`: Primary key
- `name`: Category name
- `description`: Category description

### orders
- `id`: Primary key
- `client_id`: Foreign key to clients
- `order_date`: Order date
- `status`: Order status (pending, confirmed, shipped, delivered, cancelled)
- `total_price`: Total order amount

### order_items
- `id`: Primary key
- `order_id`: Foreign key to orders
- `product_id`: Foreign key to products
- `quantity`: Item quantity
- `unit_price`: Price at time of order

### shipments
- `id`: Primary key
- `order_id`: Foreign key to orders (unique)
- `status`: Shipment status (preparing, shipping, delivered, cancelled)
- `date_ship`: Shipment date
- `adress_livraison`: Delivery address

## Bootstrap Components Used

- Navbar with responsive design
- Cards for product display
- Tables for data management
- Forms with validation
- Alerts and badges
- Buttons and modals
- Responsive grid layout

## Security Notes (For Production)

1. Use prepared statements to prevent SQL injection
2. Implement proper password hashing (bcrypt)
3. Add CSRF protection
4. Validate all user inputs
5. Use HTTPS for sensitive data
6. Implement proper session security
7. Add rate limiting for login attempts

## Troubleshooting

### "Connection failed" error
- Check database is running
- Verify credentials in `config/db.php`
- Ensure database is imported

### Products not showing
- Check products table has data
- Verify category IDs match
- Check file paths are correct

### Cart not working
- Enable sessions (check php.ini)
- Clear browser cookies
- Check session_start() in header.php

## Future Enhancements

- Payment gateway integration
- Email notifications
- Product reviews and ratings
- Wishlist functionality
- Advanced search and filters
- Inventory management
- Sales reports and analytics
- Multiple user roles

---

**Version**: 1.0
**Last Updated**: April 28, 2026
