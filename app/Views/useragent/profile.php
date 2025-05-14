<?= $this->extend('/shared/main'); ?>

<?php

    $sessionUserID = session()->get('id_user');
    $sessionUserRole = session()->get('user_role');

?>
<?= $this->section('css') ?>
    <style>

        .blurred-sides {
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .blurred-sides::before,
        .blurred-sides::after {
            content: "";
            position: absolute;
            top: 0;
            width: 30%;
            height: 100%;
            background-image: var(--bg-img);
            background-size: cover;
            background-repeat: no-repeat;
            filter: blur(6px);
            transform: scale(1.2);
            z-index: 1;
        }

        .blurred-sides::before {
            left: 0;
            background-position: center left;
        }

        .blurred-sides::after {
            right: 0;
            background-position: center right;
        }

        .blurred-sides img {
            position: relative;
            z-index: 2;
            max-width: 70%;
            height: auto;
            margin: 0 -5%; 
        }

    </style>

<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div class="container">
        <div class="my-3">
            <div class="d-flex">
                <a href="" class="btn btn-tertiary ms-auto me-3" data-mdb-modal-init data-mdb-target="#logoutModal">Logout</a>
            </div>

            <div class="mt-3 mb-3 d-flex justify-content-center">
                <div class="d-inline-flex position-relative">
                    <input class="inputUploadProfilePhoto d-none" type="file" accept="image/*"/>
                    <button class="btnUploadProfilePhoto btn btn-danger position-absolute bottom-0 end-0 rounded-circle p-0" 
                        style="width: 40px; height: 40px;"
                    >
                        <i class="fa-solid fa-camera"></i>
                    </button>

                    <?php 
                        $pngPath = "/resources/images/profilepics/$sessionUserID.png";
                        $jpgPath = "/resources/images/profilepics/$sessionUserID.jpg";

                        $profilepic = file_exists(FCPATH . ltrim($pngPath, '/')) 
                            ? $pngPath 
                            : (file_exists(FCPATH . ltrim($jpgPath, '/')) 
                            ? $jpgPath 
                            : "/resources/images/default.png");
                    ?>

                    <!--<img src="<?//= $profilepic; ?>" class="previewProfilePhoto profilepic rounded-circle border" height="100" width="100" />-->
                    <img src="<?= $profilepic . '?t=' . time(); ?>" class="previewProfilePhoto profilepic rounded-circle border" height="100" width="100" />

                </div>
            
            </div>
            <div class="d-flex justify-content-center">
                <button class="btnCancelPhoto btn btn-sm btn-secondary me-3 mb-3 d-none">Cancel</button>
                <button class="btnSavePhoto btn btn-sm btn-secondary mb-3 d-none">Save photo</button>
            </div>
            <h4 class="mb-2 text-center">
                <?= $UserData['firstname'] . ' ' . 
                    (!empty($UserData['middlename']) ? strtoupper(substr($UserData['middlename'], 0, 1)) . '. ' : '') . 
                    $UserData['lastname']; 
                ?>
            </h4>
            <div class="text-center mb-3">
                <div>
                    <?= !empty($UserData['phone']) ? $UserData['phone'] : 'Phone not set'; ?>
                </div>
                <div>
                    <?= !empty($UserData['email']) ? $UserData['email'] : 'Email not set'; ?>
                </div>
            </div>
            
            <div class="d-flex justify-content-center">
                <button class="btn btn-outline-primary"  data-mdb-modal-init data-mdb-target="#editProfileModal">Edit Profile</button>
            </div>
          
        </div>

        <div>
            <?php 
                if($sessionUserRole == 1) { ?>
                
                <div class="m-3">
                    <div class="fw-bold mb-3">Favorites</div>

                    <div class="d-flex flex-row overflow-auto gap-3 pb-3">
                    <?php foreach ($Favorites as $fav): 
                        $basePath = "/resources/images/modelphotos/" . $fav['id_restate'] . "_1";
                        $jpgPath = $_SERVER['DOCUMENT_ROOT'] . $basePath . ".jpg";
                        $pngPath = $_SERVER['DOCUMENT_ROOT'] . $basePath . ".png";

                        if (file_exists($jpgPath)) {
                            $imgSrc = $basePath . ".jpg";
                        } 
                        elseif (file_exists($pngPath)) {
                            $imgSrc = $basePath . ".png";
                        } 
                        else {
                            $imgSrc = "/resources/images/modelphotos/default.png";
                        }
                    ?>
                        <div class="bg-white rounded" style="min-width: 200px; max-width: 250px; flex: 0 0 auto;">
                            <div class="blurred-sides mb-1" style="--bg-img: url('<?= $imgSrc ?>')">
                                <img src="<?= $imgSrc ?>" alt="House Model" class="w-100">
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-center"><?= $fav['model']; ?></div>
                                <a href="/modeldetails/<?= $fav['id_restate'] ?>" class="btn btn-sm btn-outline-primary">See Details</a>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    </div>

                </div>

            <?php 
                }
                else if($sessionUserRole == 2) { ?>
                   <div class="m-3">
                        <div class="mb-3 text-muted" style="text-align: justify;"><small><?= !empty($UserData['description']) ? $UserData['description'] : 'Enter description in Edit Profile' ?></small></div>
                        <div class="row">
                            <div class="col-6">
                                <div>
                                    <small class="fw-bold">Affiliation</small>
                                </div>
                                <?php 
                                    foreach ($UserAffiliation as $ua): ?>
                                    <div><?= $ua['developer_name']; ?></div>
                                <?php endforeach; ?>
                            </div>
                            <div class="col-6">
                                <div>
                                    <small class="fw-bold">Experience</small>
                                </div>
                                <div><?= $UserData['experience']; ?>&nbsp;Years</div>
                            </div>
                        </div>
                    </div>
                <?php
                }
            ?>
        </div>

        <div style="height: 80px;"></div>

        <!-- edit profile modal -->
        <div class="modal fade" id="editProfileModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="/profile/editprofile" method="POST">
                        <div class="modal-body">
                            <div class="mb-3">
                                <a class="btn btn-tertiary">Edit Profile</a>
                            </div>
                            <div class="form-outline mb-3" data-mdb-input-init>
                                <input type="text" class="form-control" 
                                    value="<?= $UserData['lastname'] ?>" spellcheck="false" autocomplete="off"
                                    name="lastname" />
                                <label class="form-label">Lastname</label>
                            </div>
                            <div class="form-outline mb-3" data-mdb-input-init>
                                <input type="text" class="form-control" 
                                    value="<?= $UserData['middlename'] ?>" spellcheck="false" autocomplete="off"
                                    name="middlename" />
                                <label class="form-label">Middlename</label>
                            </div>
                            <div class="form-outline mb-3" data-mdb-input-init>
                                <input type="text" class="form-control" 
                                    value="<?= $UserData['firstname'] ?>" spellcheck="false" autocomplete="off"
                                    name="firstname" />
                                <label class="form-label">Firstname</label>
                            </div>
                            <div class="form-outline mb-3" data-mdb-input-init>
                                <input type="text" class="form-control" 
                                    value="<?= $UserData['email'] ?>" spellcheck="false" autocomplete="off"
                                    name="email" />
                                <label class="form-label">Email</label>
                            </div>
                            <div class="form-outline mb-3" data-mdb-input-init>
                                <input type="text" class="form-control" 
                                    value="<?= $UserData['phone'] ?>" spellcheck="false" autocomplete="off"
                                    name="phone" />
                                <label class="form-label">Phone</label>
                            </div>
                            <div class="form-outline mb-3" data-mdb-input-init>
                                <input type="text" class="form-control" 
                                    value="<?= $UserData['address'] ?>" spellcheck="false" autocomplete="off"
                                    name="address" />
                                <label class="form-label">Address</label>
                            </div>
                            <?php 
                                if($sessionUserRole == 2) { ?>
                                <button class="btn btn-tertiary">Professional Info</button>
                                <div data-mdb-input-init class="form-outline">
                                    <textarea class="form-control" spellcheck="false" autocomplete="off"
                                        name="description" rows="3"><?= $UserData['description'] ?></textarea>
                                    <label class="form-label">Description</label>
                                </div>
                                <small>For experience enter a number only in years</small>
                                <div class="form-outline mb-3" data-mdb-input-init>
                                    <input type="text" class="form-control" 
                                        value="<?= $UserData['experience'] ?>" spellcheck="false" autocomplete="off"
                                        name="experience" />
                                    <label class="form-label">Experience</label>
                                </div>
                                <div class="form-outline mb-3" data-mdb-input-init>
                                    <input type="text" class="form-control" 
                                        value="<?= $UserData['license'] ?>" spellcheck="false" autocomplete="off"
                                        name="license" />
                                    <label class="form-label">License (if applicable)</label>
                                </div>
                                <?php
                                }
                            ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" data-mdb-ripple-init>Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- logout modal -->
        <div class="modal fade" id="logoutModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="text-center">
                            <a class="btn btn-tertiary">Confirm Logout</a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">Cancel</button>
                        <a href="/logout" type="button" class="btn btn-primary" data-mdb-ripple-init>Logout</a>
                    </div>
                </div>
            </div>
        </div>
    
    </div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>

    <script>
        $(document).ready(function () {

            $(".btnUploadProfilePhoto").on('click', function () {
                $(".inputUploadProfilePhoto").click();
            });

            $(".inputUploadProfilePhoto").on('change', function () {

                const selectedFile = $(this)[0].files[0];

                if(selectedFile) {
                    const fileType = selectedFile.type;
                    const fileSize = selectedFile.size;
                    const maxFileSize = 100 * 1024 * 1024;
                    const validTypes = ["image/jpeg", "image/png"];

                    if(!validTypes.includes(fileType)) {
                        toggleAlertToast('Image should be jpg or png only.', 'error');
                        $(this).val("");
                        return;
                    }

                    if (fileSize > maxFileSize) {
                        toggleAlertToast('Image size should not exceed 5mb.', 'error');
                        $(this).val("");
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function (e) {
                        $('.previewProfilePhoto').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(selectedFile);

                    $(".btnCancelPhoto").removeClass('d-none');
                    $(".btnSavePhoto").removeClass('d-none');
                } 
                else {
                    $(".btnCancelPhoto").addClass('d-none');
                    $(".btnSavePhoto").addClass('d-none');
                }
            });

            $('.btnCancelPhoto').on('click', function () {
                location.reload();
            });

            $('.btnSavePhoto').on('click', function () {

                const selectedFile = $(".inputUploadProfilePhoto")[0].files[0];

                if(selectedFile) {
                    const formData = new FormData();
                    formData.append('profilephoto', selectedFile);

                    $.ajax({
                        url: '/profile/saveprofilephoto',
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            if (response.success) {
                                location.reload();
                            } 
                            else {
                                /** TODO */
                            }
                        },
                        error: function (xhr, status, error) {
                            /** TODO */
                        }
                    });
                } 
                else {
                    /** TODO */
                }
            });

        });
    </script>

<?= $this->endSection() ?>