# Restaurant Staff Scheduling System

This is made to simulate a real-world scheduling system for restaurant staff.
Users will utilize a webpage to manage and schedule shifts for their staff.

## How to Run
If you already have PHP and Node.js installed, skip to **Step 3**

### Step 1: Install PHP (using Homebrew)
Open Terminal to install
```brew install php```

### Step 2: Install Node.js (to run React)
```brew install node```

### Step 3: Download or Clone the Repository
This can be done from at https://github.com/wsz92/restaurantStaffScheduler, under the green "Code" Button or directly from the terminal
```git clone https://github.com/wsz92/restaurantStaffScheduler.git```

### Step 4: Run the Files
Navigate to the correct main file
``` cd restaurantStaffScheduler```

#### 4.1: Backend
```php -S localhost:8000 -t backend/public```
The backend API will be displayed at http://localhost:8000/

#### 4.2: Frontend
```
cd frontend
npm install
npm start
```
The frontend app will be displayed at: http://localhost:3000/

## Backend
The backend was coded using PHP.

## Frontend
The frontend UI was created using React, utilizing [Create React App](https://create-react-app.dev/docs/getting-started/).