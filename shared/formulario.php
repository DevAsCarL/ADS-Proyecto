<?php 

class formulario {
    protected $path = "";
    protected function __construct($path = "."){
        $this->path = $path;
    }
    protected function encabezadoShowIni($titulo){
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="<?php echo $this->path;?>/public/styles.css">
            <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" integrity="sha512-RXf+QSDCUQs5uwRKaDoXt55jygZZm2V++WUZduaU/Ui/9EGp3f/2KZVahFZBKGH0s774sd3HmrhUy+SgOFQLVQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <title><?php echo $titulo?></title>
        </head>
        <body>
        <?php
    }
    protected function encabezadoShow($titulo){
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="<?php echo $this->path;?>/public/styles.css">
            <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" integrity="sha512-RXf+QSDCUQs5uwRKaDoXt55jygZZm2V++WUZduaU/Ui/9EGp3f/2KZVahFZBKGH0s774sd3HmrhUy+SgOFQLVQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <title><?php echo $titulo?></title>
        </head>
        <body>
        <header class="main-header">
            <div class="main-header__block l-container">
                <div class="main-logo">
                    <img class="main-logo__img" src="../img/logo.png" alt=""/>
                    <h1 class="main-logo__title">PUBLYSHER</h1>
                </div>
                <nav class="main-menu__nav" id="main-menu__nav" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement" role="navigation">
                    <ul class="main-menu__ul">
                        <li class="main-menu__item" itemprop="url"><a class="main-menu__link" href="#" itemprop="name">Home</a></li>
                        <li class="main-menu__item" itemprop="url"><a class="main-menu__link" href="#" itemprop="name">Taller De Emprendedores</a></li>
                        <li class="main-menu__item" itemprop="url"><a class="main-menu__link" href="#" itemprop="name">Workshops</a></li>
                        <li class="main-menu__item" itemprop="url"><a class="main-menu__link" href="#" itemprop="name">Expositores</a></li>
                    </ul>
                </nav>
            </div>
        </header>
        <?php 
    }
    protected function piePaginaShow(){
        ?>  
        </body>
        </html>
        <?php 
    }

}

?>