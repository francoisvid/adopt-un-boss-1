
    <link rel="stylesheet" href="/assets/styles/footer.css">
    <link rel="stylesheet" href="/assets/styles/barnav.css">

<?php
if(isset($_COOKIE['tkn'])):
    $barnav_controller = new \BWB\Framework\mvc\controllers\BarnavController();
    $user = $barnav_controller->get_user();
endif;
?>

<nav class="navbar navbar-expand-sm navbar-dark"  style="background-color: #343539">
    <a class="navbar-brand" href="/"><img id="iconNavBar" src="/assets/imgs/leter-a-inside-a-black-circle.png" alt=""></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample03" aria-controls="navbarsExample03" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExample03">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="/offres">Nos Offres<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="/wiki">Comment ça marche ?<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/event">Évenements</a>
            </li>


            <div class="btn-barnav row">
                <?php
                if (!empty($_COOKIE['tkn'])) {
                    ?>

                    <div class="dropdown icon-profil">
                        <img id="photo-profil" class="d-flex rounded-circle" src="<?= $user->getPhoto(); ?>" style="height: 35px; width: 35px" alt="">
                        <div class="dropdown-content dropdown-left">
                            <a href="/profil" class="nav-link disabled"><button class="btn-deco btn btn-outline">Mon Compte</button></a>
                            <a href="/logout" class="nav-link disabled"><button class="btn-deco btn btn-outline">Deconnexion</button></a>
                        </div>
                    </div>

            <?php
            }else{
                ?>

                        <a href="/login/candidat" class="nav-link disabled"><button class="btn btn-outline-success btn-co"> Connexion </button></a>
                        <a href="/register" class="nav-link disabled"><button class="btn btn-outline-primary btn-inscription"> Inscrition </button></a>

            <?php
            }
            ?>

            </div>
        </ul>
    </div>
</nav>