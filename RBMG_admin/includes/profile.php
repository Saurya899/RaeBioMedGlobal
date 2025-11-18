    <?php
    include("../config/db.php");

    $select = "SELECT * from admin where email ='$admin_email'";
    $res = mysqli_query($conn, $select);
    $profile = mysqli_fetch_assoc($res);

    ?>
    <!-- Profile Modal -->
    <div class="modal fade" id="profileModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">My Profile</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body text-center">
            <!-- Profile Image -->
            <img src="../assets/images/<?php echo $profile['image'] ?>" id="profileImage" class="rounded-circle profile-img mb-3" alt="Profile Image">

            <!-- Name -->
            <div class="mb-3 text-start">
              <label class="form-label">Name</label>
              <input type="text" class="form-control" id="adminName" value="<?php echo $profile['name'] ?>" readonly>
            </div>

            <!-- Email -->
            <div class="mb-3 text-start">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" id="adminEmail" value="<?php echo $profile['email'] ?>" readonly>
            </div>

            <!-- Mobile -->
            <div class="mb-3 text-start">
              <label class="form-label">Mobile</label>
              <input type="text" class="form-control" id="adminMobile" value="<?php echo $profile['mobile'] ?>" readonly>
            </div>

            <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#editProfileModal" data-bs-dismiss="modal">
              Edit Profile
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Profile</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <form id="updateProfileForm" enctype="multipart/form-data">
            <div class="modal-body text-start">
              <div class="text-center mb-3">
                <img src="../assets/images/<?php echo $profile['image'] ?>" id="editProfileImage" class="rounded-circle profile-img mb-2" alt="Profile Image">
                <input type="file" class="form-control mt-2" name="image">
              </div>
              <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" class="form-control" name="name" value="<?php echo $profile['name'] ?>" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="<?php echo $profile['email'] ?>" readonly>
              </div>
              <div class="mb-3">
                <label class="form-label">Mobile</label>
                <input type="text" class="form-control" name="mobile" value="<?php echo $profile['mobile'] ?>" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-success">Update Profile</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Change Password Modal -->
    <div class="modal fade" id="passwordModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title">Change Password</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <div class="modal-body">
            <form id="passwordForm">
              <div class="mb-3">
                <label for="currentPassword" class="form-label">Current Password</label>
                <input type="password" name="current" id="currentPassword" class="form-control" required>
              </div>

              <div class="mb-3">
                <label for="newPassword" class="form-label">New Password</label>
                <input type="password" name="new" id="newPassword" class="form-control" required>
              </div>

              <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirm New Password</label>
                <input type="password" name="confirm" id="confirmPassword" class="form-control" required>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Password</button>
              </div>
            </form>
          </div>

        </div>
      </div>
    </div>

    <!-- Change Email Modal -->
    <div class="modal fade" id="emailModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title">Change Email</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <div class="modal-body">
            <form id="emailForm">
              <div class="mb-3">
                <label for="currentEmail" class="form-label">Current Email</label>
                <input type="email" name="current" id="currentEmail" class="form-control" required>
              </div>

              <div class="mb-3">
                <label for="newEmail" class="form-label">New Email</label>
                <input type="email" name="new" id="newEmail" class="form-control" required>
              </div>

              <div class="mb-3">
                <label for="confirmEmail" class="form-label">Confirm New Email</label>
                <input type="email" name="confirm" id="confirmEmail" class="form-control" required>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Email</button>
              </div>
            </form>
          </div>

        </div>
      </div>
    </div>