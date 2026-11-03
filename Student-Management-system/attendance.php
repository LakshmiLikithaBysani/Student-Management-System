<?php
include "db.php";

/* DELETE */
if (isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];

    $sql = "DELETE FROM attendance WHERE id = $id";
    $conn->query($sql);

    header("Location: attendance.php");
    exit();
}

/* ADD / UPDATE */
if (isset($_POST['save_attendance'])) {

    $roll_number = $_POST['roll_number'];
    $name = $_POST['name'];
    $date = $_POST['date'];
    $status = $_POST['status'];

    if (!empty($_POST['edit_id'])) {

        $id = $_POST['edit_id'];

        $sql = "UPDATE attendance SET
                roll_number='$roll_number',
                name='$name',
                date='$date',
                status='$status'
                WHERE id=$id";

        $conn->query($sql);

    } else {

        $sql = "INSERT INTO attendance
                (roll_number, name, date, status)
                VALUES
                ('$roll_number', '$name', '$date', '$status')";

        $conn->query($sql);
    }

    header("Location: attendance.php");
    exit();
}

/* EDIT */
$editData = null;

if (isset($_GET['edit'])) {

    $id = $_GET['edit'];

    $result = $conn->query(
        "SELECT * FROM attendance WHERE id=$id"
    );

    $editData = $result->fetch_assoc();
}

/* DISPLAY DATA */
$result = $conn->query(
    "SELECT * FROM attendance ORDER BY id DESC"
);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Attendance Management</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<!-- NAVBAR -->

<nav class="navbar">

    <div class="logo">
        🎓 Student Management System
    </div>

    <div class="nav-links">

        <a href="dashboard.html">Dashboard</a>

        <a href="students.php">Students</a>

        <a href="marks.php">Marks</a>

        <a href="attendance.php" class="active">Attendance</a>

        <a href="profile.html">Profile</a>

        <a href="login.php">Logout</a>

    </div>

</nav>


<!-- ATTENDANCE PAGE -->

<div class="attendance-page">

    <h1>Attendance Management</h1>

    <p class="attendance-subtitle">
        Manage student attendance easily
    </p>


    <!-- FORM -->

    <div class="attendance-form-card">

        <form method="POST">

            <input
                type="hidden"
                name="edit_id"
                value="<?php echo $editData['id'] ?? ''; ?>"
            >

            <div class="attendance-form-row">


                <!-- ROLL NUMBER -->

                <div class="attendance-input-group">

                    <label>Roll Number</label>

                    <input
                        type="text"
                        name="roll_number"
                        placeholder="Enter roll number"
                        value="<?php echo $editData['roll_number'] ?? ''; ?>"
                        required
                    >

                </div>


                <!-- NAME -->

                <div class="attendance-input-group">

                    <label>Student Name</label>

                    <input
                        type="text"
                        name="name"
                        placeholder="Enter student name"
                        value="<?php echo $editData['name'] ?? ''; ?>"
                        required
                    >

                </div>


                <!-- DATE -->

                <div class="attendance-input-group">

                    <label>Date</label>

                    <input
                        type="date"
                        name="date"
                        value="<?php echo $editData['date'] ?? ''; ?>"
                        required
                    >

                </div>


                <!-- STATUS -->

                <div class="attendance-input-group">

                    <label>Status</label>

                    <select name="status" required>

                        <option value="">
                            Select Status
                        </option>

                        <option value="Present"
                            <?php
                            if (($editData['status'] ?? '') == 'Present')
                                echo 'selected';
                            ?>>
                            Present
                        </option>

                        <option value="Absent"
                            <?php
                            if (($editData['status'] ?? '') == 'Absent')
                                echo 'selected';
                            ?>>
                            Absent
                        </option>

                    </select>

                </div>


                <!-- BUTTON -->

                <button
                    type="submit"
                    name="save_attendance"
                    class="add-attendance-btn"
                >

                    <?php
                    echo $editData ? "Update Attendance" : "Add Attendance";
                    ?>

                </button>

            </div>

        </form>

    </div>


    <!-- ATTENDANCE LIST -->

    <div class="attendance-list-card">

        <div class="attendance-list-header">

            <div>

                <h2>Attendance Records</h2>

            </div>

            <input
                type="text"
                id="searchAttendance"
                placeholder="Search student..."
                onkeyup="searchAttendance()"
            >

        </div>


        <!-- TABLE -->

        <div class="attendance-table-container">

            <table class="attendance-table">

                <thead>

                    <tr>

                        <th>S.No</th>

                        <th>Roll Number</th>

                        <th>Name</th>

                        <th>Date</th>

                        <th>Status</th>

                        <th>Actions</th>

                    </tr>

                </thead>


                <tbody id="attendanceTableBody">

                    <?php

                    $sno = 1;

                    while ($row = $result->fetch_assoc()) {

                    ?>

                    <tr>

                        <td>
                            <?php echo $sno++; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['roll_number']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['name']); ?>
                        </td>

                        <td>
                            <?php echo $row['date']; ?>
                        </td>

                        <td>

                            <?php

                            if ($row['status'] == "Present") {

                                echo '<span class="present-status">
                                        Present
                                      </span>';

                            } else {

                                echo '<span class="absent-status">
                                        Absent
                                      </span>';
                            }

                            ?>

                        </td>


                        <td>

                            <!-- EDIT -->

                            <a
                                href="attendance.php?edit=<?php echo $row['id']; ?>"
                                class="attendance-edit-btn"
                            >
                                Edit
                            </a>


                            <!-- DELETE -->

                            <form
                                method="POST"
                                style="display:inline;"
                                onsubmit="return confirm('Are you sure you want to delete this record?');"
                            >

                                <input
                                    type="hidden"
                                    name="delete_id"
                                    value="<?php echo $row['id']; ?>"
                                >

                                <button
                                    type="submit"
                                    class="attendance-delete-btn"
                                >
                                    Delete
                                </button>

                            </form>

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


<!-- SEARCH -->

<script>

function searchAttendance() {

    let input =
        document.getElementById("searchAttendance")
        .value
        .toLowerCase();

    let rows =
        document.querySelectorAll(
            "#attendanceTableBody tr"
        );

    rows.forEach(function(row) {

        let text =
            row.innerText.toLowerCase();

        if (text.includes(input)) {

            row.style.display = "";

        } else {

            row.style.display = "none";

        }

    });

}

</script>

</body>

</html>