<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM client WHERE id = $user_id")->fetch_assoc();
require 'includes/header.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if (empty($name) || empty($email)) {
        $message = '<div class="alert alert-danger">Name and email are required.</div>';
    } else {
        $name = $conn->real_escape_string($name);
        $email = $conn->real_escape_string($email);
        $address = $conn->real_escape_string($address);

        $sql = "UPDATE client SET name = '$name', email = '$email', adress = '$address' WHERE id = $user_id";
        
        if ($conn->query($sql)) {
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            Profile updated successfully!
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>';
            $user = $conn->query("SELECT * FROM client WHERE id = $user_id")->fetch_assoc();
        } else {
            $message = '<div class="alert alert-danger">Error updating profile: ' . $conn->error . '</div>';
        }
    }
}
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4">Edit Profile</h2>

            <?php echo $message; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($user['adress'] ?? ''); ?></textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="user-dashboard.php" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
