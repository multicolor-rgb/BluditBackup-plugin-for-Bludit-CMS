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
            }

            $folderPath = '';

            switch ($_POST['zip']) {
                case 'all':
                    $folderPath = PATH_ROOT;
                    break;
                case 'themes':
                    $folderPath = PATH_THEMES;
                    break;
                case 'plugins':
                    $folderPath = PATH_PLUGINS;
                    break;
                case 'pages':
                    $folderPath = PATH_PAGES;
                    break;
                case 'database':
                    $folderPath = PATH_DATABASES;
                    break;
                case 'plugins-database':
                    $folderPath = PLUGINS_DATABASES;
                    break;
                case 'uploads':
                    $folderPath = PATH_UPLOADS;
                    break;
                case 'bl-content':
                    $folderPath = PATH_CONTENT;
                    break;
            }

            $zip = new ZipArchive();
            $zipFileName = PATH_CONTENT . 'BluditBackup/bludit-backup-' . $_POST['zip'] . '-' . date('Y-m-d_H-i-s') . '.zip';

            if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {

                $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folderPath));

                foreach ($iterator as $file) {
                    if (!$file->isDir()) {
                        $filePath = $file->getRealPath();
                        $relativePath = substr($filePath, strlen($folderPath));

                        // Sprawdź, czy plik jest prawidłowo odczytany
                        if ($content = file_get_contents($filePath)) {
                            if (strpos($filePath, PATH_CONTENT . 'BluditBackup') !== 0) {
                                $zip->addFromString($relativePath, $content);
                            }
                        }
                    }
                }

                 if ($zip->close() === true) {
                    Alert::set('Archive Created');
                } else {
                    Alert::set('Archive not created.');
                }
            } else {
                Alert::set('Archive not created.');
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
