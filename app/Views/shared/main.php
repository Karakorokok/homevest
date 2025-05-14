<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homevest</title>

    <!-- mdbootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/8.2.0/mdb.min.css" rel="stylesheet" />
    <!-- Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <!-- DataTable -->
    <link href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/responsive/3.0.4/css/responsive.bootstrap5.css" rel="stylesheet" />

    <?= $this->renderSection('css') ?>
</head>
<body>

    <?= $this->renderSection('content') ?>

    <?= $this->include('/shared/navbar') ?>

    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" 
        crossorigin="anonymous">
    </script>
    <!-- mdbootstrap -->
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/8.2.0/mdb.umd.min.js">
    </script>
    <!-- font-awesome -->
    <script src="https://kit.fontawesome.com/5d8b225433.js" crossorigin="anonymous"></script>

    <!-- DataTable -->
    <script type="text/javascript" src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/3.0.4/js/dataTables.responsive.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/3.0.4/js/responsive.bootstrap5.js"></script>
    
    <?= $this->renderSection('js') ?>

    </body>
</html>