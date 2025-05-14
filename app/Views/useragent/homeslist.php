<?= $this->extend('/shared/main'); ?>

<?= $this->section('css') ?>

    <style>

        @media (max-width: 768px) {
            .my-offcanvas {
                width: 90% !important; 
            }
        }

        body {
            background-color: rgb(225,225,229) !important;
        }

        .case {
            text-transform: none !important;
        }

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

    <nav class="navbar bg-light sticky-top">
        <div class="container-fluid">
            <a data-mdb-toggle="offcanvas" data-mdb-offcanvas-init href="#homeListSidebar" role="button" class="">
                <i class="fa-solid fa-bars text-dark"></i>
                <span class="ms-3 text-dark"><?= $selectedDeveloper; ?>&nbsp;Homes</span>
            </a>
            <div></div>
        </div>
    </nav>

    <!-- offcanvas sidebar -->
    <div class="my-offcanvas offcanvas offcanvas-start" tabindex="-1" id="homeListSidebar">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">Select Developer</h5>
            <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn-close text-reset" data-mdb-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <form action="/filterhomes" method="POST">
                <ul class="list-unstyled">
                    <li class="border-bottom p-2">
                        <button name="developer" value="all" type="submit" class="btn btn-lg btn-tertiary case">All</button>
                    </li>
                    <?php foreach ($Developers as $dps): ?>
                        <li class="border-bottom p-2">
                            <button name="developer" value="<?= $dps['id_developer']; ?>" type="submit" class="btn btn-lg btn-tertiary case"><?= $dps['developer_name']; ?></button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </form>
        </div>
    </div>

    <div class="container-fluid">

        <div class="my-3 bg-light rounded p-3">
            <div class="mb-2">
                <form class="d-flex input-group w-auto">
                    <input
                        type="search"
                        class="form-control rounded"
                        placeholder="Search model"
                        id="homesListSearch"
                        value="<?= esc($search ?? '') ?>"
                    />
                    <span class="input-group-text border-0" id="search-addon">
                            <i class="fas fa-search"></i>
                    </span>
                </form>
            </div>
            <div class="text-center">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="sortingOption" id="sortAlphabetical" value="" checked/>
                    <label class="form-check-label"><small>A - Z</small></label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="sortingOption" id="sortByPrice" value="" />
                    <label class="form-check-label"><small>Price</small></label>
                </div>
            </div>      
        </div>

        <div>
            <?php if (!empty($Restates)): ?>
                <?php foreach ($Restates as $res): ?>
                    <?php
                        $basePath = FCPATH . 'resources/images/modelphotos/'; // Adjust FCPATH to your project root if needed
                        $imageBase = $basePath . $res['id_restate'] . '_1';
                        $imageUrlBase = '/resources/images/modelphotos/' . $res['id_restate'] . '_1';
                        
                        if (file_exists($imageBase . '.jpg')) {
                            $image = $imageUrlBase . '.jpg';
                        } 
                        elseif (file_exists($imageBase . '.png')) {
                            $image = $imageUrlBase . '.png';
                        } 
                        else {
                            $image = '/resources/images/modelphotos/default.png';
                        }
                    ?>

                    <div class="mb-3 bg-light p-3 rounded">
                        <div class="text-center"><?= htmlspecialchars($res['model']); ?></div>
                        <div class="blurred-sides mb-1" style="--bg-img: url('<?= $image ?>')">
                            <img src="<?= $image . '?t=' . time(); ?>" alt="House Model">
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <small>
                                <span class="text-primary"><?= htmlspecialchars($res['developer_name']); ?></span><br>
                                Php&nbsp;<?= number_format($res['price']); ?>
                            </small>
                            <a href="/modeldetails/<?= $res['id_restate'] ?>" class="btn btn-sm btn-outline-primary">See Details</a>
                        </div>
                    </div>    
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center">No Homes Available</div>
            <?php endif; ?>
        </div>

        
    </div>

    <div style="height: 80px;"></div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
    <script>
        $(document).ready(function() {

            function filterCards() {
                var value = $('#homesListSearch').val().toLowerCase();

                var $container = $('.container-fluid > div').last(); 
                var $cards = $container.children('.mb-3.bg-light');

                $cards.each(function() {
                    var $card = $(this);
                    var model = $card.find('.text-center').first().text().toLowerCase();
                    var developer = $card.find('.text-primary').first().text().toLowerCase();

                    if (model.indexOf(value) > -1 || developer.indexOf(value) > -1) {
                        $card.show();
                    } else {
                        $card.hide();
                    }
                });
            }

            var searchPrefill = $('#homesListSearch').val();
            if (searchPrefill) {
                filterCards();
            }

            $('#homesListSearch').on('keyup', filterCards);

            $('input[name="sortingOption"]').on('change', function() {
                var sortByPrice = $('#sortByPrice').is(':checked');

                var $container = $('.container-fluid > div').last(); 
                var $cards = $container.children('.mb-3.bg-light');

                var sortedCards = $cards.sort(function(a, b) {
                    if (sortByPrice) {
                        var priceA = parseInt($(a).find('small').text().replace(/[^0-9]/g, '')) || 0;
                        var priceB = parseInt($(b).find('small').text().replace(/[^0-9]/g, '')) || 0;
                        return priceA - priceB; 
                    } else {
                        var modelA = $(a).find('.text-center').first().text().toLowerCase();
                        var modelB = $(b).find('.text-center').first().text().toLowerCase();
                        return modelA.localeCompare(modelB);
                    }
                });

                $container.html(sortedCards);
            });
        });
    </script>

<?= $this->endSection() ?>