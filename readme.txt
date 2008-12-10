=== Extended User Profile ===
Contributors: Ralf Hortt
Donate link: http://extended-user-profile.horttcore.de
Tags: user profile, profile, author page
Requires at least: 2.7
Tested up to: 2.7
Stable tag: 1.3

Extend your the user profile

== Description ==

The webmaster can extended with the basic html knowledge, or even with some copy paste skills to add more input fields or textareas to their user profile form.

PAY ATTENTION IT WILL NOT WORK WITH OLDER PLUGIN VERSIONS VERSION
SO YOU MIGHT NEED TO CHANGE YOUR TEMPLATE

== Installation ==

Upload the extended-user-profile.php into your plugin folder and activate it.

To show the added meta values in the front end use the wordpress get_usermeta() function or my own eup_get_extended_profile(), it retrieves an object with all values.

== Frequently Asked Questions ==

How can I echo the variables?
You can easily put sth up like this on your author template:
$meta = eup_get_extended_profile(); // call all new meta values
print_r($meta); // Get an overview whats in the object
echo $meta->email // echo an meta value

Will it add any new tables?
No, it uses the wordpress usermeta table and won't mess up your database.

== ToDo ==
My Plans for the next version are:
- Adding a fileupload function, so user can attache files to their profile
- Creating a form on-the-fly

Feel free to give me a shout for your wishes.