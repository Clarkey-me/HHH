<?php
include 'admin_protect.php';
include '../connect.php';

$res = $conn->query("
    SELECT user_id, first_name, last_name, email_address, created_at, status 
    FROM `user` 
    WHERE archived = 0 OR archived IS NULL
    ORDER BY created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - Admin</title>
    <link rel="stylesheet" href="adminCSS/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        .status-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: bold;
            font-size: 0.9em;
            cursor: pointer;
            transition: 0.3s;
        }
        .active-btn   { background: #27ae60; }   /* Green = Active / Unblocked */
        .blocked-btn  { background: #e74c3c; }   /* Red = Blocked */

        .delete-btn {
            background: #c0392b;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.9em;
            font-weight: bold;
        }
        .delete-btn:hover { background: #a93226; }

        #confirmModal {
            position: fixed; inset: 0; background: rgba(0,0,0,0.8);
            display: none; justify-content: center; align-items: center; z-index: 9999;
        }
        .modal-box {
            background: #2c3e50; padding: 30px; border-radius: 12px;
            width: 90%; max-width: 420px; text-align: center; color: white;
        }
        .modal-btn {
            padding: 10px 24px; margin: 8px; border: none; border-radius: 8px;
            font-weight: bold; cursor: pointer;
        }
        .cancel-btn { background: #7f8c8d; color: white; }
        .confirm-btn { background: #f1c40f; color: #000; }
    </style>
</head>
<body class="admin-page">

<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/header.php'; ?>

<main class="admin-container">
    <h2 style="margin-bottom:20px;">User Management</h2>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Joined</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while($u = $res->fetch_assoc()): ?>
            <?php 
            $isActive = ($u['status'] ?? 'active') === 'active';
            ?>
            <tr id="row-<?= $u['user_id'] ?>">
                <td><?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?></td>
                <td><?= htmlspecialchars($u['email_address']) ?></td>
                <td><?= date("M d, Y", strtotime($u['created_at'])) ?></td>

                <td>
                    <?php if ($isActive): ?>
                        <button class="status-btn active-btn"
                                onclick="openModal(<?= $u['user_id'] ?>, 'inactive', 'block')">
                            Active
                        </button>
                    <?php else: ?>
                        <button class="status-btn blocked-btn"
                                onclick="openModal(<?= $u['user_id'] ?>, 'active', 'unblock')">
                            Blocked
                        </button>
                    <?php endif; ?>
                </td>

                <td>
                    <a href="users.php?archive=<?= $u['user_id'] ?>"
                       class="delete-btn"
                       onclick="return confirm('Permanently delete this user? This action cannot be undone.')">
                        Delete
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</main>

<!-- MODAL -->
<div id="confirmModal">
    <div class="modal-box">
        <h3>Confirm Action</h3>
        <p id="modalText" style="margin:20px 0; font-size:1.1em; line-height:1.6;"></p>
        <button class="modal-btn cancel-btn" onclick="closeModal()">Cancel</button>
        <button class="modal-btn confirm-btn" id="confirmChangeBtn">Confirm</button>
    </div>
</div>

<?php include 'includes/scripts.php'; ?>

<script>
let selectedUser = null;
let newDbStatus = null;
let actionWord = null;

function openModal(userId, dbStatus, action) {
    selectedUser = userId;
    newDbStatus = dbStatus;
    actionWord = action;

    const name = document.querySelector(`#row-${userId} td:first-child`).textContent.trim();

    if (action === 'block') {
        document.getElementById("modalText").innerHTML = `
            <strong style="color:#e74c3c; font-size:1.2em;">Block this user?</strong><br><br>
            <strong>${name}</strong> will <strong>no longer be able to log in</strong>.<br><br>
            You can unblock them anytime.
        `;
    } else {
        document.getElementById("modalText").innerHTML = `
            <strong style="color:#27ae60; font-size:1.2em;">Unblock this user?</strong><br><br>
            <strong>${name}</strong> will regain access to their account.
        `;
    }

    document.getElementById("confirmModal").style.display = "flex";
}

function closeModal() {
    document.getElementById("confirmModal").style.display = "none";
    selectedUser = null;
    newDbStatus = null;
    actionWord = null;
}

document.getElementById("confirmChangeBtn").addEventListener("click", function() {
    this.disabled = true;
    this.textContent = "Saving...";

    const fd = new FormData();
    fd.append("user_id", selectedUser);
    fd.append("status", newDbStatus);

    fetch("update_status.php", { method: "POST", body: fd })
    .then(r => r.ok ? r.text() : Promise.reject())
    .then(() => {
        const row = document.getElementById("row-" + selectedUser);
        const cell = row.children[3];

        if (newDbStatus === 'active') {
            cell.innerHTML = `<button class="status-btn active-btn" onclick="openModal(${selectedUser}, 'inactive', 'block')">Active</button>`;
        } else {
            cell.innerHTML = `<button class="status-btn blocked-btn" onclick="openModal(${selectedUser}, 'active', 'unblock')">Blocked</button>`;
        }

        closeModal();
    })
    .catch(() => alert("Failed to update status. Please try again."))
    .finally(() => {
        this.disabled = false;
        this.textContent = "Confirm";
    });
});
</script>

</body>
</html>