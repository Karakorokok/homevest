<?= $this->extend('/administrator/adminmain'); ?>

<?= $this->section('css') ?>
    <style>
     
    </style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div class="container-fluid">

        <div class="mt-5 mb-3 d-flex justify-content-center">
            <div class="d-inline-flex position-relative">
                <img src="/resources/images/admin.png" class="rounded-circle border" height="100" width="100" />
                
            </div>
        </div>
        <div class="text-center mb-3">
            <?= $Admin['firstname'] . $Admin['lastname']; ?>
        </div>
        <div class="text-center text-primary">
            Official Administrator account
        </div>
       
    </div>

<?= $this->endSection() ?>