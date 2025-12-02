# Clearit Technical Test – PHP/Laravel Solution

This repository contains a Laravel-based solution for the **Clearit Technical Dev Test**.  
It implements a complete ticket workflow with two user roles (*User* and *Agent*), document exchange, and a basic notification system.

---

## 1. Prerequisites

Before running the project, ensure you have the following installed:

- **PHP 8.1+**
- **Composer 2+**
- **Node.js and npm**
- **MySQL Server**
- **Git**

---

## 2. Local Setup & Installation

### 2.1 Clone the Repository

```bash
git clone https://github.com/cbalman/clearit-tech-test.git
cd clearit-tech-test
```
### Environment Configuration
Copy the example environment file and generate a unique application key.
```
# Create the environment file
cp .env.example .env

# Generate the application key
php artisan key:generate
```

Open the .env file and update your MySQL database credentials.
```
DB_HOST=127.0.0.1 
DB_PORT=3306
DB_DATABASE=your_clearit_db # Define your database name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

# OPTIONAL: Configure for email notification testing (e.g., using Mailpit/MailHog)
# MAIL_MAILER=smtp
# MAIL_HOST=127.0.0.1
# MAIL_PORT=1025
```

### 2.3 Install Dependencies
Install PHP dependencies via Composer and front-end dependencies via npm:
```
composer install
npm install
```
### 2.4 Database and Storage Setup
Create the database specified in your .env file, then run migrations and seeders:
```
# Run migrations, including the required notifications table
php artisan migrate:fresh --seed

# Create the symbolic link for document uploads
php artisan storage:link
```

---

## 3. Running the Application
The application requires two separate processes to manage the backend and the Vite front-end assets.

### 3.1 Start the Laravel Server (Backend)
Run the Laravel development server in your primary terminal:
```
php artisan serve
```
The application will be available at http://127.0.0.1:8000.

### 3.2 Start the Vite Server (Frontend)
Open a separate terminal tab/window and start the Vite development server to compile and serve CSS/JS assets:
```
npm run dev    
```

---

## 4. Testing Credentials and Workflow
The database seeder has created two test accounts for verifying the role-based functionality:

### 4.1 Test Scenario: User Flow (Ticket Creation & Upload)
### Test Users

| Role          | Email               | Password | Access Route         |
|---------------|----------------------|----------|-----------------------|
| User (Client) | user@clearit.test   | password | /user/dashboard       |
| Agent         | agent@clearit.test  | password | /agent/dashboard      |


### 4.2 Test Scenario: Agent Flow (Review & Documentation Exchange)

1. **Login** using `user@clearit.test`.  
   You will be redirected to the **User Dashboard**

2. Click **"Create New Ticket"**.

3. Fill the form and submit.
    - **Verification:**
        - The ticket appeas as New on the dashboard. Agent are notified.

4. Click *View Details* on the new ticket.

5 In the *Upload Documents* section, upload a test file (e.g., PDF or JPG).
    - **Verification:**
        - The file apperas in the *Attached Documents* list.


### 4.2 Test Scenario: Agent Flow (Review & Documentation Exchange)

1. **Login** using `agent@clearit.test`.  
   You will be redirected to the **Agent Dashboard**, where the newly created ticket will appear.

2. Click **"Review Ticket"** to open the ticket details.

3. In the **Agent Actions** section, click **"Request Additional Documents"**.
    - **Verification:**
        - Ticket status changes to **In Progress**
        - The current agent is assigned
        - The User receives a notification about the document request

4. *(Optional)* Change the ticket status to **Completed** and click **"Update Status"**.
