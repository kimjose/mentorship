<?php /** @var Umb\Mentorship\Models\User $currUser */
if (!in_array($currUser->category_id, [1])): ?>
    <script>
        window.location.replace('index.php');
    </script>
<?php endif; ?>
<?php
$programs = \Umb\Mentorship\Models\Program::all();

/** @var \Umb\Mentorship\Models\User $currUser */
?>


<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between">
    <ol class="breadcrumb mb-4 transparent">
        <li class="breadcrumb-item">
            <a href="index">Home</a>
        </li>
        <li class="breadcrumb-item active"> Programs</li>
    </ol>

</div>
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <button data-toggle="modal" data-target="#modalProgram" class="btn btn-primary ml-auto float-right btn-icon-split" id="btnAddProgram">
            <span class="icon text-white-50"><i class="fa fa-plus"></i> </span>
            <span class="text"> Add Program</span>
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="tableEvents">
                <thead>
                    <tr>
                        <th>#</th>
                        <!-- <th>Logo</th> -->
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <!-- <th>Logo</th> -->
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($programs as $program) :
                    ?>
                        <tr>
                            <td><?php echo $i ?></td>
                            <!-- <td class="text-center"><img class="rounded-circle" src="../public/<?php echo $program->logo; ?>" alt="program_logo" style="height: 80px; width: 80px;"></td> -->
                            <td><?php echo $program->name ?></td>
                            <td>
                                <button data-toggle="modal" data-target="#modalProgram" data-tooltip="tooltip" title="Edit Program" class="btn btn-light btn-circle btn-sm" onclick='editProgram(<?php echo json_encode($program) ?>)'>
                                    <i class="fa fa-edit text-primary"></i></button>
                            </td>
                        </tr>

                    <?php $i++;
                    endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalProgram" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Program Dialog</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="" method="post" onsubmit="event.preventDefault()" id="formProgram">

                <div class="modal-body">
                    <div class="form-group d-none">
                        <label class="custom-control-label" for="inputName">Name</label>
                        <input type="text" class="form-control" id="inputCreator" name="created_by" value="<?php echo $currUser->id; ?>">
                    </div>
                    <div class="form-group">
                        <label class="custom-control-label" for="inputName">Name</label>
                        <input type="text" class="form-control" id="inputName" name="name" required placeholder="Program name">
                    </div>
                    <!-- <div class="form-group">
                        <label for="inputLogo">Program Logo</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input rounded-circle" id="inputLogo" name="upload_file" onchange="displayImg(this,$(this))">
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div> -->

                    </div>
                    <div class="form-group d-flex justify-content-center">
                        <img src="../public/logo.png" alt="" style="width: 200px; height: 200px;" id="cimg" class="img-fluid img-thumbnail">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" name="savebtn" id="btnSaveProgram" class="btn btn-primary" onclick="saveProgram()">Save
                    </button>
                </div>
            </form>

        </div>

    </div>

</div>

<script>
    let editedId = ''
    const inputName = document.getElementById('inputName')
    const inputLogo = document.getElementById('inputLogo')
    const formProgram = document.getElementById('formProgram')
    const currUser = JSON.parse('<?php echo $currUser; ?>')

    function initialize() {
        updateNav('navPrograms')

        $("#modalProgram").on("hide.bs.modal", () => {
            editedId = ''
            formProgram.reset()
        });

    }

    function displayImg(input, _this) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#cimg').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
            console.dir(input.files[0])
        }
    }

    function saveProgram() {
        let btnSaveProgram = document.getElementById('btnSaveProgram')
        let formData = new FormData(formProgram)
        let name = inputName.value
        let data = {
            name: name
        }
        data.created_by = currUser.id;
        data.upload_file = inputLogo.files[0]
        let saveUrl = '../api/program'
        let updateUrl = `../api/program/${editedId}`
        /*fetch(
                editedId === "" ? saveUrl : updateUrl, {

                    method: "POST",
                    body: JSON.stringify(formData),
                    headers: {
                        "content-type": "application/x-www-form-urlencoded",
                        cache: false,
                        processData: false
                    }
                }
            )
            .then(response => {
                return response.json()
            })
            .then(response => {
                if (response.code === 200) {
                    toastr.success("User added successfully.")
                    // window.location.reload()
                } else throw new Error(response.message)
                // hideModal(dialogId)
            })
            .catch(error => {
                console.log(error.message);
                toastr.error(error.message)
            })*/

        $.ajax({
            url: editedId === "" ? saveUrl : updateUrl,
            type: "POST",
            data: new FormData(formProgram),
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                btnSaveProgram.setAttribute('disabled', '')
                //$("#preview").fadeOut();
                // $("#err").fadeOut();
            },
            success: function(data) {
                let response = data
                if (response.code == 200) {
                    toastr.success("Program added successfully.")
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    btnSaveProgram.removeAttribute('disabled')
                    toastr.error(response.message)
                }
            },
            error: function(e) {
                btnSaveProgram.removeAttribute('disabled')
                toastr.error(e.message)
            }
        });
    }

    function editProgram(program){
        editedId = program.id;
        inputName.value = program.name;
        $('#cimg').attr('src', `../public/${program.logo}`)
    }

    initialize()
</script>