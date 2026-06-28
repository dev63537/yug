# EventPro – Event Management Portal
> BCA Semester 5 Major Project

EventPro is a comprehensive, production-ready Full-Stack PHP & MySQL Event Management Portal designed and built for BCA Semester 5 major project submission.

---

## 🚀 Features & Modules

### 👤 User Roles
1. **Guest**: Browse homepage, filter events by category, view detailed schedules and pricing.
2. **Registered User**: Profile management, ticket checkout flow, booking history, PDF-like ticket view, password management.
3. **Event Organizer**: Organizer dashboard, publish new events with custom pricing and capacity, monitor tickets sold and revenue reports.
4. **Administrator**: Full system oversight, user management, event approvals, booking ledger, master system reports.

---

## 🛠️ Technology Stack
- **Backend**: PHP 8+ (PDO, Singleton pattern, CSRF & XSS protection, Prepared Statements)
- **Database**: MySQL (Normalized schema, foreign key constraints)
- **Frontend**: HTML5, CSS3, Bootstrap 5, Font Awesome 6, Vanilla JavaScript
- **Server**: XAMPP / Apache

---

## ⚙️ Installation & Setup (Localhost / XAMPP)

1. **Copy Project Folder**:
   Place the project directory under your XAMPP `htdocs` folder:
   `C:\xampp\htdocs\YUG\`

2. **Database Import**:
   - Start Apache and MySQL in XAMPP Control Panel.
   - Open phpMyAdmin (`http://localhost/phpmyadmin`).
   - Create a new database named `eventpro`.
   - Import the provided SQL file: `c:\xampp\htdocs\YUG\database\eventpro.sql`.

3. **Run Application**:
   Open your browser and navigate to:
   `http://localhost/YUG/`

---

## 🔑 Demo Credentials

| Role | Email | Password |
|---|---|---|
| **Admin** | `admin@eventpro.com` | `Admin@123` |
| **Organizer** | `organizer@eventpro.com` | `Org@123` |
| **User** | `user@eventpro.com` | `User@123` |

---

## 📂 Project Directory Structure

```text
/YUG
├── /admin             # Admin panel views & actions
├── /assets            # CSS, JavaScript & styling assets
├── /booking           # Ticket booking checkout & payment gateway flow
├── /config            # Database connection & application configuration
├── /database          # SQL schema & seed scripts
├── /includes          # Global header, footer, authentication & security helpers
├── /organizer         # Event organizer dashboard & event publishing tools
├── /user              # User portal & booking management
├── /uploads           # Media uploads directory
├── index.php          # Portal homepage
├── events.php         # Master events listing & search
├── event-details.php # Detailed event view
├── login.php          # User authentication
└── register.php       # Account creation
```
