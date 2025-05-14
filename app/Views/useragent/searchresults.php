<?= $this->extend('/shared/main'); ?>

<?= $this->section('content') ?>

    <nav class="navbar bg-light shadow-sm fixed-top">
        <div class="container-fluid">
                <img src="/resources/images/logo.png" height="45">
                <form action="/searchquery" method="POST" class="d-flex input-group w-auto">
                        <input
                                type="text"
                                name="searchquery"
                                class="form-control rounded"
                                placeholder="Search"
                        />
                        <button type="submit" class="btn btn-link px-3">
                                <i class="fas fa-search fa-lg text-dark"></i>
                        </button>
                </form>
        </div>
    </nav>

    <div class="container-fluid" style="position: relative; padding-top: 70px;">
        <?php if (empty($Results)): ?>
            <p class="text-center my-3">No results found.</p>
        <?php else: ?>
            <?php foreach ($Results as $res): ?>
                <p class="text-center my-3">Wrong redirect.</p>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>


<?= $this->endSection() ?>