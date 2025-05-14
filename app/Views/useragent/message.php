<?= $this->extend('/shared/main'); ?>

<?php 

    $sessionUserID = session()->get('id_user');
    $sessionUserRole = session()->get('user_role');

?>

<?= $this->section('css') ?>
    <style>
        .mytruncate {
            max-width: 200px; 
            overflow: hidden; 
            white-space: nowrap;
        }

        @media (max-width: 768px) {
        .my-offcanvas {
            width: 90% !important; 
        }
    }
    </style>

<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div class="container">
        
        <nav class="navbar fixed-top bg-mylight shadow-sm border-bottom">
            <div class="input-group px-3 mb-3 mt-1">
                <?php
                    if($sessionUserRole == 1) { ?>
                <a data-mdb-toggle="offcanvas" data-mdb-offcanvas-init href="#agentSidebar" role="button" class="">
                    <i class="fa-solid fa-bars text-dark me-3"></i>
                </a>
                <?php } ?>
                <div class="form-outline" data-mdb-input-init>
                    <input id="search-input" type="search" id="form1" class="form-control" />
                    <label class="form-label" for="form1">Search</label>
                </div>
                <button id="search-button" type="button" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </nav>

        <div class="my-offcanvas offcanvas offcanvas-start" tabindex="-1" id="agentSidebar">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasExampleLabel">Home Vest Agents</h5>
                <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn-close text-reset" data-mdb-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body">
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
                <ul class="list-unstyled">
                    <?php
                        usort($groupedAgents, function ($a, $b) {
                            $nameA = strtolower($a['lastname'] . ' ' . $a['firstname']);
                            $nameB = strtolower($b['lastname'] . ' ' . $b['firstname']);
                            return strcmp($nameA, $nameB);
                        });
                    ?>
                    <?php foreach ($groupedAgents as $agnt): ?>
                        <li class="p-2 border-bottom">
                            <a href="/messagelanding?sender=<?= $agnt['id_user'] ?>&senderfname=<?= $agnt['firstname'] ?>&senderlname=<?= $agnt['lastname'] ?>" 
                            class="d-flex justify-content-between align-items-center text-decoration-none text-primary">

                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <?php
                                            $id = $agnt['id_user'];
                                            $png = "/resources/images/profilepics/{$id}.png";
                                            $jpg = "/resources/images/profilepics/{$id}.jpg";

                                            $pic = file_exists(FCPATH . ltrim($png, '/')) ? $png :
                                                (file_exists(FCPATH . ltrim($jpg, '/')) ? $jpg :
                                                "/resources/images/default.png");
                                        ?>
                                        <img src="<?= $pic . '?t=' . time() ?>" class="rounded-circle profilepic" width="40" height="40">
                                    </div>

                                    <div>
                                        <div class="fw-bold mb-0">
                                            <?= $agnt['lastname'] . ', ' . $agnt['firstname'] . 
                                                (!empty($agnt['middlename']) ? ' ' . strtoupper(substr($agnt['middlename'], 0, 1)) . '.' : ''); 
                                            ?>
                                        </div>
                                        <div class="small text-muted mb-0">
                                            <?php 
                                                $devs = array_unique($agnt['developer_names']);
                                                sort($devs, SORT_NATURAL | SORT_FLAG_CASE);
                                                echo implode(', ', $devs);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>  
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    
        <?php
            usort($Messages, function($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });

            $uniqueMessages = []; 

            foreach ($Messages as $mm) {
                if($mm['sender'] != $sessionUserID) {
                    if (!isset($uniqueMessages[$mm['sender']])) {
                        $uniqueMessages[$mm['sender']] = $mm;
                    }
                }
            }
        ?>

        <div data-mdb-perfect-scrollbar-init style="position: relative; height: 400px; padding-top: 70px;">

            <?php if (empty($uniqueMessages)): ?>
                <p class="text-center text-muted my-3">No messages available</p>
            <?php else: ?>
                <ul class="list-unstyled mb-0">
                <?php 
                    date_default_timezone_set('Asia/Manila');
                    foreach ($uniqueMessages as $mm): 

                        $created_at = strtotime($mm['created_at']); 
                        $today = date('Y-m-d'); 
                        $message_date = date('Y-m-d', $created_at); 
                        $displayTime = ($message_date === $today) ? date('g:i A', $created_at) : date('F j', $created_at);

                    ?>
              
                    <li class="p-2 border-bottom">
                        <a href="/messagelanding?sender=<?= $mm['sender'] ?>&senderfname=<?= $mm['sender_firstname'] ?>&senderlname=<?= $mm['sender_lastname'] ?>" 
                            class="d-flex justify-content-between">

                            <div class="d-flex flex-row">
                            <div>
                                <?php
                                    $senderID = $mm['sender'];
                                    $pngPath = "/resources/images/profilepics/{$senderID}.png";
                                    $jpgPath = "/resources/images/profilepics/{$senderID}.jpg";

                                    $profilePic = file_exists(FCPATH . ltrim($pngPath, '/')) 
                                        ? $pngPath 
                                        : (file_exists(FCPATH . ltrim($jpgPath, '/')) 
                                            ? $jpgPath 
                                            : "/resources/images/default.png");
                                ?>
                                <img src="<?= $profilePic . '?t=' . time() ?>" class="d-flex align-self-center me-3 rounded-circle profilepic" width="60" height="60">
                            </div>

                            <div class="pt-1">
                                <p class="fw-bold mb-0 text-truncate mytruncate">
                                    <?= $mm['sender_firstname']?>&nbsp;<?= $mm['sender_lastname']?>
                                </p>
                                <p class="small text-muted text-truncate mytruncate">
                                    <?= $mm['messagecontent']?>
                                </p>
                            </div>
                            </div>
                            <div class="pt-1">
                                <p class="small text-muted mb-1" style="white-space: nowrap;"><?= $displayTime ?></p>
                                <small class=""></small>
                            </div>
                        </a>
                    </li>  
                       
                <?php endforeach; ?>
                <li style="height: 80px; border: none;"></li>
                </ul>
                <p id="no-results" class="text-center text-muted mt-3" style="display: none;">No Results Found</p>
            <?php endif; ?>
        </div>

    </div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
    <script>
        $(document).ready(function() {
            $('#search-input').on('keyup', function() {
                const value = $(this).val().toLowerCase().replace(/\s+/g, ' ').trim();

                let visibleCount = 0;

                $('ul.list-unstyled > li').filter(function() {
                    const name = $(this).find('.fw-bold').text().toLowerCase().replace(/\s+/g, ' ').trim();
                    const message = $(this).find('.small.text-muted').text().toLowerCase().replace(/\s+/g, ' ').trim();

                    const combined = (name + ' ' + message).replace(/\s+/g, ' ').trim();

                    const isMatch = combined.includes(value);
                    $(this).toggle(isMatch);
                    if (isMatch) visibleCount++;
                });

                $('#no-results').toggle(visibleCount === 0);
            });
        });
    </script>
<?= $this->endSection() ?>
