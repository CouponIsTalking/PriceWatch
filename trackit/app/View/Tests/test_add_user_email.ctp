<?php

echo "Slug :{$slug}:";
echo "<br/>";
echo "Email Added/ Entry Id :{$entry_id}:";
echo "<br/>";
echo "entry_from_entry_id : "; var_dump($entry_from_entry_id);
echo "<br/>";
echo "user_owns_email : "; var_dump($user_owns_email);
echo "<br/>";
echo "is_email_confirmed : "; var_dump($is_email_confirmed);
echo "<br/>";
echo "set_email_confirmed : "; var_dump($set_email_confirmed);
echo "<br/>";
echo "is_email_confirmed_now : "; var_dump($is_email_confirmed_now);
echo "<br/>";
echo "user_owns_email_after_confirmation : "; var_dump($user_owns_email_after_confirmation);
echo "<br/>";
echo "remove_email : "; var_dump($remove_email);
echo "<br/>";
echo "is_email_confirmed_after_remove : "; var_dump($is_email_confirmed_after_remove);
echo "<br/>";
echo "user_owns_email_after_remove : "; var_dump($user_owns_email_after_remove);
echo "<br/>";
?>