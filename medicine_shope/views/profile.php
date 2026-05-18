<?php
// This view shows the customer/admin profile edit form.
// It displays the current profile photo and allows a new image upload.
// The Medicine Shop logo is used automatically when no photo is available.

$title   = 'Profile';
$error   = $error ?? '';
$success = $success ?? '';

$user = $user ?? [
    'name'            => '',
    'email'           => '',
    'phone'           => '',
    'address'         => '',
    'role'            => 'customer',
    'profile_picture' => ''
];

$photo = !empty($user['profile_picture'])
    ? $user['profile_picture']
    : 'asset/Profile.png';

if (!file_exists($photo)) {
    $photo = 'asset/Profile.png';
}

require 'views/layout/header.php';
?>

<section class="page-title-box">
    <h1>My Profile</h1>
    <p>Update your personal information, password and profile picture.</p>
</section>

<?php if ($error !== ''): ?>
    <div class="alert error">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<?php if ($success !== ''): ?>
    <div class="alert success">
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>

<section class="profile-card">
    <div class="profile-photo-box">
        <img
            id="profilePreview"
            src="<?= htmlspecialchars($photo) ?>"
            alt="Profile Picture"
        >

        <h3><?= htmlspecialchars($user['name'] ?? '') ?></h3>

        <p>
            <?= htmlspecialchars(ucfirst($user['role'] ?? 'customer')) ?>
        </p>

        <span class="photo-help">
            JPG, PNG or WEBP, maximum 2 MB
        </span>
    </div>

    <form
        method="POST"
        enctype="multipart/form-data"
        onsubmit="return validateProfile()"
        class="profile-form"
    >
        <div class="form-grid">
            <div class="form-group">
                <label for="name">Name</label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="<?= htmlspecialchars($user['name'] ?? '') ?>"
                >
            </div>

            <div class="form-group">
                <label for="email">Email</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= htmlspecialchars($user['email'] ?? '') ?>"
                >
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>

                <input
                    type="text"
                    id="phone"
                    name="phone"
                    value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                >
            </div>

            <div class="form-group">
                <label for="profile_picture">Profile Picture</label>

                <input
                    type="file"
                    id="profile_picture"
                    name="profile_picture"
                    accept="image/*"
                    onchange="previewProfile(this)"
                >
            </div>
        </div>

        <div class="form-group full-row">
            <label for="address">Address</label>

            <textarea
                id="address"
                name="address"
            ><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
        </div>

        <div class="password-box">
            <h3>Password Change</h3>

            <p>
                Fill these two fields only when you want to change your password.
            </p>

            <div class="form-grid">
                <div class="form-group">
                    <label for="current_password">Current Password</label>

                    <input
                        type="password"
                        id="current_password"
                        name="current_password"
                        placeholder="Current password"
                    >
                </div>

                <div class="form-group">
                    <label for="new_password">New Password</label>

                    <input
                        type="password"
                        id="new_password"
                        name="new_password"
                        placeholder="Minimum 8 characters"
                    >
                </div>
            </div>
        </div>

        <button class="btn profile-submit" type="submit">
            Update Profile
        </button>
    </form>
</section>

<script>
function validateProfile() {
    let name = document.getElementById('name').value.trim();
    let email = document.getElementById('email').value.trim();
    let phone = document.getElementById('phone').value.trim();
    let address = document.getElementById('address').value.trim();

    if (name === '' || email === '' || phone === '' || address === '') {
        alert('Name, email, phone and address are required');
        return false;
    }

    let file = document.getElementById('profile_picture').files[0];

    if (file && file.size > 2 * 1024 * 1024) {
        alert('Profile picture must be less than 2 MB');
        return false;
    }

    return true;
}

function previewProfile(input) {
    if (input.files && input.files[0]) {
        document.getElementById('profilePreview').src = URL.createObjectURL(input.files[0]);
    }
}
</script>

<?php require 'views/layout/footer.php'; ?>