# Restaurant Staff Scheduling System

This is made to simulate a real-world scheduling system for restaurant staff.<br />
Users will utilize a webpage to manage and schedule shifts for their staff.

## How to Run
If you already have PHP and Node.js installed, skip to **Step 3**

### Step 1: Install PHP (using Homebrew for Mac)
Open Terminal to install, type in:
```
brew install php
```

### Step 2: Install Node.js to run React (using Homebrew for Mac)
In your terminal:
```
brew install node
```

### Step 3: Download or Clone the Repository
This can be done from at https://github.com/wsz92/restaurantStaffScheduler, under the green "Code" Button and download as a zip file or directly from the terminal type:
```
git clone https://github.com/wsz92/restaurantStaffScheduler.git
```

### Step 4: Run the Files
Navigate to the correct main file in your terminal
```
cd restaurantStaffScheduler
```
If you are having issues locating your file, find where you saved the github file and copy and paste the path in place of ```restaurantStaffScheduler```

#### 4.1: Backend
In your terminal:
```
php -S localhost:8000 -t backend/public
```
The backend API will be displayed at http://localhost:8000/

#### 4.2: Frontend
In your terminal:
```
cd frontend
npm install
npm start
```
The frontend app will be displayed at: http://localhost:3000/

## Backend
The backend was coded using PHP.<br/>
My approach was to branch off the functionalities into two servers, the staff (/staffList) and the shift (/shifts) servers. This made is easier to manage each request method within the servers.

### Staff Server
The Staff server has three methods: GET, which displays all the staff in a list, POST, which allows the user to create new staff members, and DELETE, which deletes existing staff from the list.<br/>
The staff information is stored at http://localhost:8000/staffList.

#### GET Method
The GET method simply displays the staff list by fetching from the local .json file.<br/> It is then formatted in a table for the user to easily read each entry.

#### POST Method
The POST method creates a new staff object that is then added to the existing staff list.<br/>
Each new staff needs a name, role, and properly formatted phonbe number entry to save to the list. If information is missing, a warning will be displayed.

#### DELETE Method
The DELETE method removes an existing staff from the list, chosen by the user.<br/>
It takes the user ID from the staff chosen and creates a new staff list, excluding the  staff to be deleted. Aftwards, the new list is saved and the deleted staff is removed.

### Shift Server
The Staff server has three methods: GET, which displays all the existing shifts in a list, POST, which allows the user to create new shifts and assign them to staff, and DELETE, which deletes existing shifts from the list.<br/>
The shift information is stored at http://localhost:8000/shifts.

#### GET Method
The GET method simply displays the shift list by fetching from the local .json file.<br/>
It is then formatted in a table for the user to easily read each entry.

#### POST Method
The POST method creates a new shift object that is then added to the existing shift list.<br/>
Each new staff needs a day, assigned staff, start time, end time, and associated role to save to the list. If information is missing, a warning will be displayed.

#### DELETE Method
The DELETE method removes an existing shift from the list, chosen by the user.<br/>
It takes the shift ID from the shift chosen and creates a new staff list, excluding the shift to be deleted. Aftwards, the new list is saved and the deleted shift is removed.

## Frontend
The frontend UI was created using React, utilizing [Create React App](https://create-react-app.dev/docs/getting-started/) to generate the files needed.<br/>
The webpage is loaded to a welcoming page that allows users to choosen what actions they want to perform, manage their staff or manage their shifts.

### Manage Staff Page
When you reach the staff page, the list of exisiting staff and their information is  displayed in a table format, this information is pulled from http://localhost:8000/staffList.<br/>
On this page users can choose to add a new staff members or delete an existing staff.

#### Add New Staff
To add a new staff, click on the  new staff button on the top center and a pop-up will appear on the page for the user to enter the information of the new staff. All information must be filled out before the staff can be saved and the phone number must be in ###-###-#### format.<br/>
If any of these errors occur, a pop-up message will notify the user before they can save. If the user wants to leave the pop-up without adding a new staff, the cancel button will close out.<br/>
Once a new staff is saved, the table will be updated to display the changes made.

#### Delete Existing Staff
To delete an existing staff member, the user can click on the trashcan icon button to the rightmost of the row. The user will then get a confirmation that they want to delelte this staff member. Once confirmed, this will send the staff's unique ID to the backend to remove the staff.<br/>
Once the deletion is successful, the table will be updated to display the changes made.

### Manage Shifts Page
When you reach the shifts page, the list of exisiting shift and its information is  displayed in a table format, this information is pulled from http://localhost:8000/shifts.<br/>
On this page users can choose to add a new shift or delete an existing shift.<br/>
Note that the times are displayed in 24-hour clock format.

#### Add New Shift
To add a new shift, click on the new shift button on the top center and a pop-up will appear on the page for the user to enter the information of the new shift. All information must be filled out before the shift can be saved, the day is selected from a calendar view and the staff is assigned from the list of exisiting staff. <br/>
If any of these errors occur, a pop-up message will notify the user before they can save. If the user wants to leave the pop-up without adding a new shift, the cancel button will close out.<br/>
Once a new shift is saved, the table will be updated to display the changes made.

#### Delete Existing Shift
To delete an existing shift , the user can click on the trashcan icon button to the rightmost of the row. The user will then get a confirmation that they want to delelte the shift. Once confirmed, this will send the shift's unique ID to the backend to remove the shift.<br/>
Once the deletion is successful, the table will be updated to display the changes made.

## Additional Notes

### Limitations and Tradeoffs
Here are some known limitations and tradeoffs:

#### Local Files
The json files that store the staff list and shift list are local files stored within this program. This means it won't allow for concurrent access with multiple users as changes made to your own file won't reflect for another user. This make it simpler to implement and run but is easier for the files to be corrupted compared to using a database.

#### Scalability
Since all the data is kept in memory and written/read to the files, issues will occur when the dataset gets larger. Currently it is fesible for a small project and dataset for testing but when files are too large, it will need a longer running time and can crash easily.

### Further Improvements
Here are a few improvements I would want to make to the solution:

**1.**
I would include a method to edit existing staff and shifts. Currently, if you want to change information, the user would need to delete the existing information and then creating a new entry. 

**2.**
I would include a view that shows the staff and their existing shifts in one page. I would also add filters to the tables so it can be sorted.

**3.**
I would include a validation check that ensure the staff assigned to the shift created has matching roles.