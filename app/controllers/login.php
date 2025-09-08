<?php 

// Initialize an empty array to hold error messages
$errors = [];

// Check if the form was submitted via POST method
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    // Create a new instance of the User class to access user-related functions
	$user = new User();
 	
    // Check if there is a user in the database with the email entered in the form
 	if($row = $user->where(['email'=>$_POST['email']]))
 	{
  		// If a user is found, verify if the entered password matches the hashed password in the database
 		if(password_verify($_POST['password'], $row[0]['password']))
 		{
 			// If the password matches, authenticate the user and redirect them to the home page
 			authenticate($row[0]);
			redirect('home');
 		}
 		else
	 	{
	 		// If the password is incorrect, store an error message for the password
	 		$errors['password'] = "wrong password";
	 	}
 	}
 	else
 	{
 		// If no user is found with the entered email, store an error message for the email
 		$errors['email'] = "wrong email";
 	}
}

// Load the login view file to display the login form
require views_path('auth/login');
