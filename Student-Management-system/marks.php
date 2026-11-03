<?php

include "db.php";

/* =========================
   DELETE MARKS
========================= */

if (isset($_POST["delete_id"])) {

    $delete_id = $_POST["delete_id"];

    $sql = "DELETE FROM marks WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();

    header("Location: marks.php");
    exit();
}


/* =========================
   ADD / UPDATE MARKS
========================= */

if (isset($_POST["action"])) {

    $action = $_POST["action"];

    $roll_number = $_POST["roll_number"];
    $name = $_POST["name"];
    $subject = $_POST["subject"];
    $marks = $_POST["marks"];


    /* Calculate Grade */

    if ($marks >= 90) {
        $grade = "A+";
    } elseif ($marks >= 80) {
        $grade = "A";
    } elseif ($marks >= 70) {
        $grade = "B";
    } elseif ($marks >= 60) {
        $grade = "C";
    } elseif ($marks >= 50) {
        $grade = "D";
    } else {
        $grade = "F";
    }


    /* ADD */

    if ($action == "add") {

        $sql = "INSERT INTO marks
                (roll_number, name, subject, marks, grade)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "sssis",
            $roll_number,
            $name,
            $subject,
            $marks,
            $grade
        );

        $stmt->execute();

        header("Location: marks.php");
        exit();
    }


    /* UPDATE */

    if ($action == "update") {

        $id = $_POST["id"];

        $sql = "UPDATE marks
                SET roll_number = ?,
                    name = ?,
                    subject = ?,
                    marks = ?,
                    grade = ?
                WHERE id = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "sssisi",
            $roll_number,
            $name,
            $subject,
            $marks,
            $grade,
            $id
        );

        $stmt->execute();

        header("Location: marks.php");
        exit();
    }
}


/* =========================
   EDIT DATA
========================= */

$edit_mode = false;

$edit_id = "";
$edit_roll = "";
$edit_name = "";
$edit_subject = "";
$edit_marks = "";


if (isset($_GET["edit"])) {

    $edit_mode = true;

    $edit_id = $_GET["edit"];

    $sql = "SELECT * FROM marks WHERE id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("i", $edit_id);

    $stmt->execute();

    $result_edit = $stmt->get_result();

    if ($result_edit->num_rows > 0) {

        $row = $result_edit->fetch_assoc();

        $edit_roll = $row["roll_number"];
        $edit_name = $row["name"];
        $edit_subject = $row["subject"];
        $edit_marks = $row["marks"];
    }
}


/* =========================
   GET ALL MARKS
========================= */

$sql = "SELECT * FROM marks ORDER BY id DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>

<html>

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Marks Management</title>

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

        <a href="students.php">Students</a>

        <a href="marks.php" class="active">Marks</a>

        <a href="attendance.php">Attendance</a>

        <a href="profile.html">Profile</a>

        <a href="login.php">Logout</a>

    </div>

</nav>



<!-- =========================
     MARKS PAGE
========================= -->

<div class="marks-page">


    <h1>Marks Management</h1>

    <p class="marks-subtitle">
        Add, edit, delete and search student marks
    </p>



    <!-- =========================
         ADD / UPDATE FORM
    ========================= -->

    <div class="marks-form-card">

        <?php if ($edit_mode) { ?>

            <h2>Edit Student Marks</h2>

        <?php } else { ?>

            <h2>Add Student Marks</h2>

        <?php } ?>


        <form method="POST" action="marks.php">


            <!-- Hidden ID -->

            <?php if ($edit_mode) { ?>

                <input
                    type="hidden"
                    name="id"
                    value="<?php echo $edit_id; ?>"
                >

            <?php } ?>


            <!-- Row 1 -->

            <div class="marks-form-row">


                <div class="marks-input-group">

                    <label>Roll Number</label>

                    <input
                        type="text"
                        name="roll_number"
                        id="marksRoll"
                        placeholder="Enter Roll Number"
                        value="<?php echo htmlspecialchars($edit_roll); ?>"
                        required
                    >

                </div>



                <div class="marks-input-group">

                    <label>Student Name</label>

                    <input
                        type="text"
                        name="name"
                        id="marksName"
                        placeholder="Enter Student Name"
                        value="<?php echo htmlspecialchars($edit_name); ?>"
                        required
                    >

                </div>


            </div>



            <!-- Row 2 -->

            <div class="marks-form-row">


                <div class="marks-input-group">

                    <label>Subject</label>

                    <select
                        name="subject"
                        id="marksSubject"
                        required
                    >

                        <option value="">Select Subject</option>

                        <option
                            value="HTML"
                            <?php if ($edit_subject == "HTML") echo "selected"; ?>
                        >
                            HTML
                        </option>

                        <option
                            value="CSS"
                            <?php if ($edit_subject == "CSS") echo "selected"; ?>
                        >
                            CSS
                        </option>

                        <option
                            value="JavaScript"
                            <?php if ($edit_subject == "JavaScript") echo "selected"; ?>
                        >
                            JavaScript
                        </option>

                        <option
                            value="Python"
                            <?php if ($edit_subject == "Python") echo "selected"; ?>
                        >
                            Python
                        </option>

                        <option
                            value="React JS"
                            <?php if ($edit_subject == "React JS") echo "selected"; ?>
                        >
                            React JS
                        </option>

                    </select>

                </div>



                <div class="marks-input-group">

                    <label>Marks Obtained</label>

                    <input
                        type="number"
                        name="marks"
                        id="marksObtained"
                        placeholder="Enter Marks"
                        min="0"
                        max="100"
                        value="<?php echo $edit_marks; ?>"
                        required
                    >

                </div>


            </div>



            <!-- Button -->

            <?php if ($edit_mode) { ?>

                <button
                    type="submit"
                    name="action"
                    value="update"
                    class="add-marks-btn"
                >
                    Update Marks
                </button>

                <a
                    href="marks.php"
                    class="add-marks-btn"
                    style="text-decoration:none; display:inline-block; margin-left:10px;"
                >
                    Cancel
                </a>

            <?php } else { ?>

                <button
                    type="submit"
                    name="action"
                    value="add"
                    class="add-marks-btn"
                >
                    Add Marks
                </button>

            <?php } ?>


        </form>

    </div>



    <!-- =========================
         MARKS LIST
    ========================= -->

    <div class="marks-list-card">


        <div class="marks-list-header">

            <h2>Student Marks List</h2>

            <input
                type="text"
                id="searchMarks"
                placeholder="Search student..."
                onkeyup="searchMarks()"
            >

        </div>



        <!-- Table -->

        <div class="marks-table-container">

            <table class="marks-table">

                <thead>

                    <tr>

                        <th>S.No</th>

                        <th>Roll Number</th>

                        <th>Name</th>

                        <th>Subject</th>

                        <th>Marks</th>

                        <th>Grade</th>

                        <th>Actions</th>

                    </tr>

                </thead>


                <tbody id="marksTableBody">


                <?php

                if ($result->num_rows > 0) {

                    $serial = 1;

                    while ($row = $result->fetch_assoc()) {

                ?>

                    <tr>

                        <td>
                            <?php echo $serial; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row["roll_number"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row["name"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row["subject"]); ?>
                        </td>

                        <td>
                            <?php echo $row["marks"]; ?>
                        </td>

                        <td>
                            <?php echo $row["grade"]; ?>
                        </td>

                        <td>


                            <!-- Edit -->

                            <a
                                href="marks.php?edit=<?php echo $row["id"]; ?>"
                                class="marks-edit-btn"
                            >
                                Edit
                            </a>



                            <!-- Delete -->

                            <form
                                method="POST"
                                action="marks.php"
                                style="display:inline;"
                                onsubmit="return confirm('Are you sure you want to delete this record?');"
                            >

                                <input
                                    type="hidden"
                                    name="delete_id"
                                    value="<?php echo $row["id"]; ?>"
                                >

                                <button
                                    type="submit"
                                    class="marks-delete-btn"
                                >
                                    Delete
                                </button>

                            </form>


                        </td>

                    </tr>


                <?php

                        $serial++;

                    }

                } else {

                ?>

                    <tr>

                        <td colspan="7" style="text-align:center;">

                            No marks records found.

                        </td>

                    </tr>

                <?php

                }

                ?>


                </tbody>

            </table>

        </div>

    </div>


</div>



<!-- =========================
     SEARCH JAVASCRIPT
========================= -->

<script>

function searchMarks() {

    let input =
        document.getElementById("searchMarks");

    let filter =
        input.value.toLowerCase();

    let table =
        document.getElementById("marksTableBody");

    let rows =
        table.getElementsByTagName("tr");


    for (let i = 0; i < rows.length; i++) {

        let text =
            rows[i].innerText.toLowerCase();

        if (text.includes(filter)) {

            rows[i].style.display = "";

        } else {

            rows[i].style.display = "none";

        }

    }

}

</script>


</body>

</html>