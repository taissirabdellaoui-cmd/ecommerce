<?php
require 'config/db.php';
require 'includes/header.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $address = trim($_POST['address'] ?? '');

    if (empty($name)) $errors[] = 'Name is required';
    if (empty($email)) $errors[] = 'Email is required';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email format';
    if (empty($password)) $errors[] = 'Password is required';
    if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters';
    if ($password !== $confirm_password) $errors[] = 'Passwords do not match';

    if (empty($errors)) {
        $check = $conn->query("SELECT id FROM client WHERE email = '" . $conn->real_escape_string($email) . "'");
        if ($check->num_rows > 0) {
            $errors[] = 'Email already registered';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $name = $conn->real_escape_string($name);
            $email = $conn->real_escape_string($email);
            $address = $conn->real_escape_string($address);

            $sql = "INSERT INTO client (name, email, adress, password, is_admin) VALUES ('$name', '$email', '$address', '$hashed_password', 0)";
            if ($conn->query($sql)) {
                $success = true;
                $_SESSION['register_success'] = true;
                header("Location: login.php?registered=1");
                exit;
            } else {
                $errors[] = 'Error creating account: ' . $conn->error;
            }
        }
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow" style="background-color: #1a1a1a; border: 1px solid rgba(0, 212, 255, 0.2); border-radius: 0;">
                <div class="card-body p-5">
                    <h2 class="card-title mb-4 text-center" style="font-family: 'Bebas Neue', serif; letter-spacing: 2px; font-weight: 900; color: #00d4ff; font-size: 2rem; text-shadow: 3px 3px 0px rgba(0, 255, 136, 0.4), 2px 2px 0px rgba(0, 0, 0, 0.8);">JOIN STYLE SPARK</h2>

                    <?php if (!empty($errors)): ?>
                        <div class="alert" style="background-color: rgba(220, 53, 69, 0.1); border: 1px solid #dc3545; color: #ff6b6b; border-radius: 4px;">
                            <?php foreach ($errors as $error): ?>
                                <div style="margin: 5px 0;"><?php echo htmlspecialchars($error); ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label" style="text-transform: uppercase; font-weight: 600; font-size: 0.85rem; letter-spacing: 1px; color: #b0b0b0;">Full Name *</label>
                            <input type="text" class="form-control" id="name" name="name" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" style="background-color: #0f0f0f; border: 1px solid #333; color: #fff;">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label" style="text-transform: uppercase; font-weight: 600; font-size: 0.85rem; letter-spacing: 1px; color: #b0b0b0;">Email Address *</label>
                            <input type="email" class="form-control" id="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" style="background-color: #0f0f0f; border: 1px solid #333; color: #fff;">
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label" style="text-transform: uppercase; font-weight: 600; font-size: 0.85rem; letter-spacing: 1px; color: #b0b0b0;">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3" style="background-color: #0f0f0f; border: 1px solid #333; color: #fff;"><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label" style="text-transform: uppercase; font-weight: 600; font-size: 0.85rem; letter-spacing: 1px; color: #b0b0b0;">Password *</label>
                            <input type="password" class="form-control" id="password" name="password" required style="background-color: #0f0f0f; border: 1px solid #333; color: #fff;">
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label" style="text-transform: uppercase; font-weight: 600; font-size: 0.85rem; letter-spacing: 1px; color: #b0b0b0;">Confirm Password *</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required style="background-color: #0f0f0f; border: 1px solid #333; color: #fff;">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3" style="text-transform: uppercase; font-weight: 900; letter-spacing: 1px;">Create Account</button>
                    </form>

                    <div class="text-center">
                        <p class="text-muted">Already have an account? <a href="login.php">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
