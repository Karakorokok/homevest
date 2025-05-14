<?= $this->extend('/shared/main'); ?>

<?= $this->section('content') ?>

    <div class="container">
        <div class="my-3">
            <div class="d-flex">
                <a href="/agentslist" class="ms-3">
                    <i class="fa-solid fa-arrow-left"></i>&nbsp;
                </a>
            </div>
            <div class="mt-3 mb-3 d-flex justify-content-center">
                <div class="d-inline-flex position-relative">
                    <?php 
                        $pngPath = "/resources/images/profilepics/{$UserData['id_user']}.png";
                        $jpgPath = "/resources/images/profilepics/{$UserData['id_user']}.jpg";

                        $profilepic = file_exists(FCPATH . ltrim($pngPath, '/')) 
                            ? $pngPath 
                            : (file_exists(FCPATH . ltrim($jpgPath, '/')) 
                            ? $jpgPath 
                            : "/resources/images/default.png");
                    ?>

                    <img src="<?= $profilepic; ?>" class="profilepic rounded-circle border" height="100" width="100" />
                </div>
            
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
        </div>

        <div>
            <div class="m-3">
                <div class="mb-3 text-muted" style="text-align: justify;"><small><?= !empty($UserData['description']) ? $UserData['description'] : 'No description provided' ?></small></div>
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
        </div>

        <div style="height: 80px;"></div>
    
    </div>

<?= $this->endSection() ?>
