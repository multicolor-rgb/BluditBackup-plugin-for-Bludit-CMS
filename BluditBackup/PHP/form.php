<h3>BluditBackup</h3>
<p class="text-muted">Easy Backup bludit with support concrete folder option to zip </p>
<hr>
<form method="POST">
    <input type="hidden" id="jstokenCSRF" name="tokenCSRF" value="<?php echo $tokenCSRF; ?>">

    <select name="zip" class="form-control my-3">
        <option value="all">Whole Website</option>
        <option value="themes">Themes</option>
        <option value="plugins">Plugins</option>
        <option value="pages">Pages</option>
        <option value="database">Databases</option>
        <option value="plugins-database">Plugins Databases</option>
        <option value="uploads">Uploads</option>
        <option value="bl-content">bl-content Folder</option>

    </select>

    <button type="submit" name="makebackup" class="btn btn-primary">Make Backup</button>
</form>

<hr>
<h3 class="pb-2 mt-4">List Backup</h3>
<ul class="list-group">
    <?php foreach (glob(PATH_CONTENT . 'BluditBackup/*.zip') as $zip) {

        echo '<li class="list-group-item d-flex justify-content-between"><a href="' . HTML_PATH_CONTENT . 'BluditBackup/' .  pathinfo($zip)['basename'] . '" download>' . pathinfo($zip)['basename'] . '<a class="btn btn-primary btn-sm" href="' . DOMAIN_ADMIN . 'plugin/bluditbackup?deletezip=' . pathinfo($zip)['basename'] . '">Delete</a></li>';
    }; ?>
</ul>
<hr>

<br>

<script type='text/javascript' src='https://storage.ko-fi.com/cdn/widget/Widget_2.js'></script>
<script type='text/javascript'>
    kofiwidget2.init('Support Me on Ko-fi', '#29abe0', 'I3I2RHQZS');
    kofiwidget2.draw();
</script>