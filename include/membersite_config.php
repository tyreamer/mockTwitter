<?PHP
require_once("./include/fg_membersite.php");

$fgmembersite = new FGMembersite();

//Provide your site name here
$fgmembersite->SetWebsiteName('leyff.com');

//Provide the email address where you want to get notifications
$fgmembersite->SetAdminEmail('webmaster@leyff.com');

//Provide your database login details here:
//hostname, user name, password, database name and table name
//note that the script will create the table (for example, fgusers in this case)
//by itself on submitting register.php for the first time
$fgmembersite->InitDB(/*hostname*/'localhost',
                      /*dbusername*/'tylre',
                      /*password*/'tylrePass',
                      /*database name*/'YellowstoneDB1',
                      /*table name*/'person');

//Update to get a random string from this link: http://tinyurl.com/randstr
$fgmembersite->SetRandomKey('qSRcVS6DrTzrPvr');

?>