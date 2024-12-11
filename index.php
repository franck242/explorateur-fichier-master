<?php

//section qui verifie si $_GET["file"] contient l'info du dossier qui a été cliqué
//si il a quelque chose, il associe $pathArray avec le nom du dossier clicqué avec un "/"
//s'il est vide, il fait en sorte que $_GET["file"] ai au moins "." dedans
if(isset($_GET["file"])){
    
    $pathArray = explode("/", $_GET["file"]);
    //ex :$pathArray peut etre un tableau avec : "Array ( [0] => . [1] => medias [2] => dossier-coucou )" dedans
    //ci $_GET["file"] contient "./medias/dossier-coucou"
}
else{
    $pathArray = array(".");
    $_GET["file"] = ".";
}
// print_r($_GET["file"]);
// print_r($pathArray);
// $fileIndex = count($pathArray);
// var_dump($pathArray[count($pathArray)-1]);
// var_dump(pathinfo($pathArray[count($pathArray)-1], PATHINFO_EXTENSION));


function downloadFile($pathArray){
    if(pathinfo($pathArray[count($pathArray)-1], PATHINFO_EXTENSION) == "git"){
        return;
    }
    if(pathinfo($pathArray[count($pathArray)-1], PATHINFO_EXTENSION) != ""){
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($pathArray[count($pathArray)-1]).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($pathArray[count($pathArray)-1]));
        readfile($pathArray[count($pathArray)-1]);
        exit;
    }
}
//section qui vérife si le dernier element cliqué est un fichier
//si oui => propose de télécharger le fichier en question
downloadFile($pathArray);
// var_dump(pathinfo($pathArray[count($pathArray)-1], PATHINFO_EXTENSION));

//section qui reverifie si $_GET["file"] est vide ou s'il contient un ".."
//si c'est le premiere cas de figure, associe $currentDir à "." 
//(mis en place pour éviter que quelqu'un retourne trop en arrière en terme de dossiers avec des "..")
//sinon, il associe $currentDir à $_GET["file"] (ex:"./medias/dossier-coucou") et $currentDirPath à d'adresse complète
//en utilisant "getcwd()" (ce qui donne le dossier actif en cours)
if(!$_GET["file"] || $_GET["file"] == ".." || in_array("..", $pathArray)){
    
    $currentDir = ".";
    
    $currentDirPath = getcwd()."/".$currentDir;
    // header("Location: http://localhost:8080/");
    // exit;
}
else{
    // ex : $currentDir = "./medias/dossier-coucou"
    $currentDir = $_GET["file"];
    // ex : $currentDirPath = "var/www/html" + "/" + "./medias/dossier-coucou"
    $currentDirPath = getcwd()."/".$currentDir;

}



function list_file($dir = "."){
    $current_dir_location = $dir;
    // print_r($current_dir_location);
    // echo 'Voici quelques informations de débogage :';
    $dir_contents = scandir($current_dir_location);
    // print_r($dir_contents);
    // print_r(error_get_last());
    // var_dump($current_dir_location);

    foreach($dir_contents as $value){
        $fileExtention = pathinfo($value, PATHINFO_EXTENSION);
        // var_dump($fileExtention);
        // var_dump($value);
        // var_dump(is_file($value));

        if (in_array($value, array(".", "..")) && in_array("index.php", $dir_contents)){

            continue;
        }
        elseif(is_dir($current_dir_location."/".$value) && $value == "."){
            
            continue;

        }
        elseif(is_dir($current_dir_location."/".$value) && $value == ".."){
            
            echo "<div class='fade-anim'><p><a href='index.php?file=".$current_dir_location."/".$value."'>
            <img src='medias/house-icon.png' alt='home logo' class='home-icon-size'></p>
            <p class='folder-name'>Home</p></a></div>";

        }
        elseif(is_dir($current_dir_location."/".$value)){
            
            echo "<div class='fade-anim'><p><a href='index.php?file=".$current_dir_location."/".$value."'>
            <img src='medias/file-icon.png' alt='folder file logo' class='icon-size'></p>
            <p class='folder-name'>".$value."</p></a></div>";

        }
        else{
            $mediasFolderContent = scandir("medias");
            // print_r($mediasFolderContent);
            $imgPath = $fileExtention."-file.png";
            // echo "is ".$imgPath." file";
            $bool = in_array($imgPath, $mediasFolderContent);
            // print_r($bool);
            if(in_array($imgPath, $mediasFolderContent)){
                
                echo "<div class='fade-anim'><p><a href='index.php?file=".$current_dir_location."/".$value."'>
                <img src='medias/".$fileExtention."-file.png' alt='".$fileExtention." file logo' class='icon-size'></p>
                <p class='file-name'>".$value."</p></a></div>";

            }
            else {
                
                echo "<div class='fade-anim'><p><a href='index.php?file=".$current_dir_location."/".$value."'>
                    <img src='medias/basic-file-icon.png' alt='default file logo' class='icon-size'></p>
                    <p class='file-name'>".$value."</p></a></div>";
            }
            
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="L'explorateur de fichiers professionnels vous permet de naviguer entres tous les dossiers
        qui composent vos projets; gain de temps, organisation et visibilité garantie!">
        <meta name="author" content="Fiona PEREIRA GOMES, Sacha HENRY, Fayçal karim, Franck MABIKAS">
        <meta name="category" content="explorateur de fichiers">
        <meta name="Keywords" content="explorateur de fichiers, professionnel, dossiers, fichiers, parcourir, naviguer,
        télécharger, php, js, css, medias, img">
        <!-- Facebook -->
        <meta property="og:locale" content="fr_FR">
        <meta property="og:title" content="explorateur de fichiers">
        <meta property="og:description" content="L'explorateur de fichiers professionnels vous permet de naviguer entres tous les dossiers
        qui composent vos projets; gain de temps, organisation et visibilité garantie!">
        <meta property="og:image" content="http://fionap.promo-vesoul33.codeur.online/explorer-fichier/medias/zip-file.png">
        <meta property="og:url" content="http://fionap.promo-vesoul33.codeur.online/explorer-fichier/index.php">
        <meta property="og:image:width" content="495">
        <meta property="og:image:height" content="365">
        <meta property="og:image:alt" content="facebook_partage">
        <!-- Twitter -->
        <meta name="twitter:title" content="explorateur de fichiers">
        <meta name="twitter:description" content="L'explorateur de fichiers professionnels vous permet de naviguer entres tous les dossiers
        qui composent vos projets; gain de temps, organisation et visibilité garantie!">
        <meta name="twitter:image" content="http://fionap.promo-vesoul33.codeur.online/explorer-fichier/medias/zip-file.png">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:site" content="https://twitter.com/">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <!--  css-->
        <link rel="stylesheet" href="css/styles.css">

        <title>Explorateur de fichiers</title>
</head>

<body>
    <header>
        <div class="container-fluid header-bg-color"> <!--TROUVER UN BACKGROUND -->
        
                <div class="row flex-column justify-content-start">
                    <div class="col-12 d-flex pt-4 pb-4">
                        <h1>Mister John Doe</h1> <!--TROUVER UNE BELLE FONT/POLICE-->
                        <p class="avatar"><img src="img/avatar-icon.png" alt="avatar-icon" width="" height=""></p> <!--TROUVER UN AVATAR-->
                    </div>
                </div>
                
        </div>
    </header>

    <main>
       <div class="container-fluid">
            <div class="row bordure-bottom light-grey-bg">  <!--TROUVER UN BACKGROUND COULEUR-->
                <div class="col-12">
                    <p class="text-uppercase font-weight-bold">fichiers: <?php echo " ".$currentDirPath."<br>\n"; ?></p>
                </div> 
            </div>      
            <div class="row">               
                <div class="col-12 d-flex flex-wrap pt-5 pb-5 ">   <!--TROUVER UN BACKGROUND -->
                    <?php list_file($currentDir); ?>
                </div>
            </div>
        </div>
    </main>
    
    <script src="js/script.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>