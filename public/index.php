<?php
require_once 'Model.php';



class User extends Model{
    protected static $table='anyusers';

}

$user=new User();

/*//find by id
$usr=$user::find(5);
if($usr){
echo $usr->first_name." ".$usr->last_name." with id=".$usr->id."<br>";
}*/

//find all
/*$usr1=User::findAll();
echo
"<table style='text-align: left; padding: 8px;'>
<tr>
    <th>First name</th>
    <th>Last name</th>
    <th>Email</th>
</tr>";
foreach ($usr1 as $usr) {
    echo '<tr> <th>'.$usr['first_name']."</th>".
        '<th>'.$usr['last_name']."</th>".
        '<th>'.$usr['email']."</th></tr>";
}
    echo '</table>';*/

//delete
/*$usr=$user::find(7);
$usr->delete();*/

//insert
$user->id=10;
$user->first_name="Leo";
$user->last_name="Leonardo";
$user->email="leo.leonardo112@gmail.com";
$user->password="leo1234leonardo";
$user->created_at=date_format ( new DateTime(), "y-m-d h:i:s" );
$user->updated_at=date_format ( new DateTime(), "y-m-d h:i:s" );
$user->save();






