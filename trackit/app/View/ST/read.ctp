<?php echo $val; 

//App::import('Component', 'UserData');
//$session = new SessionComponent(new ComponentCollection());
//debug($user_data_component);
//$UserDataComp = $this->controller->UserData;//new UserDataComponent(new ComponentCollection('Session'));
//$UserDataComp->sessionInit();
$read = $user_data_component->test_read();
echo "---\n";
//echo $UserDataComp;
echo "---\n";
echo $read;
echo "---\n";
$name = $user_data_component->getWelcomeName();
echo $name;
echo "---\n";
?>