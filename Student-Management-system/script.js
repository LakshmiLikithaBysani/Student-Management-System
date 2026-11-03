function openLogin() {
    alert("Login page will be available soon!");
}


function scrollToFeatures() {
    document.getElementById("features").scrollIntoView({
        behavior: "smooth"
    });
}
function loginUser(event) {

    event.preventDefault();

    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;


    if (email === "admin@gmail.com" &&
        password === "admin123") {

        alert("Login Successful!");

    } else {

        alert("Invalid Email or Password!");

    }

}
// ================= STUDENT MANAGEMENT =================

let editRow = null;


// Add Student
document.getElementById("studentForm")?.addEventListener("submit", function(event) {

    event.preventDefault();

    let name = document.getElementById("studentName").value;
    let roll = document.getElementById("rollNumber").value;
    let email = document.getElementById("studentEmail").value;
    let phone = document.getElementById("studentPhone").value;
    let course = document.getElementById("studentCourse").value;
    let year = document.getElementById("studentYear").value;

    let tableBody = document.getElementById("studentTableBody");


    // Edit existing student
    if (editRow !== null) {

        editRow.cells[0].textContent = roll;
        editRow.cells[1].textContent = name;
        editRow.cells[2].textContent = email;
        editRow.cells[3].textContent = phone;
        editRow.cells[4].textContent = course;
        editRow.cells[5].textContent = year;

        editRow = null;

        document.querySelector(".add-student-btn").textContent =
            "Add Student";

        alert("Student Updated Successfully!");

    }

    // Add new student
    else {

        let newRow = tableBody.insertRow();

        newRow.insertCell(0).textContent = roll;
        newRow.insertCell(1).textContent = name;
        newRow.insertCell(2).textContent = email;
        newRow.insertCell(3).textContent = phone;
        newRow.insertCell(4).textContent = course;
        newRow.insertCell(5).textContent = year;

        let actionCell = newRow.insertCell(6);

        actionCell.innerHTML = `
            <button class="edit-btn" onclick="editStudent(this)">
                Edit
            </button>

            <button class="delete-btn" onclick="deleteStudent(this)">
                Delete
            </button>
        `;

        alert("Student Added Successfully!");

    }


    // Clear form
    document.getElementById("studentForm").reset();

});


// Delete Student

function deleteStudent(button) {

    let row = button.parentElement.parentElement;

    let studentName = row.cells[1].textContent;

    let confirmDelete =
        confirm("Do you want to delete " + studentName + "?");

    if (confirmDelete) {

        row.remove();

        alert("Student Deleted Successfully!");

    }

}


// Edit Student

function editStudent(button) {

    editRow = button.parentElement.parentElement;

    document.getElementById("studentName").value =
        editRow.cells[1].textContent;

    document.getElementById("rollNumber").value =
        editRow.cells[0].textContent;

    document.getElementById("studentEmail").value =
        editRow.cells[2].textContent;

    document.getElementById("studentPhone").value =
        editRow.cells[3].textContent;

    document.getElementById("studentCourse").value =
        editRow.cells[4].textContent;

    document.getElementById("studentYear").value =
        editRow.cells[5].textContent;


    document.querySelector(".add-student-btn").textContent =
        "Update Student";

}


// Search Student

document.getElementById("searchStudent")?.addEventListener("keyup", function() {

    let searchValue = this.value.toLowerCase();

    let rows =
        document.querySelectorAll("#studentTableBody tr");

    rows.forEach(function(row) {

        let rowText = row.textContent.toLowerCase();

        if (rowText.includes(searchValue)) {

            row.style.display = "";

        } else {

            row.style.display = "none";

        }

    });

});
// ================= MARKS MANAGEMENT =================

let editMarksRow = null;


// Add / Update Marks

document.getElementById("marksForm")?.addEventListener("submit", function(event) {

    event.preventDefault();

    let roll = document.getElementById("marksRoll").value;
    let name = document.getElementById("marksName").value;
    let subject = document.getElementById("marksSubject").value;
    let marks = Number(document.getElementById("marksObtained").value);

    // Calculate Grade

    let grade;

    if (marks >= 90) {
        grade = "A+";
    }
    else if (marks >= 80) {
        grade = "A";
    }
    else if (marks >= 70) {
        grade = "B";
    }
    else if (marks >= 60) {
        grade = "C";
    }
    else if (marks >= 50) {
        grade = "D";
    }
    else {
        grade = "F";
    }


    // Update existing row

    if (editMarksRow !== null) {

        editMarksRow.cells[0].textContent = roll;
        editMarksRow.cells[1].textContent = name;
        editMarksRow.cells[2].textContent = subject;
        editMarksRow.cells[3].textContent = marks;
        editMarksRow.cells[4].textContent = grade;

        editMarksRow = null;

        document.querySelector(".add-marks-btn").textContent =
            "Add Marks";

        alert("Marks Updated Successfully!");

    }


    // Add new row

    else {

        let tableBody =
            document.getElementById("marksTableBody");

        let newRow = tableBody.insertRow();

        newRow.insertCell(0).textContent = roll;
        newRow.insertCell(1).textContent = name;
        newRow.insertCell(2).textContent = subject;
        newRow.insertCell(3).textContent = marks;
        newRow.insertCell(4).textContent = grade;


        let actionCell = newRow.insertCell(5);

        actionCell.innerHTML = `
            <button class="marks-edit-btn"
                    onclick="editMarks(this)">
                Edit
            </button>

            <button class="marks-delete-btn"
                    onclick="deleteMarks(this)">
                Delete
            </button>
        `;

        alert("Marks Added Successfully!");

    }


    // Clear form

    document.getElementById("marksForm").reset();

});


// Delete Marks

function deleteMarks(button) {

    let row =
        button.parentElement.parentElement;

    let studentName =
        row.cells[1].textContent;

    let confirmDelete =
        confirm(
            "Do you want to delete marks of "
            + studentName + "?"
        );

    if (confirmDelete) {

        row.remove();

        alert("Marks Deleted Successfully!");

    }

}


// Edit Marks

function editMarks(button) {

    editMarksRow =
        button.parentElement.parentElement;


    document.getElementById("marksRoll").value =
        editMarksRow.cells[0].textContent;

    document.getElementById("marksName").value =
        editMarksRow.cells[1].textContent;

    document.getElementById("marksSubject").value =
        editMarksRow.cells[2].textContent;

    document.getElementById("marksObtained").value =
        editMarksRow.cells[3].textContent;


    document.querySelector(".add-marks-btn").textContent =
        "Update Marks";

}


// Search Marks

document.getElementById("searchMarks")?.addEventListener(
    "keyup",
    function() {

        let searchValue =
            this.value.toLowerCase();

        let rows =
            document.querySelectorAll(
                "#marksTableBody tr"
            );


        rows.forEach(function(row) {

            let rowText =
                row.textContent.toLowerCase();

            if (rowText.includes(searchValue)) {

                row.style.display = "";

            }
            else {

                row.style.display = "none";

            }

        });

    }
);
// ================= ATTENDANCE MANAGEMENT =================

let editAttendanceRow = null;

// Add / Update Attendance
document.getElementById("attendanceForm")?.addEventListener("submit", function(event) {
    event.preventDefault();

    let roll = document.getElementById("attendanceRoll").value;
    let name = document.getElementById("attendanceName").value;
    let date = document.getElementById("attendanceDate").value;
    let status = document.getElementById("attendanceStatus").value;

    if (editAttendanceRow !== null) {

        editAttendanceRow.cells[0].textContent = roll;
        editAttendanceRow.cells[1].textContent = name;
        editAttendanceRow.cells[2].textContent = date;

        editAttendanceRow.cells[3].textContent = status;

        if (status === "Present") {
            editAttendanceRow.cells[3].className = "present-status";
        } else {
            editAttendanceRow.cells[3].className = "absent-status";
        }

        editAttendanceRow = null;

        document.querySelector(".add-attendance-btn").textContent =
            "Mark Attendance";

        alert("Attendance Updated Successfully!");

    } else {

        let tableBody = document.getElementById("attendanceTableBody");

        let newRow = tableBody.insertRow();

        newRow.insertCell(0).textContent = roll;
        newRow.insertCell(1).textContent = name;
        newRow.insertCell(2).textContent = date;

        let statusCell = newRow.insertCell(3);
        statusCell.textContent = status;

        if (status === "Present") {
            statusCell.className = "present-status";
        } else {
            statusCell.className = "absent-status";
        }

        let actionCell = newRow.insertCell(4);

        actionCell.innerHTML = `
            <button class="attendance-edit-btn"
                    onclick="editAttendance(this)">
                Edit
            </button>

            <button class="attendance-delete-btn"
                    onclick="deleteAttendance(this)">
                Delete
            </button>
        `;

        alert("Attendance Marked Successfully!");
    }

    document.getElementById("attendanceForm").reset();
});


// Delete Attendance
function deleteAttendance(button) {

    let row = button.parentElement.parentElement;

    let studentName = row.cells[1].textContent;

    let confirmDelete = confirm(
        "Do you want to delete attendance of " + studentName + "?"
    );

    if (confirmDelete) {

        row.remove();

        alert("Attendance Deleted Successfully!");
    }
}


// Edit Attendance
function editAttendance(button) {

    editAttendanceRow = button.parentElement.parentElement;

    document.getElementById("attendanceRoll").value =
        editAttendanceRow.cells[0].textContent;

    document.getElementById("attendanceName").value =
        editAttendanceRow.cells[1].textContent;

    document.getElementById("attendanceDate").value =
        editAttendanceRow.cells[2].textContent;

    document.getElementById("attendanceStatus").value =
        editAttendanceRow.cells[3].textContent;

    document.querySelector(".add-attendance-btn").textContent =
        "Update Attendance";
}


// Search Attendance
document.getElementById("searchAttendance")?.addEventListener("keyup", function() {

    let searchValue = this.value.toLowerCase();

    let rows = document.querySelectorAll(
        "#attendanceTableBody tr"
    );

    rows.forEach(function(row) {

        let rowText = row.textContent.toLowerCase();

        if (rowText.includes(searchValue)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }

    });

});