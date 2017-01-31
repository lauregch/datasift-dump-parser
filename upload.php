
<?php if ( ! empty($_FILES) ): ?>
    <?php
        $uploaddir = getcwd() . '/uploads/';
        $filename = basename($_FILES['dumpfile']['name']);
    ?>

    <?php if ( $_FILES['dumpfile']['type'] !== 'application/json' ) : ?>
        <?php $error = 'Seuls les fichiers JSON sont autorisés.'; ?>

    <?php else : ?>

        <?php if ( ! move_uploaded_file($_FILES['dumpfile']['tmp_name'], $uploaddir . $filename)) 
            $error = $_FILES['dumpfile']['error']; ?>

    <?php endif; ?>

    <?php if ( isset($error) ): ?>
         <div class="alert alert-error">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <strong>Erreur lors de l'upload du fichier :</strong>
            <?php echo $error; ?>
        </div>

    <?php else: ?>
        <div class="alert alert-success">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            Le fichier a été correctement uploadé.
        </div>
        <?php $filetoparse = $filename; ?>

    <?php endif; ?>

<?php elseif ( isset($_POST['existing_file']) ): ?>
    <?php $filetoparse = $_POST['existing_file']; ?>

<?php endif; ?>


<?php
    if ( isset($filetoparse) )
    {
        $filehandle = fopen( 'uploads/' . $filetoparse, 'r');
        if ($filehandle)
        {
            while (($interaction = fgets($filehandle)) !== false)
            {
                $interaction = json_decode($interaction, true);
                $msgs[] = $interaction;
            }
            fclose($filehandle);
        }
    }
?>


<?php 
    $dumps = array();
    if ($handle = opendir(getcwd().'/uploads'))
    {
        while (false !== ($entry = readdir($handle)) )
        {
            if ( preg_match('/.json/', $entry) ) $dumps[] = $entry;
        }
        closedir($handle);
    }
?>
<?php if ( !empty($dumps) ): ?>
    <form id="choose" class="form-inline" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
        <label>Choose an existing dump</label>
        <select name="existing_file">
    <?php foreach ($dumps as $filename): ?>
          <option<?php if ($filename===$filetoparse) echo ' selected="selected"'; ?>><?php echo $filename; ?></option>
    <?php endforeach; ?>
        </select>
        <button type="submit" name="submit" class="btn">Ok</button>
    </form>
<?php endif; ?>


<form id="upload" class="form-inline" enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
<?php if ( !empty($dumps) ): ?>
    <label>Or, upload a new file to parse</label>
<?php else: ?>
    <label>Upload a dump file to parse</label>
<?php endif; ?>
    <input type="file" name="dumpfile" title="Browse">
    <button type="submit" name="submit" class="btn">Upload</button>
</form>
