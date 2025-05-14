<?= $this->extend('/shared/main'); ?>

<?php 
    $sessionUserID = session()->get('id_user');
?>

<?= $this->section('content') ?>

    <div class="container-fluid">
        <div class="position-relative my-3">
            <a href="/home" class="position-absolute start-0">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div class="text-center">
                <?= $selectedDeveloperDisplay; ?>
            </div>
        </div>
        <div class="mb-3">
            <button class="btn btn-primary me-auto" data-mdb-modal-init data-mdb-target="#addModelModal">Add Model</button>
        </div>

        <div class=""> 
            <table id="restateTable" class="table" style="width:100%">
                <thead>
                    <tr class="">
                        <th>Model</th>
                        <th>Photos</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($Models as $mod): ?>
                        <tr>
                            <td><?= $mod['model']; ?></td>
                            <td>
                                <button class="btn btn-sm btn-primary mb-1" data-mdb-modal-init data-mdb-target="#photosModal<?= $mod['id_restate']; ?>">Upload</button>
                            
                                <!-- photos model modal -->
                                <div class="modal fade" id="photosModal<?= $mod['id_restate']; ?>" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form action="homeslisting/savemodelphoto" method="POST" enctype="multipart/form-data">
                                                <div class="modal-body ">

                                                <?php
                                                    $id = $mod['id_restate'];
                                                    $photo_dir = 'resources/images/modelphotos/';
                                                    $photos = [];

                                                    foreach (['png', 'jpg', 'jpeg'] as $ext) {
                                                        $pattern = $photo_dir . $id . '_*.' . $ext;
                                                        $matches = glob($pattern);
                                                        if ($matches !== false) {
                                                            $photos = array_merge($photos, $matches);
                                                        }
                                                    }
                                                    ?>

                                                    <input type="number" name="developerid" value="<?= $selectedDeveloper; ?>" style="display: none;"/>
                                                    <input type="text" name="developername" value="<?= $selectedDeveloperDisplay; ?>" style="display: none;"/>
                                                    <input type="number" name="id_restate" value="<?= $mod['id_restate']; ?>" style="display: none;" />
                                                    <div class="mb-3">
                                                        <a class="btn btn-tertiary">Upload Photos</a>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div>Uploaded photos</div>
                                                        <?php if (!empty($photos)): ?>
                                                            <?php foreach ($photos as $photo): ?>
                                                                <div class="col-6 position-relative">
                                                                    <img src="<?= $photo . '?t=' . time(); ?>" class="w-100 border border-dark mb-3">
                                                        
                                                                    <button type="button"
                                                                        class="btn btn-danger rounded-circle p-0 btnDeletePhoto position-absolute top-0 start-75 translate-middle"
                                                                        style="width: 30px; height: 30px;"
                                                                        data-photo="<?= $photo; ?>"
                                                                        data-developerid="<?= $selectedDeveloper; ?>"
                                                                        data-developername="<?= $selectedDeveloperDisplay; ?>"
                                                                    >
                                                                        <i class="fa-solid fa-xmark"></i>
                                                                    </button>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <p class="text-center">No uploaded photos yet.</p>
                                                        <?php endif; ?>
                                                    </div>

                                                    <div class="d-flex flex-column justify-content-center align-items-center">
                                                        <div class="modelphotos" style="display: none">
                                                            <input type="file" class="form-control" id="customFile<?= $mod['id_restate']; ?>" name="modelphotos[]" accept=".jpg,.jpeg,.png">
                                                        </div>
                                                        <div class="mb-3">
                                                            <button type="button" class="btn btn-primary" id="btnAddModel">Add Photos</button>
                                                        </div>

                                                        <div>
                                                            <p>Preview will appear here</p>
                                                            <div class="preview-container d-flex flex-wrap gap-2 mt-3"></div>
                                                        </div>
                                                       
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <button class="btn btn-sm btn-primary mb-1" data-mdb-modal-init data-mdb-target="#editModelModal<?= $mod['id_restate']; ?>">Edit</button>
                                <button class="btn btn-sm btn-danger mb-1" data-mdb-modal-init data-mdb-target="#deleteModelModal<?= $mod['id_restate']; ?>">Delete</button>

                                <!-- edit model modal -->
                                <div class="modal fade" id="editModelModal<?= $mod['id_restate']; ?>" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form action="/homeslisting" method="POST">
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <a class="btn btn-tertiary">Edit Model</a>
                                                    </div>

                                                    <input type="number" name="developerid" value="<?= $selectedDeveloper; ?>"style="display: none;"/>
                                                    <input type="text" name="developername" value="<?= $selectedDeveloperDisplay; ?>"style="display: none;"/>
                                                    <input type="text" name="creator" value="<?= $sessionUserID; ?>"style="display: none;"/>

                                                    <input type="hidden" name="id_restate" value="<?= $mod['id_restate']; ?>" />

                                                    <div class="form-outline mb-3" data-mdb-input-init>
                                                        <input type="text" class="form-control" spellcheck="false" autocomplete="off" name="model" value="<?= $mod['model']; ?>"/>
                                                        <label class="form-label">House Model</label>
                                                    </div>
                                                    <div class="form-outline mb-3" data-mdb-input-init>
                                                        <input type="text" class="form-control" spellcheck="false" autocomplete="off" name="location" value="<?= $mod['location']; ?>"/>
                                                        <label class="form-label">Location</label>
                                                    </div>
                                                    <div class="form-outline mb-3" data-mdb-input-init>
                                                        <input type="number" class="form-control" spellcheck="false" autocomplete="off" name="area_lot" step="any" value="<?= $mod['area_lot']; ?>"/>
                                                        <label class="form-label">Lot Area in sqm</label>
                                                    </div>
                                                    <div class="form-outline mb-3" data-mdb-input-init>
                                                        <input type="number" class="form-control" spellcheck="false" autocomplete="off" name="price" step="any" value="<?= $mod['price']; ?>"/>
                                                        <label class="form-label">Price</label>
                                                    </div>
                                                    <div class="form-outline mb-3" data-mdb-input-init>
                                                        <input type="number" class="form-control" spellcheck="false" autocomplete="off" name="downpayment" step="any" value="<?= $mod['downpayment']; ?>"/>
                                                        <label class="form-label">Downpayment</label>
                                                    </div>
                                                    <div class="form-outline mb-3" data-mdb-input-init>
                                                        <input type="number" class="form-control" spellcheck="false" autocomplete="off" name="reservation_fee" step="any" value="<?= $mod['reservation_fee']; ?>"/>
                                                        <label class="form-label">Reservation Fee</label>
                                                    </div>
                                                    <div class="form-outline mb-3" data-mdb-input-init>
                                                        <input type="text" class="form-control" spellcheck="false" autocomplete="off" name="years_to_pay" value="<?= $mod['years_to_pay']; ?>"/>
                                                        <label class="form-label">Years to pay</label>
                                                    </div>
                                                    <div class="form-outline mb-3" data-mdb-input-init>
                                                        <input type="number" class="form-control" spellcheck="false" autocomplete="off" name="area_floor" step="any" value="<?= $mod['area_floor']; ?>" />
                                                        <label class="form-label">Floor Area in sqm</label>
                                                    </div>
                                                    <div class="form-outline mb-3" data-mdb-input-init>
                                                        <input type="number" class="form-control" spellcheck="false" autocomplete="off" name="bedrooms" step="any" value="<?= $mod['bedrooms']; ?>"/>
                                                        <label class="form-label">Bedrooms</label>
                                                    </div>
                                                    <div class="form-outline mb-3" data-mdb-input-init>
                                                        <input type="number" class="form-control" spellcheck="false" autocomplete="off" name="comfort_rooms" step="any" value="<?= $mod['comfort_rooms']; ?>"/>
                                                        <label class="form-label">Comfort Rooms</label>
                                                    </div>
                                                    <div data-mdb-input-init class="form-outline">
                                                            <textarea class="form-control mb-3" spellcheck="false" autocomplete="off"
                                                                name="others" rows="3"><?= $mod['others']; ?></textarea>
                                                            <label class="form-label">Others</label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary" data-mdb-ripple-init>Save</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- delete model modal -->
                                <div class="modal fade" id="deleteModelModal<?= $mod['id_restate']; ?>" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form action="/homeslisting/removemodel" method="POST">
                                                <div class="modal-body">
                                               
                                                    <input type="hidden" name="id_restatetodel" value="<?= $mod['id_restate']; ?>">
                                                    <input type="number" name="developerid" value="<?= $selectedDeveloper; ?>"style="display: none;"/>
                                                    <input type="text" name="developername" value="<?= $selectedDeveloperDisplay; ?>"style="display: none;"/>

                                                    <div class="text-center my-3">Are you sure you want to delete <span class="text-danger">
                                                        <?= $mod['model']; ?>
                                                    </span>?</div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- add model modal -->
        <div class="modal fade" id="addModelModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="/homeslisting" method="POST">
                        <div class="modal-body">
                            <div class="mb-3">
                                <a class="btn btn-tertiary">Add Model</a>
                            </div>

                            <input type="number" name="developerid" value="<?= $selectedDeveloper; ?>"style="display: none;"/>
                            <input type="text" name="developername" value="<?= $selectedDeveloperDisplay; ?>"style="display: none;"/>
                            <input type="text" name="creator" value="<?= $sessionUserID; ?>"style="display: none;"/>

                            <input type="hidden" name="id_restate" value="" />
                            <div class="form-outline mb-3" data-mdb-input-init>
                                <input type="text" class="form-control" spellcheck="false" autocomplete="off" name="model" />
                                <label class="form-label">House Model</label>
                            </div>
                            <div class="form-outline mb-3" data-mdb-input-init>
                                <input type="text" class="form-control" spellcheck="false" autocomplete="off" name="location" />
                                <label class="form-label">Location</label>
                            </div>
                            <div class="form-outline mb-3" data-mdb-input-init>
                                <input type="number" class="form-control" spellcheck="false" autocomplete="off" name="area_lot" step="any"/>
                                <label class="form-label">Lot Area in sqm</label>
                            </div>
                            <div class="form-outline mb-3" data-mdb-input-init>
                                <input type="number" class="form-control" spellcheck="false" autocomplete="off" name="price" step="any" />
                                <label class="form-label">Price</label>
                            </div>
                            <div class="form-outline mb-3" data-mdb-input-init>
                                <input type="number" class="form-control" spellcheck="false" autocomplete="off" name="downpayment" step="any" />
                                <label class="form-label">Downpayment</label>
                            </div>
                            <div class="form-outline mb-3" data-mdb-input-init>
                                <input type="number" class="form-control" spellcheck="false" autocomplete="off" name="reservation_fee" step="any" />
                                <label class="form-label">Reservation Fee</label>
                            </div>
                            <div class="form-outline mb-3" data-mdb-input-init>
                                <input type="text" class="form-control" spellcheck="false" autocomplete="off" name="years_to_pay" />
                                <label class="form-label">Years to pay</label>
                            </div>
                            <div class="form-outline mb-3" data-mdb-input-init>
                                <input type="number" class="form-control" spellcheck="false" autocomplete="off" name="area_floor" step="any" />
                                <label class="form-label">Floor Area in sqm</label>
                            </div>
                            <div class="form-outline mb-3" data-mdb-input-init>
                                <input type="number" class="form-control" spellcheck="false" autocomplete="off" name="bedrooms" step="any" />
                                <label class="form-label">Bedrooms</label>
                            </div>
                            <div class="form-outline mb-3" data-mdb-input-init>
                                <input type="number" class="form-control" spellcheck="false" autocomplete="off" name="comfort_rooms" step="any" />
                                <label class="form-label">Comfort Rooms</label>
                            </div>
                            <div data-mdb-input-init class="form-outline">
                                    <textarea class="form-control mb-3" spellcheck="false" autocomplete="off"
                                        name="others" rows="3"></textarea>
                                    <label class="form-label">Others</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" data-mdb-ripple-init>Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div style="height: 80px;"></div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>

    <script>
        new DataTable('#restateTable', {
            responsive: true,
            lengthChange: false,    
            pageLength: 5  
        });
    </script>

    <script>
        $(document).ready(function() {
            // Trigger input file when Add Photo button is clicked
            $('[id^=btnAddModel]').on('click', function() {
                const modalBody = $(this).closest('.modal-body');
                modalBody.find('input[type="file"]').click();
            });

            // When files are selected, show preview
            $('input[type="file"]').on('change', function(event) {
                const files = event.target.files;
                const modalBody = $(this).closest('.modal-body');
                const previewContainer = modalBody.find('.preview-container');
                previewContainer.empty(); // Clear previous previews

                if (files.length > 0) {
                    Array.from(files).forEach(file => {
                        if (file.type.match('image.*')) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const img = $('<img>').attr('src', e.target.result).css({
                                    'max-width': '150px',
                                    'max-height': '150px',
                                    'object-fit': 'cover'
                                });
                                previewContainer.append(img);
                            }
                            reader.readAsDataURL(file);
                        }
                    });
                }
            });

            $('.btnDeletePhoto').on('click', function() {
                const button = $(this);
                const photoPath = button.data('photo');
                const developerid = button.data('developerid');
                const developername = button.data('developername');

                if (confirm('Are you sure you want to delete this photo?')) {
                    $.ajax({
                        url: '<?= site_url("/homeslisting/deletemodelphoto") ?>',
                        method: 'POST',
                        data: { photoPath: photoPath },
                        success: function(response) {
                            if (response.success) {
                                button.closest('.col-6').remove();
                            } else {
                                alert(response.message);
                            }
                        },
                        error: function() {
                            alert('Error deleting photo. Please try again.');
                        }
                    });
                }
            });
        });
    </script>

<?= $this->endSection() ?>