<?= $this->extend('/administrator/adminmain'); ?>

<?= $this->section('css') ?>
    <style>
        .color-lime {
            color: #71dd37 !important;
        }

        .color-blue {
            color: #03c3ec !important;
        }

        .color-purple {
            color: #a2a1ff !important;
        }

    </style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div class="container-fluid">
        
        <div class="row my-3">
            <div class="col-6">
                <div class="card shadow mb-3">
                    <div class="card-body text-center">
                        <h1 class="color-purple"><i class="fa-solid fa-user-tie"></i></h1>
                        <small class="card-text">Agents</small>
                        <h1 class="text-center fw-bold"><?= $AgentsCount; ?></h1>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card shadow mb-3">
                    <div class="card-body text-center">
                        <h1 class="color-blue"><i class="fa-solid fa-house"></i></h1>
                        <small class="card-text">Developers</small>
                        <h1 class="text-center fw-bold"><?= $DevelopersCount; ?></h1>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card shadow mb-3">
                    <div class="card-body text-center">
                        <h1 class="color-purple"><i class="fa-solid fa-user"></i></h1>
                        <small class="card-text">Users</small>
                        <h1 class="text-center fw-bold"><?= $UsersCount; ?></h1>
                    </div>
                </div>
            </div>
        </div>


    </div>

<?= $this->endSection() ?>