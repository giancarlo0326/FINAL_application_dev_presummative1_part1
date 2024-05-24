<?php
session_start();

// Remove session_unset(); to retain session data across page loads
// session_unset(); 

// Initialize data array in session if not set
if (!isset($_SESSION['data'])) {
    $_SESSION['data'] = [];
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete_all'])) {
        // Delete all data
        $_SESSION['data'] = [];
    } else {
        if (count($_SESSION['data']) < 10) {
            $name = $_POST['name'];
            $age = $_POST['age'];
            $birthday = $_POST['birthday'];
            $contact_number = $_POST['contact_number'];

            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image_tmp_name = $_FILES['image']['tmp_name'];
                $image_name = uniqid() . '-' . $_FILES['image']['name'];
                $upload_dir = 'uploads/';
                $image_path = $upload_dir . $image_name;

                // Ensure upload directory exists
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                move_uploaded_file($image_tmp_name, $image_path);

                // Store data in session
                $_SESSION['data'][] = [
                    'name' => $name,
                    'age' => $age,
                    'birthday' => $birthday,
                    'contact_number' => $contact_number,
                    'image_path' => $image_path
                ];
            }
            // Redirect to prevent form resubmission on refresh
            header("Location: {$_SERVER['PHP_SELF']}");
            exit();
        } else {
            $error = "You can only add up to 10 entries.";
        }
    }
}

// Sort the data by name in ascending order, ignoring case
usort($_SESSION['data'], function ($a, $b) {
    // Convert names to lowercase for case-insensitive comparison
    $nameA = strtolower($a['name']);
    $nameB = strtolower($b['name']);
    return strcmp($nameA, $nameB);
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>[M3-FORMATIVE] PHP Arrays and User Defined Functions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="https://i.postimg.cc/HxqPdwb1/gianatics-logo-white.png" class="header-img" alt="Logo">
            </a>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="centered-container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="greetings">
                                <h1>Hello!</h1>
                            </div>
                            <div>
                                <h2>You can add 10 profiles of your friends here!</h2>
                                <form id="data-form" method="post" enctype="multipart/form-data">
                                    <label for="name">Name:</label><br>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="name" name="name" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" pattern="[A-Za-z\s]+" title="Only letters and spaces are allowed" required>
                                    </div>

                                    <label for="image">Image:</label><br>
                                    <div class="input-group mb-3">
                                        <input type="file" id="image" name="image" accept="image/*" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" required>
                                    </div>

                                    <label for="age">Age:</label><br>
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control" id="age" name="age" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" required>
                                    </div>
                                    <label for="birthday">Birthday:</label><br>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" id="birthday" name="birthday" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" required>
                                    </div>
                                    <label for="contact_number">Contact Number:</label><br>
                                    <div class="input-group mb-3">
                                        <input type="tel" class="form-control" id="contact_number" name="contact_number" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" required>
                                    </div>

                                    <button type="submit" class="green-button">Add Data</button>
                                </form>
                                <?php if (isset($error)): ?>
                                    <div class="alert alert-danger mt-3"><?php echo $error; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div id="image-container">
                                <img src="https://c1.wallpaperflare.com/preview/597/640/878/technology-business-man-marketing.jpg" class="img-fluid" alt="Responsive image">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container d-flex justify-content-center align-items-center vh-50">
        <h1>You can find your data here!</h1>
    </div>

    <div class="container" id="cards-container">
        <!-- Data Entries will be inserted here -->
        <div class="row">
            <?php foreach ($_SESSION['data'] as $entry): ?>
                <div class="col-12 col-md-6 col-lg-3 d-flex justify-content-center">
                    <div class="card bg-dark text-white mb-3">
                        <img src="<?php echo $entry['image_path']; ?>" class="card-img-top" alt="Image">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($entry['name']); ?></h5>
                            <p class="card-text">
                                Age: <?php echo htmlspecialchars($entry['age']); ?><br>
                                Birthday: <?php echo htmlspecialchars($entry['birthday']); ?><br>
                                Contact Number: <?php echo htmlspecialchars($entry['contact_number']); ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="container d-flex justify-content-center align-items-center vh-50">
        <form method="post">
            <button type="submit" name="delete_all" class="btn btn-danger">Delete All Data</button>
        </form>
    </div>

    <p></p>

    <footer class="bg-dark text-light">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="footerBottom text-center text-md-start">
                        <h1>APPLICATIONS DEVELOPMENT AND EMERGING TECHNOLOGIES</h1>
                        <p></p>
                        <h4>[M3-FORMATIVE] PHP Arrays and User Defined Functions</h4>
                        <h4>Pre Summative 3 - 1</h4>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="footerBottom text-center text-md-center">
                        <img src="https://i.postimg.cc/HxqPdwb1/gianatics-logo-white.png" class="collab-img img-fluid">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="footerBottom text-center text-md-end">
                        <h1>DISCLAIMER</h1>
                        <p>This website is for educational purposes only and no copyright infringement is intended.</p>
                        <p>Copyright &copy;2024; All images used in this website are from the Internet.</p>
                        <p>Designed by <a href="https://github.com/giancarlo0326">GIAN CARLO S. VICTORINO</a>, BSITWMA - FEU TECH</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
