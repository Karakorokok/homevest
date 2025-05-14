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
    </style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <nav class="navbar bg-light sticky-top">
        <div class="container-fluid">
            <a data-mdb-toggle="offcanvas" data-mdb-offcanvas-init href="#agentListSidebar" role="button" class="">
                <i class="fa-solid fa-bars text-dark"></i>
                <span class="ms-3 text-dark"><?= $selectedDeveloper; ?>&nbsp;Agents</span>
            </a>
            <div></div>
        </div>
    </nav>

    <!-- offcanvas sidebar -->
    <div class="my-offcanvas offcanvas offcanvas-start" tabindex="-1" id="agentListSidebar">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">Home Vest Agents</h5>
            <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn-close text-reset" data-mdb-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <form action="/filteragents" method="POST">
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

        <div class="mt-3 bg-light rounded p-3">
            <div class="mb-2">
                <form class="d-flex input-group w-auto">
                    <input
                            type="search"
                            class="form-control rounded"
                            placeholder="Search"
                            id="agentsListSearch"
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
                    <input class="form-check-input" type="radio" name="sortingOption" id="sortByExperience" value="" />
                    <label class="form-check-label"><small>Experience</small></label>
                </div>
            </div>      
        </div>
        
        <?php
            $groupedAgents = [];

            foreach ($Agents as $agent) {
                $id = $agent['id_user'];

                if (!isset($groupedAgents[$id])) {
                    $groupedAgents[$id] = $agent;
                    $groupedAgents[$id]['developer_names'] = [];
                }

                $groupedAgents[$id]['developer_names'][] = $agent['developer_name'];
            }
        ?>        
        <?php if (empty($groupedAgents)): ?>
            <p class="text-center text-muted my-4">No agent/s available</p>
        <?php else: ?>
        <ul class="list-unstyled" id="agentsListUL">
            <?php
                usort($groupedAgents, function ($a, $b) {
                    $nameA = strtolower($a['lastname'] . ' ' . $a['firstname']);
                    $nameB = strtolower($b['lastname'] . ' ' . $b['firstname']);
                    return strcmp($nameA, $nameB);
                });
            ?>
            <?php foreach ($groupedAgents as $agnt): ?>
                <li class="p-2 my-3 rounded bg-white">
                    <a class="d-flex justify-content-between align-items-center text-decoration-none text-primary">
                        <div class="d-flex align-items-center mb-1">
                            <div class="me-3">
                                <?php
                                    $id = $agnt['id_user'];
                                    $png = "/resources/images/profilepics/{$id}.png";
                                    $jpg = "/resources/images/profilepics/{$id}.jpg";

                                    $pic = file_exists(FCPATH . ltrim($png, '/')) ? $png :
                                        (file_exists(FCPATH . ltrim($jpg, '/')) ? $jpg :
                                        "/resources/images/default.png");
                                ?>
                                <img src="<?= $pic . '?t=' . time(); ?>" class="rounded-circle profilepic" width="60" height="60">
                            </div>

                            <div>
                                <div class="fw-bold mb-0">
                                    <?= $agnt['firstname'] .  
                                        (!empty($agnt['middlename']) ? ' ' . strtoupper(substr($agnt['middlename'], 0, 1)) . '.' : '') . ' ' . 
                                        $agnt['lastname']; 
                                    ?>
                                </div>
                                <div class="small text-muted mb-0">
                                    <?php 
                                        $devs = array_unique($agnt['developer_names']);
                                        sort($devs, SORT_NATURAL | SORT_FLAG_CASE);
                                        echo implode(', ', $devs);
                                    ?>
                                </div>
                                <div class="small text-primary mb-0">
                                    <?= !empty($agnt['experience']) ? $agnt['experience'] : 0; ?>&nbsp;years of experience
                                </div>
                            </div>
                        </div>
                        <div class="d-flex mb-1">
                            <div class="">
                                <a href="/profile/view/<?= $agnt['id_user'] ?>" 
                                    class="btn btn-secondary">
                                    View Profile
                                </a>
                            </div>
                            <div class="ms-auto">
                                <a href="/messagelanding?sender=<?= $agnt['id_user'] ?>&senderfname=<?= $agnt['firstname'] ?>&senderlname=<?= $agnt['lastname'] ?>" 
                                    class="btn btn-primary">
                                    Consult now
                                </a>
                            </div>
                        </div>
                        
                    </a>
                </li>  
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </div>

    <div style="height: 80px;"></div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
    <script>
        $(document).ready(function() {
            var searchPrefill = $('#agentsListSearch').val();
            if (searchPrefill) {
                filterAgentsList(searchPrefill);
            }

            $('#agentsListSearch').on('keyup', function() {
                var value = $(this).val();
                filterAgentsList(value);
            });

            function filterAgentsList(query) {
                var value = query.toLowerCase();
                $('#agentsListUL > li').each(function() {
                    var name = $(this).find('.fw-bold').text().toLowerCase();
                    var devs = $(this).find('.small.text-muted').text().toLowerCase();
                    if (name.indexOf(value) > -1 || devs.indexOf(value) > -1) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }

            $('input[name="sortingOption"]').on('change', function() {
                var sortByExperience = $('#sortByExperience').is(':checked');
                var $list = $('#agentsListUL');
                var $items = $list.children('li');

                $items.sort(function(a, b) {
                    if (sortByExperience) {
                        var expA = parseInt($(a).find('.small.text-primary').text()) || 0;
                        var expB = parseInt($(b).find('.small.text-primary').text()) || 0;
                        return expB - expA;
                    } else {
                        var nameA = $(a).find('.fw-bold').text().toLowerCase();
                        var nameB = $(b).find('.fw-bold').text().toLowerCase();
                        return nameA.localeCompare(nameB); 
                    }
                });

                $list.html($items);
            });
        });
    </script>

<?= $this->endSection() ?>