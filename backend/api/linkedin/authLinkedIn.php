<?php

require './init.php';

// On arrive sur cette page pour rediriger l'utilisateur vers
// la page demandant l'autorisation pour l'utilisation de ses données

// On ne donne pas directement l'URL dans un lien car les identifiants
// de l'application (client : id et secret) sont dans l'URL de base
// (URL redirigeant vers une autre URL ne contenant pas ces informations-là)

header("Location: ".$linkedin->getAuthUrl());
exit();