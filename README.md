A web application for booking bus tickets online, built with PHP, HTML, CSS and a MySQL database. Users register once, log in, and book, view or cancel tickets, while the admin manages every booking from a separate panel.

<img width="335" height="156" alt="image" src="https://github.com/user-attachments/assets/8619f42a-0768-46d7-8a39-b3acc46975fb" />
<img width="335" height="156" alt="image" src="https://github.com/user-attachments/assets/b05b56a6-acb7-4922-8b16-e5c89f3f1ed2" />


📋 Table of Contents
What is this Project?
Key Features
How it Works: End-to-End Flow
Architecture Overview
Modules
Database Design
Data Model
Input Validation
Pricing Logic
Installation & Running
Tech Stack
User Roles
Documentation
Limitations
Future Scope
🎯 What is this Project?

Your BUS is a bus booking website that replaces the manual work of recording customer details and ticket bookings. A user registers once, and from then on, simply logging in fetches all their saved details from the database, so there is no need to enter the same information again and again.

The system supports two access levels, User and Admin. Customers can book, view and cancel tickets from anywhere at any time, while the admin can see every booking and also act on behalf of offline customers. All data is stored in a MySQL database.

✨ Key Features
Feature	Details
🔐 Role-based Access	Separate logins for User (self-registered) and Admin (fixed credentials)
📝 User Registration	Unique username per user, details saved in the database
🎟️ Book Ticket	Choose route (source and destination), travel date and number of tickets
💳 Payment Step	A booking is successful only after payment is completed
🔎 View Ticket	Look up a ticket using its booking ID
❌ Cancel Ticket	Users can cancel their own booking
📊 Booking Reports	Admin can view all bookings and filter by date
🧾 Offline Bookings	Admin can book or cancel tickets for walk-in customers
💰 Automatic Fare	Total price is calculated from the route and number of tickets
✅ Data Integrity	Unique constraints and foreign keys keep records consistent
🔄 How it Works: End-to-End Flow
┌─────────────────────────────────────────────────────────────────┐
│                          WEBSITE START                           │
└─────────────────────────────────────────────────────────────────┘

1. CHOOSE LOGIN TYPE
   ├── User  — register (unique username) or log in
   └── Admin — log in with fixed credentials

┌─────────────────────────────────────────────────────────────────┐
│                            USER FLOW                             │
└─────────────────────────────────────────────────────────────────┘
   1. Log in               → saved details are fetched from MySQL
   2. Select bus & date    → source, destination, travel date
   3. Enter tickets        → total fare is calculated
   4. Make payment         → card details validated
   5. Booking confirmed    → booking ID generated
   6. View ticket          → using booking ID
   7. Cancel ticket        → if plans change

┌─────────────────────────────────────────────────────────────────┐
│                           ADMIN FLOW                             │
└─────────────────────────────────────────────────────────────────┘
   1. View report of all bookings
   2. View bookings of a particular date
   3. Book a ticket for an offline customer
   4. Cancel a booking
🏗️ Architecture Overview

The system is a PHP web application split into five modules. The browser talks to PHP pages served by Apache, and PHP reads and writes all data in a MySQL database.

┌────────────────────────────────────────────────────────┐
│               Browser (HTML + CSS front end)             │
│             User pages          Admin pages              │
└───────────────────────────┬──────────────────────────────┘
                            │ HTTP requests
                            ▼
┌────────────────────────────────────────────────────────┐
│                PHP Application (Apache / XAMPP)          │
│  ├── User Module            — register, login, profile   │
│  ├── Admin Module           — reports, book, cancel      │
│  ├── Bus & Schedule Module  — buses, routes, seats       │
│  ├── Booking Module         — book, view, cancel         │
│  └── Payment Module         — payment and total cost     │
└───────────────────────────┬──────────────────────────────┘
                            │ SQL queries
                            ▼
┌────────────────────────────────────────────────────────┐
│                     MySQL Database                       │
│   Users · Buses · Availability · Bookings · Payments     │
└────────────────────────────────────────────────────────┘
🧩 Modules
Module	Responsibility
👤 User Module	Registration, login, profile management and validations (age, Aadhaar, etc.)
🛠️ Admin Module	View all bookings, view bookings by date, book for users, cancel tickets, generate reports
🚍 Bus & Schedule Module	Manages bus number, name, route and seat availability for specific dates
🎫 Booking Module	Lets users select buses and book tickets, storing booking details with user and bus references
💳 Payment Module	Handles payment processing, validates card details and expiry date, and calculates the total cost
🗄️ Database Design

The database is built around five related tables:

Table	Key Columns	Relationship
Users	user_id (PK), username (UNIQUE), aadhaar (UNIQUE)	One-to-many with Bookings
Buses	bus_id (PK), bus_name, route	One-to-many with Bookings and Availability
Availability	id (PK), bus_id (FK), travel_date, seats_available	Foreign key to Buses
Bookings	booking_id (PK), user_id (FK), bus_id (FK), travel_date, total_price	Foreign keys to Users and Buses
Payments	payment_id (PK), booking_id (FK), amount, status	Foreign key to Bookings
📦 Data Model

The customer record stores the following fields:

Field	Type	Size	Description
user_id	number	11	Customer ID (primary key)
name	string	100	Customer name
phone	number	10	Customer contact number
address	string	255	Customer address
age	number	11	Customer age
pincode	number	6	Pincode where the customer lives
city	string	100	City where the customer lives
aadhar	number	12	Aadhaar card number
user_name	string	100	Login username (unique)
password	string	255	Login password
country	string	100	Country where the customer lives
travel_date	date	-	Date of travel chosen by the customer
✅ Input Validation

The application validates data at several points:

Registration: the username must be unique, and details such as age and Aadhaar are validated
Aadhaar: must be unique and 12 digits long
Phone number: 10 digits
Payment: card details and expiry date are validated before the booking is confirmed
Seat availability: tracked per bus and per travel date
💰 Pricing Logic

When booking, the user selects a route and the number of tickets. The total price is generated automatically:

Total Price = Fare for selected route × Number of tickets

The booking is marked successful only after the payment is completed, and the amount and status are saved in the Payments table.

🚀 Installation & Running

Prerequisites: XAMPP (Apache + MySQL + PHP) on Windows 10 or any compatible OS.

bash
# 1. Clone the repository
git clone https://github.com/kamakshi-13/your-bus-booking-system.git

# 2. Move the project folder into XAMPP's web directory
#    Windows: C:\xampp\htdocs\your-bus-booking-system
Open the XAMPP Control Panel and start Apache and MySQL.
Open phpMyAdmin (http://localhost/phpmyadmin) and create a new database.
Import the provided .sql file into that database.
Update the database connection settings (host, username, password, database name) in the project's config file.
Open http://localhost/your-bus-booking-system in your browser.
🛠️ Tech Stack
Layer	Technology	Purpose
Frontend	HTML, CSS	Pages and styling
Backend	PHP	Application logic and server-side processing
Database	MySQL	Storing, fetching, checking and updating data
Local Server	XAMPP (Apache)	Running the application locally
Editor	Notepad++	Code editing

Minimum requirements: any normal processor and 500 MB RAM.

👥 User Roles

Admin (fixed credentials)

View a report of all bookings
View bookings of a particular date
Book tickets for offline customers
Cancel bookings

User (registers with a unique username)

Register and log in
Book a ticket and pay for it
View a ticket using the booking ID
Cancel a ticket
📄 Documentation

The project includes the following analysis and design documents:

ER Diagram
Data Flow Diagrams (Level 0 and Level 1 for User and Admin)
Use Case Diagram
Data Dictionary
Modularization details and database constraints
⚠️ Limitations
Admin credentials are fixed, which is not secure for real-world use
Sensitive details such as Aadhaar numbers are stored in the database and need stronger protection before real deployment
Runs on a local XAMPP setup and has not been deployed to a live server
No email or SMS confirmation after booking
🔮 Future Scope
Replace fixed admin credentials with a proper authentication system
Integrate a real payment gateway
Add seat selection and an e-ticket download (PDF)
Send booking confirmations by email or SMS
Let the admin add and manage buses and routes from the panel
Make the interface fully responsive for mobile
Deploy the application on a live server

Author: Kamakshi Arora
