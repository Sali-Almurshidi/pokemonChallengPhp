<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body background="img/pokemonbackground.webp">

<form id="inputField" method="post">
    <label><h1>Pokemon Name</h1></label>
    <input type="text" id="PokemonName" placeholder="Enter Pokemon or id here" name="PokemonName"/>
    <button type="submit" name="button" value="button" id="callApi"/>
    Search </button>
    <!--<input type="button" name="click" value="Search" id="callApi" onclick="getInput()"/>-->
</form>

<?php
// get the input value from the user
if (isset($_POST['button']) && !empty($_POST['PokemonName'])) {
    $pokemonName = $_POST['PokemonName'];
    getArray($pokemonName);
}
// get contents from the api
function getArray($userInput)
{
    $dataApi = file_get_contents("https://pokeapi.co/api/v2/pokemon/" . $userInput . "/");
    getInformation($dataApi);
}

// select information from the api
function getInformation($dataApi)
{
    $response = json_decode($dataApi, true); //because of true, it's in an array
    // get species url
    $speciesURL = $response['species']['url'];
    // get Pokemon Name
    $pokName = $response['name'];
    // get Pokemon id
    $pokId = $response['id'];
    // get Pokemon img
    $pokImg = $response['sprites']['front_default'];
    // get Pokemon moves
    $pokMoves = $response['moves'];
    // array for 4 moves
    $movesArray = array();
    if (count($pokMoves) > 4) {
        $movesArray[0] = $pokMoves[0]["move"]["name"];
        $movesArray[1] = $pokMoves[1]["move"]["name"];
        $movesArray[2] = $pokMoves[2]["move"]["name"];
        $movesArray[3] = $pokMoves[3]["move"]["name"];
    } else {
        foreach ($pokMoves as $key => $trend) {
            $movesArray[$key] = $trend["move"]["name"];
        }
    }
    printInfo($pokName, $pokId, $pokImg, $movesArray);
    sendURL($speciesURL);
}

// print the data on the screen
function printInfo($pokName, $pokId, $pokImg, $pokMoves)
{
    //echo print_r[$pokMoves] ;
    echo '<div class="divStyle">
        <h2 class="nameStyle">' . $pokId . '</h2>
        <h2 class="nameStyle"> ' . $pokName . '</h2>
        <img class="imageStyle" src="' . $pokImg . '">
        <ul class="nameStyle"> Abilities ';

    for ($movesFor = 0; $movesFor < 4; $movesFor++) {
        echo '<li class="nameStyle"> ' . $pokMoves[$movesFor] . '</li>';
    }
    echo '</ul>
    </div>';
}

// get the previous evolve if there is one
function sendURL($speciesURL)
{
    $speciesURLApi = file_get_contents($speciesURL);
    $responseSpeciesURL = json_decode($speciesURLApi, true); //because of true, it's in an array
    // get species url
    $speciesURL = $responseSpeciesURL['evolves_from_species'];
    if ($speciesURL != null) {
        echo $speciesURL;
        getArray($responseSpeciesURL['evolves_from_species']['name']);
    } else {
        echo '<div class="divStyle">
        <h3 class="nameStyle">This is the first evolution </h3>  </div>';
    }
}

?>
</body>
</html>