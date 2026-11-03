<?php

include "db.php";

$message = "";
$editStudent = null;


/* =========================
   ADD / UPDATE / DELETE
========================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    /* =========================
       DELETE STUDENT
    ========================= */

    if (isset($_POST["delete_id"])) {

        $id = $_POST["delete_id"];

        $sql = "DELETE FROM students WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Delete Prepare Failed: " . $conn->error);
        }

        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $message = "Student Deleted Successfully!";
        } else {
            $message = "Error deleting student: " . $stmt->error;
        }

        $stmt->close();
    }


    /* =========================
       ADD / UPDATE STUDENT
    ========================= */

    else {

        $action = $_POST["action"];

        $roll = $_POST["roll_number"];
        $name = $_POST["name"];
        $email = $_POST["email"];
        $phone = $_POST["phone"];
        $course = $_POST["course"];
        $year = $_POST["year"];


        /* =========================
           ADD STUDENT
        ========================= */

        if ($action == "add") {

            $sql = "INSERT INTO students
                    (roll_number, name, email, phone, course, year)
                    VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                die("Add Prepare Failed: " . $conn->error);
            }

            $stmt->bind_param(
                "ssssss",
                $roll,
                $name,
                $email,
                $phone,
                $course,
                $year
            );

            if ($stmt->execute()) {

                $message = "Student Added Successfully!";

            } else {

                $message = "Error adding student: " . $stmt->error;
            }

            $stmt->close();
        }


        /* =========================
           UPDATE STUDENT
        ========================= */

        if ($action == "update") {

            $id = $_POST["student_id"];

            $sql = "UPDATE students SET
                    roll_number = ?,
                    name = ?,
                    email = ?,
                    phone = ?,
                    course = ?,
                    year = ?
                    WHERE id = ?";

            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                die("Update Prepare Failed: " . $conn->error);
            }

            $stmt->bind_param(
                "ssssssi",
                $roll,
                $name,
                $email,
                $phone,
                $course,
                $year,
                $id
            );

            if ($stmt->execute()) {

                $message = "Student Updated Successfully!";

            } else {

                $message = "Error updating student: " . $stmt->error;
            }

            $stmt->close();
        }
    }
}


/* =========================
   GET STUDENT FOR EDIT
========================= */

if (isset($_GET["edit"])) {

    $id = $_GET["edit"];

    $sql = "SELECT * FROM students WHERE id = ?";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Edit Prepare Failed: " . $conn->error);
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultEdit = $stmt->get_result();

    if ($resultEdit->num_rows > 0) {

        $editStudent = $resultEdit->fetch_assoc();
    }

    $stmt->close();
}


/* =========================
   GET ALL STUDENTS
========================= */

$result = $conn->query(
    "SELECT * FROM students ORDER BY id DESC"
);

if (!$result) {
    die("Student List Error: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Students - Student Management System</title>

    <link rel="stylesheet" href="style.css">

</head>


<body>


<!-- =========================
     NAVBAR
========================= -->

<nav class="navbar">

    <div class="logo">
        🎓 Student Management System
    </div>

    <div class="nav-links">

        <a href="dashboard.html">Dashboard</a>

        <a href="students.php" class="active">
            Students
        </a>

        <a href="marks.php">
            Marks
        </a>

        <a href="attendance.php">
            Attendance
        </a>

        <a href="profile.html">
            Profile
        </a>

        <a href="login.php">
            Logout
        </a>

    </div>

</nav>



<!-- =========================
     STUDENTS PAGE
========================= -->

<section class="students-page">


    <div class="page-header">

        <h1>Student Management</h1>

        <p>
            Add, edit, delete and search student information
        </p>

    </div>



    <!-- =========================
         MESSAGE
    ========================= -->

    <?php if ($message != "") { ?>

        <div style="
            margin:20px auto;
            max-width:1000px;
            padding:15px;
            background:#f0fdf4;
            border:1px solid #86efac;
            border-radius:8px;
            color:#166534;
            text-align:center;
            font-weight:bold;
        ">

            <?php echo htmlspecialchars($message); ?>

        </div>

    <?php } ?>



    <!-- =========================
         ADD / UPDATE FORM
    ========================= -->

    <div class="student-form-card">

        <h2>

            <?php

            if ($editStudent) {
                echo "Edit Student";
            } else {
                echo "Add New Student";
            }

            ?>

        </h2>


        <form method="POST" action="students.php">


            <?php if ($editStudent) { ?>

                <input
                    type="hidden"
                    name="action"
                    value="update"
                >

                <input
                    type="hidden"
                    name="student_id"
                    value="<?php echo $editStudent['id']; ?>"
                >

            <?php } else { ?>

                <input
                    type="hidden"
                    name="action"
                    value="add"
                >

            <?php } ?>



            <div class="form-row">


                <!-- Roll Number -->

                <div class="input-group">

                    <label>
                        Roll Number
                    </label>

                    <input
                        type="text"
                        name="roll_number"
                        placeholder="Enter roll number"
                        value="<?php

                        if ($editStudent) {
                            echo htmlspecialchars(
                                $editStudent['roll_number']
                            );
                        }

                        ?>"
                        required
                    >

                </div>



                <!-- Name -->

                <div class="input-group">

                    <label>
                        Student Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        placeholder="Enter student name"
                        value="<?php

                        if ($editStudent) {
                            echo htmlspecialchars(
                                $editStudent['name']
                            );
                        }

                        ?>"
                        required
                    >

                </div>

            </div>



            <div class="form-row">


                <!-- Email -->

                <div class="input-group">

                    <label>
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        placeholder="Enter email"
                        value="<?php

                        if ($editStudent) {
                            echo htmlspecialchars(
                                $editStudent['email']
                            );
                        }

                        ?>"
                        required
                    >

                </div>



                <!-- Phone -->

                <div class="input-group">

                    <label>
                        Phone
                    </label>

                    <input
                        type="text"
                        name="phone"
                        placeholder="Enter phone number"
                        value="<?php

                        if ($editStudent) {
                            echo htmlspecialchars(
                                $editStudent['phone']
                            );
                        }

                        ?>"
                        required
                    >

                </div>

            </div>



            <div class="form-row">


                <!-- Course -->

                <div class="input-group">

                    <label>
                        Course
                    </label>

                    <input
                        type="text"
                        name="course"
                        placeholder="Enter course"
                        value="<?php

                        if ($editStudent) {
                            echo htmlspecialchars(
                                $editStudent['course']
                            );
                        }

                        ?>"
                        required
                    >

                </div>



                <!-- Year -->

                <div class="input-group">

                    <label>
                        Year
                    </label>

                    <select
                        name="year"
                        required
                    >

                        <option value="">
                            Select Year
                        </option>

                        <option value="1st Year"
                            <?php

                            if (
                                $editStudent &&
                                $editStudent['year'] == "1st Year"
                            ) {
                                echo "selected";
                            }

                            ?>
                        >
                            1st Year
                        </option>

                        <option value="2nd Year"
                            <?php

                            if (
                                $editStudent &&
                                $editStudent['year'] == "2nd Year"
                            ) {
                                echo "selected";
                            }

                            ?>
                        >
                            2nd Year
                        </option>

                        <option value="3rd Year"
                            <?php

                            if (
                                $editStudent &&
                                $editStudent['year'] == "3rd Year"
                            ) {
                                echo "selected";
                            }

                            ?>
                        >
                            3rd Year
                        </option>

                        <option value="4th Year"
                            <?php

                            if (
                                $editStudent &&
                                $editStudent['year'] == "4th Year"
                            ) {
                                echo "selected";
                            }

                            ?>
                        >
                            4th Year
                        </option>

                    </select>

                </div>

            </div>



            <!-- BUTTONS -->

            <div style="
                margin-top:20px;
                display:flex;
                gap:10px;
            ">

                <button
                    type="submit"
                    class="add-student-btn"
                >

                    <?php

                    if ($editStudent) {
                        echo "Update Student";
                    } else {
                        echo "Add Student";
                    }

                    ?>

                </button>


                <?php if ($editStudent) { ?>

                    <a
                        href="students.php"
                        class="add-student-btn"
                        style="
                            text-decoration:none;
                            background:#777;
                        "
                    >
                        Cancel
                    </a>

                <?php } ?>

            </div>


        </form>

    </div>



    <!-- =========================
         STUDENT LIST
    ========================= -->

    <div class="student-list-card">


        <div class="student-list-header">

            <div>

                <h2>
                    Student List
                </h2>

                <p>
                    All registered students
                </p>

            </div>


            <input
                type="text"
                id="searchStudent"
                placeholder="🔍 Search student..."
            >

        </div>



        <div class="table-container">

            <table>

                <thead>

                    <tr>

                        <th>
                            S.No
                        </th>

                        <th>
                            Roll Number
                        </th>

                        <th>
                            Name
                        </th>

                        <th>
                            Email
                        </th>

                        <th>
                            Phone
                        </th>

                        <th>
                            Course
                        </th>

                        <th>
                            Year
                        </th>

                        <th>
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody id="studentTable">


                <?php

                $serial = 1;

                if ($result->num_rows > 0) {

                    while ($row = $result->fetch_assoc()) {

                ?>


                    <tr>


                        <td>
                            <?php echo $serial++; ?>
                        </td>


                        <td>
                            <?php
                            echo htmlspecialchars(
                                $row['roll_number']
                            );
                            ?>
                        </td>


                        <td>
                            <?php
                            echo htmlspecialchars(
                                $row['name']
                            );
                            ?>
                        </td>


                        <td>
                            <?php
                            echo htmlspecialchars(
                                $row['email']
                            );
                            ?>
                        </td>


                        <td>
                            <?php
                            echo htmlspecialchars(
                                $row['phone']
                            );
                            ?>
                        </td>


                        <td>
                            <?php
                            echo htmlspecialchars(
                                $row['course']
                            );
                            ?>
                        </td>


                        <td>
                            <?php
                            echo htmlspecialchars(
                                $row['year']
                            );
                            ?>
                        </td>


                        <td>


                            <!-- EDIT -->

                            <a
                                href="students.php?edit=<?php
                                echo $row['id'];
                                ?>"
                                class="edit-btn"
                            >
                                Edit
                            </a>



                            <!-- DELETE -->

                            <form
                                method="POST"
                                action="students.php"
                                style="
                                    display:inline;
                                "
                                onsubmit="
                                    return confirm(
                                        'Are you sure you want to delete this student?'
                                    );
                                "
                            >

                                <input
                                    type="hidden"
                                    name="delete_id"
                                    value="<?php
                                    echo $row['id'];
                                    ?>"
                                >

                                <button
                                    type="submit"
                                    class="delete-btn"
                                >
                                    Delete
                                </button>

                            </form>


                        </td>


                    </tr>


                <?php

                    }

                } else {

                ?>

                    <tr>

                        <td
                            colspan="8"
                            style="
                                text-align:center;
                                padding:30px;
                            "
                        >

                            No students found.

                        </td>

                    </tr>

                <?php

                }

                ?>


                </tbody>

            </table>

        </div>

    </div>


</section>



<!-- =========================
     SEARCH JAVASCRIPT
========================= -->

<script>

const searchInput =
    document.getElementById("searchStudent");

const studentTable =
    document.getElementById("studentTable");


searchInput.addEventListener("keyup", function () {

    const searchValue =
        this.value.toLowerCase();

    const rows =
        studentTable.getElementsByTagName("tr");


    for (let i = 0; i < rows.length; i++) {

        const rowText =
            rows[i].innerText.toLowerCase();

        if (rowText.includes(searchValue)) {

            rows[i].style.display = "";

        } else {

            rows[i].style.display = "none";

        }

    }

});

</script>


</body>

</html>