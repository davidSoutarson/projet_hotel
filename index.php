<?php
require_once 'config/configuration.php';
require_once CONFIG_PATH . "creation_database.php";
require_once VIEWS_PATH . "header.php";
require_once CONTROLLER_PATH . "HotelController.php";

echo '<h2> Afiche tout les hotels </h2>';


require_once VIEWS_PATH . "hotel/liste_hotels.php";


require_once VIEWS_PATH . "footer.php";


# Recherchez la section "allow_url_fopen"
# phpinfo();
# vérifier si allow_url_fopen est activé, "+ ou - 6 ou 7 bloc" Core Directive  Local Value on Master Value On

# Dans le cas contrére

# Avant de procéder à une modification d'un fichier tel que php.ini, pensez à faire plusieurs sauvegardes du fichier
# dans son état fonctionnel précédent. Plusieurs moyens sont possibles, comme la création d'une copie du dossier
# (par exemple, "ma_config_php") ou l'utilisation d'outils de versionnage tels que Git/GitHub. Je conseille de faire les deux.

# Ouvrez le fichier php.ini (selon votre environnement, il peut se trouver par exemple dans
# C:\xampp\php\php.ini ou /etc/php/7.x/apache2/php.ini).
# Recherchez la ligne contenant allow_url_fopen.
# Modifier la comme ceci : allow_url_fopen = On
# Enregistrez le fichier et redémarrez votre serveur web (Apache, Nginx, etc.) pour que la modification soit prise en compte.

# Si vous n'avez pas accès au fichier php.ini, vous pouvez également essayer de l'activer temporairement via .htaccess (pour Apache) en ajoutant :
# php_value allow_url_fopen 1

# ou dans un script PHP, via ini_set() (mais dans la plupart des cas, cette directive ne peut pas être modifiée à l'exécution) :

# Cependant, la méthode recommandée reste de modifier le fichier php.ini directement.