import React, { useEffect, useState } from "react"; // load React library
import { BrowserRouter as Router, Routes, Route, Link } from "react-router-dom"; // React router to allow

// welcome page for navigation
function MainPage() {
  return (
    <div style={{ textAlign: "center", paddingTop: "60px" }}>
      <h1>🍽️ Restaurant Staff Scheduler🥢</h1>
      <h2 style={{ marginTop: "40px", marginBottom: "30px" }}> Welcome 👋🏻</h2>
      <p style={{ marginTop: "60px", marginBottom: "30px" }}>Start Managing Your Restaurant With a Click Below!</p>
      <Link to="/staffList">
        <button style={{ margin: "10px", padding: "10px 30px" }}>Manage Staff</button></Link>
      <Link to="/shifts">
        <button style={{ margin: "10px", padding: "10px 30px" }}>Manage Shifts</button></Link>
    </div>
  );
}

// managing staff page: list of all staff and adding new staff
function StaffPage() {

  // initialize constant variables
  const [staffList, setStaffList] = useState([]);
  const [showModal, setShowModal] = useState(false);
  const [newStaff, setNewStaff] = useState({ name: "", role: "", phone: "" });

  useEffect(() => {fetch("http://localhost:8000/staffList") // site that information will be fetched from
  .then(res => res.json()) // then convert to json
  .then(data => { // load the staff list from the backend 
    if (Array.isArray(data)) {
      setStaffList(data);
    } else {
      setStaffList([]);  // if the list has formatting issues, start a new empty list
    }
  })
  .catch(() => setStaffList([]));  // if the list throws an error, start a new empty list
  }, []);

  const openModal = () => { // when the new staff pop-up open, all entries should be blank
    setNewStaff({ name: "", role: "", phone: "" });
    setShowModal(true);
  };

  const closeModal = () => { // close the new staff pop-up
    setShowModal(false);
  };

  const handleChange = (field, value) => { // as the user types into the pop-up, update the new staff object
    setNewStaff({ ...newStaff, [field]: value });
  };

  const handleAddStaff = () => { // send the new staff information to the backend to be saved
  fetch("http://localhost:8000/staffList", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      Name: newStaff.name,
      Role: newStaff.role,
      Phone: newStaff.phone,
    }),
  })
  .then(async (res) => { // checks if 400 error occured and throws error
    if (!res.ok) {
      const errorData = await res.json();
      throw new Error(errorData.Error);
    }
    return res.json();
  })
  .then((data) => { // update the staff list being displayed to show new staff
    setStaffList((prevList) => [...prevList, data.staff]);
    setShowModal(false); // close the new staff pop-up
  })
  .catch((error) => {
    alert(error.message); // if error, display to user error message
    console.error("Error from New Staff:", error);
  });
  };

  return (
    // this keeps the "Back to Home" button relative to the top left of the page
    <div style={{ position: "relative", minHeight: "100vh" }}>
    <Link to="/">
      <button
        style={{position: "absolute", top: "20px", left: "20px", padding: "5px 20px"}}
      >
        ⬅ Back to Home
      </button>
    </Link> 

    {/* this keeps the "New Staff" button relative to the top center of the page */}
    <div style={{ textAlign: "center", marginBottom: "20px" }}>
      <h2 style={{ textAlign: "center", marginBottom: "20px" }}>👥 Staff Management 👥</h2>
      <button onClick={openModal} style={{ padding: "10px 15px", marginTop: "10px" }}>
          ➕ New Staff
      </button>
    </div>

    {/* formatting for the staff table */}
    <table style={{ width: "100%", borderCollapse: "collapse" }}>
      <thead>
        <tr>
          <th style={{border: "1px solid #ccc", padding: "12px", backgroundColor: "#f2f2f2", textAlign: "left",}}>Name</th>
          <th style={{border: "1px solid #ccc", padding: "12px", backgroundColor: "#f2f2f2", textAlign: "left",}}>Role</th>
          <th style={{border: "1px solid #ccc", padding: "12px", backgroundColor: "#f2f2f2", textAlign: "left",}}>Phone Number</th>
        </tr>
      </thead>
      <tbody>
      {Array.isArray(staffList) && staffList.map((staff, index) => ( // maps each staff information to each row
      <tr key={index}>
        <td style={{border: "1px solid #ccc", padding: "12px",}}>{staff.name}</td>
        <td style={{border: "1px solid #ccc", padding: "12px",}}>{staff.role}</td>
        <td style={{border: "1px solid #ccc", padding: "12px",}}>{staff.phone}</td> 
      </tr>
    ))}
      </tbody>
    </table>

  {/* formatting for the new staff pop-up */}
  {showModal && (
          <div style={{position: "fixed", top: 0, left: 0, height: "100vh", width: "100vw", backgroundColor: "rgba(0,0,0,0.4)", display: "flex", justifyContent: "center", alignItems: "center"}}>
            <div style={{backgroundColor: "#fff", padding: "25px", borderRadius: "8px", width: "300px", display: "flex", flexDirection: "column", gap: "10px"}}>
              <h3>New Staff Information</h3>
              <label>
                Name:
                <input
                  type="text"
                  value={newStaff.name}
                  onChange={(e) => handleChange("name", e.target.value)} // make changes after user inputs information
                />
              </label>
              <label>
                Role:
                <input
                  type="text"
                  value={newStaff.role}
                  onChange={(e) => handleChange("role", e.target.value)} // make changes after user inputs information
                />
              </label>
              <label>
                Phone Number:
                <input
                  type="text"
                  value={newStaff.phone}
                  onChange={(e) => handleChange("phone", e.target.value)} // make changes after user inputs information
                />
              </label>
              <div style={{ textAlign: "center", marginTop: "15px" }}>
                <button onClick={handleAddStaff} style={{ marginRight: "10px" }}>
                  Save Staff
                </button>
                <button onClick={closeModal}>Cancel Entry</button>
              </div>
            </div>
          </div>
        )}
      </div>
    );
  }


// managing shifts page: list of all current shifts and adding new shifts
function ShiftPage() {

  // initialize constant variables
  const [shiftList, setShiftList] = useState([]);
  const [showModal, setShowModal] = useState(false);
  const [newShift, setNewShift] = useState({ day: "", name: "", start: "", end: "", role: "" });

  useEffect(() => {fetch("http://localhost:8000/shifts") // site that information will be fetched from
  .then(res => res.json()) // then convert to json
  .then(data => { // load the shift list from the backend 
    if (Array.isArray(data)) {
      setShiftList(data);
    } else {
      setShiftList([]);  // if the list has formatting issues, start a new empty list
    }
  })
  .catch(() => setShiftList([]));  // if the list throws an error, start a new empty list
  }, []);

  const openModal = () => { // when the new shift pop-up open, all entries should be blank
    setNewShift({ day: "", name: "", start: "", end: "", role: "" });
    setShowModal(true);
  };

  const closeModal = () => { // close the new shift pop-up
    setShowModal(false);
  };

  const handleChange = (field, value) => { // as the user types into the pop-up, update the new shift object
    setNewShift({ ...newShift, [field]: value });
  };

  const handleAddShift = () => { // send the new staff information to the backend to be saved
  fetch("http://localhost:8000/shifts", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      Day: newShift.day,
      Name: newShift.name,
      Start: newShift.start,
      End: newShift.end,
      Role: newShift.role,
    }),
  })
  .then(async (res) => { // checks if 400 error occured and throws error
    if (!res.ok) {
      const errorData = await res.json();
      throw new Error(errorData.Error);
    }
    return res.json();
  })
  .then((data) => { // update the staff list being displayed to show new staff
    setShiftList((prevList) => [...prevList, data.shift]);
    setShowModal(false); // close the new staff pop-up
  })
  .catch((error) => {
    alert(error.message); // if error, display to user error message
    console.error("Error from New Staff:", error);
  });
  };

  return (
    // this keeps the "Back to Home" button relative to the top left of the page
    <div style={{ position: "relative", minHeight: "100vh" }}>
    <Link to="/">
      <button
        style={{position: "absolute", top: "20px", left: "20px", padding: "5px 20px"}}
      >
        ⬅ Back to Home
      </button>
    </Link>

    <div style={{ textAlign: "center", marginBottom: "20px" }}>
        <h2 style={{ textAlign: "center", marginBottom: "20px" }}>📅 Shift Management 📅</h2>
        <button onClick={openModal} style={{ padding: "10px 15px", marginTop: "10px" }}>
            ➕ New Shift
        </button>
      </div>

      {/* formatting for the shift table */}
    <table style={{ width: "100%", borderCollapse: "collapse" }}>
      <thead>
        <tr>
          <th style={{border: "1px solid #ccc", padding: "12px", backgroundColor: "#f2f2f2", textAlign: "left",}}>Day</th>
          <th style={{border: "1px solid #ccc", padding: "12px", backgroundColor: "#f2f2f2", textAlign: "left",}}>Name</th>
          <th style={{border: "1px solid #ccc", padding: "12px", backgroundColor: "#f2f2f2", textAlign: "left",}}>Start Time</th>
          <th style={{border: "1px solid #ccc", padding: "12px", backgroundColor: "#f2f2f2", textAlign: "left",}}>End Time</th>
          <th style={{border: "1px solid #ccc", padding: "12px", backgroundColor: "#f2f2f2", textAlign: "left",}}>Role</th>
        </tr>
      </thead>
      <tbody>
      {Array.isArray(shiftList) && shiftList.map((shift, index) => ( // maps each shift information to each row
      <tr key={index}>
        <td style={{border: "1px solid #ccc", padding: "12px",}}>{shift.day}</td>
        <td style={{border: "1px solid #ccc", padding: "12px",}}>{shift.name}</td>
        <td style={{border: "1px solid #ccc", padding: "12px",}}>{shift.start}</td> 
        <td style={{border: "1px solid #ccc", padding: "12px",}}>{shift.end}</td>
        <td style={{border: "1px solid #ccc", padding: "12px",}}>{shift.role}</td> 
      </tr>
    ))}
      </tbody>
    </table>


    {/* formatting for the new staff pop-up */}
    {showModal && (
            <div style={{position: "fixed", top: 0, left: 0, height: "100vh", width: "100vw", backgroundColor: "rgba(0,0,0,0.4)", display: "flex", justifyContent: "center", alignItems: "center"}}>
              <div style={{backgroundColor: "#fff", padding: "25px", borderRadius: "8px", width: "300px", display: "flex", flexDirection: "column", gap: "10px"}}>
                <h3>New Shift Information</h3>
                <label>
                  Day:
                  <input
                    type="text"
                    value={newShift.day}
                    onChange={(e) => handleChange("day", e.target.value)} // make changes after user inputs information
                  />
                </label>
                <label>
                  Name:
                  <input
                    type="text"
                    value={newShift.name}
                    onChange={(e) => handleChange("name", e.target.value)} // make changes after user inputs information
                  />
                </label>
                <label>
                  Start Time:
                  <input
                    type="text"
                    value={newShift.start}
                    onChange={(e) => handleChange("start", e.target.value)} // make changes after user inputs information
                  />
                </label>
                <label>
                  End Time:
                  <input
                    type="text"
                    value={newShift.end}
                    onChange={(e) => handleChange("end", e.target.value)} // make changes after user inputs information
                  />
                </label>
                <label>
                  Role:
                  <input
                    type="text"
                    value={newShift.role}
                    onChange={(e) => handleChange("role", e.target.value)} // make changes after user inputs information
                  />
                </label>
                <div style={{ textAlign: "center", marginTop: "15px" }}>
                  <button onClick={handleAddShift} style={{ marginRight: "10px" }}>
                    Save Staff
                  </button>
                  <button onClick={closeModal}>Cancel Entry</button>
                </div>
              </div>
            </div>
          )}



    </div>
  );
}


// loads the pages
function App() {
  return (
    <Router>
      <Routes>
        <Route path="/" element={<MainPage />} />
        <Route path="/staffList" element={<StaffPage />} />
        <Route path="/shifts" element={<ShiftPage />} />
      </Routes>
    </Router>
  );
}

export default App;