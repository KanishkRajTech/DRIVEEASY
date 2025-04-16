# 🚗 DriveEasy – Vehicle Rental Services

**DriveEasy** is a full-featured vehicle rental service web application that allows users to rent vehicles easily based on their preferences. From cars to bikes, users can browse, filter, locate, and book vehicles seamlessly. It also includes admin/partner features to manage vehicle listings.

---

## ✨ Features

- ✅ **Dynamic Vehicle Listings**
- 🎯 **Vehicle Filtering** – by price, type, and availability
- 📍 **Location-Based Search**
- 💳 **Payment Integration via Razorpay**
- 👥 **User & Partner Portals** – Register, login, and manage vehicles
- 🗺️ **Map View** – Visual location of available vehicles

---

## 🚀 Getting Started

To use or run this project locally, follow these steps:

### 1. Clone the Repository

```bash
git clone https://github.com/KanishkRajTech/DRIVEEASY
```

Copy the entire project folder into your XAMPP/htdocs directory.

### 2. Set Up the Database
- Locate the SQL file (usually named something like driveeasy.sql) in the project folder.
- Open phpMyAdmin.
- Create a new database (e.g., driveeasy).
- Import the SQL file into this database.

### 3. Configure Razorpay Payment Gateway
- Open the configuration .php file where Razorpay keys are set.
- Replace with your Razorpay API credentials:

```bash
$key_id = "your_razorpay_key_id";
$key_secret = "your_razorpay_key_secret";
```

### 4. Run the Project
Open your browser and go to:

```bash
http://localhost/DRIVEEASY
```


## 🛠️ Tech Stack
- PHP
- MySQL
- HTML/CSS
- JavaScript
- Razorpay API
- Open Street Map API
- Leaf let API
- SQL

## 📌 Notes
- Make sure your local server (XAMPP) is running.
- Ensure both PHP and MySQL services are active.
- Customize styling and add validations as per your use case.

## 🤝 Contributing
Feel free to fork this repo, make changes, and submit a pull request. Suggestions, feature requests, and improvements are always welcome!

