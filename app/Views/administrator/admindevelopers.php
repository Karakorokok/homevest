<?= $this->extend('/administrator/adminmain'); ?>


<?= $this->section('content') ?>

    <div class="container-fluid">
        <div class="text-center my-3 h5">Developers</div>

        <div class="mb-3">
            <button class="btn btn-primary me-auto" data-mdb-modal-init data-mdb-target="#addDeveloperModal">Add Developer</button>
        </div>

        <div class=""> 
            <table id="developersTable" class="table" style="width:100%">
                <thead>
                    <tr class="">
                        <th>Developer</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($Developers as $dev): ?>
                        <tr>
                            <td><?= $dev['developer_name']; ?></td>
                            <td>
                                <button type="button" class="btn btn-secondary btn-sm mb-1" data-mdb-modal-init  data-mdb-target="#editModal<?= $dev['id_developer']; ?>">Edit</button>
                                <button type="button" class="btn btn-danger btn-sm mb-1" data-mdb-modal-init  data-mdb-target="#deleteModal<?= $dev['id_developer']; ?>">Delete</button>

                                <div class="modal fade" id="editModal<?= $dev['id_developer']; ?>" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form action="/admindevelopers/savedeveloper" method="POST">
                                                <div class="modal-body">
                                                    <input type="hidden" name="id_developer" value="<?= $dev['id_developer']; ?>">
                                                    <div class="mb-3">
                                                        <a class="btn btn-tertiary">Edit Developer</a>
                                                    </div>
                                                    <div class="form-outline mb-3" data-mdb-input-init>
                                                        <input type="text" class="form-control" value="<?= $dev['developer_name']; ?>" name="developername" />
                                                        <label class="form-label">Developer name</label>
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

                                <div class="modal fade" id="deleteModal<?= $dev['id_developer']; ?>" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form action="/admindevelopers/removedeveloper" method="POST">
                                                <div class="modal-body">
                                                    <input type="hidden" name="id_developer" value="<?= $dev['id_developer']; ?>">
                                                    <div class="text-center my-3">Are you sure you want to delete <span class="text-danger"><?= $dev['developer_name']; ?></span>?</div>
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

        <!-- add developer modal -->
        <div class="modal fade" id="addDeveloperModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="/admindevelopers/savedeveloper" method="POST">
                        <div class="modal-body">
                            <div class="mb-3">
                                <a class="btn btn-tertiary">Add Developer</a>
                            </div>
                            <div class="form-outline mb-3" data-mdb-input-init>
                                <input type="text" class="form-control" spellcheck="false" autocomplete="off" name="developername" />
                                <label class="form-label">Developer name</label>
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

<?= $this->endSection() ?>

<?= $this->section('js') ?>

    <script>
        new DataTable('#developersTable', {
            responsive: true,
            lengthChange: false,    
            pageLength: 5  
        });
    </script>

<?= $this->endSection() ?>