<!DOCTYPE html>
<html>
<head>
    <title>Datasift dump parser</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="http://yandex.st/highlightjs/7.3/styles/default.min.css">

</head>
<body>

<div class="container">

    <div class="page-header">
        <h1>Datasift dump parser</h1>
    </div>

    <?php
        $msgs = array();
        include("upload.php");
    ?>

    <?php if (!empty($msgs) ): ?>
        <div id="loader"></div>
    <?php endif; ?>

    <main>
        <nav>
            <ul class="nav nav-tabs">
                <li data-target="table" class="active"><a href="#/table">Table</a></li>
                <li data-target="medias"><a href="#/medias">Medias</a></li>
                <li data-target="stats"><a href="#/stats">Stats</a></li>
                <li data-target="raw"><a href="#/raw">Raw JSON</a></li>
                <li data-target="map"><a href="#/map">Map</a></li>
            </ul>
        </nav>

        <section id="table"><?php include("table.php"); ?></section>
        <section id="medias"><?php include("medias.php"); ?></section>
        <section id="stats"><?php include("stats.php"); ?></section>
        <section id="raw"><?php include("raw.php"); ?></section>
        <section id="map"><?php include("map.php"); ?></section>
    </main>

</div> <!-- container -->

<a href="#" id="arrow-up"></div>


<script src="js/jquery-2.0.3.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap.file-input.js"></script>
<script src="js/jsrender.min.js"></script>
<script src="js/jquery.tablesorter.min.js"></script>
<script src="js/masonry.pkgd.min.js"></script>
<script src="js/imagesloaded.pkgd.min.js"></script>
<script src="js/jquery.isotope.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>

<script type="text/javascript">
    var json_data = <?php echo (empty($msgs) ? 'null' : '{messages:'.json_encode($msgs).'}'); ?>;
</script>
<script src="js/script.js"></script>

</body>
</html>