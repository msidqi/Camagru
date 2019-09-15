<?php
$host = 'mysql';
$user = 'root';
$password = 'tiger';
$dbname = 'camagru_db';

$dsn = "mysql:host=$host;dbname=$dbname;port=3306";
try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    echo "connection failed " . $e->getMessage();
}

#set default attr of fetch mode
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);




/*## get query from database
$stmt = $pdo->query('SELECT * FROM users');

##overide fetch attr
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['login'] . '<br>';
}


##default fetch attr
while ($row = $stmt->fetch()) {
    echo $row->login . '<br>';
}*/

// #Positional Prams

// $UserInput = 'user1';

// $sql = 'SELECT * FROM users WHERE login = ?';
// $stmt = $pdo->prepare($sql);
// $stmt->execute([$UserInput]);
// $users = $stmt->fetchAll();


#Named Prams

$UserInput = 'user1';
try {
    $sql = 'SELECT * FROM users WHERE `login` = :login';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(
        ['login' => $UserInput]
        // ['login' => '1'],
        // []
    );
    // $stmt->bindParam(':login', $UserInput, PDO::PARAM_STR);
    // $stmt->execute();
    $users = $stmt->fetchAll();



    print_r($users);

    // foreach($users as $user)
    //     echo $user->password . '<br>';
}
catch (PDOException $e) {
    echo "connection failed " . $e->getMessage();
}

/*
    MVC :

        Model       : Data related logic, Commm with Controller.
        View        : UI, Can be passed dynamic values from Controller, Commm with Controller.
        Controller  : Gets user input frim url/form/view/etc, processes GET,POST, gets data from model, passes data to view.
*/

?>