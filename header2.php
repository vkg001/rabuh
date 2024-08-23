<?php

?>

<nav class="navbar navbar-expand-lg navbar-dark trans-navigation fixed-top navbar-togglable">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <h3><?php echo $site_name ?></h3>
            </a>
            <!-- Toggler -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="fa fa-bars"></span>
            </button>

            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <!-- Links -->
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="./" id="navbarWelcome" role="button" aria-haspopup="true" aria-expanded="false">
                            Home
                        </a>
                        
                    </li>
                    <li class="nav-item ">
                        <a href="about" class="nav-link js-scroll-trigger">
                            About
                        </a>
                    </li>

                    <li class="nav-item ">
                        <a href="contact" class="nav-link">
                            Contact
                        </a>
                    </li>
                </ul>
            </div> <!-- / .navbar-collapse -->
        </div> <!-- / .container -->
    </nav>