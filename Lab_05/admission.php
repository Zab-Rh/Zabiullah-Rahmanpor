<?php


require_once "db.php";


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $full_name = trim($_POST["full_name"] ?? "");
    $father_name = trim($_POST["father_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $program = trim($_POST["program"] ?? "");


    if (
        $full_name === "" ||
        $father_name === "" ||
        $email === "" ||
        $phone === "" ||
        $program === ""
    ) {

        header("Location: admission.php?error=required");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        header("Location: admission.php?error=email");
        exit;
    }


    $allowed_programs = [
        "Information Systems",
        "Software Engineering",
        "Computer Science"
    ];

    if (!in_array($program, $allowed_programs, true)) {

        header("Location: admission.php?error=program");
        exit;
    }

    $stmt = $conn->prepare(
        "INSERT INTO applications
        (full_name, father_name, email, phone, program)
        VALUES (?, ?, ?, ?, ?)"
    );
    if (!$stmt) {

        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param(
        "sssss",
        $full_name,
        $father_name,
        $email,
        $phone,
        $program
    );
    if ($stmt->execute()) {

        $stmt->close();
        header("Location: admission.php?success=1");
        exit;

    } else {

        $stmt->close();

        header("Location: admission.php?error=save");
        exit;
    }
}

$result = $conn->query(
    "SELECT
        id,
        full_name,
        father_name,
        email,
        phone,
        program
     FROM applications
     ORDER BY id DESC"
);

if (!$result) {

    die("Query failed: " . $conn->error);
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Student Admission Application</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"
    >

</head>


<body class="bg-light">

<div class="container py-5">

    <div class="text-center mb-5">

        <h1 class="fw-bold text-primary">
            Student Admission Application
        </h1>

        <p class="text-muted">
            Web Information Systems
        </p>

    </div>
    <div class="row justify-content-center">

        <div class="col-lg-8 col-md-10">


            <div class="card shadow">

                <div class="card-body p-4">


                    <h2 class="mb-4">
                        Admission Form
                    </h2>

                    <?php if (isset($_GET["success"])): ?>

                        <div class="alert alert-success alert-dismissible fade show">

                            Application submitted successfully!

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="alert">
                            </button>

                        </div>

                    <?php endif; ?>

                    <?php if (isset($_GET["error"])): ?>

                        <div class="alert alert-danger">

                            <?php

                            $error = $_GET["error"];


                            if ($error === "required") {

                                echo "All fields are required.";

                            } elseif ($error === "email") {

                                echo "Please enter a valid email address.";

                            } elseif ($error === "program") {

                                echo "Please select a valid program.";

                            } else {

                                echo "Application could not be saved.";

                            }

                            ?>

                        </div>

                    <?php endif; ?>

                    <form
                        method="POST"
                        action="admission.php"
            

                        <div class="mb-3">

                            <label
                                for="full_name"
                                class="form-label fw-semibold"
                            >
                                Full Name
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="full_name"
                                name="full_name"
                                maxlength="100"
                                placeholder="Enter your full name"
                                required
                            >

                        </div>

                        <div class="mb-3">

                            <label
                                for="father_name"
                                class="form-label fw-semibold"
                            >
                                Father's Name
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="father_name"
                                name="father_name"
                                maxlength="100"
                                placeholder="Enter father's name"
                                required
                            >

                        </div>

                        <div class="mb-3">

                            <label
                                for="email"
                                class="form-label fw-semibold"
                            >
                                Email
                            </label>

                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                maxlength="100"
                                placeholder="example@gmail.com"
                                required
                            >

                        </div>

                        <div class="mb-3">

                            <label
                                for="phone"
                                class="form-label fw-semibold"
                            >
                                Phone
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="phone"
                                name="phone"
                                maxlength="20"
                                placeholder="0700123456"
                                required
                            >

                            <div class="form-text">
                                Phone number is stored as text.
                            </div>

                        </div>

                        <div class="mb-4">

                            <label
                                for="program"
                                class="form-label fw-semibold"
                            >
                                Program
                            </label>


                            <select
                                class="form-select"
                                id="program"
                                name="program"
                                required
                            >

                                <option
                                    value=""
                                    selected
                                    disabled
                                >
                                    Select a program
                                </option>


                                <option value="Information Systems">
                                    Information Systems
                                </option>


                                <option value="Software Engineering">
                                    Software Engineering
                                </option>


                                <option value="Computer Science">
                                    Computer Science
                                </option>

                            </select>

                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary w-100 py-2"
                        >
                            Submit Application
                        </button>


                    </form>


                </div>

            </div>


        </div>

    </div>

    <div class="mt-5">


        <div class="d-flex justify-content-between align-items-center mb-3">

            <h2 class="fw-bold">
                Submitted Applications
            </h2>

            <span class="badge bg-primary">
                All Applicants
            </span>

        </div>


        <div class="table-responsive">


            <table
                class="table table-striped table-bordered table-hover align-middle shadow-sm"

                <thead class="table-dark">

                    <tr>

                        <th>ID</th>

                        <th>Full Name</th>

                        <th>Father's Name</th>

                        <th>Email</th>

                        <th>Phone</th>

                        <th>Program</th>

                    </tr>

                </thead>


                <tbody>


                    <?php if ($result->num_rows > 0): ?>


                        <?php while ($row = $result->fetch_assoc()): ?>


                            <tr>


                                <td>
                                    <?= htmlspecialchars($row["id"]) ?>
                                </td>


                                <td>
                                    <?= htmlspecialchars($row["full_name"]) ?>
                                </td>


                                <td>
                                    <?= htmlspecialchars($row["father_name"]) ?>
                                </td>


                                <td>
                                    <?= htmlspecialchars($row["email"]) ?>
                                </td>


                                <td>
                                    <?= htmlspecialchars($row["phone"]) ?>
                                </td>


                                <td>
                                    <?= htmlspecialchars($row["program"]) ?>
                                </td>


                            </tr>


                        <?php endwhile; ?>


                    <?php else: ?>


                        <tr>

                            <td
                                colspan="6"
                                class="text-center text-muted py-4"
                            >
                                No applications submitted yet.
                            </td>

                        </tr>


                    <?php endif; ?>


                </tbody>


            </table>


        </div>


    </div>


</div>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous">
</script>


</body>

</html>


<?php
$result->free();

$conn->close();

?>
