# NexaCart — Clothing Brand E-Commerce Website
### Web Technologies (23CSE404) | Capstone Project

---

## Project Overview
NexaCart is a fully functional, multi-page e-commerce web application for a premium clothing brand. Built as a capstone project for the Web Technologies course (23CSE404), it demonstrates real-world integration of HTML, CSS, JavaScript, PHP (server-side placeholder), and MySQL (database-ready).

---

## Live Demo
> Deploy `index.html` via GitHub Pages. PHP pages require a live host (InfinityFree, 000webhost, etc.)

---

## Pages Included
| Page | File | Description |
|------|------|-------------|
| Home | `index.html` | Hero, categories, featured products, testimonials |
| Shop | `products.html` | Full product grid with filters, sort, and modal |
| Cart | `cart.html` | Cart management, order summary, checkout flow |
| Login / Register | `login.html` | Auth forms with JS validation |
| About | `about.html` | Brand story, stats, values, team |
| Contact | `contact.html` | Contact form, FAQ accordion, info |

---

## Technologies Used
- **HTML5** — Semantic structure, accessibility, forms
- **CSS3** — Custom Properties, Flexbox, Grid, Animations, Responsive Design
- **JavaScript (ES6+)** — DOM manipulation, localStorage cart, form validation, dynamic rendering
- **PHP (backend)** — Form handling, session management, file uploads *(requires PHP host)*
- **MySQL** — Product listings, user auth, contact submissions *(via PHP backend)*
- **Google Fonts** — Cormorant Garamond + DM Sans

---

## Features
- ✅ Responsive design (desktop, tablet, mobile)
- ✅ Dynamic product catalog with real-time filtering & search
- ✅ Shopping cart with localStorage persistence
- ✅ Quantity management & cart totals
- ✅ Coupon code system
- ✅ Checkout modal flow
- ✅ Client-side form validation (login, register, contact)
- ✅ Password strength indicator
- ✅ FAQ accordion
- ✅ Newsletter subscription
- ✅ Toast notifications
- ✅ Product quick-add & detail modal
- ✅ Mobile hamburger navigation
- ✅ Animated marquee strip

---

## PHP Backend (backend/ folder — requires hosting)
```
backend/
  login.php        — Session-based login
  register.php     — User registration + MySQL insert
  contact.php      — Contact form handler + email
  products.php     — MySQL product fetch API
  upload.php       — File upload handler
  config.php       — DB connection
```

---

## MySQL Schema
```sql
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  email VARCHAR(150) UNIQUE,
  password VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(200),
  category VARCHAR(100),
  price DECIMAL(10,2),
  original_price DECIMAL(10,2),
  image_url TEXT,
  badge VARCHAR(50),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE contacts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  email VARCHAR(150),
  subject VARCHAR(200),
  message TEXT,
  submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## Setup & Installation

### Frontend Only (GitHub Pages)
1. Clone the repo
2. Open `index.html` in a browser
3. All JS features work without a server

### With PHP Backend
1. Upload all files to a PHP host (InfinityFree, 000webhost, XAMPP)
2. Create a MySQL database and run the schema above
3. Update `backend/config.php` with your DB credentials
4. Visit your hosted URL

---

## Evaluation Criteria Coverage
| Criteria | Implementation |
|---|---|
| Design & UI (HTML+CSS) | Custom design system, responsive layout, CSS Grid/Flexbox, animations |
| JavaScript / DHTML | Dynamic product rendering, cart logic, form validation, modal, accordion, marquee |
| PHP Server-side | Login/Register forms (PHP-ready), contact handler, session management |
| Database (MySQL) | Schema for users, products, contacts; CRUD operations |
| GitHub + Deployment | GitHub Pages (frontend), PHP host (backend), this README |
| Viva / Demo | Clean, commented code; logical flow throughout |

---

## Author
**Student Name** | Roll No. | 23CSE404  
Instructor: Mir Junaid Rasool

---

*Built with ❤️ for the NexaCart brand.*
