<!DOCTYPE HTML>
<html>

	<head>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<meta name="description" content="The Amazing Burrito Finder - A Search Engine for Burritos Nearest You"/>        
		<link href="css/bootstrap.min.css" rel="stylesheet"/>
        <link href="css/bootstrap-theme.min.css" rel="stylesheet"/>
        <link href="css/custom.css" rel="stylesheet"/>

        <?php if (isset($title)): ?>
            <title>The Burrito Finder: <?= htmlspecialchars($title) ?></title>
        <?php else: ?>
            <title>The Burrito Finder</title>
        <?php endif ?>

        <script src="/js/jquery-1.10.2.min.js"></script>
        <script src="/js/bootstrap.min.js"></script>
        <script src="/js/scripts.js"></script>

    </head>

    <body>

        <div class="container">

            <div id="top">
                <div class="row navbar h4">
                    <div class="col-xs-3 col-sm-3 col-md-3 navbutton"><a href="index.php"> Search </a></div>
                    <div class="col-xs-3 col-sm-3 col-md-3 navbutton"><a href="myburritos.php"> My Burritos </a></div>
                    <?php if (isset($name)): ?> 
                    <div class="col-xs-3 col-sm-3 col-md-3 navbutton"><a href="logout.php"> Log Out </a></div>
                    <div class="col-xs-3 col-sm-3 col-md-3 navbutton"> Hi, <?= htmlspecialchars($name) ?> </div> 
                    <?php else: ?>
                    <div class="col-xs-3 col-sm-3 col-md-3 navbutton"><a href="register.php"> Register </a></div>
                    <div class="col-xs-3 col-sm-3 col-md-3 navbutton"><a href="login.php"> Log-in </a></div>
                    <?php endif ?>
                </div>
                <div class="row titlebar h1">
                    <div class="col-xs-6 col-sm-6 col-md-6 text-right">
                        <img src="img/burrito.png" class="img-responsive" alt="photo of a delicious burrito">
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6 title">
                        <?= $title ?>
                    </div>
                </div>
            </div>
            <div id="middle">
