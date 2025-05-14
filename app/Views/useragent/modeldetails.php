<?= $this->extend('/shared/main'); ?>

<?php 
    $sessionUserID = session()->get('id_user');
?>

<?= $this->section('css') ?>
    <style>
        .icon-button {
            background: none;
            border: none;
            padding: 0;
            margin: 0;
            cursor: pointer;
            color: inherit;
        }

        .icon-button:focus {
            outline: none;
        }
    </style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div class="container-fluid">

        <div class="d-flex my-3">
            <a href="/homeslist" class="">
                <i class="fa-solid fa-arrow-left"></i>&nbsp;
            </a>
        </div>
        <div class="mb-2 d-flex justify-content-between align-items-center">
            <div class="text-dark h4 mb-0"><?= $RestateDetail['model']; ?></div>
            <form action="/addtofavorites" method="POST">
                <input type="hidden" value="<?= $RestateDetail['id_restate']; ?>" name="id_restate">
                <input type="hidden" value="<?= $sessionUserID; ?>" name="id_user">
                <button class="icon-button" type="submit">
                    <?php if (empty($Favorites)): ?>
                        <i class="fa-regular fa-heart fa-lg"></i> 
                    <?php else: ?>
                        <i class="fa-solid fa-heart fa-lg text-danger"></i>
                    <?php endif; ?>
                </button>
            </form>
        </div>

        <?php
            $id_restate = $RestateDetail['id_restate'];
            $image_path = FCPATH . 'resources/images/modelphotos/';
            $image_url_path = '/resources/images/modelphotos/';

            $images = glob($image_path . "{$id_restate}_*.{jpg,png}", GLOB_BRACE);

            $image_urls = [];
            foreach ($images as $img) {
                $basename = basename($img);
                $image_urls[] = $image_url_path . $basename;
            }

            if(empty($image_urls)) {
                $image_urls[] = $image_url_path . 'default.png';
            }
        ?>

        <div id="carouselModels" class="carousel slide" data-mdb-ride="carousel" data-mdb-carousel-init data-mdb-interval="3000" data-mdb-wrap="true">
            <!-- <div class="carousel-indicators">
                <?//php foreach ($image_urls as $index => $url): ?>
                    <button type="button" data-mdb-target="#carouselModels" data-mdb-slide-to="<?//= $index ?>" class="<?//= $index === 0 ? 'active' : '' ?>"></button>
                <?//php endforeach; ?>
            </div> -->

            <div class="carousel-inner">
                <?php foreach ($image_urls as $index => $url): ?>
                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                        <img src="<?= $url ?>" class="d-block w-100" alt="Model Image <?= $index + 1 ?>">
                    </div>
                <?php endforeach; ?>
            </div>

            <button class="carousel-control-prev" type="button" data-mdb-target="#carouselModels" data-mdb-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-mdb-target="#carouselModels" data-mdb-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        
        <div class="mt-3 mb-1">
            <div class="fw-bold">Property Details</div>
        </div>

        <?php
            $iconBoxClass = 'd-flex justify-content-center align-items-center text-primary';
            $iconBoxStyle = 'width: 40px; height: 40px; flex-shrink: 0; border-radius: 50%; background-color: #e2eaf7';
        ?>

        <div class="d-flex flex-wrap">
            <div class="w-50 pe-2">
                <div class="d-flex align-items-center my-2">
                    <div class="<?= $iconBoxClass ?> me-3" style="<?= $iconBoxStyle ?>">
                        <i class="fa-solid fa-house"></i>
                    </div>
                    <div>
                        <div class="fw-bold">Model</div>
                        <div><?= $RestateDetail['model']; ?></div>
                    </div>
                </div>

                <div class="d-flex align-items-center my-2">
                    <div class="<?= $iconBoxClass ?> me-3" style="<?= $iconBoxStyle ?>">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div>
                        <div class="fw-bold">Location</div>
                        <div><?= $RestateDetail['location']; ?></div>
                    </div>
                </div>

                <div class="d-flex align-items-center my-2">
                    <div class="<?= $iconBoxClass ?> me-3" style="<?= $iconBoxStyle ?>">
                        <i class="fa-solid fa-tag"></i>
                    </div>
                    <div>
                        <div class="fw-bold">Price</div>
                        <div>Php&nbsp;<?= number_format($RestateDetail['price']); ?></div>
                    </div>
                </div>

                <div class="d-flex align-items-center my-2">
                    <div class="<?= $iconBoxClass ?> me-3" style="<?= $iconBoxStyle ?>">
                        <i class="fa-regular fa-credit-card"></i>
                    </div>
                    <div>
                        <div class="fw-bold">Reservation Fee</div>
                        <div>Php&nbsp;<?= number_format($RestateDetail['reservation_fee']); ?></div>
                    </div>
                </div>

                <div class="d-flex align-items-center my-2">
                    <div class="<?= $iconBoxClass ?> me-3" style="<?= $iconBoxStyle ?>">
                        <i class="fa-solid fa-clone"></i>
                    </div>
                    <div>
                        <div class="fw-bold">Floor Area</div>
                        <div><?= $RestateDetail['area_floor']; ?>&nbsp;sqm</div>
                    </div>
                </div>

                <div class="d-flex align-items-center my-2">
                    <div class="<?= $iconBoxClass ?> me-3" style="<?= $iconBoxStyle ?>">
                        <i class="fa-solid fa-restroom"></i>
                    </div>
                    <div>
                        <div class="fw-bold">Comfort Rooms</div>
                        <div><?= $RestateDetail['comfort_rooms']; ?></div>
                    </div>
                </div>
            </div>

            <div class="w-50 ps-2">
                <div class="d-flex align-items-center my-2">
                    <div class="<?= $iconBoxClass ?> me-3" style="<?= $iconBoxStyle ?>">
                        <i class="fa-solid fa-house-user"></i>
                    </div>
                    <div>
                        <div class="fw-bold">Developer</div>
                        <div><?= $RestateDetail['developer_name']; ?></div>
                    </div>
                </div>

                <div class="d-flex align-items-center my-2">
                    <div class="<?= $iconBoxClass ?> me-3" style="<?= $iconBoxStyle ?>">
                        <i class="fa-solid fa-clone"></i>
                    </div>
                    <div>
                        <div class="fw-bold">Lot Area</div>
                        <div><?= $RestateDetail['area_lot']; ?>&nbsp;sqm</div>
                    </div>
                </div>

                <div class="d-flex align-items-center my-2">
                    <div class="<?= $iconBoxClass ?> me-3" style="<?= $iconBoxStyle ?>">
                        <i class="fa-solid fa-money-check"></i>
                    </div>
                    <div>
                        <?php
                            $price = $RestateDetail['price'];
                            $downpayment = $RestateDetail['downpayment'];
                            $dp_percentage = ($price > 0) ? ($downpayment / $price) * 100 : 0;
                        ?>
                        <div class="fw-bold">Downpayment</div>
                        <div>Php&nbsp;<?= number_format($RestateDetail['downpayment']); ?>
                            &nbsp;(<?= number_format($dp_percentage, 2); ?>%)</div>
                    </div>
                </div>

                <div class="d-flex align-items-center my-2">
                    <div class="<?= $iconBoxClass ?> me-3" style="<?= $iconBoxStyle ?>">
                        <i class="fa-solid fa-calendar"></i>
                    </div>
                    <div>
                        <div class="fw-bold">Years to Pay</div>
                        <div><?= $RestateDetail['years_to_pay']; ?></div>
                    </div>
                </div>

                <div class="d-flex align-items-center my-2">
                    <div class="<?= $iconBoxClass ?> me-3" style="<?= $iconBoxStyle ?>">
                        <i class="fa-solid fa-bed"></i>
                    </div>
                    <div>
                        <div class="fw-bold">Bedrooms</div>
                        <div><?= $RestateDetail['bedrooms']; ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3 mb-1">
            <div class="fw-bold"><?=$RestateDetail['developer_name']; ?>&nbsp;Agents</div>
        </div>

        <div>
            <?php foreach ($RestateAgents as $index => $rag): ?>
                <?php
                    $isLast = ($index === array_key_last($RestateAgents)); // Check if this is the last element
                    $liClass = 'p-1 my-3 rounded bg-white';
                    if (!$isLast) {
                        $liClass .= ' border-bottom';
                    }
                ?>
                <ul class="list-unstyled">
                    <li class="<?= $liClass ?>">
                        <div class="d-flex justify-content-between align-items-center text-decoration-none">
                            <div class="d-flex align-items-center mb-1">
                                <div class="me-3">
                                    <?php
                                        $id = $rag['id_user'];
                                        $png = "/resources/images/profilepics/{$id}.png";
                                        $jpg = "/resources/images/profilepics/{$id}.jpg";

                                        $pic = file_exists(FCPATH . ltrim($png, '/')) ? $png :
                                            (file_exists(FCPATH . ltrim($jpg, '/')) ? $jpg :
                                            "/resources/images/default.png");
                                    ?>
                                    <img src="<?= $pic . '?t=' . time(); ?>" class="rounded-circle profilepic" width="50" height="50">
                                </div>
                                <div class="fw-bold mb-0 me-3">
                                    <?= $rag['firstname'] .  
                                        (!empty($rag['middlename']) ? ' ' . strtoupper(substr($rag['middlename'], 0, 1)) . '.' : '') . ' ' . 
                                        $rag['lastname']; 
                                    ?>
                                </div>
                                <a href="/profile/view/<?= $rag['id_user'] ?>" class="btn btn-primary">
                                    Info
                                </a>
                                <a href="/messagelanding?sender=<?= $rag['id_user'] ?>&senderfname=<?= $rag['firstname'] ?>&senderlname=<?= $rag['lastname'] ?>" class="btn btn-outline-primary ms-1">
                                    Message
                                </a>
                            </div>
                        </div>
                    </li>  
                </ul>
            <?php endforeach; ?>
        </div>

    </div>

    <div style="height: 80px;"></div>

<?= $this->endSection() ?>