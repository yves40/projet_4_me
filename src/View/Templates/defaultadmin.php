<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billet simple pour l'Alaska</title>
    <link rel="icon" type="image/gif" href="/images/favicon.png"/>
    <link rel="stylesheet" href="/styles/mobile/mainmenu.css">
    <link rel="stylesheet" href="/styles/tablet/mainmenu.css" media="screen AND (min-width: 600px)">
    <link rel="stylesheet" href="/styles/desktop/mainmenu.css" media="screen AND (min-width: 1024px)">
    <link rel="stylesheet" href="/styles/mobile/sidebar.css">
    <link rel="stylesheet" href="/styles/tablet/sidebar.css" media="screen AND (min-width: 600px)">
    <link rel="stylesheet" href="/styles/desktop/sidebar.css" media="screen AND (min-width: 1024px)">
    <link rel="stylesheet" href="/styles/mobile/admin.css">
    <link rel="stylesheet" href="/styles/tablet/admin.css" media="screen AND (min-width: 600px)">
    <link rel="stylesheet" href="/styles/desktop/admin.css" media="screen AND (min-width: 1024px)">
    <script type="module" src="/scripts/script.js"></script>
    <script type="module" src="/scripts/sidebar.js"></script>
    <script type="module" src="/scripts/admin.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <!-- <script type="module" src="/scripts/redac.js"></script> -->
    <script src="https://cdn.tiny.cloud/1/6oavl2vepor0wql2jum02y4hq8z7706271xccs800s0jeiow/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>



<body class="sitepage">
    <nav class="main_menu">
        <div class="sup">
            <?php if($loggedUser->isLogged()):?>    
                <img src="<?php echo IMAGEROOT.$loggedUser->getProfile_picture() ?>" alt="Photo de profil" class="profile-picture">
                <!-- S'affiche ?? la place de bonjour tant qu'il n'est pas connect?? -->
                <p class="user_name"><?= 'Bonjour '.$loggedUser->getPseudo(); ?></p>
            <?php else:?>
                <img src="/images/profile_pictures/defaultuserpicture.png" alt="Photo de profil" class="profile-picture">
                <a href="/users/login"><ion-icon name="log-in-outline"></ion-icon>Se connecter</a>
            <?php endif;?>
            <div class="menu_toggle"></div>
        </div>
        <ul class="menu_access">
        <li><a href="/#accueil"><ion-icon name="home-outline"></ion-icon>Accueil</a></li>
        <li><a href="/#auteur"><ion-icon name="body-outline"></ion-icon>L'Auteur</a></li>
        <li><a href="/billets/chapterlist"><ion-icon name="book-outline"></ion-icon>L'Aventure</a></li>
        <!-- S'affichent quand le user est connect?? -->
        <?php if($loggedUser->isLogged()):?>
            <li><a href="/users/profil"><ion-icon name="person-outline"></ion-icon>Mon Profil</a></li>
            <?php if($loggedUser->isAdmin()):?>
                <li><a href="/admin/admin"><ion-icon name="settings-outline"></ion-icon>Gestion</a></li>
            <?php endif;?>
            <li><a href="/users/logout"><ion-icon name="log-out-outline"></ion-icon>Se D??connecter</a></li>
        <?php endif;?>
    </ul>
    </nav>
    <?= $contenu?>
</body>

</html>