<?php

/** @var \Umb\Mentorship\Models\User $currUser */
?>


<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between">
    <ol class="breadcrumb mb-4 transparent">
        <li class="breadcrumb-item">
            <a href="index">Home</a>
        </li>
        <li class="breadcrumb-item active"> User Profile</li>
    </ol>

</div>

<div class="card shadow mb-4 border-primary">
    <div class="card-header py-3">
        <h4 class="text text-center">User Profile</h4>
    </div>
    <div class="card-body">

        <form action="" method="POST" onsubmit="event.preventDefault();" id="formUser">

            <div class="row">

                <div class="col-md-6 col-sm-12 form-group">
                    <label for="inputLastName">Last Name</label>
                    <input type="text" class="form-control" id="inputLastName" required name="last_name" value="<?php echo $currUser->last_name ?>" placeholder="Last Name">
                </div>
                <div class="col-md-6 col-sm-12 form-group">
                    <label for="inputFirstName">First Name</label>
                    <input type="text" class="form-control" id="inputFirstName" required name="first_name" value="<?php echo $currUser->first_name ?>" placeholder="First Name">
                </div>
                <div class="col-md-6 col-sm-12 form-group">
                    <label for="inputMiddleName">Middle Name</label>
                    <input type="text" class="form-control" id="inputMiddleName" required name="middle_name" value="<?php echo $currUser->middle_name ?>" placeholder="Middle Name">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPhoneNumber">Phone Number</label>
                <input type="number" class="form-control" id="inputPhoneNumber" name="phone_number" value="<?php echo $currUser->phone_number ?>" maxlength="10" placeholder="07********" required>
            </div>
            <div class="form-group">
                <label for="inputEmail">Email</label>
                <input type="text" class="form-control" id="inputEmail" name="email" value="<?php echo $currUser->email ?>" placeholder="Enter email name" required>
            </div>
            <div class="form-group">
                <label for="inputPassword">Password <small>(Leave blank if you don't wish to change it)</small></label>
                <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Enter Password">
            </div>
            <div class="form-group">
                <label for="inputConfirmPassword">Password <small>(Leave blank if you don't wish to change it)</small></label>
                <input type="password" class="form-control" id="inputConfirmPassword" name="password_confirm" placeholder="Repeat Password">
            </div>
            <div class="sticky-footer">
                <a href="index.php" type="button" class="btn btn-danger">Close</a>
                <button type="submit" name="savebtn" id="btnUpdatePrle" class="btn btn-primary" onclick="updateProfile()">Update Profile
                </button>
            </div>
            <small><span class="text-center text-primary">NB: Changes to the user profile will be effected on next login.</span></small>
        </form>
    </div>
</div>

<script type="text/javascript">
    const inputFirstName = document.querySelector("#inputFirstName")
    const inputMiddleName = document.querySelector("#inputMiddleName")
    const inputLastName = document.querySelector("#inputLastName")
    const inputPhoneNumber = document.querySelector("#inputPhoneNumber")
    const inputEmail = document.querySelector("#inputEmail")
    const inputPassword = document.querySelector("#inputPassword")
    const inputConfirmPassword = document.querySelector("#inputConfirmPassword")

    function updateProfile() {
        let firstName = inputFirstName.value.trim();
        let middleName = inputMiddleName.value.trim()
        let lastName = inputLastName.value.trim();
        let phoneNumber = inputPhoneNumber.value.trim();
        let email = inputEmail.value.trim();
        let password = inputPassword.value.trim();
        let passwordConfirm = inputConfirmPassword.value.trim();

        if (firstName === '') {
            toastr.error('Enter the first name')
            inputFirstName.focus()
            return
        }
        if (middleName === '') {
            toastr.error('Enter the middle name')
            inputMiddleName.focus()
            return
        }
        if (lastName === '') {
            toastr.error('Enter the last name')
            inputLastName.focus()
            return
        }
        if (phoneNumber === '' && phoneNumber.length < 10) {
            toastr.error('Enter a valid phone number')
            inputPhoneNumber.focus()
            return
        }
        if (password !== passwordConfirm) {
            toastr.error("Enter similar passwords")
            inputConfirmPassword.focus()
            return;
        }
        let data = {
            email: email,
            phone_number: phoneNumber,
            first_name: firstName,
            middle_name: middleName,
            last_name: lastName,
            password: password,
        }
        fetch(
                `../api/user_profile_update/${user.id}`, {

                    method: "POST",
                    body: JSON.stringify(data),
                    headers: {
                        "content-type": "application/x-www-form-urlencoded"
                    }
                }
            )
            .then(response => {
                return response.json()
            })
            .then(response => {
                if (response.code === 200) {
                    toastr.success("Profile updated successfully.")
                    setTimeout(() => {
                        window.location.reload()
                    }, 909)
                } else throw new Error(response.message)
                // hideModal(dialogId)
            })
            .catch(error => {
                console.log(error.message);
                toastr.error(error.message)
            })
    }
</script>