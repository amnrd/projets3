<!DOCTYPE html>
<html>
<head>
    <title>La Ferme de Didier</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="<?= base_url();?>asset/style.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url();?>asset/jquery/animate.css">
    <script type="text/javascript" src="<?= base_url();?>asset/jquery/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="<?= base_url();?>asset/jquery/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?= base_url();?>asset/jquery/jquery.textillate.js"></script>
    <script type="text/javascript" src="<?= base_url();?>asset/jquery/jquery.fittext.js"></script>
    <script type="text/javascript" src="<?= base_url();?>asset/jquery/jquery.lettering.js"></script>
</head>
<body>
<div class="page-bg"></div>
<nav>
    <div class="dark-bg"></div>
    <a class="account" href="account.html">
        <img src="<?= base_url();?>asset/images/user.png"/>
        <p>Pseudo</p>
    </a>
    <div class="log">
        <a href="inscription.html">Inscription</a>
        <div class="separation"></div>
        <a href="connexion.html">Connexion</a>
    </div>
</nav>
<a class="tlt title" href="index.html">La ferme de Didier</a>
<div class="content">
    <audio id="sound-chicken" src="<?= base_url();?>asset/sounds/chicken.mp3"></audio>
    <h1>ENIGME 1</h1>
    <div class="content-2">
        <div class="content-2-left">
            <h1>MOT&nbsp;&nbsp;MYSTÈRE :</h1>
            <form>
                <input class="input-text" type="text" onkeydown="this.value = this.value.toUpperCase()" onkeyup="this.value = this.value.toUpperCase()">
                <a class="valider">Ok</a>
            </form>
            <p class="tryagain">Ce n'est pas le bon mot, essaye encore !</p>
            <p class="indice-text">Tu peux cliquer sur l'image en dessous pour écouter l'indice...</p>
            <img src="<?= base_url();?>asset/images/headset.svg" onmousedown="return false"/>
        </div>
        <div class="content-2-right">
            <div class="description">
                <h1>REFERENCE DE L'ENIGME</h1>
                <h2>DESCRIPTION</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce tristique viverra risus, in semper lacus. Aliquam in tincidunt eros, at pharetra odio. Proin id enim fermentum, rutrum ipsum a, porttitor dui.</p>
            </div>
            <form method="POST" action="<?=base_url();?>Enigme/enigmeHandler/1">
                <input class="input-hidden" name="response" type="hidden" value="">
                <input class="next" type="submit" value="ÉNIGME SUIVANTE">
            </form>
        </div>
    </div>
</div>
<footer>
    <a href="contact.html" >Contact</a>
    <a href="next.html">Nos jeux</a>
    <a class="infos-legales" href="next.html">Informations légales</a>
</footer>


<!--	JAVASCRIPT 	-->

<script type="text/javascript">

    $(document).ready(function(){

        $('.tlt').textillate({
            in: {
                effect: 'bounceInDown',
                callback: function(){
                    $('nav').find('a').removeClass('tlt');
                }
            }
        });

        $('#description').find('img').click(function(){
            $(this).addClass('animated rubberBand').one('animationend', function(){
                $(this).removeClass('animated rubberBand');
            });
        });
        $('.play').find('img').click(function(){
            $(this).addClass('animated rubberBand').one('animationend', function(){
                $(this).removeClass('animated rubberBand');
            });
        });

        $('.log').find('a').first().mouseover(function(){
            $('.log').find('a').first().css('transform', 'scale(0.95)');
        });
        $('.log').find('a').first().mouseout(function(){
            $('.log').find('a').first().css('transform', 'scale(1)');
        });

        $('.log').find('a').last().mouseover(function(){
            $('.log').find('a').last().css('transform', 'scale(0.95)');
        });
        $('.log').find('a').last().mouseout(function(){
            $('.log').find('a').last().css('transform', 'scale(1)');
        });


        var completed = false;
        var essai = 0;
        var soundChicken = document.getElementById("sound-chicken");

        $('.animal-form').find('img').mouseover(function(){
            if(completed == false){
                $(this).css('transform', 'scale(0.98)');
            }
        });
        $('.animal-form').find('img').mouseout(function(){
            if(completed == false){
                $(this).css('transform', 'scale(1)');
            }
        });
        $('.animal-form').find('img').click(function(){
            if(completed == false){
                $(this).css('transform', 'scale(0.95)').one('transitionend', function(){
                    $(this).css('transform', 'scale(1)');
                });
            }
        });

        $('.valider').click(function(){
            if(completed == false){

                if($('.input-text').val() == 'POULE'){
                    $('.tryagain').text('Bravo ! Tu as trouvé le mot mystère !').css('color', '#33c054').css('opacity', '1');
                    $('.input-hidden').val('completed');
                    $('.input-text').prop('disabled', true);
                    $('.indice-text').text('Tu as réussi cette énigme mais Célestin s\'est enfuit, passe vite à l\'énigme suivante pour le rattraper !').css('opacity', '1');
                    $('.content-2-left').find('img').attr('src', 'images/chicken.svg').css('opacity', '1').css('cursor', 'default');
                    completed = true;
                }
                else{
                    if(essai >= 2){
                        $('.content-2-left').find('img').css('opacity', '1');
                        $('.tryagain').css('opacity', '1');
                        $('.indice-text').css('opacity', '1');
                    }
                    else{
                        $('.tryagain').css('opacity', '1');
                    }
                    essai++;
                }
            }
        });
        $('.input-text').click(function(){
            $('.tryagain').css('opacity', '0');
        });

        $('.content-2-left').find('img').click(function(){
            if(essai >= 2 && completed == false){
                soundChicken.play();
            }
        });

    });

</script>

<style type="text/css">

    .content{
        display: inline-block;
        margin:  80px auto 80px auto;
    }
    .content h1{
        text-align: left;
        font-family: 'Skater Girls Rock';
        font-weight: 500;
        color: #222222;
        font-size: 80px;
        margin: 0;
    }
    .content-2{
        display: flex;
        align-items: stretch;
        justify-content: center;
        width: 100%;
    }
    .content-2-left{
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        background: rgba(0,0,0,0.1);
        border-radius: 5px;
        width: 780px;
        padding: 40px 0;
        margin: 0 40px 0 0;
    }
    .content-2-left h1{
        font-size: 50px;
    }
    .content-2-left img{
        width: 100px;
        margin: 40px 0 0 0;
        opacity: 0;
        cursor: pointer;
        transition: 0.2s ease;
    }
    .content-2-left form{
        display: flex;
        align-items: center;
        justify-content: center;
        height: 35px;
        margin: 40px 0 40px 0;
    }
    .tryagain{
        font-family: 'Skater Girls Rock';
        font-size: 30px;
        color: #ee3333;
        width: 100%;
        opacity: 0;
        margin: 0;
    }
    .indice-text{
        font-family: 'Skater Girls Rock';
        font-size: 25px;
        color: #222222;
        width: 100%;
        opacity: 0;
        margin: 60px 0 0 0;
    }
    .input-text{
        text-align: center;
        font-family: 'Skater Girls Rock';
        font-size: 25px;
        color: #222222;
        border: none;
        width: 350px;
        height: 100%;
        padding: 10px;
        border-radius: 5px;
    }
    .input-text:focus{
        outline: none;
    }
    .valider{
        position: relative;
        display: inline-block;
        width: 65px;
        height: 100%;
        font-family: 'Skater Girls Rock';
        font-size: 35px;
        color: #ffffff;
        background: #33c054;
        border: none;
        border-radius: 5px;
        padding: 15px 0 5px 0;
        margin: 0 0 0 20px;
        cursor: pointer;
    }
    .content-2-right{
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-direction: column;
        width: 360px;
        padding: 20px;
        background: rgba(0,0,0,0.1);
        border-radius: 5px;
    }
    .next{
        width: 300px;
        height: 80px;
        font-family: 'Skater Girls Rock';
        font-size: 35px;
        text-decoration: none;
        color: #ffffff;
        background: #33c054;
        border-radius: 5px;
        border: none;
        margin: 50px 0 0 0;
        padding: 15px 0 0 0;
        cursor: pointer;
    }
    .description{
        text-align: left;
    }
    .description h1{
        font-family: 'Skater Girls Rock';
        font-size: 30px;
        color: #222222;
        margin: 0 0 50px 0;
    }
    .description h2{
        font-family: 'Skater Girls Rock';
        font-size: 30px;
        color: #222222;
    }
    .description p{
        font-family: 'Skater Girls Rock';
        font-size: 20px;
        color: #222222;
        text-align: justify;
    }
    @media screen and (max-device-width: 1080px){
        .content h1{
            text-align: center;
        }
        .content-2{
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .content-2-left{
            margin: 0 0 40px 0;
        }
        .content-2-right{
            width: 740px;
        }
        .content-2-right a{
            width: 300px;
            height: 100px;
            font-size: 50px;
        }
        .description h1{
            text-align: left;
        }
        .tryagain{
            font-size: 50px;
        }
        .input-text{
            font-size: 40px;
        }
        .indice-text{
            font-size: 40px;
        }
        .valider{
            width: 100px;
            font-size: 50px;
            padding: 15px 0 15px 0;
        }
    }
</style>
</body>
</html>