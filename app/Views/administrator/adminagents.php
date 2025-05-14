<?= $this->extend('/administrator/adminmain'); ?>


<?= $this->section('content') ?>

    <div class="container-fluid">
        <div class="text-center my-3 h5">Agents</div>

        <div class="mb-3">
            <button class="btn btn-primary me-auto" data-mdb-modal-init data-mdb-target="#addAgentsModal">Add Agents</button>
        </div>

        <div class=""> 
            <table id="agentsTable" class="table" style="width:100%">
                <thead>
                    <tr class="">
                        <th>Agent</th>
                        <th>Affiliation</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($Agents as $agnt): ?>
                        <tr>
                            <td>
                                <?= $agnt['firstname'] . 
                                    (!empty($agnt['middlename']) ? ' ' . strtoupper(substr($agnt['middlename'], 0, 1)) . '.' : '') . 
                                    ' ' . $agnt['lastname']; 
                                ?>
                            </td>
                            <td><?= $agnt['developer_names']; ?></td>
                            <td>
                                <button type="button" class="btn btn-secondary btn-sm mb-1" data-mdb-modal-init  data-mdb-target="#editModal<?= $agnt['id_user']; ?>">Edit</button>
                                <button type="button" class="btn btn-danger btn-sm mb-1" data-mdb-modal-init  data-mdb-target="#deleteModal<?= $agnt['id_user']; ?>">Delete</button>
                                
                                <div class="modal fade" id="editModal<?= $agnt['id_user']; ?>" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form action="/admindevelopers/editaffiliation" method="POST">
                                                <div class="modal-body">
                                                    <div class="btn btn-tertiary">AFFILIATION</div>
                                                    <input type="hidden" name="id_user" value="<?= $agnt['id_user']; ?>">
                                                    <div class="row">
                                                        <?php foreach ($Developers as $dev): ?>
                                                            <div class="col-6">
                                                                <div class="form-check">
                                                                    <?php $isChecked = in_array($dev['id_developer'], $agnt['developer_ids'] ?? []); ?>
                                                                    <input class="form-check-input" type="checkbox" name="developer_ids[]" value="<?= $dev['id_developer']; ?>" <?= $isChecked ? 'checked' : '' ?> />
                                                                    <label class="form-check-label"><?= $dev['developer_name']; ?></label>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
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

                                <div class="modal fade" id="deleteModal<?= $agnt['id_user']; ?>" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form action="/adminagents/removeagent" method="POST">
                                                <div class="modal-body">
                                                    <input type="hidden" name="id_user" value="<?= $agnt['id_user']; ?>">
                                                    <div class="text-center my-3">Are you sure you want to delete <span class="text-danger">
                                                        <?= $agnt['firstname'] . 
                                                            (!empty($agnt['middlename']) ? ' ' . strtoupper(substr($agnt['middlename'], 0, 1)) . '.' : '') . 
                                                            ' ' . $agnt['lastname']; 
                                                        ?>
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

        <!-- add agents modal -->
        <div class="modal fade" id="addAgentsModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="d-flex justify-content-center align-items-center d-none" id="loadingScreen">
                        <div class="text-center">
                            <div class="spinner-border text-primary mt-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                            </div>
                            <div class="my-1">Saving...</div>
                        </div>
                    </div>

                    <form action="/adminagents/saveagent" method="POST">
                        <div class="modal-body">
                            <div class="mb-3">
                                <a class="btn btn-tertiary">Add Agent</a>
                            </div>
                            <div class="form-outline mb-3" data-mdb-input-init>
                                <input type="text" class="form-control" spellcheck="false" autocomplete="off" name="lastname" />
                                <label class="form-label">Lastname</label>
                            </div>
                            <div class="form-outline mb-3" data-mdb-input-init>
                                <input type="text" class="form-control" spellcheck="false" autocomplete="off" name="firstname" />
                                <label class="form-label">Firstname</label>
                            </div>
                            <div class="form-outline mb-1" data-mdb-input-init>
                                <input type="text" class="form-control" spellcheck="false" autocomplete="off" name="email" />
                                <label class="form-label">Email</label>
                            </div>
                            <small>An email address is required to receive a one-time password (OTP).</small>

                            <div>
                                <div class="btn btn-tertiary">AFFILIATION</div>
                                <div class="row">
                                    <?php if (empty($Developers)): ?>
                                        <small class="text-center">No Real-Estate Developer/s available.</small>
                                    <?php else: ?>
                                        <?php foreach ($Developers as $dev): ?>
                                            <div class="col-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="developer_ids[]" value="<?= $dev['id_developer']; ?>" />
                                                    <label class="form-check-label"><?= $dev['developer_name']; ?></label>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                          
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnCancel" class="btn btn-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">Cancel</button>
                            <button type="submit" id="btnAdd" class="btn btn-primary" data-mdb-ripple-init>Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>

    <script>
        new DataTable('#agentsTable', {
            responsive: true,
            lengthChange: false,    
            pageLength: 5  
        });

        $(document).ready(function() {

            $('#btnAdd').on('click', function() {
                $('#loadingScreen').removeClass('d-none');
            });

            $('#btnCancel').on('click', function() {
                $('#loadingScreen').addClass('d-none');
            });

        });
    </script>

<?= $this->endSection() ?>