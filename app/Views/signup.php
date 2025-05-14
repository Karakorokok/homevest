<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Vest - Register</title>

    <!-- mdbootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/8.2.0/mdb.min.css" rel="stylesheet" />
    <!-- Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">

</head>
<body>

    <div class="container">
        <div class="my-4">

            <form action="/signup" method="POST" autocomplete="off">
                <div class="d-flex justify-content-center mb-1">
                    <img src="/resources/images/logo.png" alt="logo" height="60">
                </div>

                <div class="text-center mb-4">
                    Home Vest
                </div>

                <?php if (session()->getFlashdata('successMessage')): ?>
                    <div class="alert alert-primary" role="alert">
                        <?= session()->getFlashdata('successMessage') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('validationErrors')): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php foreach (session()->getFlashdata('validationErrors') as $error): ?>
                            <div><?= esc($error) ?></div> 
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php $formData = session()->getFlashdata('formData') ?? [];?>

                <div data-mdb-input-init class="form-outline mb-3">
                    <input type="text" class="form-control form-control-lg" name="username" 
                        value="<?= esc($formData['username'] ?? '') ?>"/>
                    <label class="form-label">Username</label>
                </div>
                <div data-mdb-input-init class="form-outline mb-3">
                    <input type="password" class="form-control form-control-lg" name="password" 
                        value="<?= esc($formData['password'] ?? '') ?>"/>
                    <label class="form-label">Password</label>
                </div>
                <div data-mdb-input-init class="form-outline mb-3">
                    <input type="password" class="form-control form-control-lg" name="repassword" 
                        value="<?= esc($formData['repassword'] ?? '') ?>"/>
                    <label class="form-label">Re-enter Password</label>
                </div>
                <div data-mdb-input-init class="form-outline mb-3">
                    <input type="text" class="form-control form-control-lg" name="firstname" 
                        value="<?= esc($formData['firstname'] ?? '') ?>"/>
                    <label class="form-label">Firstname</label>
                </div>
                <div data-mdb-input-init class="form-outline mb-3">
                    <input type="text" class="form-control form-control-lg" name="lastname" 
                        value="<?= esc($formData['lastname'] ?? '') ?>"/>
                    <label class="form-label">Lastname</label>
                </div>
                <div data-mdb-input-init class="form-outline mb-3">
                    <input type="text" class="form-control form-control-lg" name="email" 
                        value="<?= esc($formData['email'] ?? '') ?>"/>
                    <label class="form-label">Email</label>
                </div>
              
                <button  type="submit" class="btn btn-primary btn-lg btn-block mt-5 mb-4">Create Account</button>

                <div class="text-center">
                    <p>Already a member? <a href="/login">Login</a></p>
                </div>

                <div class="text-center mt-5 mb-3">
                    <small>&copy;&nbsp;2025 Home Vest. All rights reserved.</small>
                </div>
            </form>
     
        </div>
    </div>

    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" 
        crossorigin="anonymous">
    </script>
    <!-- mdbootstrap -->
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/8.2.0/mdb.umd.min.js">
    </script>

</body>
</html>