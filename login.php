<?php
session_start();
require 'config/db.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        $email = $conn->real_escape_string($email);
        $result = $conn->query("SELECT id, name, email, password, is_admin FROM client WHERE email = '$email'");
        
        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['is_admin'] = $user['is_admin'];
                if ($user['is_admin']) {
                    header("Location: admin/dashboard.php");
                } else {
                    $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';
                    header("Location: " . htmlspecialchars($redirect));
                }
                exit;
            }
        }
    }
}
require 'includes/header.php';
if (isset($_GET['registered'])) {
    $message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Registration successful! Please log in with password: <strong>12345678</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>';
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($email) || empty($password)) {
        $message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Email and password are required.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>';
    } else {
        $message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Invalid email or password.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>';
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow" style="background-color: #0f0f0f; border: 2px solid rgba(0, 212, 255, 0.3); border-radius: 4px;">
                <div class="card-body p-5">
                    <h2 class="card-title mb-4 text-center" style="font-family: 'Bebas Neue', serif; letter-spacing: 2px; font-weight: 900; color: #00d4ff; font-size: 2rem; text-shadow: 3px 3px 0px rgba(0, 255, 136, 0.4), 2px 2px 0px rgba(0, 0, 0, 0.8);">LOGIN</h2>

                    <?php echo $message; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label" style="text-transform: uppercase; font-weight: 600; font-size: 0.85rem; letter-spacing: 1px; color: #b0b0b0;">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" style="background-color: #1a1a1a; border: 1px solid #333; color: #fff;">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label" style="text-transform: uppercase; font-weight: 600; font-size: 0.85rem; letter-spacing: 1px; color: #b0b0b0;">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required style="background-color: #1a1a1a; border: 1px solid #333; color: #fff;">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3" style="text-transform: uppercase; font-weight: 900; letter-spacing: 1px;">Enter the Scene</button>
                    </form>

                    <div class="text-center">
                        <p class="text-muted mb-0" style="font-size: 0.9rem; color: #b0b0b0;">No account? <a href="register.php" style="color: #00d4ff; text-decoration: none; font-weight: 600;">Join Style Spark</a></p>
                    </div>
                </div>
            </div>
            

        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
