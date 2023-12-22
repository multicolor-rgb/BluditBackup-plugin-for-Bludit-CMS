<?php
class bluditBackup extends Plugin
{



    public function adminController()
    {


        if (isset($_GET['deletezip'])) {
            unlink(PATH_CONTENT . 'BluditBackup/' . $_GET['deletezip']);
            header("Refresh:0;url=" . DOMAIN_ADMIN . "plugin/bluditbackup/");
        };



        if (isset($_POST['makebackup'])) {

            if (!file_exists(PATH_CONTENT . 'BluditBackup/')) {
                mkdir(PATH_CONTENT . 'BluditBackup/', 0755);
                file_put_contents(PATH_CONTENT . 'BluditBackup/.htaccess', 'Allow from all');
            };

            if ($_POST['zip'] == 'all') {
                $folderPath = PATH_ROOT;
            } elseif ($_POST['zip'] == 'themes') {
                $folderPath = PATH_THEMES;
            } elseif ($_POST['zip'] == 'plugins') {
                $folderPath = PATH_PLUGINS;
            } elseif ($_POST['zip'] == 'pages') {
                $folderPath = PATH_PAGES;
            } elseif ($_POST['zip'] == 'database') {
                $folderPath = PATH_DATABASES;
            } elseif ($_POST['zip'] == 'plugins-database') {
                $folderPath = PLUGINS_DATABASES;
            } elseif ($_POST['zip'] == 'uploads') {
                $folderPath = PATH_UPLOADS;
            } elseif ($_POST['zip'] == 'content') {
                $folderPath = PATH_CONTENT;
            }

            $zip = new ZipArchive();
            $zipFileName = PATH_CONTENT . 'BluditBackup/bludit-backup-' . $_POST['zip'] . '-' . date('Y-m-d_H-i-s') . '.zip';

            if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {


                $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folderPath));

                foreach ($files as $file) {
                    if (!$file->isDir()) {
                        $filePath = $file->getRealPath();
                        $relativePath = substr($filePath, strlen($folderPath));

                        // Check if the file is read correctly
                        if ($content = file_get_contents($filePath)) {
                            $zip->addFromString($relativePath, $content);
                        }
                    }
                }

                // Check if closing the archive was successful
                if ($zip->close() === true) {
                    Alert::set('Archive created.');
                } else {
                    Alert::set('Archive not created.');
                }
            } else {
                Alert::set('Failed to create the archive.');
            }
        };
    }

    public function adminView()
    {
        global $security;

        $tokenCSRF = $security->getTokenCSRF();
        include($this->phpPath() . 'PHP/form.php');
    }

    public function adminSidebar()
    {
        $pluginName = Text::lowercase(__CLASS__);
        $url = HTML_PATH_ADMIN_ROOT . 'plugin/' . $pluginName;
        $html = '<a id="current-version" class="nav-link" href="' . $url . '">BluditBackup 🔥</a>';
        return $html;
    }
}
