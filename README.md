# login-and-register-with-.json
The login method will loop through all stored users and search for 
the given username. If there is a match the function will then check
if the given password matches the stored password. The method returns a 
success message if the user's username and password are correct. Else it returns an error message.

---

if you wish to check and see if someone is logged in on a certain page in your website, just add this code: (PHP)

`	session_start();
	if(!isset($_SESSION['user'])){
		header("location: login.php");	exit();
	}`

 ---
 Made By [Littleclaw](https://replit.com/@littleclaw)
 
